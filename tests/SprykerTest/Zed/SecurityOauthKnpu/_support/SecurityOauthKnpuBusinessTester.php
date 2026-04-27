<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityOauthKnpu;

use Codeception\Actor;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuCustomerIdentity;
use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuCustomerIdentityQuery;
use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuMerchantUserIdentity;
use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuMerchantUserIdentityQuery;
use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuUserIdentity;
use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuUserIdentityQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(\SprykerTest\Zed\SecurityOauthKnpu\PHPMD)
 */
class SecurityOauthKnpuBusinessTester extends Actor
{
    use _generated\SecurityOauthKnpuBusinessTesterActions;

    public function haveCustomerIdentity(CustomerTransfer $customerTransfer, ResourceOwnerTransfer $resourceOwnerTransfer): void
    {
        $entity = new SpyOauthKnpuCustomerIdentity();
        $entity->setFkCustomer($customerTransfer->getIdCustomerOrFail());
        $entity->setProvider($resourceOwnerTransfer->getProviderOrFail());
        $entity->setExternalId($resourceOwnerTransfer->getIdOrFail());
        $entity->setEmail($resourceOwnerTransfer->getEmail());
        $entity->save();
    }

    public function haveUserIdentity(UserTransfer $userTransfer, ResourceOwnerTransfer $resourceOwnerTransfer): void
    {
        $entity = new SpyOauthKnpuUserIdentity();
        $entity->setFkUser($userTransfer->getIdUserOrFail());
        $entity->setProvider($resourceOwnerTransfer->getProviderOrFail());
        $entity->setExternalId($resourceOwnerTransfer->getIdOrFail());
        $entity->setEmail($resourceOwnerTransfer->getEmail());
        $entity->save();
    }

    public function haveMerchantUserIdentity(MerchantUserTransfer $merchantUserTransfer, ResourceOwnerTransfer $resourceOwnerTransfer): void
    {
        $entity = new SpyOauthKnpuMerchantUserIdentity();
        $entity->setFkMerchantUser($merchantUserTransfer->getIdMerchantUserOrFail());
        $entity->setProvider($resourceOwnerTransfer->getProviderOrFail());
        $entity->setExternalId($resourceOwnerTransfer->getIdOrFail());
        $entity->setEmail($resourceOwnerTransfer->getEmail());
        $entity->save();
    }

    public function findCustomerIdentityByProviderAndExternalId(string $provider, string $externalId): ?SpyOauthKnpuCustomerIdentity
    {
        return SpyOauthKnpuCustomerIdentityQuery::create()
            ->filterByProvider($provider)
            ->filterByExternalId($externalId)
            ->findOne();
    }

    public function findUserIdentityByProviderAndExternalId(string $provider, string $externalId): ?SpyOauthKnpuUserIdentity
    {
        return SpyOauthKnpuUserIdentityQuery::create()
            ->filterByProvider($provider)
            ->filterByExternalId($externalId)
            ->findOne();
    }

    public function findMerchantUserIdentityByProviderAndExternalId(string $provider, string $externalId): ?SpyOauthKnpuMerchantUserIdentity
    {
        return SpyOauthKnpuMerchantUserIdentityQuery::create()
            ->filterByProvider($provider)
            ->filterByExternalId($externalId)
            ->findOne();
    }
}
