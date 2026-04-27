<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityOauthKnpu\Business\Plugin\SecurityOauthUser;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\SecurityOauthUser\KnpuOauthUserIdentityStrategyPlugin;
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
 * @group KnpuOauthUserIdentityStrategyPluginTest
 * Add your own group annotations below this line
 */
class KnpuOauthUserIdentityStrategyPluginTest extends Unit
{
    protected const string TEST_PROVIDER = 'test-provider';

    protected const string TEST_EXTERNAL_ID = 'external-user-789';

    protected SecurityOauthKnpuBusinessTester $tester;

    public function testIsApplicableReturnsTrueWhenProviderAndIdAreSet(): void
    {
        // Arrange
        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId(static::TEST_EXTERNAL_ID);

        $plugin = new KnpuOauthUserIdentityStrategyPlugin();

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

        $plugin = new KnpuOauthUserIdentityStrategyPlugin();

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

        $plugin = new KnpuOauthUserIdentityStrategyPlugin();

        // Act
        $isApplicable = $plugin->isApplicable($resourceOwnerTransfer);

        // Assert
        $this->assertFalse($isApplicable, 'Expected false when external ID is null.');
    }

    public function testResolveOauthUserReturnsUserWhenIdentityRecordExists(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'knpu-user@spryker.com']);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId(static::TEST_EXTERNAL_ID)
            ->setEmail($userTransfer->getUsername());

        $this->tester->haveUserIdentity($userTransfer, $resourceOwnerTransfer);

        $plugin = new KnpuOauthUserIdentityStrategyPlugin();

        // Act
        $resolvedUserTransfer = $plugin->resolveOauthUser($resourceOwnerTransfer);

        // Assert
        $this->assertNotNull($resolvedUserTransfer, 'Expected user to be resolved via identity record.');
        $this->assertSame($userTransfer->getIdUser(), $resolvedUserTransfer->getIdUser());
    }

    public function testResolveOauthUserReturnsNullWhenNoIdentityRecordExists(): void
    {
        // Arrange
        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId('nonexistent-user-external-id');

        $plugin = new KnpuOauthUserIdentityStrategyPlugin();

        // Act
        $resolvedUserTransfer = $plugin->resolveOauthUser($resourceOwnerTransfer);

        // Assert
        $this->assertNull($resolvedUserTransfer, 'Expected null when no identity record exists.');
    }
}
