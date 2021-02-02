<?php

namespace Xanweb\HtmlHelper;

use Xanweb\Common\Service\Provider as FoundationServiceProvider;

class ServiceProvider extends FoundationServiceProvider
{
    public function _register(): void
    {
        $this->app->singleton('helper/form/ios_toggler', function ($app) {
            return new Service\Form\IosTogglerWidget($app['helper/form']);
        });
    }
}
