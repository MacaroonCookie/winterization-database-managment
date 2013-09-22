<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

class JobsModel {
  private $state, $db, $frm, $rid;
  private $jobs = array();
  private $jobsupdate = array();

  public function __construct($state, $resid=NULL) {
    $this->state = $state;
    $this->db = Loader::db();
    $this->frm = Loader::helper('form');
    $this->$rid=$resid;
    if( $state=='set' ) {
      if( $resid==NULL ) return;
    } else if( $state=='new' ) {
      $result = $this->db("SELECT * FROM winter_JobTasks WHERE job_enabled=TRUE");
      while( $r = $result->getRow() ) {
        if( !isset($this->jobs[$r['job_name']]) ) $jobs[$r['job_name']] = array();
        array_push($this->jobs[$r['job_name']],
            array('id'=>$r['job_id'],
                  'name'=>$r['job_valuename'],
                  'type'=>$r['job_valuetype'],
                  'options'=>$r['job_valueoptions'],
                  'value'=>$r['job_valuedefault'],
                  'enabled'=>$r['job_enabled']==1?TRUE:FALSE,
                  'filled'=>FALSE));
      }
    } else {
      if( $this->rid==NULL ) return;
      $result = $this->db("SELECT * FROM winter_ResidentJobTasks LEFT JOIN winter_Residents ON resjob_resid=res_id INNER JOIN winter_JobTasks ON resjob_jobid=job_id WHERE (res_id=NULL AND job_enabled=TRUE) OR res_id=?", array($this->rid));
      while( $r = $result->getRow() ) {
        if( !isset($this->jobs[$r['job_name']]) ) $jobs[$r['job_name']] = array();
        array_push($this->jobs[$r['job_name']], array('id'=>$r['job_id'], 'name'=>$r['job_valuename'], 'type'=>$r['job_valuetype'], 'options'=>$r['job_valueoptions'], 'value'=>$r['res_id']==NULL?$r['job_valuedefault']:$r['resjob_value'], 'enabled'=>$r['job_enabled']==1?TRUE:FALSE, 'filled'=>$r['res_id']==NULL?FALSE:TRUE));
      }
    }
  }

  public function addTask($jid, $value) {
    if( $this->state!='set' ) return;
    array_push($this->jobsupdate, array('jid'=>$jid, 'rid'=>$rid, 'value'=>$value));
  }

  public function update() {
    if( $this->state!='set' ) return;
    foreach($this->jobsupdate as $j) {
      $res = $this->db->execute('SELECT resjob_value FROM winter_ResidentJobTasks WHERE resjob_jobid=? resjob_resid=?', array($j['jid'], $this->rid));
      if( $res->_numOfRows>=1 ) {
        $r = $res->getRow();
        if( $r['resjob_value'] != $j['value'] ) {
          $this->db->execute('UPDATE winter_ResidentJobTasks SET resjob_value=? WHERE resjob_jobid=? AND resjob_resid=?', array($j['value'], $j['jid'], $this->rid));
        }
      } else {
        $this->db->execute('INSERT INTO winter_ResidentJobTasks VALUES(?, ?, ?)', array($j['jid'], $this->rid, $j['value']));
      }
    }
  }

  public function getFormExtension() {
    if( $this->state=='set' ) return;
    $o = ""; // Output string
    $frm = $this->frm;
    foreach($this->jobs as $j=>$tasks) {
      $o .= "<div id=\"w-form-group-$j\" class=\"w-form-group\"><h4>";
      $maincheck = NULL;
      foreach($tasts as $t) {
        if( $t['name']==$j ) {
          $maincheck = $t;
          break;
        }
      }
      if( $maincheck!=NULL ) {
        $o .= $frm->label("w-form-jobtask-item-".$t['id'], $j);
        $o .= $frm->checkbox("w-form-jobtask-item-".$t['id'], $j, ($t['value']==NULL||$t['value']==FALSE||strtolower($t['value'])=='false')?FALSE:TRUE, array());
      } else {
        $o .= $j;
      }
      $o .= "</h4>";
      foreach($tasks as $t) {
        $o .= "<div id=\"w-form-element-".$t['id']."\" class=\"w-form-element w-form-element-".$t['enabled']?"enabled":"disabled"."\">";
        $o .= $frm->label("w-form-jobtask-".$t['id'], $t['name']);
        switch( $t['type'] ) {
        case "text":
          $o .= $frm->label("w-form-jobtask-item-".$t['id'], $t['name']);
          $o .= $frm->text("w-form-jobtask-item-".$t['id'], $t['value']==NULL?"":$t['value'], array());
          break;
        case "textarea":
          $o .= $frm->label("w-form-jobtask-item-".$t['id'], $t['name']);
          $o .= $frm->textarea("w-form-jobtask-item-".$t['id'], $t['value']==NULL?"":$t['value'], array());
          break;
        case "checkbox":
          if( $t['name']==$j ) continue;
          $o .= $frm->label("w-form-jobtask-item-".$t['id'], $t['name']);
          $o .= $frm->checkbox("w-form-jobtask-item-".$t['id'], $t['name'], ($t['value']==NULL||$t['value']==FALSE||strtolower($t['value'])=='false')?FALSE:TRUE, array());
          break;
        case "radio":
          $o .= "<div id=\"w-form-jobtask-item-group-".$t['id']."\" class=\"w-form-jobtask-item-group\"><h5>".$t['name']."</h5>";
          $i = 1;
          foreach(explode(',', $t['options']) as $opt) {
            $o .= $frm->label("w-form-jobtask-item-".$t['id'].$i++, $opt);
            $o .= $frm->radio("w-form-jobtask-item-".$t['id'], $opt, ($t['value']==NULL&&$i==2)||$t['value']==$opt?TRUE:FALSE, array());
          }
          $o .= "</div>";
          break;
        case "select":
          $options = array();
          $default = NULL;
          foreach(explode(',', $t['options']) as $opt) {
            $default=$default==NULL?$opt:$default;
            $options[$opt] = $opt;
          }
          $o .= $frm->label("w-form-jobtask-item-".$t['id'], $t['name']);
          $o .= $frm->select("w-form-jobtask-item-".$t['id'], $options, $t['value']==NULL?$default:$t['value'], array());
        }
        $o .= "</div>";
      }
      $o .= "</div>";
    }
  }
}

?>
