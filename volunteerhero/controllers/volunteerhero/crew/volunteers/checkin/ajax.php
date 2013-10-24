<?php defined('C5_EXECUTE') or die(_("Access Denied."));

class VolunteerHeroCrewVolunteersCheckinAjaxController extends Controller {

  public function on_start() {
    Loader::model('workgroups', 'volunteerhero');
    Loader::model('volunteergroups', 'volunteerhero');
    Loader::model('volunteers', 'volunteerhero');
  }

  public function view() { $this->set('output', ''); }
  public function initialPull() {
    $dict = array();
    $orgs = new VolunteerGroupList();
    $dict['organizations'] = $orgs->getList();

    $grps = new WorkGroupList();
    $dict['workgroups'] = $grps->getList();

    $vols = new VolunteerList();
    $dict['volunteers'] = $vols->getList();

    $this->set('output', $dict);
  }
}

?>
