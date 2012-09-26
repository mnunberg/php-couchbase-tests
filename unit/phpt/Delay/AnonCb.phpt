--TEST--
Delay - AnonCb
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Delay", "testAnonCb");
--EXPECT--
PHP_COUCHBASE_OK