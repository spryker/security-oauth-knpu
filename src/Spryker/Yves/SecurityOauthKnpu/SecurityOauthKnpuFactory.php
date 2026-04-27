<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Yves\SecurityOauthKnpu;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\SecurityOauthKnpu\Initiator\OauthCustomerInitiator;
use Spryker\Yves\SecurityOauthKnpu\Initiator\OauthCustomerInitiatorInterface;
use Spryker\Yves\SecurityOauthKnpu\Provider\OauthCustomerAuthenticationLinkProvider;
use Spryker\Yves\SecurityOauthKnpu\Provider\OauthCustomerAuthenticationLinkProviderInterface;
use Spryker\Yves\SecurityOauthKnpu\Reader\OauthCustomerResourceOwnerReader;
use Spryker\Yves\SecurityOauthKnpu\Reader\OauthCustomerResourceOwnerReaderInterface;

/**
 * @method \Spryker\Yves\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 */
class SecurityOauthKnpuFactory extends AbstractFactory
{
    public function createOauthCustomerAuthenticationLinkProvider(): OauthCustomerAuthenticationLinkProviderInterface
    {
        return new OauthCustomerAuthenticationLinkProvider(
            $this->getConfig(),
        );
    }

    public function createOauthCustomerInitiator(): OauthCustomerInitiatorInterface
    {
        return new OauthCustomerInitiator(
            $this->getOauthClientRegistry(),
            $this->getConfig(),
        );
    }

    public function createOauthCustomerResourceOwnerReader(): OauthCustomerResourceOwnerReaderInterface
    {
        return new OauthCustomerResourceOwnerReader(
            $this->getOauthClientRegistry(),
            $this->getConfig(),
        );
    }

    public function getOauthClientRegistry(): ClientRegistry
    {
        return $this->getProvidedDependency(SecurityOauthKnpuDependencyProvider::CLIENT_REGISTRY);
    }
}
