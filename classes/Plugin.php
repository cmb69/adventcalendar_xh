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

use Adventcalendar\Infra\Repository;
use Adventcalendar\Infra\SystemChecker;
use Adventcalendar\Infra\View;

class Plugin
{
    const VERSION = '1.0beta6';

    /**
     * @return void
     */
    public function run()
    {
        if (defined("XH_ADM") && XH_ADM) {
            XH_registerStandardPluginMenuItems(true);
            if (XH_wantsPluginAdministration('adventcalendar')) {
                $this->handleAdministration();
            }
        }
    }

    /**
     * @return void
     */
    private function handleAdministration()
    {
        global $admin, $action, $o, $pth, $plugin_tx;

        $o .= print_plugin_admin('on');
        switch ($admin) {
            case '':
                $controller = new InfoController(
                    $pth["folder"]["plugins"] . "adventcalendar/",
                    new Repository,
                    new SystemChecker,
                    new View($pth["folder"]["plugins"] . "adventcalendar/views/", $plugin_tx["adventcalendar"])
                );
                $o .= $controller->defaultAction();
                break;
            case 'plugin_main':
                $controller = new MainAdminController(
                    new Repository,
                    new View($pth["folder"]["plugins"] . "adventcalendar/views/", $plugin_tx["adventcalendar"])
                );
                ob_start();
                switch ($action) {
                    case 'prepare':
                        $controller->prepareAction();
                        break;
                    case 'view':
                        $controller->viewAction();
                        break;
                    default:
                        $controller->defaultAction();
                }
                $o .= ob_get_clean();
                break;
            default:
                $o .= plugin_admin_common();
        }
    }
}
