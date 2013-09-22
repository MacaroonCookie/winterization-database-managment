<?php defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<?php if( $this->controller->getTask()=="add" || $this->controller->getTask()=="update" || $this->controller->getTask()=="setcurrent" ) { ?>
  <div class="winter-output">
    <?php if( isset( $output ) ) { ?>
    <div class="winter-message"><p><?php print $output['message']; ?></p></div>
    <div class="winter-error-message"><p><?php print $output['error_message']; ?></p></div>
    <?php } else { ?>
    <div class="winter-error-message"><p>ERROR: Missing information from server</p></div>
    <?php } ?>
  </div>
<?php } else { ?>
  <?php if( !isset( $projects ) ) { ?>
    <div class="winter-error-message"><p>ERROR: Missing information from server</p></div>
  <?php } else { ?>
  <table class="winter-table-main" width="100%">
    <tr class="winter-table-headrow">
      <th>Year</th>
      <th colspan=2>Service Date</th>
    </tr>
    <tr class="winter-table-row">
      <td colspan=3><a id="add_project" href="#">Add New Project</a></td>
    </tr>
    <tr class="winter-table-row-hiddenform" id="winter-hidden-add" style="display:none;">
      <form method="post" action="<?=$this->action('add');?>">
      <td><?php print $f->label('project_date', 'Select Date of Service Project'); ?>&nbsp;
          <?php print $fdt->date('project_date', date('m/d/Y'), TRUE); ?></td>
      <td><?php print $f->label('project_setcurrent', 'Set Current Project?');?> &nbsp;
          <?php print $f->checkbox('project_setcurrent', 'setcurrent', True, array()); ?>
      </td>
      <td><?=$f->submit("submit_new", "Submit");?></td>
      </form>
    </tr>
    <?php foreach($projects as $project) { ?>
    <tr class="winter-table-row">
      <td><?php print date("Y", strtotime($project['date'])); ?></td>
      <td><?php print date("F jS, Y", strtotime($project['date'])); ?></td>
      <td><a id="edit_project_<?=$project['id']?>" href="#">Edit</a><?php if( !$project['current'] ) { ?><br/><a>Set Current</a><?php } ?></td>
    </tr>
    <tr class="winter-table-row-hiddenform" id="winter-hidden-update-<?php print $project["id"] ?>" style="display:none;">
    <form id="winter-project-update-<?php print $project["id"]; ?>" method="post" action="<?php print $this->action('update'); ?>">
        <?php print $f->hidden("project_id", $project["id"]); ?>
      <td colspan=2><?=$f->label('project_date_'.$project["id"], 'Date');?>&nbsp;
        <?=$fdt->date('project_date_'.$project["id"], date('m/d/Y', strtotime($project['date'])), TRUE); ?></td>
      <td><?php print $f->submit('winter-update-'.$project["id"].'-submit', "Update"); ?></td>
    </form>
    </tr>
    <script type="text/javascript">$("a#edit_project_<?=$project['id']?>").click(function() { $("tr#winter-hidden-update-<?=$project['id']?>").fadeToggle('slow');});</script>
    <?php } // End of foreach statement ?>
  </table>
  <script type="text/javascript">$("a#add_project").click(function() { $("tr#winter-hidden-add").fadeToggle('slow');});</script>
<?php } } ?>
