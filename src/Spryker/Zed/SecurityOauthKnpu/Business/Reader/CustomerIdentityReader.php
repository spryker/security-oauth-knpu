<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Business\Reader;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthKnpuIdentityCriteriaTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use Spryker\Zed\SecurityOauthKnpu\Persistence\SecurityOauthKnpuRepositoryInterface;

class CustomerIdentityReader implements CustomerIdentityReaderInterface
{
    public function __construct(
        protected SecurityOauthKnpuRepositoryInterface $securityOauthKnpuRepository,
        protected CustomerFacadeInterface $customerFacade,
    ) {
    }

    public function findCustomerByResourceOwner(ResourceOwnerTransfer $resourceOwnerTransfer): ?CustomerTransfer
    {
        $oauthKnpuCustomerIdentityTransfer = $this->securityOauthKnpuRepository->findCustomerIdentity(
            (new OauthKnpuIdentityCriteriaTransfer())
                ->setProvider($resourceOwnerTransfer->getProvider())
                ->setExternalId($resourceOwnerTransfer->getId()),
        );

        if ($oauthKnpuCustomerIdentityTransfer === null) {
            return null;
        }

        return $this->customerFacade->findCustomerById(
            (new CustomerTransfer())->setIdCustomer($oauthKnpuCustomerIdentityTransfer->getFkCustomer()),
        );
    }
}
