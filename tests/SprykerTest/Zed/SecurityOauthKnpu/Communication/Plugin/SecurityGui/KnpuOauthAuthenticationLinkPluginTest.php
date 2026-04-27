<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityOauthKnpu\Communication\Plugin\SecurityGui;

use Codeception\Test\Unit;
use Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\SecurityGui\KnpuOauthAuthenticationLinkPlugin;
use SprykerTest\Zed\SecurityOauthKnpu\SecurityOauthKnpuCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityOauthKnpu
 * @group Communication
 * @group Plugin
 * @group SecurityGui
 * @group KnpuOauthAuthenticationLinkPluginTest
 * Add your own group annotations below this line
 */
class KnpuOauthAuthenticationLinkPluginTest extends Unit
{
    protected SecurityOauthKnpuCommunicationTester $tester;

    public function testGetAuthenticationLinkReturnsLinkWithHrefWhenProviderIsConfigured(): void
    {
        // Arrange
        $providerConfig = $this->tester->buildProviderConfig('keycloak', 'kc', 'Login with Keycloak');
        $factory = $this->tester->getBusinessFactoryWithZedProviderConfigs([$providerConfig]);

        $plugin = new KnpuOauthAuthenticationLinkPlugin();
        $plugin->setBusinessFactory($factory);

        // Act
        $linkTransfer = $plugin->getAuthenticationLink();

        // Assert
        $this->assertNotNull($linkTransfer->getHref(), 'Expected href to be set when provider is configured.');
        $this->assertStringContainsString('keycloak', $linkTransfer->getHref(), 'Expected href to contain the client name.');
        $this->assertSame('Login with Keycloak', $linkTransfer->getText());
    }

    public function testGetAuthenticationLinkReturnsEmptyTransferWhenNoProviderIsConfigured(): void
    {
        // Arrange
        $factory = $this->tester->getBusinessFactoryWithZedProviderConfigs([]);

        $plugin = new KnpuOauthAuthenticationLinkPlugin();
        $plugin->setBusinessFactory($factory);

        // Act
        $linkTransfer = $plugin->getAuthenticationLink();

        // Assert
        $this->assertNull($linkTransfer->getHref(), 'Expected null href when no provider is configured.');
    }
}
