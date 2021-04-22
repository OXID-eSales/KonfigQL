<?php

/**
 * Copyright © __Vender__. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace APICodingDays\KonfigQL\Tests\Codeception\Acceptance;

use APICodingDays\KonfigQL\Tests\Codeception\AcceptanceTester;

final class CategoryQueryCest
{
    public function testFetchSingleCategoryById(AcceptanceTester $I): void
    {
        $I->haveHTTPHeader('Content-Type', 'application/json');
        $I->sendPOST('/widget.php?cl=graphql', [
            'query' => 'query {
                category (id: "943a9ba3050e78b443c16e043ae60ef3") {
                    id
                    title
                }
            }',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'data' => [
                'category' => [
                    'id'    => '943a9ba3050e78b443c16e043ae60ef3',
                    'title' => 'Kiteboarding',
                ],
            ],
        ]);
    }
}
