<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityOauthKnpu\Business\Plugin\SecurityOauthMerchantPortal;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\SecurityOauthMerchantPortal\KnpuOauthMerchantUserIdentityStrategyPlugin;
use SprykerTest\Zed\SecurityOauthKnpu\SecurityOauthKnpuBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityOauthKnpu
 * @group Business
 * @group Plugin
 * @group SecurityOauthMerchantPortal
 * @group KnpuOauthMerchantUserIdentityStrategyPluginTest
 * Add your own group annotations below this line
 */
class KnpuOauthMerchantUserIdentityStrategyPluginTest extends Unit
{
    protected const string TEST_PROVIDER = 'test-provider';

    protected const string TEST_EXTERNAL_ID = 'external-merchant-user-999';

    protected const string MERCHANT_STATUS_APPROVED = 'approved';

    protected SecurityOauthKnpuBusinessTester $tester;

    public function testIsApplicableReturnsTrueWhenProviderAndIdAreSet(): void
    {
        // Arrange
        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId(static::TEST_EXTERNAL_ID);

        $plugin = new KnpuOauthMerchantUserIdentityStrategyPlugin();

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

        $plugin = new KnpuOauthMerchantUserIdentityStrategyPlugin();

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

        $plugin = new KnpuOauthMerchantUserIdentityStrategyPlugin();

        // Act
        $isApplicable = $plugin->isApplicable($resourceOwnerTransfer);

        // Assert
        $this->assertFalse($isApplicable, 'Expected false when external ID is null.');
    }

    public function testResolveOauthMerchantUserReturnsMerchantUserWhenIdentityRecordExists(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'knpu-merchant-user@spryker.com']);
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED]);
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId(static::TEST_EXTERNAL_ID)
            ->setEmail($userTransfer->getUsername());

        $this->tester->haveMerchantUserIdentity($merchantUserTransfer, $resourceOwnerTransfer);

        $plugin = new KnpuOauthMerchantUserIdentityStrategyPlugin();

        // Act
        $resolvedMerchantUserTransfer = $plugin->resolveOauthMerchantUser($resourceOwnerTransfer);

        // Assert
        $this->assertNotNull($resolvedMerchantUserTransfer, 'Expected merchant user to be resolved via identity record.');
        $this->assertSame($merchantUserTransfer->getIdMerchantUser(), $resolvedMerchantUserTransfer->getIdMerchantUser());
    }

    public function testResolveOauthMerchantUserReturnsNullWhenNoIdentityRecordExists(): void
    {
        // Arrange
        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId('nonexistent-merchant-user-external-id');

        $plugin = new KnpuOauthMerchantUserIdentityStrategyPlugin();

        // Act
        $resolvedMerchantUserTransfer = $plugin->resolveOauthMerchantUser($resourceOwnerTransfer);

        // Assert
        $this->assertNull($resolvedMerchantUserTransfer, 'Expected null when no identity record exists.');
    }
}
