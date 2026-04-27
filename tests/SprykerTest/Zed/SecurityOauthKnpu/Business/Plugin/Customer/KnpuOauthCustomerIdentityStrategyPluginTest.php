<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityOauthKnpu\Business\Plugin\Customer;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Spryker\Shared\Kernel\Container\GlobalContainer;
use Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\Customer\KnpuOauthCustomerIdentityStrategyPlugin;
use SprykerTest\Zed\SecurityOauthKnpu\SecurityOauthKnpuBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityOauthKnpu
 * @group Business
 * @group Plugin
 * @group Customer
 * @group KnpuOauthCustomerIdentityStrategyPluginTest
 * Add your own group annotations below this line
 */
class KnpuOauthCustomerIdentityStrategyPluginTest extends Unit
{
    protected const string TEST_PROVIDER = 'test-provider';

    protected const string TEST_EXTERNAL_ID = 'external-customer-123';

    protected SecurityOauthKnpuBusinessTester $tester;

    protected function _before()
    {
        parent::_before();

        $this->tester->getContainer()->set('locale', 'de_DE');
        GlobalContainer::setContainer($this->tester->getContainer());
    }

    public function testIsApplicableReturnsTrueWhenProviderAndIdAreSet(): void
    {
        // Arrange
        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId(static::TEST_EXTERNAL_ID);

        $plugin = new KnpuOauthCustomerIdentityStrategyPlugin();

        // Act
        $isApplicable = $plugin->isApplicable($resourceOwnerTransfer);

        // Assert
        $this->assertTrue($isApplicable, 'Expected true when both provider and ID are set.');
    }

    public function testIsApplicableReturnsFalseWhenProviderIsMissing(): void
    {
        // Arrange
        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setId(static::TEST_EXTERNAL_ID);

        $plugin = new KnpuOauthCustomerIdentityStrategyPlugin();

        // Act
        $isApplicable = $plugin->isApplicable($resourceOwnerTransfer);

        // Assert
        $this->assertFalse($isApplicable, 'Expected false when provider is null.');
    }

    public function testIsApplicableReturnsFalseWhenExternalIdIsMissing(): void
    {
        // Arrange
        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER);

        $plugin = new KnpuOauthCustomerIdentityStrategyPlugin();

        // Act
        $isApplicable = $plugin->isApplicable($resourceOwnerTransfer);

        // Assert
        $this->assertFalse($isApplicable, 'Expected false when external ID is null.');
    }

    public function testResolveOauthCustomerReturnsCustomerWhenIdentityRecordExists(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([CustomerTransfer::EMAIL => 'knpu-customer@spryker.com']);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId(static::TEST_EXTERNAL_ID)
            ->setEmail($customerTransfer->getEmail());

        $this->tester->haveCustomerIdentity($customerTransfer, $resourceOwnerTransfer);

        $plugin = new KnpuOauthCustomerIdentityStrategyPlugin();

        // Act
        $resolvedCustomerTransfer = $plugin->resolveOauthCustomer($resourceOwnerTransfer);

        // Assert
        $this->assertNotNull($resolvedCustomerTransfer, 'Expected customer to be resolved via identity record.');
        $this->assertSame($customerTransfer->getIdCustomer(), $resolvedCustomerTransfer->getIdCustomer());
    }

    public function testResolveOauthCustomerReturnsNullWhenNoIdentityRecordExists(): void
    {
        // Arrange
        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId('nonexistent-external-id');

        $plugin = new KnpuOauthCustomerIdentityStrategyPlugin();

        // Act
        $resolvedCustomerTransfer = $plugin->resolveOauthCustomer($resourceOwnerTransfer);

        // Assert
        $this->assertNull($resolvedCustomerTransfer, 'Expected null when no identity record exists.');
    }
}
