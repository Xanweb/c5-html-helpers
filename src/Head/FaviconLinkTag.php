<?php

namespace Xanweb\C5\HtmlHelper\Head;

class FaviconLinkTag extends LinkTag
{
    /**
     * Favicon Link Tag Constructor.
     *
     * @param string|null $rel Eg. "apple-touch-icon" / "icon" / "manifest" / "shortcut icon"...
     * @param string|null $type Link type ("image/x-icon" "image/png")
     * @param string|null $href Link url
     * @param string|null $sizes Link sizes (32x32, 16x16, etc)
     */
    public function __construct(?string $rel = null, ?string $type = null, ?string $href = '#', ?string $sizes = null)
    {
        parent::__construct($rel, $type, $href);

        if ($sizes !== null) {
            $this->setAttribute('sizes', $sizes);
        }
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
