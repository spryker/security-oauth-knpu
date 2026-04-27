<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\SecurityOauthKnpu\Plugin\CustomerPage;

use Codeception\Test\Unit;
use Spryker\Yves\SecurityOauthKnpu\Plugin\CustomerPage\KnpuCustomerAuthenticationLinkPlugin;
use SprykerTest\Yves\SecurityOauthKnpu\SecurityOauthKnpuYvesTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group SecurityOauthKnpu
 * @group Plugin
 * @group CustomerPage
 * @group KnpuCustomerAuthenticationLinkPluginTest
 * Add your own group annotations below this line
 */
class KnpuCustomerAuthenticationLinkPluginTest extends Unit
{
    protected SecurityOauthKnpuYvesTester $tester;

    public function testGetAuthenticationLinksReturnsOneTransferPerConfiguredProvider(): void
    {
        // Arrange
        $providerConfigs = [
            $this->tester->buildProviderConfig('keycloak-customer', 'kc-c', 'Login with Keycloak'),
            $this->tester->buildProviderConfig('azure-customer', 'az-c', 'Login with Azure'),
        ];

        $factory = $this->tester->getFactoryWithCustomerProviderConfigs($providerConfigs);

        $plugin = new KnpuCustomerAuthenticationLinkPlugin();
        $plugin->setFactory($factory);

        // Act
        $links = $plugin->getAuthenticationLinks();

        // Assert
        $this->assertCount(2, $links, 'Expected one link per configured customer provider.');
        $this->assertStringContainsString('keycloak-customer', $links[0]->getHrefOrFail());
        $this->assertStringContainsString('azure-customer', $links[1]->getHrefOrFail());
    }

    public function testGetAuthenticationLinksReturnsEmptyArrayWhenNoProvidersConfigured(): void
    {
        // Arrange
        $factory = $this->tester->getFactoryWithCustomerProviderConfigs([]);

        $plugin = new KnpuCustomerAuthenticationLinkPlugin();
        $plugin->setFactory($factory);

        // Act
        $links = $plugin->getAuthenticationLinks();

        // Assert — graceful degradation: empty array, no exception
        $this->assertSame([], $links, 'Expected empty array when no customer providers are configured.');
    }
}
