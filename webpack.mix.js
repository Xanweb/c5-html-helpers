const mix = require('laravel-mix');

mix.sass('css/scss/dashboard/ios-toggle-button.scss', 'css/dashboard')
    .disableNotifications();

// Disable mix-manifest.json
// @see https://github.com/JeffreyWay/laravel-mix/issues/580
Mix.manifest.refresh = _ => void 0;
