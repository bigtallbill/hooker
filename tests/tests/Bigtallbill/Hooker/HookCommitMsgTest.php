<?php
/**
 * Created by PhpStorm.
 * User: bigtallbill
 * Date: 24/10/2014
 * Time: 20:04
 */

namespace Bigtallbill\Hooker;


class HookCommitMsgTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HookCommitMsg
     */
    protected $object;

    /**
     * @var array
     */
    protected $config;

    public function setUp()
    {
        $this->object = new HookCommitMsg();

        $this->config = json_decode(file_get_contents(TEST_ASSETS_ROOT . '/hooker.json'), true);
    }

    public function testExecuteGood()
    {
        $argv = array(
            '',
            '',
            '',
            TEST_ASSETS_ROOT . '/commit-msg-good'
        );

        $actual = $this->object->execute($argv, $this->config, 'commit-msg');
        $this->assertCount(2, $actual);

        $this->assertEquals(
            array(
                '',
                0
            ),
            $actual
        );
    }

    public function testExecuteImperative()
    {
        $actual = $this->object->execute(
            $this->getBadConfig('commit-msg-bad-non-imperative'),
            $this->config,
            'commit-msg'
        );

        $this->assertCount(2, $actual);
        $this->assertSame($actual[1], 1, 'should have an error exit code');
        $this->assertContains('imperative', $actual[0], 'should contain a message mentioning imperative wording');
    }

    public function testExecuteEmptyMessage()
    {
        $actual = $this->object->execute($this->getBadConfig('commit-msg-bad-empty'), $this->config, 'commit-msg');
        $this->assertCount(2, $actual);
        $this->assertSame($actual[1], 1, 'should have an error exit code');
        $this->assertContains('empty', $actual[0], 'should contain a warning about an empty commit message');
    }

    public function testExecuteSecondLine()
    {
        $actual = $this->object->execute($this->getBadConfig('commit-msg-bad-second-line'), $this->config, 'commit-msg');
        $this->assertCount(2, $actual);
        $this->assertSame($actual[1], 1, 'should have an error exit code');
        $this->assertContains('second', $actual[0], 'should contain a warning about the second line being non empty', true);
    }

    public function testExecuteSummaryLineLength()
    {
        $actual = $this->object->execute($this->getBadConfig('commit-msg-bad-summary-line-length'), $this->config, 'commit-msg');
        $this->assertCount(2, $actual);
        $this->assertSame($actual[1], 1, 'should have an error exit code');
        $this->assertContains('summary line', $actual[0], 'should contain a warning about the summary line', true);
    }

    public function testExecuteBodyUrls()
    {
        $actual = $this->object->execute($this->getBadConfig('commit-msg-bad-body-urls'), $this->config, 'commit-msg');
        $this->assertCount(2, $actual);
        $this->assertSame($actual[1], 0, 'should have a normal exit code');
    }

    public function testExecuteBodyLineLength()
    {
        $actual = $this->object->execute($this->getBadConfig('commit-msg-bad-body-length'), $this->config, 'commit-msg');
        $this->assertCount(2, $actual);
        $this->assertSame($actual[1], 1, 'should have an error exit code');
        $this->assertContains('single line', $actual[0], 'should contain a warning body line length', true);
    }

    public function testExecuteMerge()
    {
        $actual = $this->object->execute($this->getBadConfig('commit-msg-merge'), $this->config, 'commit-msg');
        $this->assertCount(2, $actual);
        $this->assertSame($actual[1], 0, 'should have a normal exit code');
    }

    private function getBadConfig($messageFile)
    {
        return array(
            '',
            '',
            '',
            TEST_ASSETS_ROOT . '/' . $messageFile
        );
    }
}
