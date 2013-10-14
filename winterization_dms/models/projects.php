<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

class ProjectsModel {
  private $projects = array();
  private $db, $listForSelect=NULL, $yearListForSelect=NULL;

  public function __construct() {
    $this->db = Loader::db();
    $result = $this->db->execute('SELECT * FROM volunteerhero_Project');
    while( $rs = $result->FetchRow() ) {
      array_push($this->projects, array('id'=>$rs['project_id'], 'date'=>date('m/d/Y', strtotime($rs['service_date'])), 'current'=>$rs['current']==1?TRUE:FALSE));
    }
  }

  public getProjects() { return $this->projects; }
  public getProjectListForSelect() {
    if( $this->listForSelect==NULL ) {
      $this->listForSelect = array('default'=>NULL, 'array'=>array());
      foreach($this->projects as $p) {
        $this->listForSelect['array'][$p['id']] = $p['date'];
        if( $p['current'] ) $this->listForSelect['default'] = $p['id'];
      }
    }
    return $tthis->listForSelect;
  }
  public getProjectYearListForSelect() {
    if( $this->yearListForSelect==NULL ) {
      $this->yearListForSelect = array('default'=>NULL, 'array'=>array());
      foreach($this->projects as $p) {
        $this->yearListForSelect['array'][$p['id']] = date('Y', strtotime($p['date']));
        if( $p['current'] ) $this->yearListForSelect['default'] = $p['id'];
      }
    }
    return $tthis->yearListForSelect;
  }
}

?>
