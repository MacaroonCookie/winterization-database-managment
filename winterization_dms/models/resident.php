<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::model('project', 'winterization_dms');
Loader::model('jobs', 'winterization_dms');
class ResidentModel {
  public $id=NULL, $pid, $firstname, $lastname, $address, $city, $zip, $complete, $callback, $jobs, $notes;
  private $db, $frm;
  private $state, $project;

  public function __construct($state='get', $locid=NULL, $projid=NULL, $firstname=NULL, $lastname=NULL, $address=NULL, $city=NULL, $zip=NULL, $notes=NULL, $complete=NULL, $callagain=NULL, $jobs=NULL) {
    $this->db = Loader::db();
    $this->frm = Loader::helper("form");
    $this->state = $state;
    $this->pid = $projid;
    $this->project = $projid==NULL?new ProjectModel():new ProjectModel('get', $projid);
    $this->id = $locid;
    $this->firstname = $firstname;
    $this->lastname = $lastname;
    $this->address = $address;
    $this->city = $city;
    $this->zip = $zip;
    $this->complete = $complete;
    $this->callback = $callagain;
    $this->notes = $notes;
    $this->jobs = $jobs;

    if( $state=='add' ) {
      $this->_newResident();
    } else if( $state=='update' ) {
      $this->_updateResident();
    } else if( $state=='delete' ) {
      $this->_deleteResident();
    } else {
      $this->_getResident();
    }
  }

  private function _newResident() {
    $this->db->execute('INSERT INTO winter_Residents(res_id, res_projid, res_firstname, res_lastname, res_address, res_city, res_zipcode, res_complete, res_callback, res_notes) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
        array($this->id, $this->pid, $this->firstname, $this->lastname, $this->address, $this->city, $this->zip, $this->complete, $this->callback, $this->notes));
    $this->jobs->add();
  }

  private function _getResident() {
    $result = NULL;
    if( $this->id!=NULL ) {
      $result = $this->db->execute('SELECT * FROM winter_Residents WHERE res_id=?', array($this->id));
    } else if( $this->firstname!=NULL && $this->lastname!=NULL && $this->pid!=NULL ) {
      $result = $this->db->execute('SELECT * FROM winter_Residents WHERE res_projid=? AND res_firstname LIKE ? AND res_lastname LIKE ?',
                                   array($this->pid, $this->firstname, $this->lastname));
    }
    $rs = '';
    if( $rs=$result->FetchRow() ) {
      if( $this->id==NULL ) $this->id = $rs['res_id'];
      $this->pid = $rs['res_projid'];
      $this->firstname = $rs['res_firstanem'];
      $this->lastname = $rs['res_lastname'];
      $this->name = "$this->firstname $this->lastname";
      $this->address= $rs['res_address'];
      $this->city = $rs['res_city'];
      $this->zip = $rs['res_zipcode'];
      $this->complete = $rs['complete']==1?TRUE:FALSE;
      $this->callback = $rs['callback']==1?TRUE:FALSE;
      $this->notes = $rs['notes'];
      $this->jobs = new JobsModel($this->id);
    } else {
      $this->jobs = new JobsModel();
    }
  }

  private function _updateResident() {
    $this->db->execute('UPDATE winter_Residents res_projid=?, res_firstname=?, res_lastname=?, res_address=?, res_city=?, res_zipcode=?, res_complete=?, res_callback=?, res_notes=? WHERE res_id=?',
                       array($this->pid, $this->firstname, $this->lastname, $this->address, $this->city, $this->zip, $this->complete, $this->callback, $this->notes, $this->id));
    $this->jobs->update();
  }

  private function _deleteResident() {
    if( $this->id==NULL ) return;
    $this->db->execute('DELETE FROM winter_ResidentJobTasks WHERE resjob_resid=?', array($this->id));
    $this->db->execute('DELETE FROM winter_Residents WHERE res_id=?', array($this->id));
  }

  public function getForm($action) {
    /* Returns an HTML form for the resident object.
     *   Args:
     *     action: the action URL
     * Return: string of a form in HTML format
     */
    if( $state=="del" || $state=="upd" ) { return ""; }
    else {
      $tab=1;
      $o = "<div class=\"w-form\" id=\"w-resident-form\">\n";
      $o .= "<form method=\"post\" action=\"$action\">\n";
      $o .= $frm->hidden("project_id", $this->pid);
      $o .= $frm->hidden("resident_id", $this->id);
      $o .= "<div class=\"w-form-group\" id=\"w-resident-coreinfo\">\n";
      $o .= "<div class=\"w-form-element\">\n";
        $o .= $frm->label("res_name", "Name") . "\n";
        $o .= $frm->text("res_name", $this->state=="add"?"First Last":$this->name, array("tabindex"=>$tab++)) . "\n";
      $o .= "</div>\n";
      $o .= "<div class=\"w-form-element\">\n";
        $o .= $frm->label("res_phone", "Phone #") . "\n";
        $o .= $frm->text("res_phone", $this->state=="add"?"###-###-####":$this->phone, array("tabindex"=>$tab++)) . "\n";
      $o .= "</div>\n";
      $o .= "<div class=\"w-form-element\">\n";
        $o .= $frm->label("res_zipcode", "Zipcode") . "\n";
        $o .= $frm->text("res_zipcode", $this->state=="add"?"#####":$this->zip, array("tabindex"=>$tab++)) . "\n";
      $o .= "</div>\n";
      $o .= "<div class=\"w-form-element\">\n";
        $o .= $frm->label("res_address", "Address") . "\n";
        $o .= $frm->text("res_address", $this->state=="add"?"123 State St":$this->address, array("tabindex"=>$tab++)) . "\n";
      $o .= "</div>\n";
      $o .= "<div class=\"w-form-element\">\n";
        $o .= $frm->label("res_calltime", "Preferred Call Time") . "\n";
        $o .= $frm->select("res_calltime", array("any"=>"Any Time", "morning"=>"Morning 9AM-12PM", "afternoon"=>"Afternoon 12PM-4PM", "evening"=>"Evening 4PM-9PM"), $state=="add"?"any":$this->calltime, array("tabindex"=>$tab++)) . "\n";
      $o .= "</div>\n";
      $o .= "</div><!-- END OF w-form-group -->\n";

      $o .= $jobs->getFormExtension();

      $o .= "</form>\n";
      $o .= "</div><!-- END OF w-resident-form -->\n";
      return $o;
    }
  }
}
?>
