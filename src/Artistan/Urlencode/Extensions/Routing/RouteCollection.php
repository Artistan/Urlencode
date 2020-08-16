<?php

namespace Artistan\Urlencode\Extensions\Routing;

use Illuminate\Container\Container;
use Artistan\Urlencode\Request;
use Illuminate\Support\Arr;

class RouteCollection extends \Illuminate\Routing\RouteCollection
{
    /**
     * An array of the routes keyed by method.
     *
     * @var array
     */
    protected $routes = [];

    /**
     * A flattened array of all of the routes.
     *
     * @var Route[]
     */
    protected $allRoutes = [];

    /**
     * A look-up table of routes by their names.
     *
     * @var Route[]
     */
    protected $nameList = [];

    /**
     * A look-up table of routes by controller action.
     *
     * @var Route[]
     */
    protected $actionList = [];

    /**
     * Add a Route instance to the collection.
     *
     * @param  Route  $route
     * @return Route
     */
    public function add($route)
    {
        $this->addToCollections($route);

        $this->addLookups($route);

        return $route;
    }

    /**
     * Find the first route matching a given request.
     *
     * @param  \Artistan\Urlencode\Request  $request
     * @return Route
     *
     * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function match($request)
    {
        $routes = $this->get($request->getMethod());

        // First, we will see if we can find a matching route for this current request
        // method. If we can, great, we can just return it so that it can be called
        // by the consumer. Otherwise we will check for routes with another verb.
        $route = $this->matchAgainstRoutes($routes, $request);

        return $this->handleMatchedRoute($request, $route);
    }

    /**
     * Convert the collection to a CompiledRouteCollection instance.
     *
     * @param  Router  $router
     * @param  \Illuminate\Container\Container  $container
     * @return CompiledRouteCollection
     */
    public function toCompiledRouteCollection( $router, $container)
    {
        ['compiled' => $compiled, 'attributes' => $attributes] = $this->compile();

        return (new CompiledRouteCollection($compiled, $attributes))
            ->setRouter($router)
            ->setContainer($container);
    }
}
