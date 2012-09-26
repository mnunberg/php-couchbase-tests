--TEST--
GetMulti - MgetPartial
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("GetMulti", "testMgetPartial");
--EXPECT--
PHP_COUCHBASE_OK