<?php

require_once 'ViewBase.php';
class ViewSimple extends CouchbaseViewTestCommon {
    function testMe() {
        $oo = $this->getPersistOO();
        
        $ary = array(
            "testid__" => "php"
        );
                
        $this->getPersistOO()->set($this->mk_key(), json_encode($ary));
        $h = $this->getPersistHandle();
                
        $rows = couchbase_view($h,
                               COUCHBASE_TEST_DESIGN_DOC_NAME,
                               COUCHBASE_TEST_VIEW_NAME,
                               array("stale" => "false",
                                     "foo" => "bar"));
                               
        $this->assertArrayHasKey('rows', $rows);
        $this->assertArrayHasKey('total_rows', $rows);
        $rows = $rows['rows'];
        foreach ($rows as $row) {
            $id = $row['id'];
            $ret = $oo->get($id);
            $this->assertNotNull($ret);
            $rv = $this->getPersistOO()->delete($id);
        }
    }
    
    function testSingleUri() {
        $uri = "/_design/" . COUCHBASE_TEST_DESIGN_DOC_NAME .
                "/_view/" . COUCHBASE_TEST_VIEW_NAME . "?".
                "stale=false&foo=bar";
        $h = $this->getPersistHandle();
        $rows = couchbase_view($h, $uri);
        $this->assertArrayHasKey('rows', $rows);
    }
    
    function testMissingView() {
        $h = $this->getPersistHandle();
        
        $res = couchbase_view($h, 'non-exist-design',
                              'non-exist-view',
                              array(),
                              $return_error = true);
        
        $this->assertInternalType('array', $res);
        $this->assertArrayHasKey('error', $res);
        $this->assertEquals('not_found', $res['error']);
        
        # Test with the default arguments. Should raise an exception:
        $msg = NULL;
        try {
            $res = couchbase_view($h, 'non-exist-design', 'non-exist-view');
        } catch (Exception $exc) {
            $msg = $exc->getMessage();
        }
        $this->assertNotNull($msg);
        $this->assertContains('not_found', $msg);
    }
}