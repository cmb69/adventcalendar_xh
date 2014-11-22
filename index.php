<?php

/**
 * Front-end of Adventcalendar_XH.
 *
 * PHP version 5
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

require_once $pth['folder']['plugin_classes'] . 'Controller.php';

/**
 * The plugin's version number.
 */
define('ADVENTCALENDAR_VERSION', '@ADVENTCALENDAR_VERSION@');

if (!defined('XH_ADM')) {
    define('XH_ADM', $adm);
}

/**
 * Returns the advent calendar view.
 *
 * @param string $cal A calendar name.
 * 
 * @return string (X)HTML.
 */
function adventcalendar($cal)
{
    return Adventcalendar_Controller::main($cal);
}

Adventcalendar_Controller::dispatch();

?>
