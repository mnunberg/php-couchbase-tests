--TEST--
Serialization - SerializeJsonArrayMixed
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Serialization", "testSerializeJsonArrayMixed");
--EXPECT--
PHP_COUCHBASE_OK