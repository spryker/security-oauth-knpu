<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityOauthKnpu;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\OauthKnpuProviderConfigTransfer;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Spryker\Zed\SecurityOauthKnpu\Business\SecurityOauthKnpuBusinessFactory;
use Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuDependencyProvider;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(\SprykerTest\Zed\SecurityOauthKnpu\PHPMD)
 */
class SecurityOauthKnpuCommunicationTester extends Actor
{
    use _generated\SecurityOauthKnpuCommunicationTesterActions;

    public function buildProviderConfig(string $clientName, string $statePrefix, string $linkText): OauthKnpuProviderConfigTransfer
    {
        return (new OauthKnpuProviderConfigTransfer())
            ->setClientName($clientName)
            ->setStatePrefix($statePrefix)
            ->setLinkText($linkText);
    }

    public function getBusinessFactoryWithZedProviderConfigs(array $providerConfigs, ?ClientRegistry $clientRegistry = null): SecurityOauthKnpuBusinessFactory
    {
        $this->mockConfigMethod('getZedUserProviderConfigs', function () use ($providerConfigs) {
            return $providerConfigs;
        });
        $this->setDependency(SecurityOauthKnpuDependencyProvider::CLIENT_REGISTRY, $clientRegistry ?? Stub::makeEmpty(ClientRegistry::class));

        return $this->getFactory();
    }

    public function getBusinessFactoryWithMerchantUserProviderConfigs(
        array $providerConfigs,
        ?ClientRegistry $clientRegistry = null
    ): SecurityOauthKnpuBusinessFactory {
        $this->mockConfigMethod('getMerchantUserProviderConfigs', function () use ($providerConfigs) {
            return $providerConfigs;
        });
        $this->setDependency(SecurityOauthKnpuDependencyProvider::CLIENT_REGISTRY, $clientRegistry ?? Stub::makeEmpty(ClientRegistry::class));

        return $this->getFactory();
    }
}
