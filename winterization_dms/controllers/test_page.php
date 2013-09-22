<?php
  class TestPageController extends Controller {
    public function on_start() {

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
