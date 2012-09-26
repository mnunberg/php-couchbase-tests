--TEST--
Connection - ConnectBad
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Connection", "testConnectBad");
--EXPECT--
PHP_COUCHBASE_OK