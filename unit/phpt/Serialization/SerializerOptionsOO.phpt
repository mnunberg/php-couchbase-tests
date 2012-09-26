--TEST--
Serialization - SerializerOptionsOO
--FILE--
<?php
include dirname(__FILE__)."/../../phpunit-compat/couchbase-phpt-bootstrap.php";
couchbase_phpt_runtest("Serialization", "testSerializerOptionsOO");
--EXPECT--
PHP_COUCHBASE_OK