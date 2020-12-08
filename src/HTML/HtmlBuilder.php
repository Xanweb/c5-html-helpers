<?php

namespace Xanweb\HtmlHelper\HTML;

use Concrete\Core\View\View;
use HtmlObject\Element;
use HtmlObject\Image;
use HtmlObject\Link;
use HtmlObject\Lists;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Traits\Macroable;

class HtmlBuilder
{
    use Macroable;

    /**
     * The URL generator instance.
     *
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var View
     */
    protected $view;

    /**
     * @var string|null
     */
    protected $fallbackImage;

    /**
     * Create a new HTML builder instance.
     *
     * @param UrlGenerator $url
     * @param string|null $fallbackImage
     */
    public function __construct(UrlGenerator $url, ?string $fallbackImage = null)
    {
        $this->url = $url;
        $this->view = View::getRequestInstance();
        $this->fallbackImage = $fallbackImage;
    }

    /**
     * Convert an HTML string to entities.
     *
     * @param string $value
     *
     * @return string
     */
    public function entities(string $value): string
    {
        return htmlentities($value, ENT_QUOTES, APP_CHARSET, false);
    }

    /**
     * Convert entities to HTML characters.
     *
     * @param string $value
     *
     * @return string
     */
    public function decode(string $value): string
    {
        return html_entity_decode($value, ENT_QUOTES, APP_CHARSET);
    }

    /**
     * Generate an HTML image element.
     *
     * @param string $url
     * @param string|null $alt
     * @param array $attributes
     *
     * @return Image
     */
    public function image(string $url, ?string $alt = null, array $attributes = []): Image
    {
        return Image::create($this->url->asset($url), $alt, $attributes);
    }

    /**
     * Get HTML Fallback image element.
     *
     * @param string|null $alt
     * @param array $attributes
     *
     * @return Image
     */
    public function fallbackImage(?string $alt = null, array $attributes = []): ?Image
    {
        if ($this->fallbackImage !== null) {
            return Image::create($this->url->asset($this->fallbackImage), $alt, $attributes);
        }

        return null;
    }

    /**
     * Generate a HTML link to a named route.
     *
     * @param string $name
     * @param string $title
     * @param array $parameters
     * @param array $attributes
     *
     * @return Link
     */
    public function linkRoute(string $name, ?string $title = null, array $parameters = [], array $attributes = []): Link
    {
        $url = $this->url->route($name, $parameters);

        return Link::create($url, $title ?? $url, $attributes);
    }

    /**
     * Generate a HTML link to a controller action.
     *
     * @param string $action
     * @param string $title
     * @param array $parameters
     * @param array $attributes
     *
     * @return Link
     */
    public function linkAction(string $action, ?string $title = null, array $parameters = [], array $attributes = []): Link
    {
        $url = $this->url->action($action, $parameters);

        return Link::create($url, $title ?? $url, $attributes);
    }

    /**
     * Generate a HTML link to URL.
     *
     * @param string $url
     * @param string|null $title
     * @param array $attributes
     *
     * @return Link
     */
    public function link(string $url, ?string $title = null, array $attributes = []): Link
    {
        return Link::create($url, $title ?? $url, $attributes);
    }

    /**
     * Generate a HTML link to an email address.
     *
     * @param string $email
     * @param string|null $title
     * @param array $attributes
     * @param bool $escape
     *
     * @return Link
     */
    public function mailto(string $email, ?string $title = null, $attributes = [], $escape = true): Link
    {
        $email = $this->email($email);

        $title = $title ?: $email;

        if ($escape) {
            $title = $this->entities($title);
        }

        $email = $this->obfuscate('mailto:') . $email;

        return Link::create($email, $title, $attributes);
    }

    /**
     * Obfuscate an e-mail address to prevent spam-bots from sniffing it.
     *
     * @param string $email
     *
     * @return string
     */
    public function email(string $email): string
    {
        return str_replace('@', '&#64;', $this->obfuscate($email));
    }

    /**
     * Generates non-breaking space entities based on number supplied.
     *
     * @param int $num
     *
     * @return string
     */
    public function nbsp(int $num = 1): string
    {
        return str_repeat('&nbsp;', $num);
    }

    /**
     * Generate an ordered list of items.
     *
     * @param array $list
     * @param array $attributes
     *
     * @return HtmlString|string
     */
    public function ol(array $list, array $attributes = [])
    {
        return $this->listing('ol', $list, $attributes);
    }

    /**
     * Generate an un-ordered list of items.
     *
     * @param array $list
     * @param array $attributes
     *
     * @return HtmlString|string
     */
    public function ul(array $list, array $attributes = [])
    {
        return $this->listing('ul', $list, $attributes);
    }

    /**
     * Obfuscate a string to prevent spam-bots from sniffing it.
     *
     * @param string $value
     *
     * @return string
     */
    public function obfuscate(string $value): string
    {
        $safe = '';

        foreach (str_split($value) as $letter) {
            if (ord($letter) > 128) {
                return $letter;
            }

            // To properly obfuscate the value, we will randomly convert each letter to
            // its entity or hexadecimal representation, keeping a bot from sniffing
            // the randomly obfuscated letters out of the string on the responses.
            /** @noinspection PhpUnhandledExceptionInspection */
            switch (random_int(1, 3)) {
                case 1:
                    $safe .= '&#' . ord($letter) . ';';
                    break;
                case 2:
                    $safe .= '&#x' . dechex(ord($letter)) . ';';
                    break;
                case 3:
                    $safe .= $letter;
            }
        }

        return $safe;
    }

    /**
     * Create a listing HTML element.
     *
     * @param string $type
     * @param array $list
     * @param array $attributes
     *
     * @return Lists|string
     */
    protected function listing(string $type, array $list, array $attributes = [])
    {
        $html = '';

        if (empty($list)) {
            return $html;
        }

        $children = [];
        // Essentially we will just spin through the list and build the list of the HTML
        // elements from the array. We will also handled nested lists in case that is
        // present in the array. Then we will build out the final listing elements.
        foreach ($list as $value) {
            $children[] = $this->listingElement($type, $value);
        }

        return Lists::create($type, $children, $attributes);
    }

    /**
     * Create the HTML for a listing element.
     *
     * @param string $type
     * @param mixed $value
     *
     * @return Element
     */
    protected function listingElement(string $type, $value): Element
    {
        if (is_array($value)) {
            return Element::create('li', $this->listing($type, $value));
        }

        return Element::create('li', e($value));
    }
}
