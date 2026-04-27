<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Communication;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SecurityOauthKnpu\Communication\Expander\OauthUserSecurityBuilderExpander;
use Spryker\Zed\SecurityOauthKnpu\Communication\Expander\OauthUserSecurityBuilderExpanderInterface;
use Spryker\Zed\SecurityOauthKnpu\Communication\Initiator\MerchantUserInitiator;
use Spryker\Zed\SecurityOauthKnpu\Communication\Initiator\MerchantUserInitiatorInterface;
use Spryker\Zed\SecurityOauthKnpu\Communication\Initiator\UserInitiator;
use Spryker\Zed\SecurityOauthKnpu\Communication\Initiator\UserInitiatorInterface;
use Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuDependencyProvider;

/**
 * @method \Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 * @method \Spryker\Zed\SecurityOauthKnpu\Business\SecurityOauthKnpuFacadeInterface getFacade()
 * @method \Spryker\Zed\SecurityOauthKnpu\Persistence\SecurityOauthKnpuRepositoryInterface getRepository()
 * @method \Spryker\Zed\SecurityOauthKnpu\Persistence\SecurityOauthKnpuEntityManagerInterface getEntityManager()
 */
class SecurityOauthKnpuCommunicationFactory extends AbstractCommunicationFactory
{
    public function createMerchantUserInitiator(): MerchantUserInitiatorInterface
    {
        return new MerchantUserInitiator(
            $this->getClientRegistry(),
            $this->getConfig(),
        );
    }

    public function createUserInitiator(): UserInitiatorInterface
    {
        return new UserInitiator(
            $this->getClientRegistry(),
            $this->getConfig(),
        );
    }

    public function createOauthUserSecurityBuilderExpander(): OauthUserSecurityBuilderExpanderInterface
    {
        return new OauthUserSecurityBuilderExpander();
    }

    protected function getClientRegistry(): ClientRegistry
    {
        return $this->getProvidedDependency(SecurityOauthKnpuDependencyProvider::CLIENT_REGISTRY);
    }
}
