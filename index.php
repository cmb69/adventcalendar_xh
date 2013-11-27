<?php

/**
 * Front-end of Adventcalendar_XH.
 *
 * PHP versions 4 and 5
 *
 * @category  CMSimple_XH
 * @package   Adventcalendar
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2013 Christoph M. Becker <http://3-magi.net>
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
 * The plugin's version number.
 */
define('ADVENTCALENDAR_VERSION', '@ADVENTCALENDAR_VERSION@');

if (!defined('XH_ADM')) {
    define('XH_ADM', $adm);
}

/**
 * Returns an array of indexes of the direct children of a page.
 *
 * @param int  $n            A page index.
 * @param bool $ignoreHidden Whether hidden pages should be ignored.
 * 
 * @return array
 *
 * @global int   The number of pages.
 * @global array The levels of the pages.
 * @global array The configuration of the core.
 */
function Adventcalendar_childPages($n, $ignoreHidden = true)
{
    global $cl, $l, $cf;
    
    $res = array();
    $ll = $cf['menu']['levelcatch'];
    for ($i = $n + 1; $i < $cl; $i++) {
        if ($ignoreHidden && hide($i)) {
            continue;
        }
        if ($l[$i] <= $l[$n]) {
            break;
        }
        if ($l[$i] <= $ll) {
            $res[] = $i;
            $ll = $l[$i];
        }
    }
    return $res;
}

/**
 * Returns the index of the first page with a certain heading, <var>null</var>
 * if no such page exists.
 *
 * @param string $heading A heading.
 * 
 * @return int
 *
 * @global int   The number of pages.
 * @global array The headings of the pages.
 */
function Adventcalendar_pageIndex($heading)
{
    global $cl, $h;
    
    for ($i = 0; $i < $cl; $i++) {
        if ($h[$i] == $heading) {
            return $i;
        }
    }
    return null;
}

/**
 * Returns the path of the data folder.
 *
 * @return string
 *
 * @global array The paths of system files and folders.
 *
 * @todo: Create the folder, if it doesn't exist?
 */
function Adventcalendar_dataFolder()
{
    global $pth;
    
    return "{$pth['folder']['plugins']}adventcalendar/data/";
}

/**
 * Emits the required scripts to the HEAD element.
 *
 * @return void
 *
 * @global array The paths of system files and folders.
 * @global array The configuration of the plugins.
 */
function Adventcalendar_js()
{
    global $pth, $plugin_cf;
    
    $href = "{$pth['folder']['plugins']}adventcalendar/css/colorbox.css"; // FIXME
    include_once "{$pth['folder']['plugins']}jquery/jquery.inc.php";
    include_jQuery();
    $filename = $pth['folder']['plugins']
        . 'adventcalendar/colorbox/jquery.colorbox-min.js';
    include_jQueryPlugin('colorbox', $filename);
}

/**
 * Returns the advent calendar view.
 *
 * @param string $cal A calendar name.
 * 
 * @return string (X)HTML.
 *
 * @global array The paths of system files and folders.
 * @global array The URLs of the pages.
 * @global array The configuration of the plugins.
 * 
 * @access public
 */
function adventcalendar($cal)
{
    global $pth, $u, $plugin_cf;
    
    $pcf = $plugin_cf['adventcalendar'];
    if (XH_ADM) {
        $day = 24;
    } else {
        $day = intval(floor((time() - $pcf['time_start']) / 86400)) + 1;
    }
    $contents = file_get_contents(Adventcalendar_dataFolder() . $cal . '.dat');
    $data = unserialize($contents);
    $src = Adventcalendar_dataFolder() . $cal . '+.jpg';
    $n = Adventcalendar_pageIndex($cal);
    $pages = Adventcalendar_childPages($n, false);
    Adventcalendar_js();
    $o = tag('img src="' . $src . '" usemap="#adventcalendar"')
        . '<map name="adventcalendar">';
    for ($i = 0; $i < $day; $i++) {
        if (array_key_exists($i, $pages)) {
            $coords = $data[$i];
            $href = $u[$pages[$i]] . '&amp;print';
            $o .= tag(
                'area shape="rect" coords="' . implode(',', $coords)
                . '" href="?' . $href
                . '" onclick="jQuery.colorbox({maxWidth:\'80%\',href:\'?'
                . $href . '\'}); return false"'
            );
        }
    }
    $o .= '</map>';
        
    return $o;
}

?>
