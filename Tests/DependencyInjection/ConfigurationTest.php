<?php
/**
 * @copyright 2014 hexmedia.pl
 * @author Krystian Kuczek <krystian@hexmedia.pl>
 */

namespace DependencyInjection;

use Hexmedia\AsseticBundle\DependencyInjection\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase {
    public function testBuildingTree() {
        $configuration = new Configuration();

        $tree = $configuration->getConfigTreeBuilder();

        $this->assertInstanceOf('\Symfony\Component\Config\Definition\Builder\TreeBuilder', $tree);
    }
}
 