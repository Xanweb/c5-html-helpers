<?php

namespace Xanweb\HtmlHelper;

use Xanweb\Common\Service\Provider as FoundationServiceProvider;
use Xanweb\HtmlHelper\Service\Form\Form;

class ServiceProvider extends FoundationServiceProvider
{
    public function _register(): void
    {
        $this->app->bind(\Concrete\Core\Form\Service\Form::class, Form::class);
        $this->app->singleton('helper/form', Form::class);
        $this->app->singleton('helper/form/ios_toggler', function ($app) {
            return new Service\Form\IosTogglerWidget($app['helper/form']);
        });
    }
}
