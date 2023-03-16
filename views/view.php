<?php

use Adventcalendar\Infra\View;

if (!defined("CMSIMPLE_XH_VERSION")) {header("HTTP/1.1 403 Forbidden"); exit;}

/**
 * @var View $this
 * @var string $src
 */
?>
<!-- adventcalendar cover view -->
<div id="adventcalendar_admin">Adventcalendar</div>
<img src="<?=$src?>" width="100%" alt="">
