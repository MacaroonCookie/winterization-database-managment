<?php
  class TestPageController extends Controller {
    public function on_start() {
      $html = Loader::helper("html");
      $this->addHeaderItem($html->css("jquery.ui.css"));
      $this->addHeaderItem($html->javascript("jquery.ui.js"));
    }

    public function view() {
      $u = new User();
      $this->set('output', $u->getUserName());
    }

    public function testfunction() {
      $this->set('output', 'Running Testfunction');
    }
  }
?>
