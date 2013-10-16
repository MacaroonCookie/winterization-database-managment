<?php defined('C5_EXECUTE') or die(_("Access Denied."));
  Loader::model("volunteers", "volunteerhero");
  $js = Loader::helper('json');
  $db = Loader::db();
  $list = new VolunteerList();

  echo $js->encode($list->getList());
  exit();


?>
