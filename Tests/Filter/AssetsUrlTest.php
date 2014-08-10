<?php
/**
 * @copyright 2014 hexmedia.pl
 * @author Krystian Kuczek <krystian@hexmedia.pl>
 */

namespace Filter;

use Hexmedia\AsseticBundle\Filter\AssetsUrl;

/**
 * Class AssetsUrlTest
 * @package Filter
 *
 * @cover AssetsUrl
 */
class AssetsUrlTest extends \PHPUnit_Framework_TestCase {
    public function testFilter() {
        $containerMock = $this->getMock('\Symfony\Component\DependencyInjection\Container');

        $assetUrl = new AssetsUrl($containerMock);

        $assetUrl->filterLoad($this->getAssertInterfaceMock());

    }

    public function testHash() {
        $containerMock = $this->getMock('\Symfony\Component\DependencyInjection\Container');

        $assetUrl = new AssetsUrl($containerMock);

        $hash = $assetUrl->hash();

        $this->assertInternalType('string', $hash);
    }

    public function getAssertInterfaceMock() {
        $mock = $this->getMock('\Assetic\Asset\AssetInterface');

        return $mock;
    }

}
 