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

use Pfw\View\View;

class Plugin
{
    const VERSION = '@PLUGIN_VERSION@';

    /**
     * @return string
     */
    protected static function dataFolder()
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
                $o .= self::version() . tag('hr')
                    . self::systemCheck();
                break;
            case 'plugin_main':
                switch ($action) {
                    case 'prepare':
                        $o .= self::prepare(stsl($_POST['adventcalendar_name']));
                        break;
                    default:
                        $o .= self::administration();
                }
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
            e('missing', 'file', $cal); // TODO: "Calendar $cal is not prepared!"
            return false;
        }
        $src = self::dataFolder() . $cal . '+.jpg';
        if (!file_exists($src)) {
            e('missing', 'file', $src);
            return false;
        }
        $page = Page::getByHeading($cal);
        if (!isset($page)) {
            return XH_message('fail', sprintf($ptx['message_missing_page'], $cal));
        }
        self::js();
        $o = tag(
            'img src="' . $src . '" usemap="#adventcalendar" alt="'
            . $ptx['adventcalendar'] . '"'
        );
        $o .= '<map name="adventcalendar">';
        foreach ($page->getChildren() as $i => $page) {
            if ($i >= self::getCurrentDay()) {
                break;
            }
            $coords = $data[$i];
            $href = $page->getURL() . '&amp;print';
            $o .= tag(
                'area class="adventcalendar" shape="rect" coords="'
                . implode(',', $coords) . '" href="?' . $href . '" alt="'
                . sprintf($ptx['day_n'], $i + 1) . '"'
            );
        }
        $o .= '</map>';
        return $o;
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

    /**
     * @return string
     */
    protected static function administration()
    {
        global $plugin_tx, $_XH_csrfProtection;

        $ptx = $plugin_tx['adventcalendar'];
        $cals = Calendar::getAll();

        $o = '<div id="adventcalendar_admin" class="plugineditcaption">'
            . 'Adventcalendar</div><ul>';
        foreach ($cals as $cal) {
            $o .= '<li>' . $cal->getName() . '.jpg'
                . '<form action="?adventcalendar" method="POST"'
                . ' style="display: inline">'
                . $_XH_csrfProtection->tokenInput()
                . tag('input type="hidden" name="admin" value="plugin_main"')
                . tag('input type="hidden" name="action" value="prepare"')
                . tag(
                    'input type="hidden" name="adventcalendar_name" value="'
                    . $cal->getName() . '"'
                )
                . ' '
                . tag('input type="submit" value="' . $ptx['prepare_cover'] . '"')
                . '</form>' . '</li>';
        }
        $o .= '</ul>';
        return $o;
    }

    /**
     * @return string
     */
    protected static function version()
    {
        global $pth;

        ob_start();
        (new View('adventcalendar'))
            ->template('info')
            ->data([
                'logo' => "{$pth['folder']['plugins']}adventcalendar/adventcalendar.png",
                'version' => Plugin::VERSION
            ])
            ->render();
        return ob_get_clean();
    }

    /**
     * @return string
     */
    protected static function systemCheck()
    {
        global $pth, $tx, $plugin_tx;

        $requiredVersion = '5.4.0';
        $ptx = $plugin_tx['adventcalendar'];
        $imgdir = $pth['folder']['plugins'] . 'adventcalendar/images/';
        $ok = tag('img src="' . $imgdir . 'ok.png" alt="ok"');
        $warn = tag('img src="' . $imgdir . 'warn.png" alt="warning"');
        $fail = tag('img src="' . $imgdir . 'fail.png" alt="failure"');
        $o = '<h4>' . $ptx['syscheck_title'] . '</h4>'
            . (version_compare(PHP_VERSION, $requiredVersion) >= 0 ? $ok : $fail)
            . '&nbsp;&nbsp;'
            . sprintf($ptx['syscheck_phpversion'], $requiredVersion)
            . tag('br');
        foreach (array('gd') as $ext) {
            $o .= (extension_loaded($ext) ? $ok : $fail)
                . '&nbsp;&nbsp;' . sprintf($ptx['syscheck_extension'], $ext)
                . tag('br');
        }
        $o .= (!get_magic_quotes_runtime() ? $ok : $fail)
            . '&nbsp;&nbsp;' . $ptx['syscheck_magic_quotes'] . tag('br') . tag('br');
        $o .= (strtoupper($tx['meta']['codepage']) == 'UTF-8' ? $ok : $warn)
            . '&nbsp;&nbsp;' . $ptx['syscheck_encoding'] . tag('br');
        $check = file_exists($pth['folder']['plugins'] . 'jquery/jquery.inc.php');
        $o .= ($check ? $ok : $fail) . '&nbsp;&nbsp;' . $ptx['syscheck_jquery']
            . tag('br') . tag('br');
        foreach (array('config/', 'css/', 'languages/') as $folder) {
            $folders[] = $pth['folder']['plugins'] . 'adventcalendar/' . $folder;
        }
        $folders[] = self::dataFolder();
        foreach ($folders as $folder) {
            $o .= (is_writable($folder) ? $ok : $warn) . '&nbsp;&nbsp;'
                . sprintf($ptx['syscheck_writable'], $folder) . tag('br');
        }
        return $o;
    }

    /**
     * @param string $cal
     * @return void
     */
    protected static function prepare($cal)
    {
        global $_XH_csrfProtection;

        $_XH_csrfProtection->check();
        $dn = self::dataFolder();
        $calendar = Calendar::findByName($cal);
        $im = $calendar->getImage();
        if (!$im) {
            e('cntopen', 'file', $cal); // TODO "Calendar image not readable"
            return 'wurst'.self::administration();
        }
        $calendar->calculateDoors(imagesx($im), imagesy($im));
        $image = new Image($im);
        $image->drawDoors($calendar->getDoors());

        if (!imagejpeg($im, "$dn$cal+.jpg")) {
            e('cntsave', 'file', "$dn$cal+.jpg");
            return self::administration();
        }
        if (!$calendar->save()) {
            e('cntsave', 'file', $cal); // TODO
        }

        return '<div id="adventcalendar_admin" class="plugineditcaption">'
            . 'Adventcalendar</div>'
            . tag('img src="' . "$dn$cal+.jpg" . '" width="100%" alt=""');
    }
}
