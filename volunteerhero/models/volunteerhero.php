<?php defined('C5_EXECUTE') or die(_("Access Denied."));
class VolunteerHeroModel {
  protected $_tformat = "Y-m-d H:i:s";
  private $_table, $_primarykey;
  private $_compareItems = array();

  public function __construct($table, $key) {
    $this->_table = $table;
    $this->_primarykey = $key;
  }

  private function _addToCompares($query) {
    if( !in_array($query, $this->compareItems) )
      array_push($this->compareItems, $query);
  }

  protected function _emptyQuery() { $this->_compareItems=array(); }

  protected function _addDirectCompare($column, $value)           { _addToCompares("$column = $value"); }
  protected function _addLikeCompare($column, $value)             { _addToCompares("$column LIKE \"$value\""); }
  protected function _addDateRangeCompare($column, $start, $end)  { _addToCompares("$column BETWEEN \'$start\' AND \'$end\'"); }

  protected function _getCompareClause() {
    $len = count($this->_compareItems);
    if( $len == 0 ) return "";
    $claue = " WHERE";
    for($i=0; $i<$len; $i++) {
      $clause .= " ";
      $clasue .= $this->_compareItems[$i];
      if( $i < $len-1 )
        $clause .= " AND";
    }
    return $clause;
  }

  protected function _clearCompareList() {
    $this->_compareItems = array();
  }

  protected function _getQueryStatement() {
    $query = "SELECT $this->_primarykey FROM $this->_table";
    $query .= $this->getCompareClause();
    return $query;
  }

  protected function _executeQuery($query, $vals=array()) {
    $db = Loader::db();
    return $db->Execute($query, $vals);
  }

  protected function _insertQuery($query, $vals=array()) {
    $db = Loader::db();
    $db->Execute($query, $vals);
    if( $db->AffectedRows()==0 ) return -1;
    return $db->Insert_ID();
  }

  public function _updateQuery($query, $vals=array()) {
    $db = Loader::db();
    $db->Execute($query, $vals);
    return $db->AffectedRows();
  }
}

class VolunteerHeroListModel extends VolunteerHeroModel {
  public $list = array();

  public function __construct($table, $key) {
    parent::__construct($table, $key);
  }

  public function getList() { return $this->list; }

  protected function _addObject($obj) {
    if( !in_array($obj, $this->list) )
      array_push($this->list, $obj);
  }

  protected function _getIDs() {
    $db = Loader::db();
    $res = $db->Execute($this->getQueryStatement());
    $iList = array();
    while( $row = $res->FetchRow() ) {
      if( !in_array($row[$this->_primarykey], $iList) )
        array_push($iList, $this->_primarykey);
    }
    return $iList;
  }
}

?>
