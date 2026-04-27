<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Communication\Initiator;

use Symfony\Component\HttpFoundation\RedirectResponse;

interface MerchantUserInitiatorInterface
{
    /**
     * Specification:
     * - Finds the provider config matching the given client name.
     * - Generates a unique state, stores it in the session via the knpu bundle.
     * - Returns a redirect response to the OAuth provider authorization URL.
     * - Returns null when no provider config is found for the given client name.
     *
     * @api
     */
    public function getOauthRedirectResponse(string $clientName): ?RedirectResponse;
}
