<?php

use Adventcalendar\Infra\View;

if (!defined("CMSIMPLE_XH_VERSION")) {header("HTTP/1.1 403 Forbidden"); exit;}

/**
 * @var View $this
 * @var string $src
 * @var array<array{coords:string,href:string}> $doors
 */
?>
<!-- adventcalendar -->
<img src="<?=$src?>" usemap="#adventcalendar" alt="<?=$this->text('adventcalendar')?>">
<map name="adventcalendar">
<?foreach ($doors as $i => $door):?>
	<area class="adventcalendar" shape="rect" coords="<?=$door['coords']?>" href="<?=$door['href']?>" 
		  alt="<?=$this->text('day_n', $i)?>">
<?endforeach?>
</map>
