<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Yves\SecurityOauthKnpu\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\SecurityOauthKnpu\SecurityOauthKnpuFactory getFactory()
 * @method \Spryker\Yves\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 */
class OauthInitiateController extends AbstractController
{
    protected const string REQUEST_PARAM_CLIENT = 'client';

    protected const string MESSAGE_PROVIDER_NOT_FOUND = 'security_oauth_knpu.error.provider_not_found';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_LOGIN
     */
    protected const string ROUTE_LOGIN = 'login';

    public function initiateAction(Request $request): RedirectResponse
    {
        $clientName = $request->query->getString(static::REQUEST_PARAM_CLIENT);

        $redirectResponse = $this->getFactory()
            ->createOauthCustomerInitiator()
            ->getOauthRedirectResponse($clientName);

        if ($redirectResponse === null) {
            $this->addErrorMessage(static::MESSAGE_PROVIDER_NOT_FOUND);

            return $this->redirectResponseInternal(static::ROUTE_LOGIN);
        }

        return $redirectResponse;
    }
}
