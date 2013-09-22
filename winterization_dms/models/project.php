<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
class ProjectModel {
  public $id, $current;
  private $date_int, $db, $state;

  public function __construct($state='get', $id=NULL, $date=NULL, $current=FALSE) {
    $this->state = $state;
    $this->db = Loader::db();
    if( $this->state==NULL ) {
      exit();
    } else if( $this->state=='get' ) {
      $result = NULL;
      if( $id==NULL ) {
        $result = $this->db->execute("SELECT * FROM winter_Project WHERE current=TRUE");
      } else {
        $result = $this->db->execute("SELECT * FROM winter_Project WHERE project_id=?", array($id));
        if( $result->RecordCount() <= 0 ) {
          return;
        }
      }
      $rs = $result->FetchRow();
      $this->date_int = strtotime($rs['service_date']);
      $this->id = $rs['project_id'];
      $this->current = $rs['current']==1?TRUE:FALSE;
      // Need to add error checking
    } else if( $this->state=='add' ) {
      if( $date==NULL ) return;
      $this->db->execute("INSERT INTO winter_Project(service_date, current) VALUES (CAST(? AS DATE), ?)", array(date("Y-m-d", strtotime($date)), $current));
      // Need to add error checking
    } else if( $this->state=='update' ) {
      if( $date==NULL || $id==NULL ) return;
      $this->db->execute("UPDATE winter_Project SET service_date=CAST(? AS DATE), current=? WHERE project_id=?", array(date("Y-m-d", strtotime($date)), $current, $id));
      // Need to add error checking
    }
  }

  public function getDate($format="m/d/Y") { return date($format, $this->date_int); }
  public function getYear() { return $this->getDate("Y"); }
  public function getDateInt() { return $this->date_int; }
  public function getID() { return $this->id; }
  public function getCurrent() { return $this->current; }
}
?>
