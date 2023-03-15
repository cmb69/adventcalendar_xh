<?php

use Adventcalendar\Calendar;
use Adventcalendar\Infra\View;

/**
 * @var View $this
 * @var array<Calendar> $calendars
 * @var string $url
 * @var string $csrfTokenInput
 */
?>
<div id="adventcalendar_admin" class="plugineditcaption">Adventcalendar</div>
<ul>
<?php foreach ($calendars as $calendar):?>
    <li><?=$calendar->getName()?>.jpg
        <form action="<?=$url?>" method="POST" style="display: inline">
            <?=$csrfTokenInput?>
            <input type="hidden" name="adventcalendar_name" value="<?=$calendar->getName()?>">
            <input type="submit" value="<?=$this->text('prepare_cover')?>">
        </form>
    </li>
<?php endforeach?>
</ul>
