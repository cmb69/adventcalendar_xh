<?php

/*
 * Copyright 2023 Christoph M. Becker
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
use Adventcalendar\Infra\Jquery;
use Adventcalendar\Infra\Pages;
use Adventcalendar\Infra\Repository;
use Adventcalendar\Infra\Shuffler;
use Adventcalendar\Infra\SystemChecker;
use Adventcalendar\Infra\View;

class Dic
{
    public static function makeMainController(): MainController
    {
        global $pth, $plugin_cf;
        return new MainController(
            $pth["folder"]["plugins"] . "adventcalendar/",
            $plugin_cf["adventcalendar"],
            new Pages(),
            self::makeRepository(),
            new Jquery(),
            self::makeView()
        );
    }

    public static function makeInfoController(): InfoController
    {
        global $pth;

        return new InfoController(
            $pth["folder"]["plugins"] . "adventcalendar/",
            self::makeRepository(),
            new SystemChecker(),
            self::makeView()
        );
    }

    public static function makeMainAdminController(): MainAdminController
    {
        global $plugin_cf;

        return new MainAdminController(
            $plugin_cf["adventcalendar"],
            new CsrfProtector(),
            self::makeRepository(),
            new Shuffler(),
            self::makeDoorDrawer(),
            self::makeView()
        );
    }

    private static function makeDoorDrawer(): DoorDrawer
    {
        global $plugin_cf;

        return new DoorDrawer(
            $plugin_cf["adventcalendar"]["color_door"],
            $plugin_cf["adventcalendar"]["color_font"],
            $plugin_cf["adventcalendar"]["color_fringe"],
        );
    }

    private static function makeRepository(): Repository
    {
        global $pth, $plugin_cf;

        if (trim($plugin_cf["adventcalendar"]["folder_data"]) === "") {
            $folder = $pth["folder"]["plugins"] . "adventcalendar/data/";
        } else {
            $folder = $pth["folder"]["base"] . rtrim(trim($plugin_cf["adventcalendar"]["folder_data"]), "/") . "/";
        }
        return new Repository($folder);
    }

    private static function makeView(): View
    {
        global $pth, $plugin_tx;

        return new View($pth["folder"]["plugins"] . "adventcalendar/views/", $plugin_tx["adventcalendar"]);
    }
}
