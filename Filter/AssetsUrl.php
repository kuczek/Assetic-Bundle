<?php
/**
 * @copyright 2014 hexmedia.pl
 * @author Krystian Kuczek <krystian@hexmedia.pl>
 */

namespace Hexmedia\AsseticBundle\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;
use Hexmedia\AsseticBundle\Utils\UrlReplacer;
use Symfony\Component\DependencyInjection\Exception\InactiveScopeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Container;
use Assetic\Filter\HashableInterface;

class AssetsUrl implements FilterInterface, HashableInterface
{

    /**
     *
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function filterDump(AssetInterface $asset)
    {
        $this->doFilter($asset);
    }

    private function doFilter(AssetInterface $asset)
    {
        $content = $asset->getContent();

        $replacer = new UrlReplacer($this->container);

        $asset->setContent($replacer->replace($content));
    }

    public function filterLoad(AssetInterface $asset)
    {
        $this->doFilter($asset);
    }

    public function hash()
    {
        return microtime(true) . md5(microtime(true)); //always different
    }
}
