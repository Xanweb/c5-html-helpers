<?php

namespace Xanweb\C5\HtmlHelper\Head;

use HtmlObject\Traits\Tag as HtmlObjectTag;

class LinkTag extends HtmlObjectTag implements Tag
{
    /**
     * {@inheritdoc}
     */
    protected $element = 'link';

    /**
     * Whether the element is self-closing.
     *
     * @var bool
     */
    protected $isSelfClosing = true;

    /**
     * Link Tag Constructor.
     *
     * @param string|null $rel Eg. "stylesheet"
     * @param string|null $type Link type ("text/css")
     * @param string|null $href Link url
     * @param string|null $media Link media (screen, print, etc)
     */
    public function __construct(?string $rel = null, ?string $type = null, ?string $href = '#', ?string $media = null)
    {
        $attributes = [];
        foreach (['rel', 'type', 'href', 'media'] as $k) {
            if (${$k} !== null) {
                $attributes[$k] = ${$k};
            }
        }

        $this->setAttributes($attributes);
    }

    /**
     * Static alias for constructor.
     *
     * @param string|null $rel Eg. "stylesheet"
     * @param string|null $type Link type ("text/css")
     * @param string|null $href Link url
     * @param string|null $media Link media (screen, print, etc)
     *
     * @return static
     */
    public static function create(?string $rel = null, ?string $type = null, ?string $href = '#', ?string $media = null)
    {
        return new static($rel, $type, $href, $media);
    }
}
