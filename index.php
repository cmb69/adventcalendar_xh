<?php

/**
 * Front-end of Adventcalendar_XH.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Adventcalendar
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2017 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Adventcalendar_XH
 */

/*
 * Prevent direct access and usage from unsupported CMSimple_XH versions.
 */
if (!defined('CMSIMPLE_XH_VERSION')
    || strpos(CMSIMPLE_XH_VERSION, 'CMSimple_XH') !== 0
    || version_compare(CMSIMPLE_XH_VERSION, 'CMSimple_XH 1.6', 'lt')
) {
    header('HTTP/1.1 403 Forbidden');
    header('Content-Type: text/plain; charset=UTF-8');
    die(<<<EOT
Adventcalendar_XH detected an unsupported CMSimple_XH version.
Uninstall Adventcalendar_XH or upgrade to a supported CMSimple_XH version!
EOT
    );
}

/**
 * The plugin's version number.
 */
define('ADVENTCALENDAR_VERSION', '@ADVENTCALENDAR_VERSION@');

/**
 * Returns the advent calendar view.
 *
 * @param string $cal A calendar name.
 * 
 * @return string (X)HTML.
 */
function adventcalendar($cal)
{
    return Adventcalendar\Controller::main($cal);
}

Adventcalendar\Controller::dispatch();

?>
