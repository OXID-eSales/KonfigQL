<?php

declare(strict_types = 1);

namespace APICodingDays\KonfigQL\Setting\Controller;

use APICodingDays\KonfigQL\Setting\DataType\Setting as SettingDataType;
use APICodingDays\KonfigQL\Setting\Infrastructure\SettingRepository;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
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
     * Updates a single setting
     *
     * @Mutation()
     * @param string $settingName Name (OXVARNAME) of the setting
     * @param string $value       Value (OXVARVALUE, encoded) of the setting
     * @return bool
     */
    public function updateSetting(string $settingName, string $value): bool
    {
        return $this->settingsService->updateSingleSetting($settingName, $value);
    }
}
