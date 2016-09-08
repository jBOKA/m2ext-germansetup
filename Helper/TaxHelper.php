<?php
/**
 * Created by PhpStorm.
 * User: jakob
 * Date: 07.09.16
 * Time: 20:22
 */

namespace Ohjunge\GermanSetup\Helper;

use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Store\Model\ResourceModel\Store\Collection as StoreCollection;
use Magento\Directory\Model\CountryFactory;
use Magento\Tax\Api\Data\TaxRateInterfaceFactory;
use Magento\Tax\Api\Data\TaxRateTitleInterfaceFactory;
use Magento\Tax\Api\TaxRateRepositoryInterfaceFactory;
use Magento\Tax\Api\Data\TaxRuleInterfaceFactory;
use Magento\Tax\Api\TaxRuleRepositoryInterfaceFactory;

class TaxHelper
{

    protected $_searchCriteriaBuilderFactory;
    protected $_publicStores;
    protected $_countryFactory;
    protected $_taxRateFactory;
    protected $_taxRateTitleFactory;
    protected $_taxRateRepoFactory;
    protected $_taxRuleFactory;
    protected $_taxRuleRepoFactory;

    public function __construct(
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        StoreCollection $storeCollection,
        CountryFactory $countryFactory,
        TaxRateInterfaceFactory $taxRateFactory,
        TaxRateTitleInterfaceFactory $taxRateTitleFactory,
        TaxRateRepositoryInterfaceFactory $taxRateRepoFactory,
        TaxRuleInterfaceFactory $taxRuleFactory,
        TaxRuleRepositoryInterfaceFactory $taxRuleRepoFactory
    ) {
        $this->_searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->_publicStores = $storeCollection->setLoadDefault(false);
        $this->_countryFactory = $countryFactory;
        $this->_taxRateFactory = $taxRateFactory;
        $this->_taxRateTitleFactory = $taxRateTitleFactory;
        $this->_taxRateRepoFactory = $taxRateRepoFactory;
        $this->_taxRuleFactory = $taxRuleFactory;
        $this->_taxRuleRepoFactory = $taxRuleRepoFactory;
    }

    public function createTaxRate($data)
    {
        $country = $this->_countryFactory->create()->loadByCode($data['tax_country_code'],'iso2_code');
        $countryId = $country->getId();
        if (!$countryId) {
            throw new \Exception('The country has an invalid code ('.$data['tax_country_code'].')');
        }
        $postcode = (empty($data['tax_postcode'])) ? '*' : $data['tax_postcode'];
        $rate = floatval($data['rate']);
        $zipIsRange = $data['zip_is_range'] ? 1 : null;
        $countryId = $countryId;

        $modelData = [
            'code' => $data['code'],
            'tax_country_id' => $countryId,
            'tax_region_id' => null,
            'tax_postcode' => $postcode,
            'rate' => $rate,
            'zip_is_range' => $zipIsRange,
            'zip_from' => $data['zip_from'],
            'zip_to' => $data['zip_to'],
        ];
        $rateModel = $this->_taxRateFactory->create()->loadByCode($modelData['code']);
        $rateModel->addData($modelData);

        $rateTitles = [];
        foreach ($this->_publicStores as $store) {
            $taxRateTitle = $this->_taxRateTitleFactory->create();
            $taxRateTitle->setStoreId($store->getId());
            $taxRateTitle->setValue($data['title']);
            $rateTitles[] = $taxRateTitle;
        }

        $rateModel->setTitles($rateTitles);

        $rateRepo = $this->_taxRateRepoFactory->create();
        $rateRepo->save($rateModel);

        return true;

    }

    public function createTaxRule($data)
    {
        $modelData = [
            'code' => $data['code'],
            'priority' => $data['priority'],
            'position' => $data['position'],
            'calculate_subtotal' => $data['calculate_subtotal']
        ];

        $ruleModel = $this->_getTaxRuleByCode($modelData['code']);
        if ($ruleModel === null) {
            $ruleModel = $this->_taxRuleFactory->create();
        }
        $ruleModel->setData($modelData);

        $taxRateIds = [];
        foreach ($data['tax_rate_codes'] as $taxRateCode) {
            $searchCriteriaBuilder = $this->_searchCriteriaBuilderFactory->create();
            $searchCriteria = $searchCriteriaBuilder
                ->addFilter('code',$taxRateCode,'eq')
                ->create();
            $taxRateIds[] = $this->_taxRateRepoFactory->create()
                ->getList($searchCriteria)
                ->getItems()[0]->getId();
        }

        $ruleModel->setTaxRateIds($taxRateIds);
        $ruleModel->setProductTaxClassIds($data['product_tax_class_ids']);
        $ruleModel->setCustomerTaxClassIds($data['customer_tax_class_ids']);

        $ruleRepo = $this->_taxRuleRepoFactory->create();
        try {
            $ruleRepo->save($ruleModel);
        } catch (\Magento\Framework\Exception\AlreadyExistsException $e) {
            return false;
        }

        return true;

    }

    protected function _getTaxRuleByCode($taxRuleCode)
    {
        $searchCriteriaBuilder = $this->_searchCriteriaBuilderFactory->create();
        $searchCriteria = $searchCriteriaBuilder
            ->addFilter('code',$taxRuleCode,'eq')
            ->create();
        $taxRules = $this->_taxRuleRepoFactory->create()
            ->getList($searchCriteria)
            ->getItems();

        if (count($taxRules)) {
            return reset($taxRules); // get first
        } else {
            return null;
        }
    }

}