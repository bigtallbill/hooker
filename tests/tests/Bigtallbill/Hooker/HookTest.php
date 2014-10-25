<?php
/**
 * Created by PhpStorm.
 * User: bigtallbill
 * Date: 24/10/2014
 * Time: 19:51
 */

namespace Bigtallbill\Hooker;


class HookTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Hook
     */
    protected $object;

    public function setUp()
    {
        $this->object = $this->getMockForAbstractClass("\\Bigtallbill\\Hooker\\Hook");
    }

    /**
     * @dataProvider dataProviderTestTransformNameToKey
     *
     * @param $orig
     * @param $expected
     */
    public function testTransformNameToKey($orig, $expected)
    {
        $actual = $this->object->transformHookNameToKey($orig);
        $this->assertEquals($expected, $actual);
    }

    //--------------------------------------
    // DATA PROVIDERS
    //--------------------------------------

    public function dataProviderTestTransformNameToKey()
    {
        return array(
            array('pre-commit', 'preCommit'),
            array('commit-msg', 'commitMsg'),
        );
    }
}
