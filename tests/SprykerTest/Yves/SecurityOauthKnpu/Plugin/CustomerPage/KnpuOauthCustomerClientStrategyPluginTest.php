<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\SecurityOauthKnpu\Plugin\CustomerPage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ResourceOwnerRequestTransfer;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use Spryker\Yves\SecurityOauthKnpu\Plugin\CustomerPage\KnpuOauthCustomerClientStrategyPlugin;
use SprykerTest\Yves\SecurityOauthKnpu\SecurityOauthKnpuYvesTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group SecurityOauthKnpu
 * @group Plugin
 * @group CustomerPage
 * @group KnpuOauthCustomerClientStrategyPluginTest
 * Add your own group annotations below this line
 */
class KnpuOauthCustomerClientStrategyPluginTest extends Unit
{
    protected const string TEST_CLIENT_NAME = 'keycloak-customer';

    protected const string TEST_STATE_PREFIX = 'kc-c';

    protected SecurityOauthKnpuYvesTester $tester;

    public function testIsApplicableReturnsTrueWhenStateMatchesConfiguredCustomerProvider(): void
    {
        // Arrange
        $providerConfig = $this->tester->buildProviderConfig(static::TEST_CLIENT_NAME, static::TEST_STATE_PREFIX, 'Login');
        $factory = $this->tester->getFactoryWithCustomerProviderConfigs([$providerConfig]);

        $plugin = new KnpuOauthCustomerClientStrategyPlugin();
        $plugin->setFactory($factory);

        $requestTransfer = (new ResourceOwnerRequestTransfer())
            ->setState(static::TEST_STATE_PREFIX . '_some-random-suffix');

        // Act
        $isApplicable = $plugin->isApplicable($requestTransfer);

        // Assert
        $this->assertTrue($isApplicable, 'Expected true when state matches a configured customer provider prefix.');
    }

    public function testIsApplicableReturnsFalseWhenStateDoesNotMatchAnyProvider(): void
    {
        // Arrange
        $providerConfig = $this->tester->buildProviderConfig(static::TEST_CLIENT_NAME, static::TEST_STATE_PREFIX, 'Login');
        $factory = $this->tester->getFactoryWithCustomerProviderConfigs([$providerConfig]);

        $plugin = new KnpuOauthCustomerClientStrategyPlugin();
        $plugin->setFactory($factory);

        $requestTransfer = (new ResourceOwnerRequestTransfer())
            ->setState('unknown_some-suffix');

        // Act
        $isApplicable = $plugin->isApplicable($requestTransfer);

        // Assert
        $this->assertFalse($isApplicable, 'Expected false when state does not match any provider.');
    }

    public function testIsApplicableReturnsFalseWhenNoProvidersAreConfigured(): void
    {
        // Arrange
        $factory = $this->tester->getFactoryWithCustomerProviderConfigs([]);

        $plugin = new KnpuOauthCustomerClientStrategyPlugin();
        $plugin->setFactory($factory);

        $requestTransfer = (new ResourceOwnerRequestTransfer())
            ->setState(static::TEST_STATE_PREFIX . '_some-suffix');

        // Act
        $isApplicable = $plugin->isApplicable($requestTransfer);

        // Assert
        $this->assertFalse($isApplicable, 'Expected false when no providers are configured.');
    }

    public function testGetResourceOwnerReturnsSuccessfulResponseWhenTokenExchangeSucceeds(): void
    {
        // Arrange
        $resourceOwnerMock = $this->getMockBuilder(ResourceOwnerInterface::class)->getMock();
        $resourceOwnerMock->method('getId')->willReturn('ext-customer-001');
        $resourceOwnerMock->method('toArray')->willReturn(['email' => 'customer@spryker.com']);

        $oauthClientMock = $this->getMockBuilder(OAuth2ClientInterface::class)->getMock();
        $oauthClientMock->method('getAccessToken')->willReturn(new AccessToken(['access_token' => 'test-token']));
        $oauthClientMock->method('fetchUserFromToken')->willReturn($resourceOwnerMock);

        $clientRegistryMock = $this->getMockBuilder(ClientRegistry::class)->disableOriginalConstructor()->getMock();
        $clientRegistryMock->method('getClient')->willReturn($oauthClientMock);

        $providerConfig = $this->tester->buildProviderConfig(static::TEST_CLIENT_NAME, static::TEST_STATE_PREFIX, 'Login');
        $factory = $this->tester->getFactoryWithCustomerProviderConfigs([$providerConfig], $clientRegistryMock);

        $plugin = new KnpuOauthCustomerClientStrategyPlugin();
        $plugin->setFactory($factory);

        $requestTransfer = (new ResourceOwnerRequestTransfer())
            ->setState(static::TEST_STATE_PREFIX . '_some-suffix')
            ->setCode('customer-auth-code-xyz');

        // Act
        $responseTransfer = $plugin->getResourceOwner($requestTransfer);

        // Assert
        $this->assertTrue($responseTransfer->getIsSuccessful(), 'Expected successful response when token exchange succeeds.');
        $this->assertNotNull($responseTransfer->getResourceOwner(), 'Expected resource owner to be set.');
    }

    public function testGetResourceOwnerReturnsFailureWhenNoProvidersAreConfigured(): void
    {
        // Arrange
        $factory = $this->tester->getFactoryWithCustomerProviderConfigs([]);

        $plugin = new KnpuOauthCustomerClientStrategyPlugin();
        $plugin->setFactory($factory);

        $requestTransfer = (new ResourceOwnerRequestTransfer())
            ->setState(static::TEST_STATE_PREFIX . '_some-suffix')
            ->setCode('customer-auth-code-xyz');

        // Act
        $responseTransfer = $plugin->getResourceOwner($requestTransfer);

        // Assert
        $this->assertFalse($responseTransfer->getIsSuccessful(), 'Expected failure when no providers are configured.');
    }
}
