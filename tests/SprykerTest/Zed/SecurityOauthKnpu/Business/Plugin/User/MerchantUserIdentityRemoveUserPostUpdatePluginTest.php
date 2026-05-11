<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityOauthKnpu\Business\Plugin\User;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Generated\Shared\Transfer\UserCollectionResponseTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\User\MerchantUserIdentityRemoveUserPostUpdatePlugin;
use SprykerTest\Zed\SecurityOauthKnpu\SecurityOauthKnpuBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityOauthKnpu
 * @group Business
 * @group Plugin
 * @group User
 * @group MerchantUserIdentityRemoveUserPostUpdatePluginTest
 * Add your own group annotations below this line
 */
class MerchantUserIdentityRemoveUserPostUpdatePluginTest extends Unit
{
    protected const string TEST_PROVIDER = 'test-provider';

    protected const string TEST_EXTERNAL_ID_A = 'ext-remove-user-a-001';

    protected const string TEST_EXTERNAL_ID_B = 'ext-remove-user-b-002';

    protected const string MERCHANT_STATUS_APPROVED = 'approved';

    protected const string USER_STATUS_DELETED = 'deleted';

    protected const string USER_STATUS_ACTIVE = 'active';

    protected const string USER_STATUS_BLOCKED = 'blocked';

    protected SecurityOauthKnpuBusinessTester $tester;

    public function testPostUpdateRemovesMerchantUserIdentityForDeletedUser(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'deleted-user-identity@spryker.com']);
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED]);
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId(static::TEST_EXTERNAL_ID_A)
            ->setEmail('deleted-user-identity@spryker.com');

        $this->tester->haveMerchantUserIdentity($merchantUserTransfer, $resourceOwnerTransfer);

        $userCollectionResponseTransfer = (new UserCollectionResponseTransfer())
            ->addUser(
                (new UserTransfer())
                    ->setIdUser($userTransfer->getIdUserOrFail())
                    ->setStatus(static::USER_STATUS_DELETED),
            );

        $plugin = new MerchantUserIdentityRemoveUserPostUpdatePlugin();

        // Act
        $plugin->postUpdate($userCollectionResponseTransfer);

        // Assert
        $this->assertNull(
            $this->tester->findMerchantUserIdentityByProviderAndExternalId(static::TEST_PROVIDER, static::TEST_EXTERNAL_ID_A),
            'Expected merchant user identity to be removed for deleted user.',
        );
    }

    public function testPostUpdateDoesNotRemoveIdentityForActiveUser(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'active-user-identity@spryker.com']);
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED]);
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId('ext-active-user-003')
            ->setEmail('active-user-identity@spryker.com');

        $this->tester->haveMerchantUserIdentity($merchantUserTransfer, $resourceOwnerTransfer);

        $userCollectionResponseTransfer = (new UserCollectionResponseTransfer())
            ->addUser(
                (new UserTransfer())
                    ->setIdUser($userTransfer->getIdUserOrFail())
                    ->setStatus(static::USER_STATUS_ACTIVE),
            );

        $plugin = new MerchantUserIdentityRemoveUserPostUpdatePlugin();

        // Act
        $plugin->postUpdate($userCollectionResponseTransfer);

        // Assert
        $this->assertNotNull(
            $this->tester->findMerchantUserIdentityByProviderAndExternalId(static::TEST_PROVIDER, 'ext-active-user-003'),
            'Expected merchant user identity to be preserved for active user.',
        );
    }

    public function testPostUpdateDoesNotRemoveIdentityForBlockedUser(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'blocked-user-identity@spryker.com']);
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED]);
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId('ext-blocked-user-004')
            ->setEmail('blocked-user-identity@spryker.com');

        $this->tester->haveMerchantUserIdentity($merchantUserTransfer, $resourceOwnerTransfer);

        $userCollectionResponseTransfer = (new UserCollectionResponseTransfer())
            ->addUser(
                (new UserTransfer())
                    ->setIdUser($userTransfer->getIdUserOrFail())
                    ->setStatus(static::USER_STATUS_BLOCKED),
            );

        $plugin = new MerchantUserIdentityRemoveUserPostUpdatePlugin();

        // Act
        $plugin->postUpdate($userCollectionResponseTransfer);

        // Assert
        $this->assertNotNull(
            $this->tester->findMerchantUserIdentityByProviderAndExternalId(static::TEST_PROVIDER, 'ext-blocked-user-004'),
            'Expected merchant user identity to be preserved for blocked user.',
        );
    }

    public function testPostUpdateOnlyRemovesIdentitiesOfDeletedUsers(): void
    {
        // Arrange
        $userTransferA = $this->tester->haveUser([UserTransfer::USERNAME => 'deleted-isolation-a@spryker.com']);
        $merchantTransferA = $this->tester->haveMerchant([MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED]);
        $merchantUserTransferA = $this->tester->haveMerchantUser($merchantTransferA, $userTransferA);
        $resourceOwnerTransferA = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId(static::TEST_EXTERNAL_ID_A)
            ->setEmail('deleted-isolation-a@spryker.com');

        $this->tester->haveMerchantUserIdentity($merchantUserTransferA, $resourceOwnerTransferA);

        $userTransferB = $this->tester->haveUser([UserTransfer::USERNAME => 'active-isolation-b@spryker.com']);
        $merchantTransferB = $this->tester->haveMerchant([MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED]);
        $merchantUserTransferB = $this->tester->haveMerchantUser($merchantTransferB, $userTransferB);
        $resourceOwnerTransferB = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId(static::TEST_EXTERNAL_ID_B)
            ->setEmail('active-isolation-b@spryker.com');

        $this->tester->haveMerchantUserIdentity($merchantUserTransferB, $resourceOwnerTransferB);

        $userCollectionResponseTransfer = (new UserCollectionResponseTransfer())
            ->addUser(
                (new UserTransfer())
                    ->setIdUser($userTransferA->getIdUserOrFail())
                    ->setStatus(static::USER_STATUS_DELETED),
            )
            ->addUser(
                (new UserTransfer())
                    ->setIdUser($userTransferB->getIdUserOrFail())
                    ->setStatus(static::USER_STATUS_ACTIVE),
            );

        $plugin = new MerchantUserIdentityRemoveUserPostUpdatePlugin();

        // Act
        $plugin->postUpdate($userCollectionResponseTransfer);

        // Assert
        $this->assertNull(
            $this->tester->findMerchantUserIdentityByProviderAndExternalId(static::TEST_PROVIDER, static::TEST_EXTERNAL_ID_A),
            'Expected identity of deleted user A to be removed.',
        );

        $this->assertNotNull(
            $this->tester->findMerchantUserIdentityByProviderAndExternalId(static::TEST_PROVIDER, static::TEST_EXTERNAL_ID_B),
            'Expected identity of active user B to be preserved.',
        );
    }

    public function testPostUpdateDoesNothingWhenDeletedUserHasNoIdentity(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'no-identity-deleted@spryker.com']);

        $userCollectionResponseTransfer = (new UserCollectionResponseTransfer())
            ->addUser(
                (new UserTransfer())
                    ->setIdUser($userTransfer->getIdUserOrFail())
                    ->setStatus(static::USER_STATUS_DELETED),
            );

        $plugin = new MerchantUserIdentityRemoveUserPostUpdatePlugin();

        // Act
        $result = $plugin->postUpdate($userCollectionResponseTransfer);

        // Assert
        $this->assertCount(
            1,
            $result->getUsers(),
            'Expected collection to be returned unchanged when user has no identity records.',
        );
    }
}
