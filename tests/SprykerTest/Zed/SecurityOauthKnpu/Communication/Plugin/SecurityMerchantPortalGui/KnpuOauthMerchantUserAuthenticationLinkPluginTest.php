<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityOauthKnpu\Communication\Plugin\SecurityMerchantPortalGui;

use Codeception\Test\Unit;
use Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\SecurityMerchantPortalGui\KnpuOauthMerchantUserAuthenticationLinkPlugin;
use SprykerTest\Zed\SecurityOauthKnpu\SecurityOauthKnpuCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityOauthKnpu
 * @group Communication
 * @group Plugin
 * @group SecurityMerchantPortalGui
 * @group KnpuOauthMerchantUserAuthenticationLinkPluginTest
 * Add your own group annotations below this line
 */
class KnpuOauthMerchantUserAuthenticationLinkPluginTest extends Unit
{
    protected SecurityOauthKnpuCommunicationTester $tester;

    public function testGetAuthenticationLinksReturnsOneTransferPerConfiguredProvider(): void
    {
        // Arrange
        $providerConfigs = [
            $this->tester->buildProviderConfig('keycloak-mp', 'kc-mp', 'Login with Keycloak'),
            $this->tester->buildProviderConfig('azure-mp', 'az-mp', 'Login with Azure'),
        ];

        $factory = $this->tester->getBusinessFactoryWithMerchantUserProviderConfigs($providerConfigs);

        $plugin = new KnpuOauthMerchantUserAuthenticationLinkPlugin();
        $plugin->setBusinessFactory($factory);

        // Act
        $links = $plugin->getAuthenticationLinks();

        // Assert
        $this->assertCount(2, $links, 'Expected one link per configured provider.');
        $this->assertStringContainsString('keycloak-mp', $links[0]->getHrefOrFail());
        $this->assertStringContainsString('azure-mp', $links[1]->getHrefOrFail());
    }

    public function testGetAuthenticationLinksReturnsEmptyArrayWhenNoProvidersConfigured(): void
    {
        // Arrange
        $factory = $this->tester->getBusinessFactoryWithMerchantUserProviderConfigs([]);

        $plugin = new KnpuOauthMerchantUserAuthenticationLinkPlugin();
        $plugin->setBusinessFactory($factory);

        // Act
        $links = $plugin->getAuthenticationLinks();

        // Assert
        $this->assertSame([], $links, 'Expected empty array when no providers are configured.');
    }
}
