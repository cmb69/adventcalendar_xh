<img src="<?=$src?>" usemap="#adventcalendar" alt="<?=$this->text('adventcalendar')?>">
<map name="adventcalendar">
<?php foreach ($doors as $i => $door):?>
    <area class="adventcalendar" shape="rect" coords="<?=$door->coords?>" href="<?=$door->href?>" 
          alt="<?=$this->text('day_n', $i)?>">
<?php endforeach?>
</map>
