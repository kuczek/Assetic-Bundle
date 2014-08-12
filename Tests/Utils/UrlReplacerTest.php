<?php
/**
 * @copyright 2014 hexmedia.pl
 * @author Krystian Kuczek <krystian@hexmedia.pl>
 */

namespace Utils;

use Hexmedia\AsseticBundle\Utils\UrlReplacer;
use org\bovigo\vfs\vfsStream;

/**
 * Class UrlReplacerTest
 * @package Utils
 *
 * @cover UrlReplacer
 */
class UrlReplacerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param $content
     * @param $expected
     *
     * @dataProvider provideContentData
     */
    public function testReplace($content, $expected, $readFrom, $shouldCall)
    {
        $containerMock = $this->getMock('\Symfony\Component\DependencyInjection\ContainerInterface');
        $kernelMock = $this->getMock('\Symfony\Component\HttpKernel\Kernel', array(), array(), '', false);
        $bundleMock = $this->getMock('\Symfony\Component\HttpKernel\Bundle\BundleInterface', array(), array(), '', false);
        $assetsHelperMock = $this->getMock('\Symfony\Component\Templating\Helper\AssetsHelper');

        $containerMock
            ->expects($this->any())
            ->method("get")
            ->with($this->logicalOr(
                'kernel',
                'templating.helper.assets'
            ))
            ->will($this->returnCallback(function ($name) use ($kernelMock, $assetsHelperMock) {
                if ($name == 'kernel') {
                    return $kernelMock;
                } else if ($name == 'templating.helper.assets') {
                    return $assetsHelperMock;
                }
            }));

        $kernelMock
            ->expects($this->any())
            ->method('getRootDir')
            ->willReturn("vfs://");

        $kernelMock
            ->expects($this->exactly($shouldCall ? 1 : 0))
            ->method("getBundle")
            ->willReturn($bundleMock);

        $kernelMock
            ->expects($this->any())
            ->method('locateResource')
            ->with($this->anything())
            ->will($this->returnCallback(function ($resource) {
                vfsStream::setup($resource);
                return "vfs://" . $resource;
            }));


        $assetsHelperMock
            ->expects($this->any())
            ->method('getUrl')
            ->willReturn($expected);

        $replacer = new UrlReplacer($containerMock);

        $current = $replacer->replace($content, $readFrom);

        $this->assertEquals($expected, $current);
    }

    public function provideContentData()
    {
        return array(
            array('@TestBundle/Resources/public/img/img.png', "../img/img.png", null, true),
            array('@@TestBundle/Resources/public/img/img.png', "vfs:///../web/bundles/img/img.png", null, true),
            array('@HexmediaTestBundle/Resources/public/img/img.png', "http://cdn.hexmedia.pl/bundles/hexmediatest/img/img.png", "http://cdn.hexmedia.pl/", true),
            array('@@HexmediaTestBundle/Resources/public/img/img.png', "vfs:///../web/bundles/im-g/img.png", "http://cdn.hexmedia.pl/", true),
            array('@import "wp.pl";', '@import "wp.pl";', null, false),
            array('@@import "wp.pl";', '@@import "wp.pl";', null, false),
            array('@import "wp.pl";', '@import "wp.pl";', "http://cdn.hexmedia.pl/", false),
            array('@@import "wp.pl";', '@@import "wp.pl";', "http://cdn.hexmedia.pl/", false),
        );
    }
}
 