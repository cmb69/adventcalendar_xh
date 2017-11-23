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

use Pfw\Url;
use Pfw\View\View;

class Plugin
{
    const VERSION = '@PLUGIN_VERSION@';

    /**
     * @return string
     */
    public static function dataFolder()
    {
        global $pth, $plugin_cf;

        $pcf = $plugin_cf['adventcalendar'];

        if ($pcf['folder_data'] == '') {
            $fn = $pth['folder']['plugins'] . 'adventcalendar/data/';
        } else {
            $fn = $pth['folder']['base'] . $pcf['folder_data'];
        }
        if (substr($fn, -1) != '/') {
            $fn .= '/';
        }
        if (file_exists($fn)) {
            if (!is_dir($fn)) {
                e('cntopen', 'folder', $fn);
            }
        } else {
            if (!mkdir($fn, 0777, true)) {
                e('cntwriteto', 'folder', $fn);
            }
        }
        return $fn;
    }

    /**
     * @return void
     */
    public static function dispatch()
    {
        if (XH_ADM) {
            XH_registerStandardPluginMenuItems(true);
            if (XH_wantsPluginAdministration('adventcalendar')) {
                self::handleAdministration();
            }
        }
    }

    /**
     * @return void
     */
    protected static function handleAdministration()
    {
        global $admin, $action, $o;

        $o .= print_plugin_admin('on');
        switch ($admin) {
            case '':
                ob_start();
                (new InfoController)->defaultAction();
                $o .= ob_get_clean();
                break;
            case 'plugin_main':
                $controller = new MainAdminController;
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
                $o .= plugin_admin_common($action, $admin, 'adventcalendar');
        }
    }
}
