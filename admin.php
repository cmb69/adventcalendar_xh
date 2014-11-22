<?php

/**
 * Administration of Adventcalendar_XH.
 *
 * PHP versions 4 and 5
 *
 * @category  CMSimple_XH
 * @package   Adventcalendar
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2014 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Adventcalendar_XH
 */

/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

/**
 * Returns the version information view.
 *
 * @return string (X)HTML.
 *
 * @global array The paths of system files and folders.
 */
function Adventcalendar_version()
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
function Adventcalendar_systemCheck()
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
    $folders[] = Adventcalendar_dataFolder();
    foreach ($folders as $folder) {
        $o .= (is_writable($folder) ? $ok : $warn) . '&nbsp;&nbsp;'
            . sprintf($ptx['syscheck_writable'], $folder) . tag('br');
    }
    return $o;
}

/**
 * Returns an allocated color.
 *
 * @param resource $im  A GD image.
 * @param string   $col A 24-bit hexadecimal RGB value.
 * 
 * @return int
 */
function Adventcalendar_color($im, $col)
{
    $c = base_convert($col, 16, 10);
    $r = $c >> 16;
    $g = ($c & 0xffff) >> 8;
    $b = $c & 0xff;
    return imagecolorallocate($im, $r, $g, $b);
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
function Adventcalendar_prepare($cal)
{
    global $plugin_cf;
    
    $pcf = $plugin_cf['adventcalendar'];
    $dn = Adventcalendar_dataFolder();
    
    if (($im = imagecreatefromjpeg($dn . $cal . '.jpg')) === false) {
        e('cntopen', 'file', "$dn$cal.jpg");
        return Adventcalendar_administration();
    }
    $w = imagesx($im);
    $h = imagesy($im);
    if ($w >= $h) {
        $doorsPerRow = 6; $doorsPerCol = 4;
    } else {
        $doorsPerRow = 4; $doorsPerCol = 6;
    }
    $dw = $pcf['door_width'];
    $dh = $pcf['door_height'];
    $dx = ($w - $doorsPerRow * $dw) / ($doorsPerRow + 1);
    $dy = ($h - $doorsPerCol * $dh) / ($doorsPerCol + 1);
    $dc = Adventcalendar_color($im, $pcf['color_door']);
    $fc = Adventcalendar_color($im, $pcf['color_font']);
    $sc = Adventcalendar_color($im, $pcf['color_fringe']);
    
    $doors = array();
    for ($i = 0; $i < $doorsPerRow; $i++) {
        $x1 = ($i + 1) * $dx + $i * $dw;
        $x2 = $x1 + $dw;
        for ($j = 0; $j < $doorsPerCol; $j++) {
            $y1 = ($j + 1) * $dy + $j * $dh;
            $y2 = $y1 + $dh;
            $doors[] = array(round($x1), round($y1), round($x2), round($y2));
        }
    }
    shuffle($doors);

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
        return Adventcalendar_administration();
    }
    if (($fh = fopen("$dn$cal.dat", 'wb')) === false
        || fwrite($fh, serialize($doors)) === false
    ) {
        e('cntsave', 'file', "$dn$cal.dat");
    }
    if ($fh !== false) {
        fclose($fh);
    }

    return '<div id="adventcalendar_admin" class="plugineditcaption">'
        . 'Adventcalendar</div>'
        . tag('img src="' . "$dn$cal+.jpg" . '" width="100%" alt=""');
}

/**
 * Returns the main administration view.
 *
 * @return string (X)HTML.
 *
 * @global array The localization of the plugins.
 */
function Adventcalendar_administration()
{
    global $plugin_tx;
    
    $ptx = $plugin_tx['adventcalendar'];
    $cals = array();
    $dn = Adventcalendar_dataFolder();
    $dh = opendir($dn);
    while (($fn = readdir($dh)) !== false) {
        if (pathinfo($dn . $fn, PATHINFO_EXTENSION) == 'jpg'
            && strpos($bn = basename($fn, '.jpg'), '+') != strlen($bn) - 1
        ) {
            $cals[] = $fn;
        }
    }
    closedir($dh);
    
    $o = '<div id="adventcalendar_admin" class="plugineditcaption">'
        . 'Adventcalendar</div><ul>';
    foreach ($cals as $cal) {
        $o .= '<li>' . $cal
            . '<form action="?adventcalendar" method="POST" style="display: inline">'
            . tag('input type="hidden" name="admin" value="plugin_main"')
            . tag('input type="hidden" name="action" value="prepare"')
            . tag(
                'input type="hidden" name="adventcalendar_name" value="'
                . basename($cal, '.jpg') . '"'
            )
            . ' '
            . tag('input type="submit" value="' . $ptx['prepare_cover'] . '"')
            . '</form>' . '</li>';
    }
    $o .= '</ul>';
    return $o;
}

/*
 * Handle the plugin administration.
 */
if (isset($adventcalendar) && $adventcalendar == 'true') {
    $o .= print_plugin_admin('on');
    switch ($admin) {
    case '':
        $o .= Adventcalendar_version() . tag('hr') . Adventcalendar_systemCheck();
        break;
    case 'plugin_main':
        switch ($action) {
        case 'prepare':
            $o .= Adventcalendar_prepare(stsl($_POST['adventcalendar_name']));
            break;
        default:
            $o .= Adventcalendar_administration();
        }
        break;
    default:
        $o .= plugin_admin_common($action, $admin, $plugin);
    }
}

?>
