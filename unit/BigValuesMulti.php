<?php
require_once 'GetMulti.php';

class BigValuesMulti extends GetMulti {
    protected function setUp() {
        parent::setUp();
        $this->iteration_count = 10000;
    }
}
