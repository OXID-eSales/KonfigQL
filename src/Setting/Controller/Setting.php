<?php

declare(strict_types = 1);

namespace APICodingDays\KonfigQL\Setting\Controller;

use APICodingDays\KonfigQL\Setting\DataType\Setting as SettingDataType;
use APICodingDays\KonfigQL\Setting\Infrastructure\SettingRepository;
use TheCodingMachine\GraphQLite\Annotations\Query;

final class Setting
{
    /** @var SettingRepository */
    private $settingsService;

    public function __construct(SettingRepository $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * @Query()
     * @param string|null $settingName
     *
     * @return SettingDataType[]
     */
    public function settings(string $settingName = null): array
    {
        if (empty($settingName)) {
            return $this->settingsService->settings();
        } else {
            return [$this->settingsService->getSingleSetting($settingName)];
        }
    }

    /**
     * @Mutation()
     * @param $settingName
     * @param $value
     */
    public function updateSetting($settingName, $value)
    {
        $this->settingsService->updateSingleSetting($settingName, $value);
    }
}
