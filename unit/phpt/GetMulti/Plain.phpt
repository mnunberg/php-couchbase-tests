--TEST--
GetMulti - Plain
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("GetMulti", "testPlain");
--EXPECT--
PHP_COUCHBASE_OK