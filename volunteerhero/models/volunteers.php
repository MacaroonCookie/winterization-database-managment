<?php defined('C5_EXECUTE') or die(_("Access Denied."));
class VolunteerModel {
  private name=NULL, email=NULL, checkedin=NULL,
      uid=NULL,  // User ID (Concrete5 reference)
      rid=NULL,  // Registered ID (VH ref)
      vgid=NULL, // Volunteer group id
      wgid=NULL, // Work Group id
      pid=NULL;  // Project Year Id

  public function __construct($id=NULL, $name=NULL) {
    if( $id==NULL && $name==NULL ) {
      return;
    }
    $db = Loader::db();

    $query = "SELECT uID, uEmail, uName, regvol_id, regvol_grpid, ".
             "regvol_wrkgrpid, regvol_projid, regvol_checkedin FROM Users".
             "INNER JOIN volunteerheroRegisteredVolunteer ON ".
             "Users.uID=volunteerheroRegisteredVolunteer.regvol_uid ".
             "WHERE "(.$id==NULL?"":"uID=$id ").
             ($id!=NULL&&$name!=NULL?"AND ":"").
             ($name==NULL?"":"uName LIKE \"$name\"");
    $res = $db->Execute($query);
    if( $res ) {
      $row = $r->FetchRow();
      $this->name = $row['uName'];
      $this->uid  = $row['uID'];
      $this->email= $row['uEmail'];
      $this->rid  = $row['regvol_id'];
      $this->vgid = $row['regvol_grpid'];
      $this->wgid = $row['regvol_wrkgrpid'];
      $this->pid  = $row['regvol_projid'];
      $this->checkedin = $row['regvol_checkedin'];
    }
  }

  public function getName()  { return $this->name; }
  public function getEmail() { return $this->email; }
  public function getCheckedIn() { return $this->checkedin; }
  public function getUserID() { return $this->uid; }
  public function getRegisteredID() { return $this->rid; }
  public function getVolunteerGroupID() { return $this->vgid; }
  public function getWorkGroupID() { return $this->wgid; }
  public function getProjectYearID() { return $this->pid; }

  public function setVolunteerGroup($vid) {
    if( $this->uID == NULL ) return NULL;
    $db = Loader::db();
    $db->Execute("UPDATE volunteerheroRegisteredVolunteer SET ".
        "regvol_grpid = ? WHERE regvol_id=?", array($vid, $this->rid));
    if( $db->Affected_Rows()!=1 ) return NULL;
    $this->vgid = $vid;
    return $vid;
  }

  public function setWorkGroup($wid) {
    if( $this->uID == NULL ) return NULL;
    $db = Loader::db();
    $db->Execute("UPDATE volunteerheroRegisteredVolunteer SET ".
        "regvol_wrkgrpid = ? WHERE regvol_id=?", array($wid, $this->rid));
    if( $db->Affected_Rows()!=1 ) return NULL;
    $this->wgid = $wid;
    return $wid;
  }

  public function checkIn($cin=TRUE) {
    if( $this->uID == NULL ) return NULL;
    $db = Loader::db();
    $db->Execute("UPDATE volunteerheroRegisteredVolunteer SET ".
        "regvol_checkedin=? WHERE regvol_id=?", array($cin, $this->rid));
    if( $db->Affected_Rows()!=1 ) return NULL;
    $this->checkedin = $cin;
    return $cin;
  }

  public function delete() {
    if( $this->uID == NULL ) return false;
    $db = Loader::db();
    $db->Execute("DELETE FROM volunteerheroRegisteredVolunteer WHERE ".
        "regvol_id = ?", array($this->rid));
    if( $db->Afftected_Rows()==1 ) {
      $this->uid==NULL;
    }
    return $db->Affected_Rows()==1;
  }

  public static function register($uid, $vgid, $wgid, $pid, $checkedin=FALSE) {
    $db = Loader::db();
    $query = "INSERT INTO volunteerheroRegisteredVolunteer(regvol_grpid, ".
             "regvol_wrkgrpid, regvol_projid, regvol_uid, regvol_checkedin) ".
             "VALUES($vgid, $wgid, $pid, $uid, $checkedin)");
    $ret = $id = NULL;
    if( $db->AffectedRows() != 0 ) {
      $id = $db->Insert_ID();
      $ret = new VolunteerModel($id);
    }
    return $ret;
  }
}
?>
