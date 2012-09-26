--TEST--
Connection - PersistentConnection
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Connection", "testPersistentConnection");
--EXPECT--
PHP_COUCHBASE_OK