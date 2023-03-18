<?php

use Adventcalendar\Infra\View;

if (!defined("CMSIMPLE_XH_VERSION")) {header("HTTP/1.1 403 Forbidden"); exit;}

/**
 * @var View $this
 * @var string $url
 * @var string $token
 */
?>
<!-- adventcalendar confirmation -->
<section id="adventcalendar_admin">
  <h1>Adventcalendar – <?=$this->text('menu_main')?></h1>
  <form method="post">
    <input type="hidden" name="xh_csrf_token" value="<?=$token?>">
    <p><img src="<?=$url?>"></p>
    <p>
      <button name="adventcalendar_do"><?=$this->text('prepare_cover')?></button>
    </p>
  </form>
</section>
