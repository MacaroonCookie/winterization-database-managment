<?php defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::model('volunteerhero', 'volunteerhero');

class WorkGroupList extends VolunteerHeroListModel {
  public function __construct($id=NULL, $name=NULL, $status=NULL, $evntid=NULL) {
    parent::__construct("volunteerheroWorkGroup", "wrkgrp_id");
    if( $id!=NULL ) $this->_addDirectCompare("wrkgrp_id", $id);
    if( $name!=NULL ) $this->_addLikeCompare("wrkgrp_name", $name);
    if( $status!=NULL ) $this->_addDirectCompare("wrkgrp_status", $status);
    if( $eventid!=NULL ) $this->_addDirectCompare("wrkgrp_projid", $evntid);

    $query = "SELECT * FROM volunteerheroWorkGroup";
    $query .= $this->_getCompareClause();

    $res = $this->_executeQuery($query);
    while( $row=$res->FetchRow() ) {
      $this->_addObject(WorkGroup::getFromRow($row));
    }
  }

  public static function getWorkGroupsByID($id) { return new WorkGroupList($id); }
  public static function getWorkGroupsByName($name) { return new WorkGroupList(NULL, $name); }
  public static function getWorkGroupsByStatus($status) { return new WorkGroupList(NULL, NULL, $status); }
  public static function getWorkGroupsByEvent($eid) { return new WorkGroupList(NULL, NULL, NULL, $eid); }
}

class WorkGroup extends VolunteerHeroModel {
  public $wid=NULL, $eid=NULL, $name=NULL, $status=NULL;

  private function _select($id=NULL, $name=NULL, $row=NULL ) {
    if( $id==NULL && $name==NULL && $row==NULL ) return;

    if( $row==NULL ) {
      $query = "SELECT wrkgrp_id, wrkgrp_projid, wrkgrp_name, wrkgrp_status ".
             "FROM volunteerheroWorkGroup WHERE ".
             ($id==NULL?"":"wrkgrp_id=$id ").
             ($id!=NULL&&$name!=NULL?"AND ":"").
             ($name==NULL?"":"wrkgrp_name LIKE \"$name\"");
      $res = $this->_executeQuery($query);
      $row = $res->FetchRow();
    }

    $this->wid = $row['wrkgrp_id'];
    $this->eid = $row['wrkgrp_projid'];
    $this->name = $row['wrkgrp_name'];
    $this->status = $row['wrkgrp_status'];
  }

  private function _update($rowname, $value) {
    if( $value==NULL && $rowname==NULL ) return;
    $db = Loader::db();
    $db->Execute("UPDATE volunteerheroWorkGroup SET $rowname=? WHERE ".
        "wrkgrp_id=?", array($value, $this->wid));
    if( $db->AffectedRows()!=1 ) return NULL;
    _select($this->wid);
  }

  public function __construct($id=NULL, $name=NULL, $row=NULL) {
    parent::__construct("volunteerheroWorkGroup", "wrkgrp_id");
    $this->_select($id, $name, $row); }
  public function getName() { return $this->name; }
  public function getStatus() { return $this->status; }
  public function getWorkGroupID() { return $this->wid; }
  public function getEventID() { return $this->eid; }
  public static function getByID($id) { return new WorkGroup($id); }
  public static function getByName($name) { return new WorkGroup(NULL, $name); }
  public static function getFromRow($row) { return new WorkGroup(NULL, NULL, $row); }

  public function setEventID($eid) { return _update("wrkgrp_projid", $eid); }
  public function setName($name) { return _update("wrkgrp_name", $name); }
  public function setStatus($status) { return _update("wrkgrp_status", $status); }

  public function delete() {
    if( $this->wid==NULL ) return FALSE;
    $db = Loader::db();
    $db->Execute("DELTE FROM volunteerheroWorkGroup WHERE wrkgrp_id=?", array($this->wid));
    if( $db->AffectedRows()!=1 ) return FALSE;
    $this->wid = NULL;
    $this->eid = NULL;
    $this->status = NULL;
    $this->name = NULL;
    return TRUE;
  }

  public static function add($name, $eid, $status=0) {
    $db = Loader::db();
    $db->Execute("INSERT INTO volunteerheroWorkGroup(wrkgrp_name, ".
        "wrkgrp_projid, wrkgrp_status) VALUES(\"?\", ?, ?)", array($name, $eid, $status));
    $ret = NULL;
    if( $db->AffectedRows() != 0 ) {
      $id = $db->Insert_ID();
      $ret = new WorkGroup($id);
    }
    return $ret;
  }
}

?>
