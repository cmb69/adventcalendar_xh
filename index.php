<?php

/**
 * Front-end of Adventcalendar_XH.
 *
 * Copyright (c) 2012 Christoph M. Becker (see license.txt)
 */


if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


define('ADVENTCALENDAR_VERSION', '1beta2');


if (!defined('XH_ADM')) {
    define('XH_ADM', $adm);
}


/**
 * Returns the list of indexes of direct children of page no. $n.
 *
 * @param  int $n
 * @param  bool $ignoreHidden  Whether hidden pages should be ignored.
 * @return array of int.
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
 * Returns the index of the first page with the $heading.
 *
 * @param  string $heading
 * @return int
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
 */
function Adventcalendar_dataFolder()
{
    global $pth;
    
    return "{$pth['folder']['plugins']}adventcalendar/data/";
}


/**
 * Emits the required scripts to the <head>.
 *
 * @return void
 */
function Adventcalendar_js()
{
    global $pth, $plugin_cf;
    
    $href = "{$pth['folder']['plugins']}adventcalendar/css/colorbox.css";
    include "{$pth['folder']['plugins']}jquery/jquery.inc.php";
    include_jQuery();
    $js = "{$pth['folder']['plugins']}adventcalendar/colorbox/jquery.colorbox-min.js";
    include_jQueryPlugin('colorbox', $js);
}


/**
 * Returns the advent calendar view.
 *
 * @access public
 * @param  string $cal  The name of the calendar.
 * @return string  The (X)HTML.
 */
function Adventcalendar($cal)
{
    global $adm, $pth, $u, $plugin_cf;
    
    $pcf = $plugin_cf['adventcalendar'];
    if (XH_ADM) {
        $day = 24;
    } else {
        $day = intval(floor((time() - $pcf['time_start']) / 86400)) + 1;
    }
    $data = unserialize(file_get_contents(Adventcalendar_dataFolder() . $cal . '.dat'));
    $src = Adventcalendar_dataFolder() . $cal . '+.jpg';
    $n = Adventcalendar_pageIndex($cal);
    $pages = Adventcalendar_childPages($n);
    Adventcalendar_js();
    $o = tag('img src="' . $src . '" usemap="#adventcalendar"')
        . '<map name="adventcalendar">';
    for ($i = 0; $i < $day; $i++) {
        if (array_key_exists($i, $pages)) {
            $coords = $data[$i];
            $href = $u[$pages[$i]] . '&amp;print';
            $o .= tag('area shape="rect" coords="' . implode(',', $coords) . '" href="?' . $href
                      . '" onclick="jQuery.colorbox({maxWidth:\'80%\',href:\'?' . $href . '\'}); return false"');
        }
    }
    $o .= '</map>';
        
    return $o;
}

?>
