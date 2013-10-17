<?php defined('C5_EXECUTE') or die(_("Access Denied."));

class WorkGroupList {
  public wList = array();

  public function __construct($id=NULL, $name=NULL, $status=NULL, $eventid=NULL) {
    $compare = array();
    if( $id!=NULL ) array_push($compare, "wrkgrp_id=$id");
    if( $name!=NULL ) array_push($compare, "wrkgrp_name LIKE \"$name\"");
    if( $status!=NULL ) array_push($compare, "wrkgrp_status=$status");
    if( $eventid!=NULL ) array_push($compare, "wrkgrp_projid=$eventid");

    $query = "SELECT wrkgrp_id FROM volunteerheroWorkGroup";
    $len = count($compare);
    if( $len > 0 ) {
      $query .= " WHERE";
      for($i=0; $i<$len; $i++) {
        $query .= " " . $compare[$i];
        if( $i < $len-1 ) $query .= " AND";
      }
    }

    $db = Loader::db();
    $res = $db->Execute($query);
    while( $row=$res->FetchRow() ) {
      $w = WorkGroup::getByID($row['wrkgrp_id']);
      if( !in_array($w, $wList) ) array_push($this->wList, $w);
    }
  }

  public function getList() { return $this->wList; }
  public static function getWorkGroupByID($id) { return new WorkGroupList($id); }
  public static function getWorkGroupByName($name) { return new WorkGroupList(NULL, $name); }
  public static function getWorkGroupByStatus($status) { return new WorkGroupList(NULL, NULL, $status); }
  public static function getWorkGroupByEvent($eid) { return new WorkGroupList(NULL, NULL, NULL, $eid); }
}

class WorkGroup {
  public wid=NULL, eid=NULL, name=NULL, status=NULL;

  private function _select(?$id=NULL, $name=NULL) {
    if( $id==NULL && $name==NULL ) return;
    $db = Loader::db();

    $query = "SELECT wrkgrp_id, wrkgrp_projid, wrkgrp_name, wrkgrp_status ".
             "FROM volunteerheroWorkGroup WHERE ".
             ($id==NULL?"":"wrkgrp_id=$id ").
             ($id!=NULL&&$name!=NULL?"AND ":"").
             ($name==NULL?"":"wrkgrp_name LIKE \"$name\"");
    $res = $db->Execute($query);
    if($res) {
      $row = $res->FetchRow();
      $this->wid = $row['wrkgrp_id'];
      $this->eid = $row['wrkgrp_projid'];
      $this->name = $row['wrkgrp_name'];
      $this->status = $row['wrkgrp_status'];
    }
  }

  private function _update($rowname, $value) {
    $db = Loader::db();
    $db->Execute("UPDATE volunteerheroWorkGroup SET $rowname=? WHERE ".
        "wrkgrp_id=?", array($value, $this->wid);
    if( $db->AffectedRows()!=1 ) return NULL;
    _select($this->wid);
  }

  public function __construct($id=NULL, $name=NULL) { _select($id, $name); }
  public function getName() { return $this->name; }
  public function getStatus() { return $this->status; }
  public function getWorkGroupId() { return $this->wid; }
  public function getEventID() { return $this->eid; }
  public static function getByID($id) { return new WorkGroup($id); }
  public static function getByName($name) { return new WorkGroup(NULL, $name); }

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
