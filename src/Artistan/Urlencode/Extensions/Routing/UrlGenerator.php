<?php
/**
 * User: cpeterson
 * Date: 2013
 */

namespace Artistan\Urlencode\Extensions\Routing;

use Illuminate\Routing\UrlGenerator as UrlGen;
use Artistan\Urlencode\Extensions\Routing\RouteUrlGenerator;

class UrlGenerator extends UrlGen
{
    /**
     * Get the Route URL generator instance.
     *
     * @return \Illuminate\Routing\RouteUrlGenerator
     */
    protected function routeUrl()
    {
        if (! $this->routeGenerator) {
            $this->routeGenerator = new RouteUrlGenerator($this, $this->request);
        }

        return $this->routeGenerator;
    }
}
