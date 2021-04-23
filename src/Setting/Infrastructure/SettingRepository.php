<?php

declare(strict_types = 1);

namespace APICodingDays\KonfigQL\Setting\Infrastructure;

use APICodingDays\KonfigQL\Setting\DataType\Setting;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Base\Infrastructure\Legacy;

final class SettingRepository
{
    /** @var Legacy */
    private $legacyService;

    /** @var QueryBuilderFactoryInterface */
    private $queryBuilderFactory;

    public function __construct(
        Legacy $legacyService,
        QueryBuilderFactoryInterface $queryBuilderFactory
    ) {
        $this->legacyService = $legacyService;
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    /**
     * @var string[]
     */
    private $filterInternalConfig = [
        'aServersData',
        'blEnableIntangibleProdAgreement',
        'blShowTSCODMessage',
        'blShowTSInternationalFeesMessage',
        'iOlcSuccess',
        'sBackTag',
        'sClusterId',
        'sOnlineLicenseCheckTime',
        'sOnlineLicenseNextCheckTime',
        'sParcelService',
        'blUseContentCaching',
        'iTimeToUpdatePrices',
        'IMA',
        'IMD',
        'IMS',
        'OXSERIAL',
        'iFailedOnlineCallsCount'
    ];

    public function settings(): array
    {
        $configKey = $this->legacyService->getConfigParam('sConfigKey');
        $shopId = $this->legacyService->getShopId();

        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select('OXID, OXVARNAME, decode(OXVARVALUE, :confKey) AS OXVARVALUE, OXVARTYPE')
            ->from('oxconfig')
            ->where('OXSHOPID = :shopid')
            ->setParameters(
                [
                    'confKey' => $configKey,
                    'shopid' => $shopId,
                ]
            );
        /** @var \Doctrine\DBAL\Statement $result */
        $result = $queryBuilder->execute();
        $settings = $result->fetchAll();

        // Filter out Internal Config parameters
        $filteredSettings = array_filter($settings, function ($configVar) {
            return (!in_array($configVar['OXVARNAME'], $this->filterInternalConfig));
        });

        return array_map(function ($setting) {
            return new Setting($setting);
        }, $filteredSettings);
    }

    public function getSingleSetting(string $settingName):Setting
    {
        $configKey = $this->legacyService->getConfigParam('sConfigKey');
        $shopId = $this->legacyService->getShopId();

        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select('OXID, OXVARNAME, decode(OXVARVALUE, :confKey) AS OXVARVALUE, OXVARTYPE')
            ->from('oxconfig')
            ->where('OXSHOPID = :shopid')
            ->andWhere('OXVARNAME = :name')
            ->setParameters(
                [
                    'confKey' => $configKey,
                    'shopid' => $shopId,
                    'name' => $settingName
                ]
            );
        /** @var \Doctrine\DBAL\Statement $result */
        $result = $queryBuilder->execute();
        $setting = $result->fetch();

        return new Setting($setting);
    }

    public function updateSingleSetting(string $settingName, string $value): bool
    {
        $configKey = $this->legacyService->getConfigParam('sConfigKey');
        $shopId = $this->legacyService->getShopId();
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder
            ->update('oxconfig')
            ->set('OXVARVALUE', 'encode(:value, :key)')
            ->where('OXSHOPID = :shopId')
            ->where('OXVARNAME = :name')
            ->setParameters([
                'shopId' => $shopId,
                'name'   => $settingName,
                'value'  => $value,
                'key'    => $configKey,
        ]);

        return (bool) $queryBuilder->execute();
    }
}
