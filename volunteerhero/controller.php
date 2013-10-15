<?php
defined('C5_EXECUTE') or die("Access Denied.");

class VolunteerHeroPackage extends Package {
  protected $pkgHandle = 'volunteerhero';
  protected $appVersionRequired = '5.6.1.2';
  protected $pkgVersion = '1.0';

  private $install_groups = array("volunteerhero_crew"=>"Description",
                                  "volunteerhero_president"=>"Description",
                                  "volunteerhero_volunteer"=>"Description",
                                  "volunteerhero_resident"=>"Description");

  public function getPackageDescription() {
    return t("Package Description (fill in later)");
  }

  public function getPackageName() {
    return t("Volunteer Hero");
  }

  public function install() {
    $pkg = parent::install();

    Loader::model("groups");
    foreach($install_groups as $g) {
      $grp = Group::getByName($g);
      if( !($grp instanceof Group) ) {
        $g->delete();
      }
      Group::add($g, $install_groups[$g]);
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
