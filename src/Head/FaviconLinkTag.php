<?php

namespace Xanweb\HtmlHelper\Head;

use HtmlObject\Traits\Tag as HtmlObjectTag;

class FaviconLinkTag extends HtmlObjectTag implements Tag
{
    /**
     * {@inheritdoc}
     */
    protected $element = 'link';

    /**
     * Whether the element is self closing.
     *
     * @var bool
     */
    protected $isSelfClosing = true;

    /**
     * Create a new Link.
     *
     * @param string|null $rel Eg. "apple-touch-icon" / "icon" / "manifest" / "shortcut icon"...
     * @param string|null $type Link type ("image/x-icon" "image/png")
     * @param string|null $href Link url
     * @param string|null $sizes Link sizes (32x32, 16x16, etc)
     */
    public function __construct(?string $rel = null, ?string $type = null, ?string $href = '#', ?string $sizes = null)
    {
        $attributes = [];
        foreach (['rel', 'type', 'href', 'sizes'] as $k) {
            if (${$k} !== null) {
                $attributes[$k] = ${$k};
            }
        }

        $this->setAttributes($attributes);
    }

    /**
     * Static alias for constructor.
     *
     * @param string|null $rel Eg. "apple-touch-icon" / "icon" / "manifest" / "shortcut icon"...
     * @param string|null $type Link type ("image/x-icon" "image/png")
     * @param string|null $href Link url
     * @param string|null $sizes Link sizes (32x32, 16x16, etc)
     *
     * @return static
     */
    public static function create(?string $rel = null, ?string $type = null, ?string $href = '#', ?string $sizes = null)
    {
        return new static($rel, $type, $href, $sizes);
    }
}
