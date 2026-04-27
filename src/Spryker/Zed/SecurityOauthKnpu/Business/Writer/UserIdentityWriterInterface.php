<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Business\Writer;

use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface UserIdentityWriterInterface
{
    /**
     * Creates or updates the OAuth identity record linking the user to the provider.
     * Skips persistence when provider or external ID is missing from the resource owner.
     */
    public function persistUserIdentityByResourceOwner(
        UserTransfer $userTransfer,
        ResourceOwnerTransfer $resourceOwnerTransfer,
    ): void;
}
