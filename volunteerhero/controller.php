<?php
defined('C5_EXECUTE') or die("Access Denied.");

class VolunteerHeroPackage extends Package {
  protected $pkgHandle = 'volunteerhero';
  protected $appVersionRequired = '5.6.1.2';
  protected $pkgVersion = '1.0';

  private $install_groups = array("Volunteer Hero Crew Member"=>"Description",
                                  "Volunteer Hero President"=>"Description",
                                  "Volunteer Hero Volunteer"=>"Description",
                                  "Volunteer Hero Resident"=>"Description");

  public function getPackageDescription() {
    return t("A system to manage volunteers. Allowing for volunteer registration, check-in, and much more, Volunteer Hero will help you organize your event.");
  }

  public function getPackageName() {
    return t("Volunteer Hero");
  }

  public function install() {
    $pkg = parent::install();

    Loader::model("groups");
    foreach($this->install_groups as $g=>$d) {
      $grp = Group::getByName($g);
      if( $grp instanceof Group ) {
        $grp->delete();
      }
      $id = Group::add($g, $d)->getGroupID();
    }

    // Install Block Example
    //BlockType::installBlockTypeFromPackage('block_handle', $pkg);
    Loader::model('single_page');
    SinglePage::add('/test_page', $pkg);
    SinglePage::add('/test_page/test2', $pkg);
    SinglePage::add('/volunteerhero', $pkg);
    SinglePage::add('/volunteerhero/administration', $pkg);
    SinglePage::add('/volunteerhero/administration/residentjobs', $pkg);
    SinglePage::add('/volunteerhero/crew', $pkg);
    SinglePage::add('/volunteerhero/crew/projects', $pkg);
    SinglePage::add('/volunteerhero/crew/residents', $pkg);
    SinglePage::add('/volunteerhero/crew/residents/resident', $pkg);
    SinglePage::add('/volunteerhero/crew/mapping', $pkg);
    SinglePage::add('/volunteerhero/crew/mapping/ajax', $pkg);
    SinglePage::add('/volunteerhero/crew/volunteers', $pkg);
    SinglePage::add('/volunteerhero/crew/volunteers/checkin', $pkg);
    SinglePage::add('/volunteerhero/crew/volunteers/checkin/ajax', $pkg);
    SinglePage::add('/volunteerhero/crew/volunteers/organization', $pkg);
    SinglePage::add('/volunteerhero/crew/volunteers/organization/ajax', $pkg);
    SinglePage::add('/developer', $pkg);
  }
}
?>
