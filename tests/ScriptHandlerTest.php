<?php
/**
 * File part of the GMaissa Composer Parameter Handler package
 *
 * @category  GMaissa
 * @package   GMaissa.ComposerParameterHandler
 * @author    Guillaume Maïssa <prod.g@maissa.fr>
 * @copyright 2016 Guillaume Maïssa
 * @license   https://opensource.org/licenses/MIT MIT
 */
namespace GMaissa\ComposerParameterHandler\Tests;

use GMaissa\ComposerParameterHandler\Composer\ScriptHandler;
use PHPUnit\Framework\TestCase;

/**
 * Parameter Handler Composer Script test class
 *
 * @category  GMaissa
 * @package   GMaissa.ComposerParameterHandler
 * @author    Guillaume Maïssa <prod.g@maissa.fr>
 * @copyright 2016 Guillaume Maïssa
 */
class ScriptHandlerTest extends TestCase
{
    private $event;
    private $io;
    private $package;

    protected function setUp()
    {
        parent::setUp();

        $this->event = $this->prophesize('Composer\Script\Event');
        $this->io = $this->prophesize('Composer\IO\IOInterface');
        $this->package = $this->prophesize('Composer\Package\PackageInterface');
        $composer = $this->prophesize('Composer\Composer');
        //$config = $this->prophesize('Composer\Config');

        $composer->getPackage()->willReturn($this->package);
        //$composer->getConfig()->willReturn($config);
        $this->event->getComposer()->willReturn($composer);
        $this->event->getIO()->willReturn($this->io);
    }

    /**
     * @dataProvider provideInvalidConfiguration
     */
    public function testInvalidConfiguration(array $extras, $exceptionMessage)
    {
        $this->package->getExtra()->willReturn($extras);

        chdir(__DIR__);

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage($exceptionMessage);

        ScriptHandler::removeHandledFiles($this->event->reveal());
    }

    public function provideInvalidConfiguration()
    {
        return array(
            'no extra' => array(
                array(),
                'The parameter handler needs to be configured through the extra.incenteev-parameters setting.',
            ),
            'invalid type' => array(
                array('incenteev-parameters' => 'not an array'),
                'The extra.incenteev-parameters setting must be an array or a configuration object.',
            ),
            'invalid type for multiple file' => array(
                array('incenteev-parameters' => array('not an array')),
                'The extra.incenteev-parameters setting must be an array of configuration objects.',
            ),
            'no file' => array(
                array('incenteev-parameters' => array()),
                'The extra.incenteev-parameters.file setting is required to use this script handler.',
            ),
        );
    }

    /**
     * @dataProvider provideRemoveHandledFiles
     */
    public function testRemoveHandledFiles($files, array $extras)
    {
        $this->package->getExtra()->willReturn($extras);

        chdir(__DIR__);
        foreach ($files as $file) {
            touch($file);
        }

        ScriptHandler::removeHandledFiles($this->event->reveal());

        foreach ($files as $file) {
            $this->assertFileNotExists($file);
        }
    }

    public function provideRemoveHandledFiles()
    {
        return array(
            'single file exists' => array(
                array('test.yml'),
                array('incenteev-parameters' => array('file' => 'test.yml'))
            ),
            'multiple files exist' => array(
                array('test.yml', 'test2.yml'),
                array('incenteev-parameters' => array(array('file' => 'test.yml'), array('file' => 'test2.yml')))
            ),
            'single file does not exists' => array(
                array(),
                array('incenteev-parameters' => array('file' => 'test.yml'))
            ),
            'multiple files do not exist' => array(
                array(),
                array('incenteev-parameters' => array(array('file' => 'test.yml'), array('file' => 'test2.yml')))
            ),
        );
    }
}
