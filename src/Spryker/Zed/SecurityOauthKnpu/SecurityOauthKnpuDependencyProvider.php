<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\Application\OauthKnpuApplicationPlugin;

class SecurityOauthKnpuDependencyProvider extends AbstractBundleDependencyProvider
{
    public const string CLIENT_REGISTRY = 'CLIENT_REGISTRY';

    public const string FACADE_CUSTOMER = 'FACADE_CUSTOMER';

    public const string FACADE_USER = 'FACADE_USER';

    public const string FACADE_MERCHANT_USER = 'FACADE_MERCHANT_USER';

    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addClientRegistry($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addUserFacade($container);
        $container = $this->addMerchantUserFacade($container);

        return $container;
    }

    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addClientRegistry($container);

        return $container;
    }

    protected function addClientRegistry(Container $container): Container
    {
        $container->set(static::CLIENT_REGISTRY, function (Container $container) {
            return $container->getApplicationService(OauthKnpuApplicationPlugin::SERVICE_OAUTH_CLIENT_REGISTRY);
        });

        return $container;
    }

    protected function addCustomerFacade(Container $container): Container
    {
        $container->set(static::FACADE_CUSTOMER, function (Container $container) {
            return $container->getLocator()->customer()->facade();
        });

        return $container;
    }

    protected function addUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_USER, function (Container $container) {
            return $container->getLocator()->user()->facade();
        });

        return $container;
    }

    protected function addMerchantUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_USER, function (Container $container) {
            return $container->getLocator()->merchantUser()->facade();
        });

        return $container;
    }
}
