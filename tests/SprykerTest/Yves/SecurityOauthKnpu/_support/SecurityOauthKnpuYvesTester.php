<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\SecurityOauthKnpu;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\OauthKnpuProviderConfigTransfer;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Spryker\Yves\SecurityOauthKnpu\SecurityOauthKnpuDependencyProvider;
use Spryker\Yves\SecurityOauthKnpu\SecurityOauthKnpuFactory;

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
 * @SuppressWarnings(\SprykerTest\Yves\SecurityOauthKnpu\PHPMD)
 */
class SecurityOauthKnpuYvesTester extends Actor
{
    use _generated\SecurityOauthKnpuYvesTesterActions;

    public function buildProviderConfig(string $clientName, string $statePrefix, string $linkText): OauthKnpuProviderConfigTransfer
    {
        return (new OauthKnpuProviderConfigTransfer())
            ->setClientName($clientName)
            ->setStatePrefix($statePrefix)
            ->setLinkText($linkText);
    }

    public function getFactoryWithCustomerProviderConfigs(array $providerConfigs, ?ClientRegistry $clientRegistry = null): SecurityOauthKnpuFactory
    {
        $this->mockConfigMethod('getCustomerProviderConfigs', function () use ($providerConfigs) {
            return $providerConfigs;
        });
        $this->setDependency(SecurityOauthKnpuDependencyProvider::CLIENT_REGISTRY, $clientRegistry ?? Stub::makeEmpty(ClientRegistry::class));

        return $this->getFactory();
    }
}
