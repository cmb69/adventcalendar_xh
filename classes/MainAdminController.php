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
use Adventcalendar\Infra\Image;
use Adventcalendar\Infra\Repository;
use Adventcalendar\Infra\View;
use Adventcalendar\Logic\Util;
use Adventcalendar\Value\Html;
use Adventcalendar\Value\Response;

class MainAdminController
{
    /** @var array<string,string> */
    private $conf;

    /** @var CsrfProtector */
    private $csrfProtector;

    /** @var Repository */
    private $repository;

    /** @var Image */
    private $image;

    /** @var View */
    private $view;

    /** @param array<string,string> $conf */
    public function __construct(
        array $conf,
        CsrfProtector $csrfProtector,
        Repository $repository,
        Image $image,
        View $view
    ) {
        $this->conf = $conf;
        $this->csrfProtector = $csrfProtector;
        $this->repository = $repository;
        $this->image = $image;
        $this->view = $view;
    }

    public function defaultAction(): Response
    {
        global $sn;

        return Response::create($this->view->render("admin", [
            'url' => "$sn?adventcalendar&admin=plugin_main&action=prepare",
            'csrfTokenInput' => Html::of($this->csrfProtector->tokenInput()),
            'calendars' => $this->repository->findCalendars()
        ]));
    }

    public function prepareAction(): Response
    {
        $this->csrfProtector->check();
        $cal = $_POST['adventcalendar_name'];
        $dn = $this->repository->dataFolder();
        $image = $this->repository->findImage($cal);
        if ($image === null) {
            return Response::create($this->view->error("error_read", "$dn$cal.jpg"));
        }
        [$width, $height, $data] = $image;
        $doors = Util::calculateDoors($width, $height, (int) $this->conf['door_width'], (int) $this->conf['door_height']);
        $doors = $this->image->shuffleDoors($doors);
        $data = $this->image->drawDoors($data, $doors);

        if (!$this->repository->saveCover($cal, $data)) {
            return Response::create($this->view->error("error_save", "$dn$cal+.jpg"));
        }
        if (!$this->repository->saveDoors($cal, $doors)) {
            return Response::create($this->view->error("error_save", "$dn$cal.dat"));
        }

        $url = CMSIMPLE_URL . "?adventcalendar&admin=plugin_main&action=view&adventcalendar_name=$cal";
        return Response::redirect($url);
    }

    public function viewAction(): Response
    {
        $dn = $this->repository->dataFolder();
        $cal = $_GET['adventcalendar_name'];
        return Response::create($this->view->render("view", [
            'src' => "$dn$cal+.jpg",
        ]));
    }
}
