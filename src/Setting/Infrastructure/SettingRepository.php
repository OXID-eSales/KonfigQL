<?php

declare(strict_types = 1);

namespace APICodingDays\KonfigQL\Setting\Infrastructure;

use APICodingDays\KonfigQL\Setting\DataType\Setting;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;

final class SettingRepository
{
    public function settings(): array
    {
        $configKey = Registry::getConfig()->getConfigParam('sConfigKey');
        $shopId = Registry::getConfig()->getShopId();
        $db = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
        $query = "SELECT OXID, OXVARNAME, decode(OXVARVALUE, ?) AS OXVARVALUE, OXVARTYPE
                    FROM oxconfig
                    WHERE OXSHOPID = ?";
        $settings = $db->getAll($query, [$configKey, $shopId]);

        return array_map(function ($setting) {
            return new Setting($setting);
        }, $settings);
    }

    public function getSingleSetting($settingName)
    {
        $configKey = Registry::getConfig()->getConfigParam('sConfigKey');
        $shopId = Registry::getConfig()->getShopId();
        $db = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
        $query = "SELECT OXID, decode(OXVARVALUE, ?) AS OXVARVALUE, OXVARTYPE
                    FROM oxconfig
                    WHERE OXSHOPID = ?
                    AND OXVARNAME = ?";

        $setting = $db->getCol($query, [$configKey, $shopId, $settingName]);

        return new Setting($setting);
    }

    public function updateSingleSetting($settingName, $value)
    {
        $configKey = Registry::getConfig()->getConfigParam('sConfigKey');
        $shopId = Registry::getConfig()->getShopId();
        $db = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
        $query = "UPDATE oxconfig
            SET OXVARVALUE = encode(?,?)
            WHERE OXSHOPID = ?
            AND OXVARNAME = ?
            ";

        $db->execute($query, [$value, $configKey, $shopId, $settingName]);
    }
}
