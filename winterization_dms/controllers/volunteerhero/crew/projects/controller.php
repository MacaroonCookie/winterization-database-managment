<?php
  defined('C5_EXECUTE') or die(_("Access Denied."));
  class VolunteerheroAdministrationProjectsController extends Controller {
    private $d,$h;

    public function on_start() {
      $this->h = Loader::helper('html');
      $this->d = Loader::db();
      $this->set('f', Loader::helper('form'));
      $this->set('fdt', Loader::helper('form/date_time'));
    }

    public function view() {
      $this->addHeaderItem($this->h->javascript('jquery.js'));
      $this->addHeaderItem($this->h->javascript('winterization/administration/projects.js', 'winterization_dms'));
      $projects_array = array();
      $result = $this->d->execute("SELECT * FROM winter_Project ORDER BY service_date DESC");
      while( $row=$result->fetchRow() ) {
        array_push($projects_array, array("id"=>$row["project_id"], "date"=>$row["service_date"], "current"=>$row["current"]));
      }
      $this->set("projects", $projects_array);
    }

    public function add() {
      if( !isset($_POST['project_date']) || !isset($_POST['project_setcurrent']) ) $this->_redirectPage();
      $new_date = date('Y-m-d', strtotime($this->post('project_date')));
      $setcurrent = $this->post('project_setcurrent');
      $result = $this->d->execute('INSERT INTO winter_Project(service_date) VALUES (CAST(? AS DATE))', array($new_date));
      print_r($this->d);
      if( $this->d->hasAffectedRows == TRUE ) {
        if( $setcurrent ) {
          $newid = $this->d->Insert_ID();
          $this->_setCurrent($newid);
        }
        $this->_redirectPage();
      } else {
        $this->set('output', array('message'=>'Unsuccessfully added new project. Possibly a project with the same date already exists.', 'error_message'=>$this->d->ErrorMsg()));
      }
    }

    public function update() {
      if( !isset($_POST['project_id']) || !isset($_POST['project_date_'.$this->post('project_id')]) ) $this->_redirectPage();
      $id = $this->post('project_id');
      $date = date('Y-m-d', strtotime($this->post('project_date_'.$id)));
      $this->d->execute('UPDATE winter_Project SET service_date=CAST(? AS DATE) WHERE project_id=?', array($date, $id));
      if( $this->d->hasAffectedRows >= 1 ) {
        $this->_redirectPage();
      } else {
        $this->set('output', array('message'=>'Unsuccessfully updated the project. Does the project exist?', 'error_message'=>$this->d->ErrorMsg()));
      }
    }

    public function setcurrent() {
      if( !isset($_POST['project_id']) ) $this->_redirectPage();
      if( $this->_setCurrent($this->post('project_id')) ) $this->_redirectPage();
      else $this->set('output', array('message'=>'Unsuccessfully updated the project. Does the project exist?', 'error_message'=>$this->d->ErrorMsg()));
    }

    private function _redirectPage() {
      $this->redirect('/winterization/administration/projects');
    }

    private function _setCurrent($id) {
      $result = $this->d->execute('UPDATE winter_Project SET current=TRUE WHERE project_id=?', array($id));
      if( $this->d->hasAffectedRows == TRUE ) {
        $this->d->execute('UPDATE winter_Project SET current=FALSE WHERE current=TRUE AND project_id!=?', array($id));
        return TRUE;
      } else {
        return FALSE;
      }
    }
  }
?>
