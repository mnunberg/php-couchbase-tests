<?php
require_once 'Common.php';
class AppendPrepend extends CouchbaseTestCommon {
        /* 014 */

    /**
     * @test Append and Prepend
     * @pre Set a key @c foo, prepend the prefix @c prefix_, append
     * the suffix @c suffix_.
     *
     * @post Get the key. The key should now be @c prefix_foo_suffix
     *
     * @remark
     * Variant: OO
     *
     * @test_plans{2.6}
     */
    function testAppendPrependOO() {
        $key = $this->mk_key();
        $value = "foo";
        $oo = $this->oo;
        $oo->add($key, $value);
        $oo->prepend($key, "prefix_");
        $oo->append($key, "_suffix");
        $this->assertEquals("prefix_$value" . "_suffix",
                            $oo->get($key));
    }

    function testAppendPrepend() {
        $key = $this->mk_key();
        $value = "foo";
        $h = $this->handle;
        couchbase_add($h, $key, $value);
        couchbase_prepend($h, $key, "prefix_");
        couchbase_append($h, $key, "_suffix");

        $this->assertEquals("prefix_$value" . "_suffix",
                            couchbase_get($h, $key));
    }

}
