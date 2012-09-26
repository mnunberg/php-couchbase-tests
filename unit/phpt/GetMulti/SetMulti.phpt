--TEST--
GetMulti - SetMulti
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("GetMulti", "testSetMulti");
--EXPECT--
PHP_COUCHBASE_OK