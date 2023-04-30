<?php

/*
 * Copyright 2012-2023 Christoph M. Becker
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

use Adventcalendar\Infra\Jquery;
use Adventcalendar\Infra\Pages;
use Adventcalendar\Infra\Repository;
use Adventcalendar\Infra\Request;
use Adventcalendar\Infra\View;
use Adventcalendar\Value\Response;

class MainController
{
    /** @var string */
    private $pluginFolder;

    /** @var array<string,string> */
    private $conf;

    /** @var Pages */
    private $pages;

    /** @var Repository */
    private $repository;

    /** @var Jquery */
    private $jquery;

    /** @var View */
    private $view;

    /** @param array<string,string> $conf */
    public function __construct(
        string $pluginFolder,
        array $conf,
        Pages $pages,
        Repository $repository,
        Jquery $jquery,
        View $view
    ) {
        $this->pluginFolder = $pluginFolder;
        $this->conf = $conf;
        $this->pages = $pages;
        $this->repository = $repository;
        $this->jquery = $jquery;
        $this->view = $view;
    }

    public function __invoke(Request $request, string $calendar): Response
    {
        if (($doors = $this->repository->findDoors($calendar)) === null) {
            return Response::create($this->view->error("error_not_prepared", $calendar));
        }
        if (($cover = $this->repository->findCover($calendar)) === null) {
            return Response::create($this->view->error("error_not_prepared", $calendar));
        }
        if (($page = $this->pages->findByHeading($calendar)) < 0) {
            return Response::create($this->view->error("message_missing_page", $calendar));
        }
        $this->jquery->include();
        $this->jquery->includePlugin("colorbox", $this->pluginFolder . "colorbox/jquery.colorbox-min.js");
        return Response::create($this->view->render("main", [
            "src" => $cover,
            "doors" => $this->doorRecords($request, $page, $doors),
            "width" => $this->conf["lightbox_width"],
            "height" => $this->conf["lightbox_height"],
        ]));
    }

    /**
     * @param array<array{int,int,int,int}> $doors
     * @return array<array{day:int,coords:string,href:string}>
     */
    private function doorRecords(Request $request, int $page, array $doors): array
    {
        $subPages = array_slice($this->pages->childrenOf($page), 0, $this->currentDay($request));
        return array_map(function (int $i, int $page) use ($request, $doors) {
            return [
                "day" => $i + 1,
                "coords" => implode(",", $doors[$i]),
                "href" => $request->url()->withPage($this->pages->urlOf($page))->withParam("print")->relative(),
            ];
        }, array_keys($subPages), array_values($subPages));
    }

    private function currentDay(Request $request): int
    {
        if ($request->adm()) {
            return 24;
        } else {
            $start = strtotime($this->conf["date_start"]);
            return (int) floor(($request->time() - $start) / 86400) + 1;
        }
    }
}
