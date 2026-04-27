<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Persistence;

use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuCustomerIdentityQuery;
use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuMerchantUserIdentityQuery;
use Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuUserIdentityQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SecurityOauthKnpu\Persistence\Propel\Mapper\OauthKnpuIdentityMapper;

/**
 * @method \Spryker\Zed\SecurityOauthKnpu\Persistence\SecurityOauthKnpuRepositoryInterface getRepository()
 * @method \Spryker\Zed\SecurityOauthKnpu\Persistence\SecurityOauthKnpuEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 */
class SecurityOauthKnpuPersistenceFactory extends AbstractPersistenceFactory
{
    public function getOauthKnpuCustomerIdentityQuery(): SpyOauthKnpuCustomerIdentityQuery
    {
        return SpyOauthKnpuCustomerIdentityQuery::create();
    }

    public function getOauthKnpuUserIdentityQuery(): SpyOauthKnpuUserIdentityQuery
    {
        return SpyOauthKnpuUserIdentityQuery::create();
    }

    public function getOauthKnpuMerchantUserIdentityQuery(): SpyOauthKnpuMerchantUserIdentityQuery
    {
        return SpyOauthKnpuMerchantUserIdentityQuery::create();
    }

    public function createOauthKnpuIdentityMapper(): OauthKnpuIdentityMapper
    {
        return new OauthKnpuIdentityMapper();
    }
}
