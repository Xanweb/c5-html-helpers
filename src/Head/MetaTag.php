<?php

namespace Xanweb\C5\HtmlHelper\Head;

use HtmlObject\Traits\Tag as HtmlObjectTag;

class MetaTag extends HtmlObjectTag implements Tag
{
    /**
     * {@inheritdoc}
     */
    protected $element = 'meta';

    /**
     * @var string
     */
    protected $keyAttribute = 'name';

    /**
     * {@inheritdoc}
     */
    protected $isSelfClosing = true;

    /**
     * Build a new Meta Tag.
     *
     * @param string $keyValue
     * @param string $content
     */
    public function __construct(string $keyValue, string $content)
    {
        $this->setAttributes([
            $this->keyAttribute => h($keyValue),
            'content' => h($content),
        ]);
    }

    /**
     * Set Main Attribute Key.
     *
     * @param string $keyAttribute Default value: 'name'
     */
    protected function setKeyAttribute(string $keyAttribute): void
    {
        $this->keyAttribute = $keyAttribute;
    }

    /**
     * Static alias for constructor.
     *
     * @param string $keyValue
     * @param string $content
     *
     * @return static
     */
    public static function create(string $keyValue, string $content)
    {
        return new static($keyValue, $content);
    }
}
