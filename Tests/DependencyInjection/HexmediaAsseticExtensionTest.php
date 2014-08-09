<?php
/**
 * @copyright 2014 hexmedia.pl
 * @author Krystian Kuczek <krystian@hexmedia.pl>
 */

namespace DependencyInjection;

use Hexmedia\AsseticBundle\DependencyInjection\HexmediaAsseticExtension;

class HexmediaAsseticExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $extension = new HexmediaAsseticExtension();

        $containerMock = $this->getMock('\Symfony\Component\DependencyInjection\ContainerBuilder');

        $containerMock
            ->expects($this->exactly(2))
            ->method('setParameter')
            ->with(
                $this->logicalOr("hex.assetic.read_from", "hex.assetic.write_to"),
                $this->logicalOr("http://cdn.hexmedia.pl/", "s3://user:secret@bucket/")
            );

        $extension->load(array(
            array(
                'write_to' => "s3://user:secret@bucket/",
                'read_from' => "http://cdn.hexmedia.pl/"
            )
        ), $containerMock);
    }
}
