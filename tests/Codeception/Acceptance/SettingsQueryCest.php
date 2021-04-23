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
            'id' => "serial2",
            'displayName' => "sTagList",
            'internalName' => "sTagList",
            'value' => "1619101614",
            'helpText' => "",
        ];
        $I->assertContains($expectingSettings, $settingsData);
    }

    public function testFetchOneSettings(AcceptanceTester $I): void
    {
        $I->haveHTTPHeader('Content-Type', 'application/json');
        $I->sendPOST('/widget.php?cl=graphql', [
            'query' => 'query {
                settings (settingName: "sTagList") {
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
        $I->seeResponseContainsJson([
            'data' => [
                'settings' => [
                    'id' => "serial2",
                    'displayName' => "sTagList",
                    'internalName' => "sTagList",
                    'value' => "1619101614",
                    'helpText' => "",
                ],
            ],
        ]);
    }
}
