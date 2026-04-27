<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Yves\SecurityOauthKnpu\Initiator;

use Symfony\Component\HttpFoundation\RedirectResponse;

interface OauthCustomerInitiatorInterface
{
    /**
     * Specification:
     * - Finds the configured provider by client name.
     * - Returns null when no provider matches the given client name.
     * - Generates a unique state value and stores it in the session via knpu bundle.
     * - Returns a redirect response to the OAuth provider authorization URL.
     *
     * @api
     */
    public function getOauthRedirectResponse(string $clientName): ?RedirectResponse;
}
