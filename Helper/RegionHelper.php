<?php namespace Ohjunge\GermanSetup\Helper;

class RegionHelper
{

    protected $_regionFactory;
    protected $_regionCollectionFactory;

    public function __construct(
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory
    ) {
        $this->_regionFactory = $regionFactory;
        $this->_regionCollectionFactory = $regionCollectionFactory;
    }

    public function getIdByName($regionName, $countryId)
    {
        $region = $this->_regionFactory->create();
        $region->loadByName($regionName, $countryId);
        if (!$region->getId()) {
            throw new \Exception('Region not found for country id '.$countryId.' and region code '.$regionName);
        }
        return $region->getId();
    }
    
    public function getIdByCode($regionCode, $countryId)
    {
        $region = $this->_regionFactory->create();
        $region->loadByName($regionCode, $countryId);
        if (!$region->getId()) {
            throw new \Exception('Region not found for country id '.$countryId.' and region code '.$regionCode);
        }
        return $region->getId();
    }
    
    public function getNamesByCountryId($countryId)
    {
        $regionCollection = $this->_regionCollectionFactory->create();
        $optionArray = $regionCollection->addCountryFilter($countryId)->toOptionArray();
        // remove empty option
        unset($optionArray[0]);
        $names = array_map(function($item) { return $item['title']; }, $optionArray);
        return $names;
    }

}