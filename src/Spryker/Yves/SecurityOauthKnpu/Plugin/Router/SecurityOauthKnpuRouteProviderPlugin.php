<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Yves\SecurityOauthKnpu\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

/**
 * @method \Spryker\Yves\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 */
class SecurityOauthKnpuRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @see \Spryker\Yves\SecurityOauthKnpu\Provider\OauthCustomerAuthenticationLinkProvider::ROUTE_PATH_OAUTH_INITIATE
     */
    protected const string ROUTE_PATH_OAUTH_INITIATE = '/login/oauth-start';

    protected const string ROUTE_NAME_OAUTH_INITIATE = 'security-oauth-knpu-initiate';

    /**
     * {@inheritDoc}
     * - Adds the OAuth initiate route for customer authentication.
     *
     * @api
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addOauthInitiateRoute($routeCollection);

        return $routeCollection;
    }

    protected function addOauthInitiateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            static::ROUTE_PATH_OAUTH_INITIATE,
            'SecurityOauthKnpu',
            'OauthInitiate',
            'initiateAction',
        );

        $routeCollection->add(static::ROUTE_NAME_OAUTH_INITIATE, $route);

        return $routeCollection;
    }
}
