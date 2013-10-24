<?php defined('C5_EXECUTE') or die(_("Access Denied."));

class VolunteerList extends VolunteerHeroListModel {
  public function __construct($checkedin=NULL, $name=NULL, $email=NULL, $grpid=NULL, $wrkgrpid=NULL, $evntid=NULL) {
    parent::__construct("volunteerheroRegisteredVolunteer", "regvol_id");

    if( $checkedin!=NULL ) $this->_addDirectCompare("regvol_checkedin", 1);
    if( $name!=NULL ) $this->_addLikeCompare("uName", $name);
    if( $email!=NULL ) $this->_addLikeCompare("uEmail", $email);
    if( $grpid!=NULL ) $this->_addDirectCompare("regvol_grpid", $grpid);
    if( $wrkgrpid!=NULL ) $this->_addDirectcompare("regvol_wrkgrpid", $wrkgrpid);
    if( $evntid!=NULL ) $this->_addDirectCompare("regvol_projid", $evntid);

    $query = "SELECT uID, uEmail, uName, regvol_id, regvol_grpid, ".
             "regvol_wrkgrpid, regvol_projid, regvol_checkedin FROM Users ".
             "INNER JOIN volunteerheroRegisteredVolunteer ON ".
             "Users.uID=volunteerheroRegisteredVolunteer.regvol_uid";
    $query = $query . $this->_getCompareClause();
    $res = $this->_executeQuery($query);
    while( $row = $res->FetchRow() ) {
      $this->_addObject(Volunteer::getFromRow($row));
    }
  }
}

class Volunteer {
  public $name=NULL, $email=NULL, $checkedin=NULL,
      $uid=NULL,  // User ID (Concrete5 reference)
      $rid=NULL,  // Registered ID (VH ref)
      $vgid=NULL, // Volunteer group id
      $wgid=NULL, // Work Group id
      $pid=NULL;  // Project Year Id

  public function __construct($id=NULL, $name=NULL, $row=NULL) {
    if( $id==NULL && $name==NULL && $row==NULL ) {
      return;
    }
    if( $row==NULL ) {
      if( $id!=NULL ) $this->_addDirectCompare("uID", $id);
      if( $name!=NULL ) $this->_addLikeCompare("uName", $name);

      $query = "SELECT uID, uEmail, uName, regvol_id, regvol_grpid, ".
               "regvol_wrkgrpid, regvol_projid, regvol_checkedin FROM Users ".
               "INNER JOIN volunteerheroRegisteredVolunteer ON ".
               "Users.uID=volunteerheroRegisteredVolunteer.regvol_uid";
      $query = $query . $this->getCompareClause();
      $res = $this->_executeQuery($query);
      $row = $res->FetchRow();
    }

    $this->name = $row['uName'];
    $this->uid  = $row['uID'];
    $this->email= $row['uEmail'];
    $this->rid  = $row['regvol_id'];
    $this->vgid = $row['regvol_grpid'];
    $this->wgid = $row['regvol_wrkgrpid'];
    $this->pid  = $row['regvol_projid'];
    $this->checkedin = $row['regvol_checkedin'];
  }

  public function getName()  { return $this->name; }
  public function getEmail() { return $this->email; }
  public function getCheckedIn() { return $this->checkedin; }
  public function getUserID() { return $this->uid; }
  public function getRegisteredID() { return $this->rid; }
  public function getVolunteerGroupID() { return $this->vgid; }
  public function getWorkGroupID() { return $this->wgid; }
  public function getProjectYearID() { return $this->pid; }
  public static function getByID($id) { return new Volunteer($id); }
  public static function getByName($name) { return new Volunteer(NULL, $name); }
  public static function getFromRow($row) { return new Volunteer(NULL, NULL, $row);; }

  public function setVolunteerGroup($vid) {
    if( $this->uid == NULL ) return NULL;
    $res = $this->_updateQuery("UPDATE volunteerheroRegisteredVolunteer SET ".
        "regvol_grpid = ? WHERE regvol_id=?", array($vid, $this->rid));
    if( $res!=1 ) return NULL;
    $this->vgid = $vid;
    return $vid;
  }

  public function setWorkGroup($wid) {
    if( $this->uid == NULL ) return NULL;
    $res = $this->_updateQuery("UPDATE volunteerheroRegisteredVolunteer SET ".
        "regvol_wrkgrpid = ? WHERE regvol_id=?", array($wid, $this->rid));
    if( $res!=1 ) return NULL;
    $this->wgid = $wid;
    return $wid;
  }

  public function checkIn($cin=TRUE) {
    if( $this->uID == NULL ) return NULL;
    $res = $this->_updateQuery("UPDATE volunteerheroRegisteredVolunteer SET ".
        "regvol_checkedin=? WHERE regvol_id=?", array($cin, $this->rid));
    if( $res!=1 ) return NULL;
    $this->checkedin = $cin;
    return $cin;
  }

  public function delete() {
    if( $this->uID == NULL ) return false;
    $res = $this->_updateQuery("DELETE FROM volunteerheroRegisteredVolunteer WHERE ".
        "regvol_id = ?", array($this->rid));
    if( $res==1 ) {
      $this->uid==NULL;
    }
    return $res==1;
  }

  public static function register($uid, $vgid, $wgid, $pid, $checkedin=FALSE) {
    $query = "INSERT INTO volunteerheroRegisteredVolunteer(regvol_grpid, ".
             "regvol_wrkgrpid, regvol_projid, regvol_uid, regvol_checkedin) ".
             "VALUES($vgid, $wgid, $pid, $uid, ".($checkedin?1:0).")";
    $res = $this->_insertQuery($query);
    $ret = $id = NULL;
    if( $res != 0 ) {
      $ret = new Volunteer($res);
    }
    return $ret;
  }
}
?>
