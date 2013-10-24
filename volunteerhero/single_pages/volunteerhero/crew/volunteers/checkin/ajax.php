<?php defined('C5_EXECUTE') or die(_("Access Denied."));

$json = Loader::helper('json');
echo $json->encode($output);
exit();

?>
