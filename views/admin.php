<?php

use Adventcalendar\Infra\View;

if (!defined("CMSIMPLE_XH_VERSION")) {header("HTTP/1.1 403 Forbidden"); exit;}

/**
 * @var View $this
 * @var list<string> $calendars
 * @var string $url
 * @var string $token
 */
?>
<!-- adventcalendar admin overview -->
<div id="adventcalendar_admin">Adventcalendar</div>
<ul>
<?foreach ($calendars as $calendar):?>
  <li><?=$calendar?>.jpg
    <form action="<?=$url?>" method="post" style="display: inline">
      <input type="hidden" name="xh_csrf_token" value="<?=$token?>">
      <input type="hidden" name="adventcalendar_name" value="<?=$calendar?>">
      <input type="submit" value="<?=$this->text('prepare_cover')?>">
    </form>
  </li>
<?endforeach?>
</ul>
