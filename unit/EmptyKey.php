<?php

require_once 'Common.php';

/* replaces 018 */

class EmptyKey extends CouchbaseTestCommon
{
    /**
     * @dataProvider empty_key_functions
     *
     * @test Empty Keys
     *
     * @pre
     *
     * perform a mutation (the operation is passed as an argument to
     * this function) operation with an empty key
     *
     * @post
     * Error message indicating that an empty key was passed
     *
     * @param $fn The couchbase function to execute
     * @param $params The params to pass to the function
     * @param $do_skip Whether to not run the assertion. Needed for the
     * *_multi functions.
     *
     *
     * @remark
     * Variants: Set, Add, Replace
     *
     * @test_plans{2.1.3, 3.5}
     */
    private function _test_empty_common($fn, $params, $do_skip = false) {
        $msg = NULL;
        $oo = $this->oo;

        if ($do_skip) {
            $this->markTestSkipped("Skipping empty key test for " . $fn);
        }

        try {
            call_user_func_array( array(&$oo, $fn), $params);
        } catch (Exception $exc) {
            $msg = $exc->getMessage();
            $this->assertContains('Empty', $msg,
                                  "Got 'Empty' exception for " . $fn);
        }
        $this->assertNotNull($msg, 'Got an exception for ' . $fn);
    }

    public function testEmptyKey() {
        $ret = array();
        array_push($ret, array('set', array('', 'foo')));
        array_push($ret, array('replace', array('', 'foo')));
        array_push($ret, array('add', array('', 'foo')));
        array_push($ret, array('get', array(''), true));

        array_push($ret, array('getMulti',
            array(
                array("")
            ), true));

        array_push($ret, array('setMulti',
            array(
                array('' => 2)
            ), true));


        foreach ($ret as $params) {
            call_user_func_array(array(&$this, "_test_empty_common"), $params);
        }
    }



}

?>
