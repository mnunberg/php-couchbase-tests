--TEST--
MutateBasic - SetOO
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("MutateBasic", "testSetOO");
--EXPECT--
PHP_COUCHBASE_OK