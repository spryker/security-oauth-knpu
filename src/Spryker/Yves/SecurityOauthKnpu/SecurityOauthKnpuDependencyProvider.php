<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Yves\SecurityOauthKnpu;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\SecurityOauthKnpu\Plugin\Application\OauthKnpuApplicationPlugin;

class SecurityOauthKnpuDependencyProvider extends AbstractBundleDependencyProvider
{
    public const string CLIENT_REGISTRY = 'CLIENT_REGISTRY';

    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
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
}
