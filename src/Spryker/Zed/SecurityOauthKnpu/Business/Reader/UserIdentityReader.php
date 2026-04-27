<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Business\Reader;

use Generated\Shared\Transfer\OauthKnpuIdentityCriteriaTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityOauthKnpu\Persistence\SecurityOauthKnpuRepositoryInterface;
use Spryker\Zed\User\Business\UserFacadeInterface;

class UserIdentityReader implements UserIdentityReaderInterface
{
    public function __construct(
        protected SecurityOauthKnpuRepositoryInterface $securityOauthKnpuRepository,
        protected UserFacadeInterface $userFacade,
    ) {
    }

    public function findUserByResourceOwner(ResourceOwnerTransfer $resourceOwnerTransfer): ?UserTransfer
    {
        $oauthKnpuUserIdentityTransfer = $this->securityOauthKnpuRepository->findUserIdentity(
            (new OauthKnpuIdentityCriteriaTransfer())
                ->setProvider($resourceOwnerTransfer->getProvider())
                ->setExternalId($resourceOwnerTransfer->getId()),
        );

        if ($oauthKnpuUserIdentityTransfer === null) {
            return null;
        }

        $userCollectionTransfer = $this->userFacade->getUserCollection(
            (new UserCriteriaTransfer())->setUserConditions(
                (new UserConditionsTransfer())
                    ->addIdUser($oauthKnpuUserIdentityTransfer->getFkUserOrFail())
                    ->setThrowUserNotFoundException(false),
            ),
        );

        if ($userCollectionTransfer->getUsers()->count() === 0) {
            return null;
        }

        return $userCollectionTransfer->getUsers()->getIterator()->current();
    }
}
