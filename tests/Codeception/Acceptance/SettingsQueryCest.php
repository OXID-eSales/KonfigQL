<?php

declare(strict_types=1);

namespace APICodingDays\KonfigQL\Tests\Codeception\Acceptance;

use APICodingDays\KonfigQL\Tests\Codeception\AcceptanceTester;

final class SettingsQueryCest
{
    public function testFetchAllSettings(AcceptanceTester $I): void
    {
        $I->haveHTTPHeader('Content-Type', 'application/json');
        $I->sendPOST('/widget.php?cl=graphql', [
            'query' => 'query {
                settings {
                    id
                    displayName
                    internalName
                    value
                    helpText
                }
            }',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $result = $I->grabJsonResponseAsArray();
        $settingsData = $result['data']['settings'];

        $expectingSettings = [
            'id' => "8563fba1965a11df3.34244997",
            'displayName' => "blEnterNetPrice",
            'internalName' => "blEnterNetPrice",
            'value' => "",
            'helpText' => "",
        ];
        $I->assertContains($expectingSettings, $settingsData);
    }
}
