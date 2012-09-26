--TEST--
MutateBasic - AddOO
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("MutateBasic", "testAddOO");
--EXPECT--
PHP_COUCHBASE_OK