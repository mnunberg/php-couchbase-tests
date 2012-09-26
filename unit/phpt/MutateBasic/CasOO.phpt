--TEST--
MutateBasic - CasOO
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("MutateBasic", "testCasOO");
--EXPECT--
PHP_COUCHBASE_OK