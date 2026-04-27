<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityOauthKnpu\Communication\Plugin\AclMerchantPortal;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\AclMerchantPortal\SecurityOauthKnpuMerchantUserAclEntityConfigurationExpanderPlugin;
use SprykerTest\Zed\SecurityOauthKnpu\SecurityOauthKnpuCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityOauthKnpu
 * @group Communication
 * @group Plugin
 * @group AclMerchantPortal
 * @group SecurityOauthKnpuMerchantUserAclEntityConfigurationExpanderPluginTest
 * Add your own group annotations below this line
 */
class SecurityOauthKnpuMerchantUserAclEntityConfigurationExpanderPluginTest extends Unit
{
    protected const string MERCHANT_USER_IDENTITY_ENTITY = 'Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuMerchantUserIdentity';

    /**
     * READ | UPDATE | CREATE bitmask
     */
    protected const int EXPECTED_PERMISSION_MASK = 0b111;

    protected SecurityOauthKnpuCommunicationTester $tester;

    public function testExpandAddsOauthKnpuMerchantUserIdentityMetadata(): void
    {
        // Arrange
        $aclEntityMetadataConfigTransfer = (new AclEntityMetadataConfigTransfer())
            ->setAclEntityMetadataCollection(new AclEntityMetadataCollectionTransfer());

        $plugin = new SecurityOauthKnpuMerchantUserAclEntityConfigurationExpanderPlugin();

        // Act
        $expandedConfigTransfer = $plugin->expand($aclEntityMetadataConfigTransfer);

        // Assert
        $metadataCollection = $expandedConfigTransfer->getAclEntityMetadataCollectionOrFail()->getCollection();
        $this->assertArrayHasKey(
            static::MERCHANT_USER_IDENTITY_ENTITY,
            $metadataCollection,
            'Expected SpyOauthKnpuMerchantUserIdentity metadata to be added.',
        );

        $metadata = $metadataCollection[static::MERCHANT_USER_IDENTITY_ENTITY];
        $this->assertSame(static::MERCHANT_USER_IDENTITY_ENTITY, $metadata->getEntityName());
        $this->assertSame(static::EXPECTED_PERMISSION_MASK, $metadata->getDefaultGlobalOperationMask());
    }

    public function testExpandPreservesExistingMetadataEntries(): void
    {
        // Arrange
        $existingEntityName = 'Some\Existing\Entity';
        $existingMetadataCollection = new AclEntityMetadataCollectionTransfer();
        $existingMetadataCollection->addAclEntityMetadata(
            $existingEntityName,
            (new AclEntityMetadataTransfer())->setEntityName($existingEntityName),
        );

        $aclEntityMetadataConfigTransfer = (new AclEntityMetadataConfigTransfer())
            ->setAclEntityMetadataCollection($existingMetadataCollection);

        $plugin = new SecurityOauthKnpuMerchantUserAclEntityConfigurationExpanderPlugin();

        // Act
        $expandedConfigTransfer = $plugin->expand($aclEntityMetadataConfigTransfer);

        // Assert
        $metadataCollection = $expandedConfigTransfer->getAclEntityMetadataCollectionOrFail()->getCollection();
        $this->assertArrayHasKey($existingEntityName, $metadataCollection, 'Expected existing metadata entry to be preserved.');
        $this->assertArrayHasKey(static::MERCHANT_USER_IDENTITY_ENTITY, $metadataCollection, 'Expected new metadata entry to be added.');
    }
}
