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
use Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\Customer\KnpuOauthCustomerIdentityPersistencePlugin;
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
 * @group KnpuOauthCustomerIdentityPersistencePluginTest
 * Add your own group annotations below this line
 */
class KnpuOauthCustomerIdentityPersistencePluginTest extends Unit
{
    protected const string TEST_PROVIDER = 'test-provider';

    protected const string TEST_EXTERNAL_ID = 'external-customer-456';

    protected const string TEST_EMAIL = 'knpu-persist-customer@spryker.com';

    protected SecurityOauthKnpuBusinessTester $tester;

    protected function _before()
    {
        parent::_before();

        $this->tester->getContainer()->set('locale', 'de_DE');
        GlobalContainer::setContainer($this->tester->getContainer());
    }

    public function testPostResolveCreatesIdentityRecordOnFirstLogin(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([CustomerTransfer::EMAIL => static::TEST_EMAIL]);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId(static::TEST_EXTERNAL_ID)
            ->setEmail(static::TEST_EMAIL);

        $plugin = new KnpuOauthCustomerIdentityPersistencePlugin();

        // Act
        $plugin->postResolve($customerTransfer, $resourceOwnerTransfer);

        // Assert
        $identityEntity = $this->tester->findCustomerIdentityByProviderAndExternalId(static::TEST_PROVIDER, static::TEST_EXTERNAL_ID);
        $this->assertNotNull($identityEntity, 'Expected identity record to be created.');
        $this->assertSame($customerTransfer->getIdCustomer(), $identityEntity->getFkCustomer());
        $this->assertSame(static::TEST_EMAIL, $identityEntity->getEmail());
    }

    public function testPostResolveUpdatesExistingIdentityRecordOnSubsequentLogin(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([CustomerTransfer::EMAIL => static::TEST_EMAIL]);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setId(static::TEST_EXTERNAL_ID)
            ->setEmail(static::TEST_EMAIL);

        $this->tester->haveCustomerIdentity($customerTransfer, $resourceOwnerTransfer);

        $plugin = new KnpuOauthCustomerIdentityPersistencePlugin();

        // Act
        $plugin->postResolve($customerTransfer, $resourceOwnerTransfer);

        // Assert
        $identityEntity = $this->tester->findCustomerIdentityByProviderAndExternalId(static::TEST_PROVIDER, static::TEST_EXTERNAL_ID);
        $this->assertNotNull($identityEntity, 'Expected identity record to be updated, not duplicated.');
        $this->assertSame($customerTransfer->getIdCustomer(), $identityEntity->getFkCustomer());
    }

    public function testPostResolveSkipsWhenProviderIsMissing(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([CustomerTransfer::EMAIL => static::TEST_EMAIL]);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setId(static::TEST_EXTERNAL_ID)
            ->setEmail(static::TEST_EMAIL);

        $plugin = new KnpuOauthCustomerIdentityPersistencePlugin();

        // Act
        $plugin->postResolve($customerTransfer, $resourceOwnerTransfer);

        // Assert
        $identityEntity = $this->tester->findCustomerIdentityByProviderAndExternalId('', static::TEST_EXTERNAL_ID);
        $this->assertNull($identityEntity, 'Expected no record when provider is missing.');
    }

    public function testPostResolveSkipsWhenExternalIdIsMissing(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([CustomerTransfer::EMAIL => static::TEST_EMAIL]);

        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setProvider(static::TEST_PROVIDER)
            ->setEmail(static::TEST_EMAIL);

        $plugin = new KnpuOauthCustomerIdentityPersistencePlugin();

        // Act
        $plugin->postResolve($customerTransfer, $resourceOwnerTransfer);

        // Assert
        $identityEntity = $this->tester->findCustomerIdentityByProviderAndExternalId(static::TEST_PROVIDER, '');
        $this->assertNull($identityEntity, 'Expected no record when external ID is missing.');
    }
}
