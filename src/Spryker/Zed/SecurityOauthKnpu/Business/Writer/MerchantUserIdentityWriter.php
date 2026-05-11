<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Business\Writer;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\OauthKnpuIdentityCriteriaTransfer;
use Generated\Shared\Transfer\OauthKnpuMerchantUserIdentityTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Generated\Shared\Transfer\UserCollectionResponseTransfer;
use Spryker\Zed\SecurityOauthKnpu\Persistence\SecurityOauthKnpuEntityManagerInterface;
use Spryker\Zed\SecurityOauthKnpu\Persistence\SecurityOauthKnpuRepositoryInterface;

class MerchantUserIdentityWriter implements MerchantUserIdentityWriterInterface
{
    protected const string USER_STATUS_DELETED = 'deleted';

    public function __construct(
        protected SecurityOauthKnpuRepositoryInterface $securityOauthKnpuRepository,
        protected SecurityOauthKnpuEntityManagerInterface $securityOauthKnpuEntityManager,
    ) {
    }

    public function persistMerchantUserIdentityByResourceOwner(
        MerchantUserTransfer $merchantUserTransfer,
        ResourceOwnerTransfer $resourceOwnerTransfer,
    ): void {
        if ($resourceOwnerTransfer->getId() === null || $resourceOwnerTransfer->getProvider() === null) {
            return;
        }

        $merchantUserIdentityTransfer = (new OauthKnpuMerchantUserIdentityTransfer())
            ->setFkMerchantUser($merchantUserTransfer->getIdMerchantUserOrFail())
            ->setProvider($resourceOwnerTransfer->getProviderOrFail())
            ->setExternalId($resourceOwnerTransfer->getIdOrFail())
            ->setEmail($resourceOwnerTransfer->getEmail());

        $existingIdentity = $this->securityOauthKnpuRepository->findMerchantUserIdentity(
            (new OauthKnpuIdentityCriteriaTransfer())
                ->setProvider($merchantUserIdentityTransfer->getProvider())
                ->setExternalId($merchantUserIdentityTransfer->getExternalId()),
        );

        if ($existingIdentity !== null) {
            $merchantUserIdentityTransfer->setIdOauthKnpuMerchantUserIdentity(
                $existingIdentity->getIdOauthKnpuMerchantUserIdentityOrFail(),
            );
            $this->securityOauthKnpuEntityManager->updateMerchantUserIdentity($merchantUserIdentityTransfer);

            return;
        }

        $this->securityOauthKnpuEntityManager->createMerchantUserIdentity($merchantUserIdentityTransfer);
    }

    public function removeMerchantUserIdentities(UserCollectionResponseTransfer $userCollectionResponseTransfer): UserCollectionResponseTransfer
    {
        $userIds = [];
        foreach ($userCollectionResponseTransfer->getUsers() as $userTransfer) {
            if ($userTransfer->getStatus() !== static::USER_STATUS_DELETED) {
                continue;
            }

            $userIds[] = $userTransfer->getIdUserOrFail();
        }

        if ($userIds === []) {
            return $userCollectionResponseTransfer;
        }

        $merchantUserIdentityIds = $this->securityOauthKnpuRepository->getMerchantUserIdentityIdsByUserIds($userIds);

        if ($merchantUserIdentityIds === []) {
            return $userCollectionResponseTransfer;
        }

        $this->securityOauthKnpuEntityManager->removeMerchantUserIdentitiesByIds($merchantUserIdentityIds);

        return $userCollectionResponseTransfer;
    }
}
