<?php
/**
 * Created by PhpStorm.
 * User: bigtallbill
 * Date: 09/01/15
 * Time: 22:42
 */

namespace Bigtallbill\Hooker;


class HookerTest extends \PHPUnit_Framework_TestCase
{
    /** @var Hooker */
    protected $hooker;

    public function setUp()
    {
        $this->hooker = new Hooker(TEST_TMP_ROOT, TEST_TMP_ROOT);
    }

    public function test_transformHookNameToClass()
    {
        $actual = $this->hooker->transformHookNameToClass('pre-commit');
        $this->assertSame('HookPreCommit', $actual);
    }
}
