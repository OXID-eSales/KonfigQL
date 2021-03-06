<?php

declare(strict_types = 1);

namespace APICodingDays\KonfigQL\Setting\Infrastructure;

use APICodingDays\KonfigQL\Setting\DataType\Setting;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Base\Exception\NotFound;
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
        'iFailedOnlineCallsCount',
        // Remove token until we have a different way to display long values
        'sJsonWebTokenSignature',
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


        $settingObjects = array_map(function ($setting) {
            return new Setting($setting);
        }, $filteredSettings);

        $filteredSettingObjects = array_filter($settingObjects, function (Setting $setting) {
            return $setting->displayName() != $setting->internalName();
        });

        return $filteredSettingObjects;
    }

    public function getSingleSetting(string $settingId):Setting
    {
        $configKey = $this->legacyService->getConfigParam('sConfigKey');
        $shopId = $this->legacyService->getShopId();

        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select('OXID, OXVARNAME, decode(OXVARVALUE, :confKey) AS OXVARVALUE, OXVARTYPE')
            ->from('oxconfig')
            ->where('OXSHOPID = :shopid')
            ->andWhere('OXID = :id')
            ->setParameters(
                [
                    'confKey' => $configKey,
                    'shopid' => $shopId,
                    'id' => $settingId
                ]
            );
        /** @var \Doctrine\DBAL\Statement $result */
        $result = $queryBuilder->execute();

        if ($result->rowCount() !== 1) {
            throw new NotFound();
        }

        $setting = $result->fetch();

        return new Setting($setting);
    }

    public function updateSingleSetting(string $settingId, string $value): bool
    {
        $configKey = $this->legacyService->getConfigParam('sConfigKey');
        $shopId = $this->legacyService->getShopId();
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder
            ->update('oxconfig')
            ->set('OXVARVALUE', 'encode(:value, :key)')
            ->where('OXSHOPID = :shopId')
            ->where('OXID = :id')
            ->setParameters([
                'shopId' => $shopId,
                'id'   => $settingId,
                'value'  => $value,
                'key'    => $configKey,
        ]);

        return (bool) $queryBuilder->execute();
    }
}
