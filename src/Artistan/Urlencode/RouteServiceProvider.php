<?php namespace Artistan\Urlencode;

use Artistan\Urlencode\Extensions\Routing\Route as Route;
use Artistan\Urlencode\Extensions\Routing\Router as Router;
use Artistan\Urlencode\Extensions\Routing\UrlGenerator as UrlGenerator;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as IllRouteServiceProvider;
use Illuminate\Support\Facades\Route as RouteFacade;


class RouteServiceProvider extends IllRouteServiceProvider {
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot() {
        $originalRouter = $this->app['router'];

        // replace the app('router')
        $this->app->singleton(
            'router',
            function ($app) {
                return new Router($app['events'], $app);
            }
        );
        // replace the app('url')
        $this->app->singleton(
            'url',
            function ($app) {
                $routes = $app['router']->getRoutes();
                $app->instance('routes', $routes);
                $url = new UrlGenerator(
                    $routes, $app->rebinding(
                    'request',
                    function ($app, $request) {
                        $app['url']->setRequest($request);
                    }
                )
                );
                $url->setSessionResolver(function () {
                    return $this->app['session'];
                });
                $app->rebinding('routes', function ($app, $routes) {
                    $app['url']->setRoutes($routes);
                });
                return $url;
            });

        parent::boot();

        $this->_load($originalRouter);
        //You can change the app router but you can't change the kernel router
        //and kernel uses his own router to dispatch
        //so we need to change the kernel router too
        $kernel = $this->app[Kernel::class];
        if(is_callable([$kernel, 'setRouter'])){
            $kernel->setRouter($this->app['router']);
        }
    }

    private function _load($originalRouter) {
        $this->app['router']->setRoutes($this->app['router']->getRoutes());
        $this->app['router']->cloneMiddleware($originalRouter);
    }

    public function register() {
        $this->app->bind(RouteFacade::class, Route::class);
        $this->app->bind(\Illuminate\Contracts\Routing\UrlGenerator::class, UrlGenerator::class);
    }
}
