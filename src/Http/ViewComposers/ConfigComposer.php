<?php

namespace Rapidez\Core\Http\ViewComposers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Rapidez\Core\Models\Attribute;
use Rapidez\Core\Models\Config;

class ConfigComposer
{
    public function compose(View $view)
    {
        $exposedFrontendConfigValues = Arr::only(
            config('rapidez'),
            array_merge(config('rapidez.exposed'), ['store_code'])
        );

        config(['frontend' => array_merge(
            config('frontend') ?: [],
            $exposedFrontendConfigValues
        )]);

        config(['frontend.storage_token' => Cache::rememberForever('storage.token', fn () => md5(Str::random(20)))]);

        config(['frontend.locale' => Config::getCachedByPath('general/locale/code', 'en_US')]);
        config(['frontend.currency' => Config::getCachedByPath('currency/options/default')]);
        config(['frontend.redirect_cart' => (bool)Config::getCachedByPath('checkout/cart/redirect_to_cart')]);

        config(['frontend.searchable' => Arr::pluck(Attribute::getCachedWhere(function ($attribute) {
            return $attribute['search'];
        }), 'code')]);
    }
}
