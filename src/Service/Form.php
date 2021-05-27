<?php

namespace Xanweb\HtmlHelper\Service;

use Concrete\Core\Form\Service\Form as CoreForm;
use HtmlObject\Element;

class Form extends CoreForm
{
    /**
     * Creates a label tag.
     *
     * @param string $forFieldID the id of the associated element
     * @param string $innerHTML the inner html of the label
     * @param array $miscFields additional fields appended to the element (a hash array of attributes name => value), possibly including 'class'
     *                          added support for tooltip in $miscFields: $miscFields['tooltip'] = t('My Help Text')
     *
     * @return string
     */
    public function label($forFieldID, $innerHTML, $miscFields = [])
    {
        if (isset($miscFields['tooltip'])) {
            $innerHTML .= ' ' . Element::create(
                'i',
                '',
                [
                    'title' => $miscFields['tooltip'],
                    'class' => 'launch-tooltip fas fa-question-circle',
                ]
            );

            unset($miscFields['tooltip']);
        }

        $required = isset($miscFields['required']) && $miscFields['required'];

        unset($miscFields['required']);

        $labelHtml = parent::label($forFieldID, $innerHTML, $miscFields);

        if ($required) {
            $labelHtml .= ' <div class="float-right"><span class="text-muted small">' . t('Required') . '</span></div>';
        }

        return $labelHtml;
    }
}
