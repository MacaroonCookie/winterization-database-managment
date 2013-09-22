<?php
  class DeveloperController extends Controller {
    public function on_start() {}
    public function view() {}
    public function uninstalltables() {
      $db = Loader::db();
      $schema = Database::getADOSchema();
      // The address of the xml file may be incorrect. This is assuming that the method
      //   will automatically look in the WinterizationDMS package directory for the file
      //   (as though the package directory is the rootdir).
      $sql = $schema->RemoveSchema("db.xml");
      $schema->ExecuteSchema();
    }
?>
