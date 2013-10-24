<?php defined('C5_EXECUTE') or die(_("Access Denied."));

class VolunteerGroupList extends VolunteerHeroListModel {
  private $tformat = "Y-m-d H:i:s";
  public function __construct($id=NULL, $name=NULL, $description=NULL, $creator=NULL, $created=NULL, $eventid=NULL, $daterange_start=NULL, $daterange_end=NULL) {
    parent::__construct("volunteerheroVolunteerGroup", "volgrp_id");
    if( $id!=NULL ) $this->_addDirectCompare("volgrp_id", $id);
    if( $name!=NULL ) $this->_addLikeCompare("volgrp_name", $name);
    if( $description!=NULL ) $this->_addLikeCompare("volgrp_description", $description);
    if( $creator!=NULL ) $this->_addDirectCompare("volgrp_creator", $creator);
    if( $created!=NULL ) $this->_addDirectCompare("volgrp_created", $created);
    if( $eventid!=NULL ) $this->_addDirectCompare("volgrp_projid", $eventid);
    if( $daterange_start!=NULL && $daterange_end!=NULL )
        $this->_addDateRangeCompare("volgrp_created",
            date($this->tformat, strtotime($daterange_start)),
            date($this->tformat, strtotime($daterange_end)));

    $res = $this->_executeQuery("SELECT * FROM volunteerheroVolunteerGroup".$this->_getCompareClause());
    while( $row = $res->FetchRow() ) {
      $this->_addObject(VolunteerGroup::getFromRow($row));
    }
  }
}

class VolunteerGroup extends VolunteerHeroModel {
  public $vgid, $name, $description, $eid, $creator, $created;

  private function _select($id=NULL, $name=NULL, $row=NULL) {
    if( $id==NULL && $name==NULL && $row==NULL) return;
    if( $row==NULL ) {
      $res = $this->_executeQuery("SELECT volgrp_id, volgrp_name, volgrp_description, ".
          "volgrp_projid, volgrp_creator, volgrp_created FROM ".
          "volunteerheroVolunteerGroup WHERE ".($id==NULL?"":"volgrp_id=$id").
          ($id!=NULL&&$name==NULL?" AND ":"").($name==NULL?"":"volgrp_name LIKE ".
          "\"$name\""));
      $row = $res->FetchRow();
    }
    // Set values
    $this->vgid = $row['volgrp_id'];
    $this->name = $row['volgrp_name'];
    $this->description = $row['volgrp_description'];
    $this->eid = $row['volgrp_projid'];
    $this->creator = $row['volgrp_creator'];
    $this->created = $row['volgrp_created'];
  }

  private function _update($rowname, $value) {
    if( $this->vgid==NULL ) return NULL;
    $rows = $this->_updateQuery("UPDATE volunteerheroVolunteerGroup SET $rowname=? WHERE ".
        "volgrp_id=?", array($value, $this->$vgid));
    if( $rows!=1 ) return NULL;
    $this->_select($this->vgid);
  }

  public function __construct($id=NULL, $name=NULL, $row=NULL) {
    parent::__construct("volunteerheroVolunteerGroup", "volgrp_id");
    $this->_select($id, $name, $row);
  }
  public function getName() { return $this->name; }
  public function getVolunteerGroupID() { return $this->vgid; }
  public function getDescription() { return $this->description; }
  public function getEventID() { return $this->eid; }
  public function getCreatorID() { return $this->creator; }
  public function getDateCreated() { return $this->created; }
  public static function getByID($id) { return new VolunteerGroup($id); }
  public static function getByName($name) { return new VolunteerGroup(NULL, $name); }
  public static function getFromRow($row) { return new VolunteerGroup(NULL, NULL, $row); }

  public function setEventID($eid) { return _update("volgrp_projid", $eid); }
  public function setName($name) { return _update("volgrp_name", $name); }
  public function setDescription($description) { return _update("volgrp_description", $description); }
  public function setCreator($userid) { return _update("volgrp_creator", $uid); }

  public function delete() {
    if( $this->vgid==NULL ) return FALSE;
    $rows = $this->_updateQuery("DELETE FROM volunteerheroVolunteerGroup WHERE volgrp_id=?", array($this->vgid));
    if( $rows!=1 ) return FALSE;
    $this->vgid = NULL;
    $this->name = NULL;
    $this->description = NULL;
    $this->creator = NULL;
    $this->eid = NULL;
    $this->created = NULL;
    return TRUE;
  }

  public static function add($name, $description, $eventid, $creatid) {
    $id = $this->_insertQuery("INSERT INTO volunteerheroVolunteerGroup(volgrp_name, ".
        "volgrp_description, volgrp_projid, volgrp_creator) VALUES (?, ?, ?, ?)",
        array($name, $description, $eventid, $creatid));
    if( $id !=-1 ) {
      return VolunteerGroup::getByID($id);
    }
    return NULL;
  }
}
