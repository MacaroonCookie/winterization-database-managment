<?php

$db = Loader::db();
$frm = Loader::helper('form');
$result = $db->execute('SELECT NULL');
print_r($db);
echo '<br/><br/>';
print_r(get_class_methods($db));
echo '<br/><br/>';
print_r($result);
echo '<br/><br/>';
print_r(get_class_methods($result));
echo '<br/><br/>';
echo '<p>'.$result->_numOfRows . '</p>';
echo '<h4><label for="hi">Hi</label><input type="checkbox" name="hi" value="hi"/></h4>';
echo $frm->radio('hello', 'Hello', 'hello');
echo $frm->label('hello2', 'Hi!');
echo $frm->radio('hello', 'Hi', TRUE);
?>

<div id="effect" style="height: 100px; width: 75px; border: 3px solid black;"></div>
<script>
  $("#effect").click(function() {
    $("#effect").slideUp(1000);
    setTimeout(function() {$("#effect").slideDown(1000);}, 3000);
    return false;
  });
</script>
