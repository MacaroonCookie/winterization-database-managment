<?php
defined('C5_EXECUTE') or die("Access Denied.");

class WinterizationDMSPackage extends Package {
  protected $pkgHandle = 'winterization_dms';
  protected $appVersionRequired = '5.6.1.2';
  protected $pkgVersion = '1.0';

  private $install_groups = array("winterization_crew"=>"Description",
                                  "winterization_president"=>"Description",
                                  "winterization_volunteer"=>"Description",
                                  "winterization_resident"=>"Description");

  public function getPackageDescription() {
    return t("Package Description (fill in later)");
  }

  public function getPackageName() {
    return t("Winterization DMS");
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
    SinglePage::add('/winterization', $pkg);
    SinglePage::add('/winterization/administration', $pkg);
    SinglePage::add('/winterization/administration/residentjobs', $pkg);
    SinglePage::add('/winterization/crew', $pkg);
    SinglePage::add('/winterization/crew/projects', $pkg);
    SinglePage::add('/winterization/crew/residents', $pkg);
    SinglePage::add('/winterization/crew/residents/resident', $pkg);
    SinglePage::add('/winterization/crew/mapping', $pkg);
    SinglePage::add('/winterization/crew/mapping/ajax', $pkg);
    SinglePage::add('/winterization/crew/volunteers', $pkg);
    SinglePage::add('/winterization/crew/volunteers/checkin', $pkg);
    SinglePage::add('/winterization/crew/volunteers/checkin/ajax', $pkg);
    SinglePage::add('/winterization/crew/volunteers/organization', $pkg);
    SinglePage::add('/winterization/crew/volunteers/organization/ajax', $pkg);
    SinglePage::add('/developer', $pkg);
  }
}
?>
