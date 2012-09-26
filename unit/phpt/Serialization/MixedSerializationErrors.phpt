--TEST--
Serialization - MixedSerializationErrors
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Serialization", "testMixedSerializationErrors");
--EXPECT--
PHP_COUCHBASE_OK