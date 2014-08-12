<?php
/**
 * @copyright 2014 hexmedia.pl
 * @author Krystian Kuczek <krystian@hexmedia.pl>
 */

namespace Hexmedia\AsseticBundle\Utils;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InactiveScopeException;
use Symfony\Component\Filesystem\Filesystem;

class UrlReplacer
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * @param string $content
     * @param string|null $readFrom
     *
     * @return string
     */
    public function replace($content, $readFrom = null)
    {
        //For php 5.3
        $that = $this;
        $container = $this->container;
        try {
            return preg_replace_callback('/(?P<resource>\@{1,3}[A-Za-z\_]+Bundle[A-Za-z0-9\_\.\/\-]*)/', function($matches) use ($that, $readFrom, $container) {
                return $that->replaceResources($matches, $readFrom, $container);
            }, $content);
        } catch (\Exception $e) {
            return $content;
        }
    }

    /**
     * @param string$matches
     * @param string|null $readFrom
     * @return string
     */
    public function replaceResources($matches, $readFrom = null, $container)
    {
        $fs = new Filesystem();
        $resource = $matches['resource'];

        preg_match('/(?P<at>\@{1,3})([A-Z][A-Za-z0-9\_\-]*)/', $resource, $matches);

        while ($resource{1} == "@") {
            $resource = substr($resource, 1);
        }

        $bundle = $container->get('kernel')->getBundle($matches[2]);
        $path = $container->get('kernel')->locateResource($resource);

        if ($fs->exists($path)) {

            if (preg_match('/Resources\/public\/(?P<path>.*)/', $path, $matches2)) {
                $path = 'bundles/' . preg_replace(
                        '/bundle$/',
                        '',
                        strtolower($bundle->getName())
                    ) . '/' . $matches2['path'];

                $path = str_replace("//", "/", $path);

                if ($matches['at'] == "@@") {
                    return $container->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . $path;
                }

                try {
                    return $this->container->get('templating.helper.assets')->getUrl($path);
                } catch (InactiveScopeException $e) {
                    return "../" . $path;
                }
            }
        }

        return $resource;
    }
}