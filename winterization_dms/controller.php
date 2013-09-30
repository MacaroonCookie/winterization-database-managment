<?php
defined('C5_EXECUTE') or die("Access Denied.");

class WinterizationDMSPackage extends Package {
  protected $pkgHandle = 'winterization_dms';
  protected $appVersionRequired = '5.6.1.2';
  protected $pkgVersion = '1.0';

  public function getPackageDescription() {
    return t("Package Description (fill in later)");
  }

  public function getPackageName() {
    return t("Winterization DMS");
  }

  public function install() {
    $pkg = parent::install();

    // Install Block Example
    //BlockType::installBlockTypeFromPackage('block_handle', $pkg);
    Loader::model('single_page');
    SinglePage::add('/test_page', $pkg);
    SinglePage::add('/test_page/test2', $pkg);
    SinglePage::add('/winterization', $pkg);
    SinglePage::add('/winterization/administration', $pkg);
    SinglePage::add('/winterization/administration/projects', $pkg);
    SinglePage::add('/winterization/administration/residentjobs', $pkg);
    SinglePage::add('/winterization/crew', $pkg);
    SinglePage::add('/winterization/crew/residents', $pkg);
    SinglePage::add('/winterization/crew/residents/resident', $pkg);
    SinglePage::add('/developer', $pkg);
  }
}
?>
