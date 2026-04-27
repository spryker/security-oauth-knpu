<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SecurityOauthKnpu\Communication\SecurityOauthKnpuCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 */
class OauthUserInitiateController extends AbstractController
{
    protected const string REQUEST_PARAM_CLIENT = 'client';

    protected const string MESSAGE_PROVIDER_NOT_FOUND = 'OAuth login is unavailable. Please contact your administrator.';

    public function indexAction(Request $request): RedirectResponse
    {
        $clientName = $request->query->getString(static::REQUEST_PARAM_CLIENT);

        $redirectResponse = $this->getFactory()
            ->createUserInitiator()
            ->getOauthRedirectResponse($clientName);

        if ($redirectResponse === null) {
            $this->addErrorMessage(static::MESSAGE_PROVIDER_NOT_FOUND);

            return $this->redirectResponse($this->getFactory()->getConfig()->getUrlZedLogin());
        }

        return $redirectResponse;
    }
}
