--TEST--
ServerStats - Stats
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("ServerStats", "testStats");
--EXPECT--
PHP_COUCHBASE_OK