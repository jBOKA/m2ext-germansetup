<?php
/**
 * Created by PhpStorm.
 * User: jakob
 * Date: 07.09.16
 * Time: 12:08
 */

namespace Ohjunge\GermanSetup\Helper;

use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\CacheInterface;

class ConfigHelper
{

    protected $configWriter;
    protected $cacheManager;

    public function __construct(
        WriterInterface $configWriter,
        CacheInterface $cacheManager
    ) {
        $this->configWriter = $configWriter;
        $this->cacheManager = $cacheManager;
    }

    public function setDefaultCountry($countryCode)
    {
        $xmlPath = \Magento\Directory\Helper\Data::XML_PATH_DEFAULT_COUNTRY;
        $this->configWriter->save($xmlPath,$countryCode);
    }

    public function setAllowedCountries($countryCodes)
    {
        $configStr = is_array($countryCodes) ? implode(',',$countryCodes) : $countryCodes;
        $this->configWriter->save('general/country/allow',$configStr);
    }

    public function setOptionalZipCountries($countryCodes)
    {
        $xmlPath = \Magento\Directory\Helper\Data::OPTIONAL_ZIP_COUNTRIES_CONFIG_PATH;
        $configStr = is_array($countryCodes) ? implode(',',$countryCodes) : $countryCodes;
        $this->configWriter->save($xmlPath,$configStr);
    }

    public function setEuCountries($countryCodes)
    {
        $configStr = is_array($countryCodes) ? implode(',',$countryCodes) : $countryCodes;
        $this->configWriter->save('general/country/eu_countries',$configStr);
    }

    public function setTopDestinations($countryCodes)
    {
        $xmlPath = \Magento\Directory\Helper\Data::XML_PATH_TOP_COUNTRIES;
        $configStr = is_array($countryCodes) ? implode(',',$countryCodes) : $countryCodes;
        $this->configWriter->save($xmlPath,$configStr);
    }

    public function setAllowToChooseStateIfOptionalEnabled()
    {
        $xmlPath = \Magento\Directory\Helper\Data::XML_PATH_DISPLAY_ALL_STATES;
        $this->configWriter->save($xmlPath,1);
    }

    public function setAllowToChooseStateIfOptionalDisabled()
    {
        $xmlPath = \Magento\Directory\Helper\Data::XML_PATH_DISPLAY_ALL_STATES;
        $this->configWriter->save($xmlPath,0);
    }

    public function setTimezone($timezoneStr)
    {
        $xmlPath = \Magento\Directory\Helper\Data::XML_PATH_DEFAULT_TIMEZONE;
        $this->configWriter->save($xmlPath,$timezoneStr);
    }

    public function setLocale($timezoneStr)
    {
        $xmlPath = \Magento\Directory\Helper\Data::XML_PATH_DEFAULT_LOCALE;
        $this->configWriter->save($xmlPath,$timezoneStr);
    }

    public function setWeightUnitToKgs()
    {
        $xmlPath = \Magento\Directory\Helper\Data::XML_PATH_WEIGHT_UNIT;
        $this->configWriter->save($xmlPath,'kgs');
    }

    public function setWeightUnitToLbs()
    {
        $xmlPath = \Magento\Directory\Helper\Data::XML_PATH_WEIGHT_UNIT;
        $this->configWriter->save($xmlPath,'lbs');
    }

    public function setFirstWeekdayToMonday()
    {
        $this->configWriter->save('general/locale/firstday',1);
    }

    public function setWeekendToSaSu()
    {
        $this->configWriter->save('general/locale/weekend','0,6');
    }

    protected function _resetTaxSettingNotification()
    {
        // reset backend tax setting notification
        $this->configWriter->save(
            \Magento\Tax\Model\Config::XML_PATH_TAX_NOTIFICATION_IGNORE_DISCOUNT,
            0
        );
        $this->configWriter->save(
            \Magento\Tax\Model\Config::XML_PATH_TAX_NOTIFICATION_IGNORE_PRICE_DISPLAY,
            0
        );
    }

    public function setToSingleStoreMode()
    {
        $this->configWriter->save('general/single_store_mode/enabled',1);
    }

    public function setShippingTaxClassToTaxableGoods()
    {
        // for constant see tax's InstallData
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_SHIPPING_TAX_CLASS;
        $this->configWriter->save($xml_path,2);
    }

    public function setDefaultProductTaxClassToTaxableGoods()
    {
        // for value's constant see tax's InstallData
        $xml_path = \Magento\Tax\Helper\Data::CONFIG_DEFAULT_PRODUCT_TAX_CLASS;
        $this->configWriter->save($xml_path,2);
    }

    public function setDefaultCustomerTaxClassToRetailCustomer()
    {
        // for value's constant see tax's InstallData
        $xml_path = \Magento\Tax\Helper\Data::CONFIG_DEFAULT_CUSTOMER_TAX_CLASS;
        $this->configWriter->save($xml_path,3);
    }

    public function setTaxCalculationMethodBasedOnTotal()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_ALGORITHM;
        $this->configWriter->save($xml_path,\Magento\Tax\Model\Calculation::CALC_TOTAL_BASE);
    }

    public function setTaxCalculationMethodBasedOnRow()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_ALGORITHM;
        $this->configWriter->save($xml_path,\Magento\Tax\Model\Calculation::CALC_ROW_BASE);
    }

    public function setTaxCalculationMethodBasedOnUnit()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_ALGORITHM;
        $this->configWriter->save($xml_path,\Magento\Tax\Model\Calculation::CALC_UNIT_BASE);
    }

    public function setTaxCalculationBasedOnOrigin()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_BASED_ON;
        $this->configWriter->save($xml_path,'origin');
        $this->_resetTaxSettingNotification();
    }

    public function setTaxCalculationBasedOnShipping()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_BASED_ON;
        $this->configWriter->save($xml_path,'shipping');
        $this->_resetTaxSettingNotification();
    }

    public function setTaxCalculationBasedOnBilling()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_BASED_ON;
        $this->configWriter->save($xml_path,'billing');
        $this->_resetTaxSettingNotification();
    }

    public function setCatalogPricesIncludeTax()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_PRICE_INCLUDES_TAX;
        $this->configWriter->save($xml_path,1);
        $this->cacheManager->clean(['checkout_quote']);
    }

    public function setCatalogPricesExcludeTax()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_PRICE_INCLUDES_TAX;
        $this->configWriter->save($xml_path,0);
        $this->cacheManager->clean(['checkout_quote']);
    }

    public function setShippingPricesIncludeTax()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_SHIPPING_INCLUDES_TAX;
        $this->configWriter->save($xml_path,1);
        $this->cacheManager->clean(['checkout_quote']);
    }

    public function setShippingPricesExcludeTax()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_SHIPPING_INCLUDES_TAX;
        $this->configWriter->save($xml_path,0);
        $this->cacheManager->clean(['checkout_quote']);
    }

    public function setApplyCustomerTaxAfterDiscount()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_APPLY_AFTER_DISCOUNT;
        $this->configWriter->save($xml_path,1);
        $this->_resetTaxSettingNotification();
    }

    public function setApplyCustomerTaxBeforeDiscount()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_APPLY_AFTER_DISCOUNT;
        $this->configWriter->save($xml_path,0);
        $this->_resetTaxSettingNotification();
    }

    public function setApplyDiscountOnPricesIncludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_DISCOUNT_TAX;
        $this->configWriter->save($xml_path,1);
        $this->_resetTaxSettingNotification();
    }

    public function setApplyDiscountOnPricesExcludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_DISCOUNT_TAX;
        $this->configWriter->save($xml_path,0);
        $this->_resetTaxSettingNotification();
    }

    public function setApplyTaxOnCustomPriceIfAvailable()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_APPLY_ON;
        $this->configWriter->save($xml_path,0);
    }

    public function setApplyTaxOnOriginalPriceOnly()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_APPLY_ON;
        $this->configWriter->save($xml_path,1);
    }

    public function setCrossBorderTradeEnabled()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_CROSS_BORDER_TRADE_ENABLED;
        $this->configWriter->save($xml_path,1);
    }

    public function setCrossBorderTradeDisabled()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_CROSS_BORDER_TRADE_ENABLED;
        $this->configWriter->save($xml_path,0);
    }

    public function setDefaultTaxDestinationCalculationCountry($countryCode)
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_DEFAULT_COUNTRY;
        $this->configWriter->save($xml_path,$countryCode);
        // maybe add region and postcode later on
        // region: CONFIG_XML_PATH_DEFAULT_REGION
        // postcode: CONFIG_XML_PATH_DEFAULT_POSTCODE
    }

    public function setDisplayProductPricesInCatalogIncludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_PRICE_DISPLAY_TYPE;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_INCLUDING_TAX
        );
        $this->_resetTaxSettingNotification();
    }

    public function setDisplayProductPricesInCatalogExcludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_PRICE_DISPLAY_TYPE;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_EXCLUDING_TAX
        );
        $this->_resetTaxSettingNotification();
    }

    public function setDisplayProductPricesInCatalogIncludingAndExcludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_PRICE_DISPLAY_TYPE;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_BOTH
        );
        $this->_resetTaxSettingNotification();
    }

    public function setDisplayShippingPricesIncludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_DISPLAY_SHIPPING;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_INCLUDING_TAX
        );
        $this->_resetTaxSettingNotification();
    }

    public function setDisplayShippingPricesExcludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_DISPLAY_SHIPPING;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_EXCLUDING_TAX
        );
        $this->_resetTaxSettingNotification();
    }

    public function setDisplayShippingPricesIncludingAndExcludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::CONFIG_XML_PATH_DISPLAY_SHIPPING;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_BOTH
        );
        $this->_resetTaxSettingNotification();
    }

    public function setCartDisplayPricesIncludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_CART_PRICE;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_INCLUDING_TAX
        );
        $this->_resetTaxSettingNotification();
    }

    public function setCartDisplayPricesExcludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_CART_PRICE;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_EXCLUDING_TAX
        );
        $this->_resetTaxSettingNotification();
    }

    public function setCartDisplayPricesIncludingAndExcludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_CART_PRICE;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_BOTH
        );
        $this->_resetTaxSettingNotification();
    }

    public function setCartDisplaySubtotalIncludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_CART_SUBTOTAL;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_INCLUDING_TAX
        );
        $this->_resetTaxSettingNotification();
    }

    public function setCartDisplaySubtotalExcludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_CART_SUBTOTAL;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_EXCLUDING_TAX
        );
        $this->_resetTaxSettingNotification();
    }

    public function setCartDisplaySubtotalIncludingAndExcludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_CART_SUBTOTAL;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_BOTH
        );
        $this->_resetTaxSettingNotification();
    }

    public function setCartDisplayShippingIncludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_CART_SHIPPING;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_INCLUDING_TAX
        );
        $this->_resetTaxSettingNotification();
    }

    public function setCartDisplayShippingExcludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_CART_SHIPPING;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_EXCLUDING_TAX
        );
        $this->_resetTaxSettingNotification();
    }

    public function setCartDisplayShippingIncludingAndExcludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_CART_SHIPPING;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_BOTH
        );
        $this->_resetTaxSettingNotification();
    }

    public function setCartDisplayIncludeTaxInOrderTotalEnabled()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_CART_GRANDTOTAL;
        $this->configWriter->save($xml_path,1);
    }

    public function setCartDisplayIncludeTaxInOrderTotalDisabled()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_CART_GRANDTOTAL;
        $this->configWriter->save($xml_path,0);
    }

    public function setCartDisplayFullTaxSummaryEnabled()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_CART_FULL_SUMMARY;
        $this->configWriter->save($xml_path,1);
    }

    public function setCartDisplayFullTaxSummaryDisabled()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_CART_FULL_SUMMARY;
        $this->configWriter->save($xml_path,0);
    }

    public function setCartDisplayZeroTaxSubtotalEnabled()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_CART_ZERO_TAX;
        $this->configWriter->save($xml_path,1);
    }

    public function setCartDisplayZeroTaxSubtotalDisabled()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_CART_ZERO_TAX;
        $this->configWriter->save($xml_path,0);
    }

    public function setSalesDisplayPricesIncludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_SALES_PRICE;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_INCLUDING_TAX
        );
        $this->_resetTaxSettingNotification();
    }

    public function setSalesDisplayPricesExcludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_SALES_PRICE;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_EXCLUDING_TAX
        );
        $this->_resetTaxSettingNotification();
    }

    public function setSalesDisplayPricesIncludingAndExcludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_SALES_PRICE;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_BOTH
        );
        $this->_resetTaxSettingNotification();
    }

    public function setSalesDisplaySubtotalIncludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_SALES_SUBTOTAL;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_INCLUDING_TAX
        );
        $this->_resetTaxSettingNotification();
    }

    public function setSalesDisplaySubtotalExcludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_SALES_SUBTOTAL;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_EXCLUDING_TAX
        );
        $this->_resetTaxSettingNotification();
    }

    public function setSalesDisplaySubtotalIncludingAndExcludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_SALES_SUBTOTAL;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_BOTH
        );
        $this->_resetTaxSettingNotification();
    }

    public function setSalesDisplayShippingIncludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_SALES_SHIPPING;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_INCLUDING_TAX
        );
        $this->_resetTaxSettingNotification();
    }

    public function setSalesDisplayShippingExcludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_SALES_SHIPPING;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_EXCLUDING_TAX
        );
        $this->_resetTaxSettingNotification();
    }

    public function setSalesDisplayShippingIncludingAndExcludingTax()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_SALES_SHIPPING;
        $this->configWriter->save(
            $xml_path,
            \Magento\Tax\Model\Config::DISPLAY_TYPE_BOTH
        );
        $this->_resetTaxSettingNotification();
    }

    public function setSalesDisplayIncludeTaxInOrderTotalEnabled()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_SALES_GRANDTOTAL;
        $this->configWriter->save($xml_path,1);
    }

    public function setSalesDisplayIncludeTaxInOrderTotalDisabled()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_SALES_GRANDTOTAL;
        $this->configWriter->save($xml_path,0);
    }

    public function setSalesDisplayFullTaxSummaryEnabled()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_SALES_FULL_SUMMARY;
        $this->configWriter->save($xml_path,1);
    }

    public function setSalesDisplayFullTaxSummaryDisabled()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_SALES_FULL_SUMMARY;
        $this->configWriter->save($xml_path,0);
    }

    public function setSalesDisplayZeroTaxSubtotalEnabled()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_SALES_ZERO_TAX;
        $this->configWriter->save($xml_path,1);
    }

    public function setSalesDisplayZeroTaxSubtotalDisabled()
    {
        $xml_path = \Magento\Tax\Model\Config::XML_PATH_DISPLAY_SALES_ZERO_TAX;
        $this->configWriter->save($xml_path,0);
    }

    public function setCurrencyBaseToEuro()
    {
        $xml_path = \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_BASE;
        $this->configWriter->save($xml_path,'EUR');
    }

    public function setCurrencyDefaultToEuro()
    {
        $xml_path = \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_DEFAULT;
        $this->configWriter->save($xml_path,'EUR');
    }

    public function setCurrencyAllowedToEuro()
    {
        $xml_path = \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_ALLOW;
        $this->configWriter->save($xml_path,'EUR');
    }


}