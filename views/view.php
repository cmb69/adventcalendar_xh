<?php

use Adventcalendar\Infra\View;

if (!defined("CMSIMPLE_XH_VERSION")) {header("HTTP/1.1 403 Forbidden"); exit;}

/**
 * @var View $this
 * @var string $src
 */
?>
<!-- adventcalendar cover view -->
<section id="adventcalendar_admin">
  <h1>Adventcalendar – <?=$this->text('menu_main')?></h1>
  <p><img src="<?=$src?>"></p>
</section>
