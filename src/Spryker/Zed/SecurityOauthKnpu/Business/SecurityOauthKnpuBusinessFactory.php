<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Business;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface;
use Spryker\Zed\SecurityOauthKnpu\Business\Provider\MerchantUserAuthenticationLinkProvider;
use Spryker\Zed\SecurityOauthKnpu\Business\Provider\MerchantUserAuthenticationLinkProviderInterface;
use Spryker\Zed\SecurityOauthKnpu\Business\Provider\OauthAuthenticationLinkProvider;
use Spryker\Zed\SecurityOauthKnpu\Business\Provider\OauthAuthenticationLinkProviderInterface;
use Spryker\Zed\SecurityOauthKnpu\Business\Reader\CustomerIdentityReader;
use Spryker\Zed\SecurityOauthKnpu\Business\Reader\CustomerIdentityReaderInterface;
use Spryker\Zed\SecurityOauthKnpu\Business\Reader\MerchantUserIdentityReader;
use Spryker\Zed\SecurityOauthKnpu\Business\Reader\MerchantUserIdentityReaderInterface;
use Spryker\Zed\SecurityOauthKnpu\Business\Reader\OauthMerchantPortalResourceOwnerReader;
use Spryker\Zed\SecurityOauthKnpu\Business\Reader\OauthMerchantPortalResourceOwnerReaderInterface;
use Spryker\Zed\SecurityOauthKnpu\Business\Reader\OauthUserResourceOwnerReader;
use Spryker\Zed\SecurityOauthKnpu\Business\Reader\OauthUserResourceOwnerReaderInterface;
use Spryker\Zed\SecurityOauthKnpu\Business\Reader\UserIdentityReader;
use Spryker\Zed\SecurityOauthKnpu\Business\Reader\UserIdentityReaderInterface;
use Spryker\Zed\SecurityOauthKnpu\Business\Writer\CustomerIdentityWriter;
use Spryker\Zed\SecurityOauthKnpu\Business\Writer\CustomerIdentityWriterInterface;
use Spryker\Zed\SecurityOauthKnpu\Business\Writer\MerchantUserIdentityWriter;
use Spryker\Zed\SecurityOauthKnpu\Business\Writer\MerchantUserIdentityWriterInterface;
use Spryker\Zed\SecurityOauthKnpu\Business\Writer\UserIdentityWriter;
use Spryker\Zed\SecurityOauthKnpu\Business\Writer\UserIdentityWriterInterface;
use Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuDependencyProvider;
use Spryker\Zed\User\Business\UserFacadeInterface;

/**
 * @method \Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 * @method \Spryker\Zed\SecurityOauthKnpu\Persistence\SecurityOauthKnpuRepositoryInterface getRepository()
 * @method \Spryker\Zed\SecurityOauthKnpu\Persistence\SecurityOauthKnpuEntityManagerInterface getEntityManager()
 */
class SecurityOauthKnpuBusinessFactory extends AbstractBusinessFactory
{
    public function createOauthAuthenticationLinkProvider(): OauthAuthenticationLinkProviderInterface
    {
        return new OauthAuthenticationLinkProvider(
            $this->getConfig(),
        );
    }

    public function getOauthClientRegistry(): ClientRegistry
    {
        return $this->getProvidedDependency(SecurityOauthKnpuDependencyProvider::CLIENT_REGISTRY);
    }

    public function createOauthUserResourceOwnerReader(): OauthUserResourceOwnerReaderInterface
    {
        return new OauthUserResourceOwnerReader(
            $this->getOauthClientRegistry(),
            $this->getConfig(),
        );
    }

    public function createOauthMerchantPortalResourceOwnerReader(): OauthMerchantPortalResourceOwnerReaderInterface
    {
        return new OauthMerchantPortalResourceOwnerReader(
            $this->getOauthClientRegistry(),
            $this->getConfig(),
        );
    }

    public function createCustomerIdentityReader(): CustomerIdentityReaderInterface
    {
        return new CustomerIdentityReader(
            $this->getRepository(),
            $this->getCustomerFacade(),
        );
    }

    public function createCustomerIdentityWriter(): CustomerIdentityWriterInterface
    {
        return new CustomerIdentityWriter(
            $this->getRepository(),
            $this->getEntityManager(),
        );
    }

    public function createUserIdentityReader(): UserIdentityReaderInterface
    {
        return new UserIdentityReader(
            $this->getRepository(),
            $this->getUserFacade(),
        );
    }

    public function createUserIdentityWriter(): UserIdentityWriterInterface
    {
        return new UserIdentityWriter(
            $this->getRepository(),
            $this->getEntityManager(),
        );
    }

    public function getCustomerFacade(): CustomerFacadeInterface
    {
        return $this->getProvidedDependency(SecurityOauthKnpuDependencyProvider::FACADE_CUSTOMER);
    }

    public function createMerchantUserIdentityReader(): MerchantUserIdentityReaderInterface
    {
        return new MerchantUserIdentityReader(
            $this->getRepository(),
            $this->getMerchantUserFacade(),
        );
    }

    public function createMerchantUserIdentityWriter(): MerchantUserIdentityWriterInterface
    {
        return new MerchantUserIdentityWriter(
            $this->getRepository(),
            $this->getEntityManager(),
        );
    }

    public function getUserFacade(): UserFacadeInterface
    {
        return $this->getProvidedDependency(SecurityOauthKnpuDependencyProvider::FACADE_USER);
    }

    public function getMerchantUserFacade(): MerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(SecurityOauthKnpuDependencyProvider::FACADE_MERCHANT_USER);
    }

    public function createMerchantUserAuthenticationLinkProvider(): MerchantUserAuthenticationLinkProviderInterface
    {
        return new MerchantUserAuthenticationLinkProvider(
            $this->getConfig(),
        );
    }
}
