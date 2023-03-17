<?php

/*
 * Copyright 2012-2017 Christoph M. Becker
 *
 * This file is part of Adventcalendar_XH.
 *
 * Adventcalendar_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Adventcalendar_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Adventcalendar_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Adventcalendar;

use Adventcalendar\Infra\CsrfProtector;
use Adventcalendar\Infra\DoorDrawer;
use Adventcalendar\Infra\Repository;
use Adventcalendar\Infra\Request;
use Adventcalendar\Infra\Shuffler;
use Adventcalendar\Infra\View;
use Adventcalendar\Logic\Util;
use Adventcalendar\Value\Response;

class MainAdminController
{
    /** @var array<string,string> */
    private $conf;

    /** @var CsrfProtector */
    private $csrfProtector;

    /** @var Repository */
    private $repository;

    /** @var Shuffler */
    private $shuffler;

    /** @var DoorDrawer */
    private $doorDrawer;

    /** @var View */
    private $view;

    /** @param array<string,string> $conf */
    public function __construct(
        array $conf,
        CsrfProtector $csrfProtector,
        Repository $repository,
        Shuffler $shuffler,
        DoorDrawer $doorDrawer,
        View $view
    ) {
        $this->conf = $conf;
        $this->csrfProtector = $csrfProtector;
        $this->repository = $repository;
        $this->shuffler = $shuffler;
        $this->doorDrawer = $doorDrawer;
        $this->view = $view;
    }

    public function __invoke(Request $request, string $action): Response
    {
        switch ($action) {
            default:
                return $this->overview($request);
            case "prepare":
                return $this->prepare();
            case "view":
                return $this->view();
        }
    }

    private function overview(Request $request): Response
    {
        return Response::create($this->view->render("admin", [
            'url' => $request->sn() . "?adventcalendar&admin=plugin_main&action=prepare",
            'token' => $this->csrfProtector->token(),
            'calendars' => $this->repository->findCalendars()
        ]));
    }

    private function prepare(): Response
    {
        $this->csrfProtector->check();
        $cal = $_POST['adventcalendar_name'];
        $image = $this->repository->findImage($cal);
        if ($image === null) {
            return Response::create($this->view->error("error_missing_image", $cal));
        }
        [$data, $doors] = $this->doPrepare($image);
        if (!$this->repository->saveCover($cal, $data)) {
            return Response::create($this->view->error("error_save_cover", $cal));
        }
        if (!$this->repository->saveDoors($cal, $doors)) {
            return Response::create($this->view->error("error_save_doors", $cal));
        }
        $url = CMSIMPLE_URL . "?adventcalendar&admin=plugin_main&action=view&adventcalendar_name=$cal";
        return Response::redirect($url);
    }

    /**
     * @param array{int,int,string} $image
     * @return array{string,array<array{int,int,int,int}>}
     */
    private function doPrepare(array $image): array
    {
        [$width, $height, $data] = $image;
        $doorWidth = (int) $this->conf['door_width'];
        $doorHeight = (int) $this->conf['door_height'];
        $doors = Util::calculateDoors($width, $height, $doorWidth, $doorHeight);
        $doors = $this->shuffler->shuffle($doors);
        $data = $this->doorDrawer->drawDoors($data, $doors);
        return [$data, $doors];
    }

    private function view(): Response
    {
        $cal = $_GET['adventcalendar_name'];
        $cover = $this->repository->findCover($cal);
        if ($cover === null) {
            return Response::create($this->view->error("error_not_prepared", $cal));
        }
        return Response::create($this->view->render("view", [
            'src' => $cover,
        ]));
    }
}
