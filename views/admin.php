<?php

use Adventcalendar\Infra\View;

if (!defined("CMSIMPLE_XH_VERSION")) {header("HTTP/1.1 403 Forbidden"); exit;}

/**
 * @var View $this
 * @var list<array{id:string,name:string,url:string}> $calendars
 * @var string $url
 * @var string $token
 */
?>
<!-- adventcalendar admin overview -->
<section id="adventcalendar_admin">
  <h1>Adventcalendar – <?=$this->text('menu_main')?></h1>
  <form method="get">
    <input type="hidden" name="selected" value="adventcalendar">
    <input type="hidden" name="admin" value="plugin_main">
    <table>
<?foreach ($calendars as $calendar):?>
      <tr>
        <td><input id="<?=$calendar['id']?>" type="radio" name="adventcalendar_name" value="<?=$calendar['name']?>"></td>
        <td><label for="<?=$calendar['id']?>"><?=$calendar['name']?></label></td>
        <td><label for="<?=$calendar['id']?>"><img src="<?=$calendar['url']?>"></label></td>
      </tr>
<?endforeach?>
    </table>
    <p>
      <button name="action" value="prepare"><?=$this->text('prepare_cover')?></button>
    </p>
  </form>
</section>
