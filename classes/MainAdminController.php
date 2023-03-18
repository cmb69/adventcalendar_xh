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

    public function __invoke(Request $request): Response
    {
        switch ($request->action()) {
            default:
                return $this->overview();
            case "prepare":
                return $this->prepare($request);
            case "do_prepare":
                return $this->doPrepare($request);
            case "view":
                return $this->view($request);
        }
    }

    private function overview(): Response
    {
        return Response::create($this->view->render("admin", [
            "calendars" => $this->calendarRecords(),
        ]))->withTitle("Adventcalendar – " . $this->view->text("menu_main"));
    }

    /** @return list<array{id:string,name:string,url:string}> */
    private function calendarRecords(): array
    {
        return array_map(function (string $calendar) {
            return [
                "id" => "adventcalendar_label_$calendar",
                "name" => $calendar,
                "url" => $this->repository->findImageUrl($calendar),
            ];
        }, $this->repository->findCalendars());
    }

    private function prepare(Request $request): Response
    {
        $calendar = $request->url()->param("adventcalendar_name");
        return Response::create($this->view->render("confirm", [
            "url" => $this->repository->findImageUrl($calendar),
            "token" => $this->csrfProtector->token(),
        ]))->withTitle("Adventcalendar – " . $this->view->text("menu_main"));
    }

    private function doPrepare(Request $request): Response
    {
        $this->csrfProtector->check();
        $calendar = $request->url()->param("adventcalendar_name");
        $image = $this->repository->findImage($calendar);
        if ($image === null) {
            return Response::create($this->view->error("error_missing_image", $calendar))
                ->withTitle("Adventcalendar – " . $this->view->text("menu_main"));
        }
        [$data, $doors] = $this->doDoPrepare($image);
        if (!$this->repository->saveCover($calendar, $data)) {
            return Response::create($this->view->error("error_save_cover", $calendar))
                ->withTitle("Adventcalendar – " . $this->view->text("menu_main"));
        }
        if (!$this->repository->saveDoors($calendar, $doors)) {
            return Response::create($this->view->error("error_save_doors", $calendar))
                ->withTitle("Adventcalendar – " . $this->view->text("menu_main"));
        }
        $location = $request->url()->withPage("adventcalendar")->withParam("admin", "plugin_main")
            ->withParam("action", "view")->withParam("adventcalendar_name", $calendar)->absolute();
        return Response::redirect($location);
    }

    /**
     * @param array{int,int,string} $image
     * @return array{string,array<array{int,int,int,int}>}
     */
    private function doDoPrepare(array $image): array
    {
        [$width, $height, $data] = $image;
        $doorWidth = (int) $this->conf['door_width'];
        $doorHeight = (int) $this->conf['door_height'];
        $doors = Util::calculateDoors($width, $height, $doorWidth, $doorHeight);
        $doors = $this->shuffler->shuffle($doors);
        $data = $this->doorDrawer->drawDoors($data, $doors);
        return [$data, $doors];
    }

    private function view(Request $request): Response
    {
        $calendar = $request->url()->param("adventcalendar_name");
        $cover = $this->repository->findCover($calendar);
        if ($cover === null) {
            return Response::create($this->view->error("error_not_prepared", $calendar))
                ->withTitle("Adventcalendar – " . $this->view->text("menu_main"));
        }
        return Response::create($this->view->render("view", [
            'src' => $cover,
        ]))->withTitle("Adventcalendar – " . $this->view->text("menu_main"));
    }
}
