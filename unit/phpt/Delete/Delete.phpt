--TEST--
Delete - Delete
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Delete", "testDelete");
--EXPECT--
PHP_COUCHBASE_OK