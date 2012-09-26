--TEST--
ServerStats - Issue115
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("ServerStats", "testIssue115");
--EXPECT--
PHP_COUCHBASE_OK