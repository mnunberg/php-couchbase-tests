--TEST--
GetMulti - SetMultiOO
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("GetMulti", "testSetMultiOO");
--EXPECT--
PHP_COUCHBASE_OK