<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Business\Writer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthKnpuCustomerIdentityTransfer;
use Generated\Shared\Transfer\OauthKnpuIdentityCriteriaTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Spryker\Zed\SecurityOauthKnpu\Persistence\SecurityOauthKnpuEntityManagerInterface;
use Spryker\Zed\SecurityOauthKnpu\Persistence\SecurityOauthKnpuRepositoryInterface;

class CustomerIdentityWriter implements CustomerIdentityWriterInterface
{
    public function __construct(
        protected SecurityOauthKnpuRepositoryInterface $securityOauthKnpuRepository,
        protected SecurityOauthKnpuEntityManagerInterface $securityOauthKnpuEntityManager,
    ) {
    }

    public function persistCustomerIdentityByResourceOwner(
        CustomerTransfer $customerTransfer,
        ResourceOwnerTransfer $resourceOwnerTransfer,
    ): void {
        if ($resourceOwnerTransfer->getProvider() === null || $resourceOwnerTransfer->getId() === null) {
            return;
        }

        $customerIdentityTransfer = (new OauthKnpuCustomerIdentityTransfer())
            ->setFkCustomer($customerTransfer->getIdCustomerOrFail())
            ->setProvider($resourceOwnerTransfer->getProviderOrFail())
            ->setExternalId($resourceOwnerTransfer->getIdOrFail())
            ->setEmail($resourceOwnerTransfer->getEmail());

        $existingIdentity = $this->securityOauthKnpuRepository->findCustomerIdentity(
            (new OauthKnpuIdentityCriteriaTransfer())
                ->setProvider($customerIdentityTransfer->getProvider())
                ->setExternalId($customerIdentityTransfer->getExternalId()),
        );

        if ($existingIdentity !== null) {
            $customerIdentityTransfer->setIdOauthKnpuCustomerIdentity($existingIdentity->getIdOauthKnpuCustomerIdentity());
            $this->securityOauthKnpuEntityManager->updateCustomerIdentity($customerIdentityTransfer);

            return;
        }

        $this->securityOauthKnpuEntityManager->createCustomerIdentity($customerIdentityTransfer);
    }
}
