<?php
namespace Xanweb\HtmlHelper;

use Concrete\Core\Foundation\Service\Provider as CoreServiceProvider;

class ServiceProvider extends CoreServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('helper/form/ios_toggler', function ($app) {
            return new Service\Form\IosTogglerWidget($app['helper/form']);
        });
    }
}
