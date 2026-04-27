<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityOauthKnpu\Business\Plugin\SecurityOauthUser;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\SecurityOauthUser\KnpuOauthUserIdentityPersistencePlugin;
use SprykerTest\Zed\SecurityOauthKnpu\SecurityOauthKnpuBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityOauthKnpu
 * @group Business
 * @group Plugin
 * @group SecurityOauthUser
 * @group KnpuOauthUserIdentityPersistencePluginTest
 * Add your own group annotations below this line
 */
class KnpuOauthUserIdentityPersistencePluginTest extends Unit
{
    protected const string TEST_PROVIDER = 'test-provider';

    protected const string TEST_EXTERNAL_ID = 'external-user-persist-101';

    protected const string TEST_EMAIL = 'knpu-persist-user@spryker.com';

    protected SecurityOauthKnpuBusinessTester $tester;

    public function testPostResolveCreatesIdentityRecordOnFirstLogin(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => static::TEST_EMAIL]);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId(static::TEST_EXTERNAL_ID)
            ->setEmail(static::TEST_EMAIL);

        $plugin = new KnpuOauthUserIdentityPersistencePlugin();

        // Act
        $plugin->postResolve($userTransfer, $resourceOwnerTransfer);

        // Assert
        $identityEntity = $this->tester->findUserIdentityByProviderAndExternalId(static::TEST_PROVIDER, static::TEST_EXTERNAL_ID);
        $this->assertNotNull($identityEntity, 'Expected user identity record to be created.');
        $this->assertSame($userTransfer->getIdUser(), $identityEntity->getFkUser());
        $this->assertSame(static::TEST_EMAIL, $identityEntity->getEmail());
    }

    public function testPostResolveUpdatesExistingIdentityRecordOnSubsequentLogin(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => static::TEST_EMAIL]);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId(static::TEST_EXTERNAL_ID)
            ->setEmail(static::TEST_EMAIL);

        $this->tester->haveUserIdentity($userTransfer, $resourceOwnerTransfer);

        $plugin = new KnpuOauthUserIdentityPersistencePlugin();

        // Act
        $plugin->postResolve($userTransfer, $resourceOwnerTransfer);

        // Assert
        $identityEntity = $this->tester->findUserIdentityByProviderAndExternalId(static::TEST_PROVIDER, static::TEST_EXTERNAL_ID);
        $this->assertNotNull($identityEntity, 'Expected identity record to be updated, not duplicated.');
        $this->assertSame($userTransfer->getIdUser(), $identityEntity->getFkUser());
    }

    public function testPostResolveSkipsWhenProviderIsMissing(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => static::TEST_EMAIL]);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setId(static::TEST_EXTERNAL_ID)
            ->setEmail(static::TEST_EMAIL);

        $plugin = new KnpuOauthUserIdentityPersistencePlugin();

        // Act
        $plugin->postResolve($userTransfer, $resourceOwnerTransfer);

        // Assert
        $identityEntity = $this->tester->findUserIdentityByProviderAndExternalId('', static::TEST_EXTERNAL_ID);
        $this->assertNull($identityEntity, 'Expected no record when provider is missing.');
    }

    public function testPostResolveSkipsWhenExternalIdIsMissing(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => static::TEST_EMAIL]);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setEmail(static::TEST_EMAIL);

        $plugin = new KnpuOauthUserIdentityPersistencePlugin();

        // Act
        $plugin->postResolve($userTransfer, $resourceOwnerTransfer);

        // Assert
        $identityEntity = $this->tester->findUserIdentityByProviderAndExternalId(static::TEST_PROVIDER, '');
        $this->assertNull($identityEntity, 'Expected no record when external ID is missing.');
    }
}
