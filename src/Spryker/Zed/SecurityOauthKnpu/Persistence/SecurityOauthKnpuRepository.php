<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Persistence;

use Generated\Shared\Transfer\OauthKnpuCustomerIdentityTransfer;
use Generated\Shared\Transfer\OauthKnpuIdentityCriteriaTransfer;
use Generated\Shared\Transfer\OauthKnpuMerchantUserIdentityTransfer;
use Generated\Shared\Transfer\OauthKnpuUserIdentityTransfer;
use Orm\Zed\SecurityOauthKnpu\Persistence\Map\SpyOauthKnpuMerchantUserIdentityTableMap;
use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuCustomerIdentityQuery;
use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuMerchantUserIdentityQuery;
use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuUserIdentityQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SecurityOauthKnpu\Persistence\SecurityOauthKnpuPersistenceFactory getFactory()
 */
class SecurityOauthKnpuRepository extends AbstractRepository implements SecurityOauthKnpuRepositoryInterface
{
    public function findCustomerIdentity(OauthKnpuIdentityCriteriaTransfer $oauthKnpuIdentityCriteriaTransfer): ?OauthKnpuCustomerIdentityTransfer
    {
        $oauthKnpuCustomerIdentityQuery = $this->getFactory()->getOauthKnpuCustomerIdentityQuery();
        $oauthKnpuCustomerIdentityQuery = $this->applyCustomerIdentityCriteria($oauthKnpuCustomerIdentityQuery, $oauthKnpuIdentityCriteriaTransfer);

        $oauthKnpuCustomerIdentity = $oauthKnpuCustomerIdentityQuery->findOne();

        if ($oauthKnpuCustomerIdentity === null) {
            return null;
        }

        return $this->getFactory()
            ->createOauthKnpuIdentityMapper()
            ->mapOauthKnpuCustomerIdentityEntityToOauthKnpuCustomerIdentityTransfer($oauthKnpuCustomerIdentity, new OauthKnpuCustomerIdentityTransfer());
    }

    public function findUserIdentity(OauthKnpuIdentityCriteriaTransfer $oauthKnpuIdentityCriteriaTransfer): ?OauthKnpuUserIdentityTransfer
    {
        $oauthKnpuUserIdentityQuery = $this->getFactory()->getOauthKnpuUserIdentityQuery();
        $oauthKnpuUserIdentityQuery = $this->applyUserIdentityCriteria($oauthKnpuUserIdentityQuery, $oauthKnpuIdentityCriteriaTransfer);

        $oauthKnpuUserIdentity = $oauthKnpuUserIdentityQuery->findOne();

        if ($oauthKnpuUserIdentity === null) {
            return null;
        }

        return $this->getFactory()
            ->createOauthKnpuIdentityMapper()
            ->mapOauthKnpuUserIdentityEntityToOauthKnpuUserIdentityTransfer($oauthKnpuUserIdentity, new OauthKnpuUserIdentityTransfer());
    }

    /**
     * @module MerchantUser
     *
     * @param array<int> $userIds
     *
     * @return array<int>
     */
    public function getMerchantUserIdentityIdsByUserIds(array $userIds): array
    {
        return $this->getFactory()
            ->getOauthKnpuMerchantUserIdentityQuery()
            ->addJoin(
                SpyOauthKnpuMerchantUserIdentityTableMap::COL_FK_MERCHANT_USER,
                'spy_merchant_user.id_merchant_user',
                Criteria::INNER_JOIN,
            )
            ->add('spy_merchant_user.fk_user', $userIds, Criteria::IN)
            ->select([SpyOauthKnpuMerchantUserIdentityTableMap::COL_ID_OAUTH_KNPU_MERCHANT_USER_IDENTITY])
            ->find()
            ->getArrayCopy();
    }

    public function findMerchantUserIdentity(OauthKnpuIdentityCriteriaTransfer $oauthKnpuIdentityCriteriaTransfer): ?OauthKnpuMerchantUserIdentityTransfer
    {
        $oauthKnpuMerchantUserIdentityQuery = $this->getFactory()->getOauthKnpuMerchantUserIdentityQuery();
        $oauthKnpuMerchantUserIdentityQuery = $this->applyMerchantUserIdentityCriteria($oauthKnpuMerchantUserIdentityQuery, $oauthKnpuIdentityCriteriaTransfer);

        $oauthKnpuMerchantUserIdentity = $oauthKnpuMerchantUserIdentityQuery->findOne();

        if ($oauthKnpuMerchantUserIdentity === null) {
            return null;
        }

        return $this->getFactory()
            ->createOauthKnpuIdentityMapper()
            ->mapOauthKnpuMerchantUserIdentityEntityToOauthKnpuMerchantUserIdentityTransfer($oauthKnpuMerchantUserIdentity, new OauthKnpuMerchantUserIdentityTransfer());
    }

    protected function applyCustomerIdentityCriteria(
        SpyOauthKnpuCustomerIdentityQuery $oauthKnpuCustomerIdentityQuery,
        OauthKnpuIdentityCriteriaTransfer $oauthKnpuIdentityCriteriaTransfer
    ): SpyOauthKnpuCustomerIdentityQuery {
        if ($oauthKnpuIdentityCriteriaTransfer->getProvider() !== null) {
            $oauthKnpuCustomerIdentityQuery->filterByProvider($oauthKnpuIdentityCriteriaTransfer->getProvider());
        }

        if ($oauthKnpuIdentityCriteriaTransfer->getExternalId() !== null) {
            $oauthKnpuCustomerIdentityQuery->filterByExternalId($oauthKnpuIdentityCriteriaTransfer->getExternalId());
        }

        if ($oauthKnpuIdentityCriteriaTransfer->getFkCustomer() !== null) {
            $oauthKnpuCustomerIdentityQuery->filterByFkCustomer($oauthKnpuIdentityCriteriaTransfer->getFkCustomer());
        }

        if ($oauthKnpuIdentityCriteriaTransfer->getEmail() !== null) {
            $oauthKnpuCustomerIdentityQuery->filterByEmail($oauthKnpuIdentityCriteriaTransfer->getEmail());
        }

        return $oauthKnpuCustomerIdentityQuery;
    }

    protected function applyUserIdentityCriteria(
        SpyOauthKnpuUserIdentityQuery $oauthKnpuUserIdentityQuery,
        OauthKnpuIdentityCriteriaTransfer $oauthKnpuIdentityCriteriaTransfer
    ): SpyOauthKnpuUserIdentityQuery {
        if ($oauthKnpuIdentityCriteriaTransfer->getProvider() !== null) {
            $oauthKnpuUserIdentityQuery->filterByProvider($oauthKnpuIdentityCriteriaTransfer->getProvider());
        }

        if ($oauthKnpuIdentityCriteriaTransfer->getExternalId() !== null) {
            $oauthKnpuUserIdentityQuery->filterByExternalId($oauthKnpuIdentityCriteriaTransfer->getExternalId());
        }

        if ($oauthKnpuIdentityCriteriaTransfer->getFkUser() !== null) {
            $oauthKnpuUserIdentityQuery->filterByFkUser($oauthKnpuIdentityCriteriaTransfer->getFkUser());
        }

        if ($oauthKnpuIdentityCriteriaTransfer->getEmail() !== null) {
            $oauthKnpuUserIdentityQuery->filterByEmail($oauthKnpuIdentityCriteriaTransfer->getEmail());
        }

        return $oauthKnpuUserIdentityQuery;
    }

    protected function applyMerchantUserIdentityCriteria(
        SpyOauthKnpuMerchantUserIdentityQuery $oauthKnpuMerchantUserIdentityQuery,
        OauthKnpuIdentityCriteriaTransfer $oauthKnpuIdentityCriteriaTransfer,
    ): SpyOauthKnpuMerchantUserIdentityQuery {
        if ($oauthKnpuIdentityCriteriaTransfer->getProvider() !== null) {
            $oauthKnpuMerchantUserIdentityQuery->filterByProvider($oauthKnpuIdentityCriteriaTransfer->getProvider());
        }

        if ($oauthKnpuIdentityCriteriaTransfer->getExternalId() !== null) {
            $oauthKnpuMerchantUserIdentityQuery->filterByExternalId($oauthKnpuIdentityCriteriaTransfer->getExternalId());
        }

        if ($oauthKnpuIdentityCriteriaTransfer->getFkMerchantUser() !== null) {
            $oauthKnpuMerchantUserIdentityQuery->filterByFkMerchantUser($oauthKnpuIdentityCriteriaTransfer->getFkMerchantUser());
        }

        if ($oauthKnpuIdentityCriteriaTransfer->getEmail() !== null) {
            $oauthKnpuMerchantUserIdentityQuery->filterByEmail($oauthKnpuIdentityCriteriaTransfer->getEmail());
        }

        return $oauthKnpuMerchantUserIdentityQuery;
    }
}
