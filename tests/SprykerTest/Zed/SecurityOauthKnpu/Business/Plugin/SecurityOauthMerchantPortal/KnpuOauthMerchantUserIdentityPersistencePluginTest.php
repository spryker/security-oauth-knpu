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
use Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\SecurityOauthMerchantPortal\KnpuOauthMerchantUserIdentityPersistencePlugin;
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
 * @group KnpuOauthMerchantUserIdentityPersistencePluginTest
 * Add your own group annotations below this line
 */
class KnpuOauthMerchantUserIdentityPersistencePluginTest extends Unit
{
    protected const string TEST_PROVIDER = 'test-provider';

    protected const string TEST_EXTERNAL_ID = 'external-merchant-user-persist-202';

    protected const string TEST_EMAIL = 'knpu-persist-merchant-user@spryker.com';

    protected const string MERCHANT_STATUS_APPROVED = 'approved';

    protected SecurityOauthKnpuBusinessTester $tester;

    public function testPostResolveCreatesIdentityRecordOnFirstLogin(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => static::TEST_EMAIL]);
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED]);
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId(static::TEST_EXTERNAL_ID)
            ->setEmail(static::TEST_EMAIL);

        $plugin = new KnpuOauthMerchantUserIdentityPersistencePlugin();

        // Act
        $plugin->postResolve($merchantUserTransfer, $resourceOwnerTransfer);

        // Assert
        $identityEntity = $this->tester->findMerchantUserIdentityByProviderAndExternalId(static::TEST_PROVIDER, static::TEST_EXTERNAL_ID);
        $this->assertNotNull($identityEntity, 'Expected merchant user identity record to be created.');
        $this->assertSame($merchantUserTransfer->getIdMerchantUser(), $identityEntity->getFkMerchantUser());
        $this->assertSame(static::TEST_EMAIL, $identityEntity->getEmail());
    }

    public function testPostResolveUpdatesExistingIdentityRecordOnSubsequentLogin(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => static::TEST_EMAIL]);
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED]);
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId(static::TEST_EXTERNAL_ID)
            ->setEmail(static::TEST_EMAIL);

        $this->tester->haveMerchantUserIdentity($merchantUserTransfer, $resourceOwnerTransfer);

        $plugin = new KnpuOauthMerchantUserIdentityPersistencePlugin();

        // Act
        $plugin->postResolve($merchantUserTransfer, $resourceOwnerTransfer);

        // Assert
        $identityEntity = $this->tester->findMerchantUserIdentityByProviderAndExternalId(static::TEST_PROVIDER, static::TEST_EXTERNAL_ID);
        $this->assertNotNull($identityEntity, 'Expected identity record to be updated, not duplicated.');
        $this->assertSame($merchantUserTransfer->getIdMerchantUser(), $identityEntity->getFkMerchantUser());
    }

    public function testPostResolveSkipsWhenProviderIsMissing(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => static::TEST_EMAIL]);
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED]);
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setId(static::TEST_EXTERNAL_ID)
            ->setEmail(static::TEST_EMAIL);

        $plugin = new KnpuOauthMerchantUserIdentityPersistencePlugin();

        // Act
        $plugin->postResolve($merchantUserTransfer, $resourceOwnerTransfer);

        // Assert
        $identityEntity = $this->tester->findMerchantUserIdentityByProviderAndExternalId('', static::TEST_EXTERNAL_ID);
        $this->assertNull($identityEntity, 'Expected no record when provider is missing.');
    }

    public function testPostResolveSkipsWhenExternalIdIsMissing(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => static::TEST_EMAIL]);
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED]);
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setEmail(static::TEST_EMAIL);

        $plugin = new KnpuOauthMerchantUserIdentityPersistencePlugin();

        // Act
        $plugin->postResolve($merchantUserTransfer, $resourceOwnerTransfer);

        // Assert
        $identityEntity = $this->tester->findMerchantUserIdentityByProviderAndExternalId(static::TEST_PROVIDER, '');
        $this->assertNull($identityEntity, 'Expected no record when external ID is missing.');
    }
}
