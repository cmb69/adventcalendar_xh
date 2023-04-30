<?php

use Adventcalendar\Infra\View;

if (!defined("CMSIMPLE_XH_VERSION")) {header("HTTP/1.1 403 Forbidden"); exit;}

/**
 * @var View $this
 * @var string $src
 * @var array<array{day:int,coords:string,href:string}> $doors
 * @var string $width
 * @var string $height
 */
?>
<!-- adventcalendar -->
<script type="module">
jQuery("area.adventcalendar").click(function (event) {
    let map = jQuery(event.currentTarget).parent("map");
    jQuery.colorbox({
        iframe: true, href: this.href,
        maxWidth: "100%", maxHeight: "100%",
        innerWidth: map.data("width"), innerHeight: map.data("height")
    });
    event.preventDefault();
});
</script>
<img src="<?=$src?>" usemap="#adventcalendar" alt="<?=$this->text('adventcalendar')?>">
<map name="adventcalendar" data-width="<?=$width?>" data-height="<?=$height?>">
<?foreach ($doors as $door):?>
    <area class="adventcalendar" shape="rect" coords="<?=$door['coords']?>" href="<?=$door['href']?>" 
          alt="<?=$this->text('day_n', $door['day'])?>">
<?endforeach?>
</map>
