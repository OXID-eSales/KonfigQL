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
                    internalName
                    value
                }
            }',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $result = $I->grabJsonResponseAsArray();
        $settingsData = $result['data']['settings'];

        $expectingSettings = [
            'internalName' => "iTopNaviCatCount",
            'value' => "4"
        ];
        $I->assertContains($expectingSettings, $settingsData);
    }

    public function testFetchOneSetting(AcceptanceTester $I): void
    {
        $I->haveHTTPHeader('Content-Type', 'application/json');
        $I->sendPOST('/widget.php?cl=graphql', [
            'query' => 'query {
                settings (settingId: "8563fba1965a219c9.51133344") {
                    displayName
                    value
                    helpText
                }
            }',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $result = $I->grabJsonResponseAsArray();
        $settingsData = $result['data']['settings'][0];
        $expectingSettings = [
                    'displayName' => "Lagerverwaltung aktiv",
                    'value' => "1",
                    'helpText' => "",
        ];
        $I->assertSame($expectingSettings, $settingsData);
    }

    public function testUpdateSetting(AcceptanceTester $I): void
    {
        $I->haveHTTPHeader('Content-Type', 'application/json');
        $I->sendPOST('/widget.php?cl=graphql', [
            'query' => 'mutation {
                updateSetting (
                  settingId: "8563fba1965a25500.87856483"
                  value: "2"
                )
            }',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'data' => [
                "updateSetting" => false
            ],
        ]);
    }
}
