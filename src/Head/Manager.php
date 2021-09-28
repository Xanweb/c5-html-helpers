<?php

namespace Xanweb\HtmlHelper\Head;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Illuminate\Support\Str;
use Symfony\Component\EventDispatcher\GenericEvent;
use Xanweb\HtmlHelper\Head\Tag as HeadTag;

/**
 * @see https://evilmartians.com/chronicles/how-to-favicon-in-2021-six-files-that-fit-most-needs
 */
class Manager implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var MetaTag[]
     */
    protected $metaTags;

    /**
     * @var LinkTag[]
     */
    protected $linkTags;

    public function __construct()
    {
        $this->metaTags = [];
        $this->linkTags = [];
    }

    /**
     * Register favicon.ico for legacy browsers.
     * It's highly recommended being placed under webroot https://example.com/favicon.ico.
     * Some tools, like RSS readers, just request /favicon.ico from the server and don’t bother looking elsewhere.
     *
     * @param string $favIconURL relative favicon path (usually favicon.ico with 32×32 of size)
     *
     * @return Manager
     */
    public function registerIcoFavicon(string $favIconURL): self
    {
        // We need size="any" for <link> to .ico file
        // to fix [Chrome bug](https://twitter.com/subzey/status/1417099064949235712) of choosing ICO file over SVG.
        $this->registerHeadTag(new FaviconLinkTag('icon' , 'image/x-icon', $favIconURL, 'any'), 'icon');

        return $this;
    }

    /**
     * Register a single SVG icon with light/dark version for modern browsers
     * The SVG can contain media queries like @.media (prefers-color-scheme: dark).
     * This will allow you to toggle the same icon between light and dark system themes.
     *
     * @param string $svgFilePath relative favicon path (*.svg)
     *
     * @see https://blog.tomayac.com/2019/09/21/prefers-color-scheme-in-svg-favicons-for-dark-mode-icons/
     *
     * @return Manager
     */
    public function registerSVGFavicon(string $svgFilePath): self
    {
        return $this->registerHeadTag(new FaviconLinkTag('icon', 'image/svg+xml', $svgFilePath), 'icon-svg');
    }

    /**
     * Register Apple Touch Icon
     *
     * @param string $appleIconURL relative path (Type: PNG & Size: 180×180)
     *
     * @return Manager
     */
    public function registerAppleTouchIcon(string $appleIconURL): self
    {
        return $this->registerHeadTag(new FaviconLinkTag('apple-touch-icon', null, $appleIconURL), 'apple-touch-icon');
    }

    /**
     * Register Web app manifest with 192×192 and 512×512 PNG icons for Android devices.
     * @see https://developers.google.com/web/fundamentals/web-app-manifest
     *
     * @param string $manifestFilePath path to .webmanifest file
     *
     * @return Manager
     */
    public function registerManifestFile(string $manifestFilePath): self
    {
        $tag = new FaviconLinkTag('manifest', null, $manifestFilePath);
        $tag->setAttribute('crossorigin', 'use-credentials');

        return $this->registerHeadTag($tag, 'manifest');
    }

    public function registerHeadTag(HeadTag $headerTag, string $key = ''): self
    {
        if (empty($key)) {
            $key = Str::quickRandom(8);
        }

        if ($headerTag instanceof MetaTag) {
            $this->metaTags[$key] = (string) $headerTag;
        } elseif ($headerTag instanceof LinkTag) {
            $this->linkTags[$key] = (string) $headerTag;
        }

        return $this;
    }

    /**
     * Register Header Listener.
     *
     * @param callable $register
     */
    public function setup(callable $register): void
    {
        $this->app['director']->addListener('on_header_required_ready', function (GenericEvent $evt) use ($register) {
            $register($this);

            if ($this->metaTags !== []) {
                $evt->setArgument('metaTags', array_merge($evt->getArgument('metaTags'), $this->metaTags));
            }

            if ($this->linkTags !== []) {
                $evt->setArgument('linkTags', array_merge($evt->getArgument('linkTags'), $this->linkTags));
            }
        });
    }
}
