# Concrete5 HTML Helpers

## Installation

Include library to your composer.json
```bash
composer require xanweb/c5-html-helpers
```
### 1- IOS Toggler Widget
```php 
<div class="form-group ios__toggler-form-group">
    <?= $form->label($view->field('myField'), t('My Field Name')); ?>
    <div class="pull-right">
        <?php app('helper/form/ios_toggler')->output($view->field('myField'), 1, $value); ?>
    </div>
</div>
```

### 2- Register Favicon
You can add the following code under package on_start or better in theme header_top.php
<br><b>The most important thing that the code should be executed before including 'header_required.php' element.</b> 
```php
use \Xanweb\HtmlHelper\Head\Manager as HeadManager;

...

HeadManager::setup(function(Manager $manager) {
    $manager->registerManifestFile('/path/to/manifest.webmanifest');
    $manager->registerIcoFavicon('/path/to/favicon.ico');
    $manager->registerSVGFavicon('/path/to/favicon.svg');
    $manager->registerAppleTouchIcon('/path/to/favicon.png');
});
``` 
