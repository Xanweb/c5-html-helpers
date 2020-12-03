<?php

namespace Xanweb\HtmlHelper\Head;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Illuminate\Support\Str;
use Symfony\Component\EventDispatcher\GenericEvent;
use Xanweb\HtmlHelper\Head\Tag as HeadTag;

class Manager implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var MetaTag[]
     */
    protected $metaTags = [];

    /**
     * @var FaviconLinkTag[]
     */
    protected $linkTags;

    /**
     * Register Base Favicon
     *
     * @param string $favIconURL relative favicon path (*.ico)
     *
     * @return Manager
     */
    public function registerBaseFavicon(string $favIconURL): self
    {
        $this->registerHeaderTag(new FaviconLinkTag('shortcut icon', 'image/x-icon', $favIconURL), 'shortcut icon');
        $this->registerHeaderTag(new FaviconLinkTag('icon' ,'image/x-icon', $favIconURL), 'icon');

        return $this;
    }

    /**
     * Register Favicon
     *
     * @param string $favIconURL relative favicon path (*.png)
     * @param string $sizes (32x32, 16x16, etc)
     *
     * @return Manager
     */
    public function registerFavicon(string $favIconURL, string $sizes): self
    {
        return $this->registerHeaderTag(new FaviconLinkTag('icon', 'image/png', $favIconURL, $sizes), "icon-$sizes");
    }

    /**
     * Register Apple Touch Icon
     *
     * @param string $appleIconURL relative path (*.png)
     * @param string|null $sizes (32x32, 16x16, etc)
     *
     * @return Manager
     */
    public function registerAppleTouchIcon(string $appleIconURL, ?string $sizes = null): self
    {
        $key = $sizes ? "apple-touch-icon-$sizes" : 'apple-touch-icon';
        return $this->registerHeaderTag(new FaviconLinkTag('apple-touch-icon', null, $appleIconURL, $sizes), $key);
    }

    /**
     * Register PreComposed Apple Touch Icon
     *
     * @param string $appleIconURL relative path (*.png)
     * @param string|null $sizes (32x32, 16x16, etc)
     *
     * @return Manager
     */
    public function registerPreComposedAppleTouchIcon(string $appleIconURL, ?string $sizes = null): self
    {
        $key = $sizes ? "apple-touch-icon-precomposed-$sizes" : 'apple-touch-icon-precomposed';
        return $this->registerHeaderTag(new FaviconLinkTag('apple-touch-icon-precomposed', null, $appleIconURL, $sizes), $key);
    }

    /**
     * Register Modern Windows Icon Config.
     *
     * @param string $xmlFile XML Config File
     *
     * @return Manager
     */
    public function registerMsTileXmlConfig(string $xmlFile): self
    {
        $site = $this->app['site']->getSite();
        $siteName = ($site !== null) ? tc('SiteName', $site->getSiteName()) : '';
        $this->registerHeaderTag(new MetaTag('msapplication-config', $xmlFile), 'msapplication-config');
        $this->registerHeaderTag(new MetaTag('application-name', $siteName), 'msapplication-config');

        return $this;
    }

    /**
     * Register Manifest File.
     * @see https://developers.google.com/web/fundamentals/web-app-manifest
     *
     * @param string $manifestFilePath
     *
     * @return Manager
     */
    public function registerManifestFile(string $manifestFilePath): self
    {
        return $this->registerHeaderTag(new FaviconLinkTag('manifest', null, $manifestFilePath), 'manifest');
    }

    /**
     * Set Browser Toolbar Color (<meta name="theme-color">)
     *
     * @param string $color
     *
     * @return Manager
     */
    public function registerBrowserToolbarColor(string $color): self
    {
        return $this->registerHeaderTag(new MetaTag('theme-color', $color), 'browserToolbarColor');
    }

    public function registerHeaderTag(HeadTag $headerTag, string $key = ''): self
    {
        if (empty($key)) {
            $key = Str::quickRandom(8);
        }

        if ($headerTag instanceof MetaTag) {
            $this->metaTags[$key] = (string) $headerTag;
        } elseif ($headerTag instanceof FaviconLinkTag) {
            $this->linkTags[$key] = (string) $headerTag;
        }

        return $this;
    }

    /**
     * Register Header Listener.
     *
     * @param callable $register
     */
    public function register(callable $register): void
    {
        $this->app['director']->addListener('on_header_required_ready', function (GenericEvent $evt) use ($register) {
            $register($this);

            $metaTags = $this->metaTags;
            $linkTags = $this->linkTags;
            if (!empty($metaTags)) {
                $evt->setArgument('metaTags', array_merge($evt->getArgument('metaTags'), $metaTags));
            }

            if (!empty($linkTags)) {
                $evt->setArgument('linkTags', array_merge($evt->getArgument('linkTags'), $linkTags));
            }
        });
    }
}
