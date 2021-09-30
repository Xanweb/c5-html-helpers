<?php

namespace Xanweb\C5\HtmlHelper\Service\Form;

use Concrete\Core\View\View;
use Concrete\Core\Form\Service\Form;
use Xanweb\ExtAsset\Asset\VendorAssetManager;

class IosTogglerWidget
{
    /**
     * @var Form
     */
    protected $form;

    public function __construct(Form $form)
    {
        $this->form = $form;

        /** @noinspection PhpUnhandledExceptionInspection */
        VendorAssetManager::register(
            'vendor-css',
            'dashboard/ios/toggler',
            'css/dashboard/ios-toggle-button.css',
            'xanweb/c5-html-helpers',
            ['minify' => false]
        );
    }

    /**
     * Generates a Toggler.
     *
     * @param string $key The name/id of the element. It should end with '[]' if it's to return an array on submit.
     * @param string $value String value sent to server, if checkbox is checked, on submit
     * @param string $isChecked "Checked" value (subject to be overridden by $_REQUEST). Checkbox is checked if value is true (string). Note that 'false' (string) evaluates to true (boolean)!
     * @param array $miscFields additional fields appended to the element (a hash array of attributes name => value), possibly including 'class'
     */
    public function output($key, $value, $isChecked = false, $miscFields = []): void
    {
        $view = View::getInstance();
        $view->requireAsset('vendor-css', 'dashboard/ios/toggler');

        $miscFields['class'] = trim(($miscFields['class'] ?? '') . ' ios__toggler ios__toggler-round-flat');

        echo '<div>'
            . $this->form->checkbox($key, $value, $isChecked, $miscFields) . $this->form->label($key, '')
            . '</div>';
    }
}
