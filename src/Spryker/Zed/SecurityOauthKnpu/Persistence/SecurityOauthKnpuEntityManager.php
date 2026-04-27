<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Persistence;

use Generated\Shared\Transfer\OauthKnpuCustomerIdentityTransfer;
use Generated\Shared\Transfer\OauthKnpuMerchantUserIdentityTransfer;
use Generated\Shared\Transfer\OauthKnpuUserIdentityTransfer;
use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuCustomerIdentity;
use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuMerchantUserIdentity;
use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuUserIdentity;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SecurityOauthKnpu\Persistence\SecurityOauthKnpuPersistenceFactory getFactory()
 */
class SecurityOauthKnpuEntityManager extends AbstractEntityManager implements SecurityOauthKnpuEntityManagerInterface
{
    public function createCustomerIdentity(OauthKnpuCustomerIdentityTransfer $oauthKnpuCustomerIdentityTransfer): OauthKnpuCustomerIdentityTransfer
    {
        $oauthKnpuCustomerIdentityEntity = new SpyOauthKnpuCustomerIdentity();
        $oauthKnpuCustomerIdentityEntity = $this->getFactory()
            ->createOauthKnpuIdentityMapper()
            ->mapOauthKnpuCustomerIdentityTransferToOauthKnpuCustomerIdentityEntity($oauthKnpuCustomerIdentityTransfer, $oauthKnpuCustomerIdentityEntity);

        $oauthKnpuCustomerIdentityEntity->save();

        return $this->getFactory()
            ->createOauthKnpuIdentityMapper()
            ->mapOauthKnpuCustomerIdentityEntityToOauthKnpuCustomerIdentityTransfer($oauthKnpuCustomerIdentityEntity, $oauthKnpuCustomerIdentityTransfer);
    }

    public function updateCustomerIdentity(OauthKnpuCustomerIdentityTransfer $oauthKnpuCustomerIdentityTransfer): OauthKnpuCustomerIdentityTransfer
    {
        $oauthKnpuCustomerIdentityEntity = $this->getFactory()->getOauthKnpuCustomerIdentityQuery()
            ->filterByIdOauthKnpuCustomerIdentity($oauthKnpuCustomerIdentityTransfer->getIdOauthKnpuCustomerIdentityOrFail())
            ->findOneOrCreate();

        $oauthKnpuCustomerIdentityEntity = $this->getFactory()
            ->createOauthKnpuIdentityMapper()
            ->mapOauthKnpuCustomerIdentityTransferToOauthKnpuCustomerIdentityEntity($oauthKnpuCustomerIdentityTransfer, $oauthKnpuCustomerIdentityEntity);

        $oauthKnpuCustomerIdentityEntity->save();

        return $this->getFactory()
            ->createOauthKnpuIdentityMapper()
            ->mapOauthKnpuCustomerIdentityEntityToOauthKnpuCustomerIdentityTransfer($oauthKnpuCustomerIdentityEntity, $oauthKnpuCustomerIdentityTransfer);
    }

    public function createUserIdentity(OauthKnpuUserIdentityTransfer $oauthKnpuUserIdentityTransfer): OauthKnpuUserIdentityTransfer
    {
        $oauthKnpuUserIdentity = new SpyOauthKnpuUserIdentity();
        $oauthKnpuUserIdentity = $this->getFactory()
            ->createOauthKnpuIdentityMapper()
            ->mapOauthKnpuUserIdentityTransferToOauthKnpuUserIdentityEntity($oauthKnpuUserIdentityTransfer, $oauthKnpuUserIdentity);

        $oauthKnpuUserIdentity->save();

        return $this->getFactory()
            ->createOauthKnpuIdentityMapper()
            ->mapOauthKnpuUserIdentityEntityToOauthKnpuUserIdentityTransfer($oauthKnpuUserIdentity, $oauthKnpuUserIdentityTransfer);
    }

    public function updateUserIdentity(OauthKnpuUserIdentityTransfer $oauthKnpuUserIdentityTransfer): OauthKnpuUserIdentityTransfer
    {
        $oauthKnpuUserIdentityEntity = $this->getFactory()->getOauthKnpuUserIdentityQuery()
            ->filterByIdOauthKnpuUserIdentity($oauthKnpuUserIdentityTransfer->getIdOauthKnpuUserIdentityOrFail())
            ->findOneOrCreate();

        $oauthKnpuUserIdentityEntity = $this->getFactory()
            ->createOauthKnpuIdentityMapper()
            ->mapOauthKnpuUserIdentityTransferToOauthKnpuUserIdentityEntity($oauthKnpuUserIdentityTransfer, $oauthKnpuUserIdentityEntity);

        $oauthKnpuUserIdentityEntity->save();

        return $this->getFactory()
            ->createOauthKnpuIdentityMapper()
            ->mapOauthKnpuUserIdentityEntityToOauthKnpuUserIdentityTransfer($oauthKnpuUserIdentityEntity, $oauthKnpuUserIdentityTransfer);
    }

    public function createMerchantUserIdentity(
        OauthKnpuMerchantUserIdentityTransfer $oauthKnpuMerchantUserIdentityTransfer
    ): OauthKnpuMerchantUserIdentityTransfer {
        $oauthKnpuMerchantUserIdentityEntity = new SpyOauthKnpuMerchantUserIdentity();
        $oauthKnpuMerchantUserIdentityEntity = $this->getFactory()
            ->createOauthKnpuIdentityMapper()
            ->mapOauthKnpuMerchantUserIdentityTransferToOauthKnpuMerchantUserIdentityEntity($oauthKnpuMerchantUserIdentityTransfer, $oauthKnpuMerchantUserIdentityEntity);

        $oauthKnpuMerchantUserIdentityEntity->save();

        return $this->getFactory()
            ->createOauthKnpuIdentityMapper()
            ->mapOauthKnpuMerchantUserIdentityEntityToOauthKnpuMerchantUserIdentityTransfer($oauthKnpuMerchantUserIdentityEntity, $oauthKnpuMerchantUserIdentityTransfer);
    }

    public function updateMerchantUserIdentity(
        OauthKnpuMerchantUserIdentityTransfer $oauthKnpuMerchantUserIdentityTransfer
    ): OauthKnpuMerchantUserIdentityTransfer {
        $oauthKnpuMerchantUserIdentityEntity = $this->getFactory()->getOauthKnpuMerchantUserIdentityQuery()
            ->filterByIdOauthKnpuMerchantUserIdentity($oauthKnpuMerchantUserIdentityTransfer->getIdOauthKnpuMerchantUserIdentityOrFail())
            ->findOneOrCreate();

        $oauthKnpuMerchantUserIdentityEntity = $this->getFactory()
            ->createOauthKnpuIdentityMapper()
            ->mapOauthKnpuMerchantUserIdentityTransferToOauthKnpuMerchantUserIdentityEntity($oauthKnpuMerchantUserIdentityTransfer, $oauthKnpuMerchantUserIdentityEntity);

        $oauthKnpuMerchantUserIdentityEntity->save();

        return $this->getFactory()
            ->createOauthKnpuIdentityMapper()
            ->mapOauthKnpuMerchantUserIdentityEntityToOauthKnpuMerchantUserIdentityTransfer($oauthKnpuMerchantUserIdentityEntity, $oauthKnpuMerchantUserIdentityTransfer);
    }
}
