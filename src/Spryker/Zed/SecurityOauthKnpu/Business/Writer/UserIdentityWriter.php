<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Business\Writer;

use Generated\Shared\Transfer\OauthKnpuIdentityCriteriaTransfer;
use Generated\Shared\Transfer\OauthKnpuUserIdentityTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityOauthKnpu\Persistence\SecurityOauthKnpuEntityManagerInterface;
use Spryker\Zed\SecurityOauthKnpu\Persistence\SecurityOauthKnpuRepositoryInterface;

class UserIdentityWriter implements UserIdentityWriterInterface
{
    public function __construct(
        protected SecurityOauthKnpuRepositoryInterface $securityOauthKnpuRepository,
        protected SecurityOauthKnpuEntityManagerInterface $securityOauthKnpuEntityManager,
    ) {
    }

    public function persistUserIdentityByResourceOwner(
        UserTransfer $userTransfer,
        ResourceOwnerTransfer $resourceOwnerTransfer,
    ): void {
        if ($resourceOwnerTransfer->getId() === null || $resourceOwnerTransfer->getProvider() === null) {
            return;
        }

        $userIdentityTransfer = (new OauthKnpuUserIdentityTransfer())
            ->setFkUser($userTransfer->getIdUserOrFail())
            ->setProvider($resourceOwnerTransfer->getProviderOrFail())
            ->setExternalId($resourceOwnerTransfer->getIdOrFail())
            ->setEmail($resourceOwnerTransfer->getEmail());

        $existingIdentity = $this->securityOauthKnpuRepository->findUserIdentity(
            (new OauthKnpuIdentityCriteriaTransfer())
                ->setProvider($userIdentityTransfer->getProvider())
                ->setExternalId($userIdentityTransfer->getExternalId()),
        );

        if ($existingIdentity !== null) {
            $userIdentityTransfer->setIdOauthKnpuUserIdentity($existingIdentity->getIdOauthKnpuUserIdentity());
            $this->securityOauthKnpuEntityManager->updateUserIdentity($userIdentityTransfer);

            return;
        }

        $this->securityOauthKnpuEntityManager->createUserIdentity($userIdentityTransfer);
    }
}
