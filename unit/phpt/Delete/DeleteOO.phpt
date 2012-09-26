--TEST--
Delete - DeleteOO
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Delete", "testDeleteOO");
--EXPECT--
PHP_COUCHBASE_OK