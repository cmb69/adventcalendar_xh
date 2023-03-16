<?php

use Adventcalendar\Infra\View;

if (!defined("CMSIMPLE_XH_VERSION")) {header("HTTP/1.1 403 Forbidden"); exit;}

/**
 * @var View $this
 * @var string $logo
 * @var string $version
 * @var list<array{class:string,key:string,arg:string,statekey:string}> $checks
 */
?>
<!-- adventcalendar plugin info -->
<h1>Adventcalendar <?=$version?></h1>
<div>
  <h2><?=$this->text('syscheck_title')?></h2>
<?foreach ($checks as $check):?>
  <p class="<?=$check['class']?>"><?=$this->text($check['key'], $check['arg'])?><?=$this->text($check['statekey'])?></p>
<?endforeach?>
</div>
