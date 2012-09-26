--TEST--
MutateBasic - Add
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("MutateBasic", "testAdd");
--EXPECT--
PHP_COUCHBASE_OK