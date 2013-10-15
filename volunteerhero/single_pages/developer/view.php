<?php
if($this->getTask()=='update') {?>
  <p>Uninstalled DB Tables</p>
<?php } else { ?>
  <p><a a href=<?=$this->url('/developer', 'uninstalltables');?>>Uninstall DB Tables</a></p>
<?php { ?>
