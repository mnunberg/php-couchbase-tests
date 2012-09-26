--TEST--
Connection - ConnectOO
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Connection", "testConnectOO");
--EXPECT--
PHP_COUCHBASE_OK