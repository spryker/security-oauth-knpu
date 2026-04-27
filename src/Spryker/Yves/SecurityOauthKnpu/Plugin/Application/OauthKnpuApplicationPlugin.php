<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Yves\SecurityOauthKnpu\Plugin\Application;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

/**
 * Bridges KnpuOAuth2ClientBundle's ClientRegistry from Symfony container to Spryker DI.
 * Also registers the OAuth callback route with Symfony's router for Knpu URL generation.
 */
class OauthKnpuApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    public const string SERVICE_OAUTH_CLIENT_REGISTRY = 'oauth.client_registry';

    protected const string KNPU_OAUTH_REGISTRY_SERVICE_ID = 'knpu.oauth2.registry';

    protected const string ROUTE_NAME_OAUTH_CUSTOMER_CALLBACK = 'security-oauth-customer-callback';

    protected const string ROUTE_PATH_OAUTH_CUSTOMER_CALLBACK = '/login/oauth-callback';

    protected const string SERVICE_ROUTER = 'router';

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addOauthClientRegistry($container);
        $this->registerOauthCallbackRoute();

        return $container;
    }

    protected function addOauthClientRegistry(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_OAUTH_CLIENT_REGISTRY, function (): ClientRegistry {
            return $this->getClientRegistryFromSymfonyContainer();
        });

        return $container;
    }

    protected function getClientRegistryFromSymfonyContainer(): ClientRegistry
    {
        /** @var \KnpU\OAuth2ClientBundle\Client\ClientRegistry $clientRegistry */
        $clientRegistry = $this->getService(static::KNPU_OAUTH_REGISTRY_SERVICE_ID);

        return $clientRegistry;
    }

    /**
     * Registers the OAuth callback route with Symfony's router.
     * This is needed because Knpu generates redirect URLs using Symfony's router,
     * but Spryker uses its own router that doesn't share routes with Symfony.
     */
    protected function registerOauthCallbackRoute(): void
    {
        $router = $this->findService(static::SERVICE_ROUTER);

        if (!$router instanceof Router) {
            return;
        }

        $routeCollection = $router->getRouteCollection();
        $this->addOauthCallbackRoute($routeCollection);
    }

    protected function addOauthCallbackRoute(RouteCollection $routeCollection): void
    {
        if ($routeCollection->get(static::ROUTE_NAME_OAUTH_CUSTOMER_CALLBACK) !== null) {
            return;
        }

        $route = new Route(static::ROUTE_PATH_OAUTH_CUSTOMER_CALLBACK);
        $routeCollection->add(static::ROUTE_NAME_OAUTH_CUSTOMER_CALLBACK, $route);
    }
}
