<?php
  Loader::model('resident', 'winterization_dms');
  class WinterizationCrewResidentsController extends Controller {
    private $resident;
    public function on_start() {
      $resident = new ResidentModel();
    }
    public function view() {
      $this->set('output', 'View->view is working');
    }
  }
?>

