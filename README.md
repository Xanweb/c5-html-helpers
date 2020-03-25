# Concrete5 HTML Helpers

## Installation

Include library to your composer.json
```bash
composer require xanweb/c5-html-helpers
```
### 1- IOS Toggler Widget
```php 
<?php 
    $togglerWidget = app('helper/form/ios_toggler');
?>
<div class="form-group ios__toggler-form-group">
    <?= $form->label($view->field('myField'), t('My Field Name')); ?>
    <div class="pull-right">
        <?php $togglerWidget->output($view->field('myField'), 1, $value); ?>
    </div>
</div>
```
