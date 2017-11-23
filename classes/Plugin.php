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

    /**
     * @param string $cal
     * @return string
     */
    public static function main($cal)
    {
        global $plugin_tx;

        $ptx = $plugin_tx['adventcalendar'];
        $calendar = Calendar::findByName($cal);
        $data = $calendar->getDoors();
        if (!isset($data)) {
            return XH_message('fail', $ptx['error_read'], self::dataFolder() . $cal . '.dat');
        }
        $src = self::dataFolder() . $cal . '+.jpg';
        if (!file_exists($src)) {
            return XH_message('fail', $ptx['error_read'], $src);
        }
        $page = Page::getByHeading($cal);
        if (!isset($page)) {
            return XH_message('fail', sprintf($ptx['message_missing_page'], $cal));
        }
        self::js();
        $doors = [];
        foreach ($page->getChildren() as $i => $page) {
            if ($i >= self::getCurrentDay()) {
                break;
            }
            $coords = implode(',', $data[$i]);
            $href = '?' . $page->getURL() . '&print';
            $doors[$i + 1] = (object) compact('coords', 'href');
        }
        ob_start();
        (new View('adventcalendar'))
            ->template('main')
            ->data(compact('src', 'doors'))
            ->render();
        return ob_get_clean();
    }

    /**
     * @return int
     */
    protected static function getCurrentDay()
    {
        global $plugin_cf;

        if (XH_ADM) {
            return 24;
        } else {
            $start = strtotime($plugin_cf['adventcalendar']['date_start']);
            return (int) floor((time() - $start) / 86400) + 1;
        }
    }

    /**
     * @return void
     */
    protected static function js()
    {
        global $pth, $plugin_cf, $hjs;
        static $again = false;

        if ($again) {
            return;
        }
        $again = true;
        $pcf = $plugin_cf['adventcalendar'];
        include_once $pth['folder']['plugins'] . 'jquery/jquery.inc.php';
        include_jQuery();
        $filename = $pth['folder']['plugins']
            . 'adventcalendar/colorbox/jquery.colorbox-min.js';
        include_jQueryPlugin('colorbox', $filename);
        $width = $pcf['lightbox_width'];
        $height = $pcf['lightbox_height'];
        $hjs .= <<<EOS
            <script type="text/javascript">/* <![CDATA[ */
            jQuery(function () {
                jQuery("area.adventcalendar").click(function (event) {
                        jQuery.colorbox({
                            iframe: true, href: this.href,
                            maxWidth: "100%", maxHeight: "100%",
                            innerWidth: "$width", innerHeight: "$height"
                        });
                        event.preventDefault();
                });
            });
            /* ]]> */</script>

EOS;
    }
}
