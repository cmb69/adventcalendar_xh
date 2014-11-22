<?php

/**
 * The controller.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Adventcalendar
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2014 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Adventcalendar_XH
 */

/**
 * The controller.
 *
 * @category CMSimple_XH
 * @package  Adventcalendar
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Adventcalendar_XH
 */
class Adventcalendar_Controller
{
    /**
     * Returns the path of the data folder.  Tries to create it, if necessary.
     *
     * @return string
     *
     * @global array The paths of system files and folders.
     * @global array The configuration of the plugins.
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
     * Dispatches on plugin related requests.
     *
     * @return void
     */
    public static function dispatch()
    {
        if (XH_ADM) {
            self::handleAdministration();
        }
    }

    /**
     * Handles the plugin administration.
     *
     * @return void
     *
     * @global string Whether the plugin administration has been requested.
     * @global string The value of the <var>admin</var> GP parameter.
     * @global string The value of the <var>action</var> GP parameter.
     * @global string The (X)HTML fragment of the contents area.
     */
    protected static function handleAdministration()
    {
        global $adventcalendar, $admin, $action, $o;

        if (isset($adventcalendar) && $adventcalendar == 'true') {
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
    }

    /**
     * Returns the advent calendar view.
     *
     * @param string $cal A calendar name.
     *
     * @return string (X)HTML.
     *
     * @global array The paths of system files and folders.
     * @global array The configuration of the plugins.
     * @global array The localization of the plugins.
     */
    public static function main($cal)
    {
        global $pth, $plugin_cf, $plugin_tx;

        $pcf = $plugin_cf['adventcalendar'];
        $ptx = $plugin_tx['adventcalendar'];
        if (XH_ADM) {
            $day = 24;
        } else {
            $day = (int) floor((time() - strtotime($pcf['date_start'])) / 86400) + 1;
        }
        $calendar = Adventcalendar_Calendar::findByName($cal);
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
        $page = Adventcalendar_Page::getByHeading($cal);
        if (isset($page)) {
            self::js();
            $o = tag(
                'img src="' . $src . '" usemap="#adventcalendar" alt="'
                . $ptx['adventcalendar'] . '"'
            );
            $o .= '<map name="adventcalendar">';
            foreach ($page->getChildren() as $i => $page) {
                if ($i >= $day) {
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
        }

        return $o;
    }

    /**
     * Emits the required scripts to the HEAD element.
     *
     * @return void
     *
     * @global array The paths of system files and folders.
     * @global array The configuration of the plugins.
     * @global string The (X)HTML fragment to insert into the HEAD element.
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
     * Returns the main administration view.
     *
     * @return string (X)HTML.
     *
     * @global array The localization of the plugins.
     */
    protected static function administration()
    {
        global $plugin_tx;

        $ptx = $plugin_tx['adventcalendar'];
        $cals = Adventcalendar_Calendar::getAll();

        $o = '<div id="adventcalendar_admin" class="plugineditcaption">'
            . 'Adventcalendar</div><ul>';
        foreach ($cals as $cal) {
            $o .= '<li>' . $cal->getName() . '.jpg'
                . '<form action="?adventcalendar" method="POST"'
                . ' style="display: inline">'
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
     * Returns the version information view.
     *
     * @return string (X)HTML.
     *
     * @global array The paths of system files and folders.
     */
    protected static function version()
    {
        global $pth;

        return '<h1><a href="http://3-magi.net/?CMSimple_XH/Adventcalendar_XH">'
            . 'Adventcalendar_XH</a></h1>'
            . tag(
                'img style="float: left; margin-right: 10px" src="'
                . $pth['folder']['plugins'] . 'adventcalendar/adventcalendar.png"'
                . ' alt="Plugin icon"'
            )
            . '<p>Version: ' . ADVENTCALENDAR_VERSION . '</p>'
            . '<p>Copyright &copy; 2012-2014 <a href="http://3-magi.net/">'
            . 'Christoph M. Becker</a></p>'
            . '<p style="text-align:justify">This program is free software:'
            . ' you can redistribute it and/or modify'
            . ' it under the terms of the GNU General Public License as published by'
            . ' the Free Software Foundation, either version 3 of the License, or'
            . ' (at your option) any later version.</p>'
            . '<p style="text-align:justify">This program is distributed'
            . ' in the hope that it will be useful,'
            . ' but WITHOUT ANY WARRANTY; without even the implied warranty of'
            . ' MERCHAN&shy;TABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the'
            . ' GNU General Public License for more details.</p>'
            . '<p style="text-align:justify">You should have received a copy of the'
            . ' GNU General Public License along with this program.  If not, see'
            . ' <a href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/'
            . '</a>.</p>';
    }

    /**
     * Returns the requirements information view.
     *
     * @return string  (X)HTML.
     *
     * @global array The paths of system files and folders.
     * @global array The localization of the core.
     * @global array The localization of the plugins.
     */
    protected static function systemCheck()
    {
        global $pth, $tx, $plugin_tx;

        $requiredVersion = '4.3.0';
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
     * Prepares an image as advent calendar.
     *
     * @param string $cal An image file name.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     */
    protected static function prepare($cal)
    {
        global $plugin_cf;

        $pcf = $plugin_cf['adventcalendar'];

        $dn = self::dataFolder();
        $calendar = Adventcalendar_Calendar::findByName($cal);
        $im = $calendar->getImage();
        if (!$im) {
            e('cntopen', 'file', $cal); // TODO "Calendar image not readable"
            return self::administration();
        }
        $calendar->calculateDoors(imagesx($im), imagesy($im));
        $doors = $calendar->getDoors();
        $dc = self::color($im, $pcf['color_door']);
        $fc = self::color($im, $pcf['color_font']);
        $sc = self::color($im, $pcf['color_fringe']);
        for ($i = 0; $i < 24; $i++) {
            list($x1, $y1, $x2, $y2) = $doors[$i];
            imagerectangle($im, $x1, $y1, $x2, $y2, $dc);
            for ($j = $x1 + 1; $j <= $x1 + 3; $j++) {
                for ($k = $y1; $k <= $y1 + 2; $k++) {
                    imagestring($im, 5, $j, $k, $i + 1, $sc);
                }
            }
            imagestring($im, 5, $x1 + 2, $y1 + 1, $i + 1, $fc);
        }

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

    /**
     * Returns an allocated color.
     *
     * @param resource $im  A GD image.
     * @param string   $col A 24-bit hexadecimal RGB value.
     *
     * @return int
     */
    protected static function color($im, $col)
    {
        $c = base_convert($col, 16, 10);
        $r = $c >> 16;
        $g = ($c & 0xffff) >> 8;
        $b = $c & 0xff;
        return imagecolorallocate($im, $r, $g, $b);
    }
}

?>
