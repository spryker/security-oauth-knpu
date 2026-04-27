<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Business\Reader;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\OauthKnpuIdentityCriteriaTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface;
use Spryker\Zed\SecurityOauthKnpu\Persistence\SecurityOauthKnpuRepositoryInterface;

class MerchantUserIdentityReader implements MerchantUserIdentityReaderInterface
{
    public function __construct(
        protected SecurityOauthKnpuRepositoryInterface $securityOauthKnpuRepository,
        protected MerchantUserFacadeInterface $merchantUserFacade,
    ) {
    }

    public function findMerchantUserByResourceOwner(ResourceOwnerTransfer $resourceOwnerTransfer): ?MerchantUserTransfer
    {
        $oauthKnpuMerchantUserIdentityTransfer = $this->securityOauthKnpuRepository->findMerchantUserIdentity(
            (new OauthKnpuIdentityCriteriaTransfer())
                ->setProvider($resourceOwnerTransfer->getProvider())
                ->setExternalId($resourceOwnerTransfer->getId()),
        );

        if ($oauthKnpuMerchantUserIdentityTransfer === null) {
            return null;
        }

        return $this->merchantUserFacade->findMerchantUser(
            (new MerchantUserCriteriaTransfer())
                ->setIdMerchantUser($oauthKnpuMerchantUserIdentityTransfer->getFkMerchantUserOrFail())
                ->setWithUser(true),
        );
    }
}
