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

use Adventcalendar\Infra\Pages;
use Adventcalendar\Infra\Repository;
use Adventcalendar\Infra\Request;
use Adventcalendar\Infra\Responder;
use Adventcalendar\Infra\View;

/**
 * @param string $cal
 * @return string
 */
function adventcalendar($cal)
{
    global $pth, $plugin_cf, $plugin_tx;

    $controller = new Adventcalendar\MainController(
        $plugin_cf["adventcalendar"],
        new Pages,
        new Repository,
        new View($pth["folder"]["plugins"] . "adventcalendar/views/", $plugin_tx["adventcalendar"])
    );
    return Responder::respond($controller->defaultAction(Request::current(), $cal));
}

(new Adventcalendar\Plugin)->run();
