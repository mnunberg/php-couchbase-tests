--TEST--
Delay - FetchOne
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Delay", "testFetchOne");
--EXPECT--
PHP_COUCHBASE_OK