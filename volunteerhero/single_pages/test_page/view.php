<?php

$db = Loader::db();
$frm = Loader::helper('form');
$result = $db->execute('INSERT INTO volunteerheroProject(service_date,current) VALUES("5/5/2012-0:0:0", 1)');
print("<h2>ID:".$db->Insert_ID()."&nbsp;RRows: ".$result->RecordCount()."&nbsp;DBRows: ".$db->Affected_Rows()."</h2>");

print("<h3>Result</h3>");
foreach(get_class_methods($result) as $c) {
  print($c . "<br/>");
}
print("<h3>DB</h3>");
foreach(get_class_methods($db) as $c) {
  print($c . "<br/>");
}
exit();
/*print_r($db);
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
echo $frm->radio('hello', 'Hi', TRUE);*/

Loader::model("groups");
echo Group::getByName("dne") instanceof Group?"True":"False";
echo "<br/>";
echo Group::getByName("winterization_crew") instanceof Group?"True":"False";
exit();
?>

<div id="effect" style="height: 100px; width: 75px; border: 3px solid black;"></div>
<script>
  $("#effect").click(function() {
    $("#effect").slideUp(1000);
    setTimeout(function() {$("#effect").slideDown(1000);}, 3000);
    return false;
  });
</script>
