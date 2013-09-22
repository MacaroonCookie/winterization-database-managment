<?php
  $update=($this->controller->getTask()=="update" ? True : False);
  if( $this->controller->getTask()=='add' || $this->controller->getTask()=='update' ) {
    $form = Loader::helper('form');
    print $form->hidden("method", $update?"update":"add");
    if($update) {
      print $form->hidden("org_name", $oname);
    }?>
  <tr colspan=2><th>Add Resident</th></tr>
  <div id="form_resident">
    <!-- This is not complete. I need to add id's and tags to add javascript. -->
    <!-- Can possibly use 'placeholder' attribute in inputs to add hints -->
    <!-- Need to add ability to pull data from previous year and insert around form -->
    <!-- Add Hints & Tips around form -->
    <form method="post" action="<?php print $this->action('addresident'); ?>">
    <div><?php print $form->label("res_name", "Name"); ?>
         <?php print $form->text("res_name", ($update?$oname:"First Last"), array("tabindex"=>1)); ?></div>
    <div><?php print $form->label("res_phone", "Phone #"); ?>
         <?php print $form->text("res_phone", ($update?$ophone:"###-###-####"), array("tabindex"=>2)); ?></div>
    <div><?php print $form->label("res_zipcode", "Zipcode"); ?>
         <?php print $form->text("res_zipcode", ($update?$ozipcode:"#####"), array("tabindex"=>3)); ?></div>
    <div><?php print $form->label("res_address", "Address"); ?>
         <?php print $form->text("res_address", ($update?$oaddress:"123 State St"), array("tabindex"=>4)); ?></div>
    <div><?php print $form->label("res_calltime", "Preferred Call Time"); ?>
         <?php print $form->select("res_calltime", array("any"=>"Any Time", "morning"=>"Morning 9AM-12PM", "afternoon"=>"Afternoon 12PM-4PM", "evening"=>"Evening 4PM-9PM"), ($update?$ocalltime:"any"), array("tabindex"=>5)); ?></div>
    <!-- Raking Section -->
    <div><?php print $form->label("res_raking", "Raking"); ?>
         <?php print $form->checkbox("res_raking", "raking", $update?$oraking:False, array("tabindex"=>6)); ?>
         <?php print $form->label("res_lawnsize", "Lawn Size");
               print $form->select("res_lawnsize", array("small"=>"Small", "medium"=>"Medium", "large"=>"Large", "xlarge"=>"Extra Large (Dragon)", "xxlarge"=>"XX Large (Double Dragon)"), ($update?$olawnsize:"small"), array("tabindex"=>7)); ?></div>
    <div><?php print $form->label("res_numrakes", "# of Rakes");
               print $form->text("res_numrakes", $updates?$onumrakes:"#", array("tabindex"=>8)); ?></div>
    <div><?php print $form->label("res_leafdisposal", "Method to Dispose of Leaves");
               print $form->select("res_leafdisposal", array("curb"=>"Rake to Curbside", "bag"=>"Bag", "burn"=>"Rake to Burn Pile"), ($update?$oleafdisposal:"curb"), array("tabindex"=>9)); ?></div>
    <!-- Gutter Cleaning -->
    <!-- Only allowed to clean gutters on first floot -->
    <div><?php print $form->label("res_gutters", "Gutter Cleaning");
               print $form->checkbox("res_gutters", "gutters", $update?$ogutters:False, array("tabindex"=>10)); ?></div>
    <div><?php print $form->label("res_gutterladder", "Does Resident Have Ladder?");
               print $form->radio("res_gutterladder", "yes", $update&&$oladder?"yes":"no", array("tabindex"=>12)); ?>Yes
         <?php print $form->radio("res_gutterladder", "no", $update&&$oladder?"yes":"no", array("tabindex"=>11)); ?>No</div>
    <!-- Yard Care -->
    <div><?php print $form->label("res_bushes", "Bush Trimming");
               print $form->checkbox("res_bushes", "bushtrimming", $update?$obushtrimming:False, array("tabindex"=>13)); ?></div>
    <div><?php print $form->label("res_clippers", "Does Resident Have Clippers?");
               print $form->radio("res_clippers", "yes", $update&&$oclippers?"yes":"no", array("tabindex"=>15)); ?>Yes
         <?php print $form->radio("res_clippers", "no", $update&&$oclippers?"yes":"no", array("tabindex"=>14)); ?>No</div>
    <!-- Window Washing -->
    <div><?php print $form->label("res_windowwashing", "Window Washing");
               print $form->checkbox("res_windowwashing", "windowwashing", $update?$owindowwashing:False, array("tabindex"=>16)); ?></div>
    <div><?php print $form->label("res_numwindows", "# of Windows (Approximation)");
               print $form->text("res_numwindows", $update?$onumwindows:"#", array("tabindex"=>17)); ?></div>
    <div><?php print $form->label("res_comments", "Comments"); ?><br/>
         <?php print $form->textarea("res_comments", $update?$ocomments:"", array("tabindex"=>18, "wrap"=>"soft", "rows"=>15, "cols"=>50, "maxlength"=>256)); ?></div>
    <div><?php print $form->submit("res_submit", "Add Resident", array("tabindex"=>19)); ?> </div>
    </form>
  </div>
<?php } else {

  }
?>
