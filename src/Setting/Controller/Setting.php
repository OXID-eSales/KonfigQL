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
     * @param string|null $settingId
     *
     * @return SettingDataType[]
     */
    public function settings(string $settingId = null): array
    {
        if (empty($settingId)) {
            return $this->settingsService->settings();
        } else {
            return [$this->settingsService->getSingleSetting($settingId)];
        }
    }

    /**
     * Updates a single setting
     *
     * @Mutation()
     * @param string $settingId   Id (OXID) of the setting
     * @param string $value       Value (OXVARVALUE, encoded) of the setting
     * @return bool
     */
    public function updateSetting(string $settingId, string $value): bool
    {
        return $this->settingsService->updateSingleSetting($settingId, $value);
    }
}
