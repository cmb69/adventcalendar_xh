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

use Adventcalendar\Dic;
use Adventcalendar\Infra\Responder;

if (!defined("CMSIMPLE_XH_VERSION")) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

/**
 * @var string $admin
 * @var string $action
 * @var string $o
 */

XH_registerStandardPluginMenuItems(true);

if (XH_wantsPluginAdministration("adventcalendar")) {
    $o .= print_plugin_admin("on");
    switch ($admin) {
        case "":
            $o .= Dic::makeInfoController()->defaultAction();
            break;
        case "plugin_main":
            $o .= Responder::respond(Dic::makeMainAdminController()($action));
            break;
        default:
            $o .= plugin_admin_common();
    }
}
