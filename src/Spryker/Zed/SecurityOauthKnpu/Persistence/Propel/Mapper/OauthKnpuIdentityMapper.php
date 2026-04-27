<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OauthKnpuCustomerIdentityTransfer;
use Generated\Shared\Transfer\OauthKnpuMerchantUserIdentityTransfer;
use Generated\Shared\Transfer\OauthKnpuUserIdentityTransfer;
use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuCustomerIdentity;
use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuMerchantUserIdentity;
use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuUserIdentity;

class OauthKnpuIdentityMapper
{
    public function mapOauthKnpuCustomerIdentityEntityToOauthKnpuCustomerIdentityTransfer(
        SpyOauthKnpuCustomerIdentity $entity,
        OauthKnpuCustomerIdentityTransfer $transfer
    ): OauthKnpuCustomerIdentityTransfer {
        return $transfer->fromArray($entity->toArray(), true)
            ->setIdOauthKnpuCustomerIdentity($entity->getIdOauthKnpuCustomerIdentity());
    }

    public function mapOauthKnpuCustomerIdentityTransferToOauthKnpuCustomerIdentityEntity(
        OauthKnpuCustomerIdentityTransfer $transfer,
        SpyOauthKnpuCustomerIdentity $entity
    ): SpyOauthKnpuCustomerIdentity {
        $entity->fromArray($transfer->modifiedToArray());

        return $entity;
    }

    public function mapOauthKnpuUserIdentityEntityToOauthKnpuUserIdentityTransfer(
        SpyOauthKnpuUserIdentity $entity,
        OauthKnpuUserIdentityTransfer $transfer
    ): OauthKnpuUserIdentityTransfer {
        return $transfer->fromArray($entity->toArray(), true)
            ->setIdOauthKnpuUserIdentity($entity->getIdOauthKnpuUserIdentity());
    }

    public function mapOauthKnpuUserIdentityTransferToOauthKnpuUserIdentityEntity(
        OauthKnpuUserIdentityTransfer $transfer,
        SpyOauthKnpuUserIdentity $entity
    ): SpyOauthKnpuUserIdentity {
        $entity->fromArray($transfer->modifiedToArray());

        return $entity;
    }

    public function mapOauthKnpuMerchantUserIdentityEntityToOauthKnpuMerchantUserIdentityTransfer(
        SpyOauthKnpuMerchantUserIdentity $entity,
        OauthKnpuMerchantUserIdentityTransfer $transfer,
    ): OauthKnpuMerchantUserIdentityTransfer {
        return $transfer->fromArray($entity->toArray(), true)
            ->setIdOauthKnpuMerchantUserIdentity($entity->getIdOauthKnpuMerchantUserIdentity());
    }

    public function mapOauthKnpuMerchantUserIdentityTransferToOauthKnpuMerchantUserIdentityEntity(
        OauthKnpuMerchantUserIdentityTransfer $transfer,
        SpyOauthKnpuMerchantUserIdentity $entity,
    ): SpyOauthKnpuMerchantUserIdentity {
        $entity->fromArray($transfer->modifiedToArray());

        return $entity;
    }
}
