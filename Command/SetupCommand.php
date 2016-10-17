<?php namespace Ohjunge\GermanSetup\Command;

use Magento\Framework\App\State;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Registry;
use Ohjunge\GermanSetup\Helper\ConfigHelperFactory;
use Ohjunge\GermanSetup\Helper\TaxHelperFactory;

class SetupCommand extends AbstractCommand
{

    protected $registry;
    protected $configHelper;
    protected $taxHelper;

    public function __construct(
        ConfigHelperFactory $configHelperFactory,
        TaxHelperFactory $taxHelperFactory,
        State $state,
        Registry $registry
    ) {
        $this->registry = $registry;
        $this->configHelper = $configHelperFactory->create();
        $this->taxHelper = $taxHelperFactory->create();
        parent::__construct($state);
    }

    protected function configure()
    {
        $this->setName('ohjunge:germansetup');
        $this->setDescription('Setup config for German single-store-mode shop');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setAreaCode();

        $this->registry->register('isSecureArea', true);

        $this->configHelper->setToSingleStoreMode();
        $output->writeln('Setting: single-store-mode -> enable');

        $this->configHelper->setShippingTaxClassToTaxableGoods();
        $output->writeln('Setting: shipping tax class -> "Taxable Goods"');

        $this->configHelper->setDefaultProductTaxClassToTaxableGoods();
        $output->writeln('Setting: default product tax class -> "Taxable Goods"');

        $this->configHelper->setDefaultCustomerTaxClassToRetailCustomer();
        $output->writeln('Setting: default customer tax class -> "Retail Customer"');

        $createTaxRateSuccess = $this->taxHelper->createTaxRate([
            'code' => 'DE19',
            'tax_country_code' => 'DE',
            'tax_region_id' => null,
            'tax_postcode' => '',
            'rate' => 19,
            'zip_is_range' => 0,
            'zip_from' => null,
            'zip_to' => null,
            'title' => 'MwSt.'
        ]);
        if ($createTaxRateSuccess) {
            $output->writeln('Tax: created tax rate DE19');
        } else {
            $output->writeln('<error>Tax: rate could not be created DE19</error>');
        }

        $createTaxRuleSuccess = $this->taxHelper->createTaxRule([
            'code' => 'DE19',
            'priority' => 0,
            'position' => 0,
            'calculate_subtotal' => 0,
            'tax_rate_codes' => ['DE19'],
            'product_tax_class_ids' => [2], // Taxable Goods, for constant see tax's InstallData
            'customer_tax_class_ids' => [3], // Retail Customer, for constant see tax's InstallData
        ]);
        if ($createTaxRuleSuccess && false) {
            $output->writeln('Tax: created tax rule for rate DE19');
        } else {
            $output->writeln('<error>Tax: rule could not be created DE19 - maybe it already exists</error>');
        }


        $this->configHelper->setDefaultCountry('DE');
        $output->writeln('Setting: default country -> "DE"');

        $this->configHelper->setAllowedCountries(['DE']);
        $output->writeln('Setting: allowed countries -> "DE"');

        $this->configHelper->setOptionalZipCountries(['DE']);
        $output->writeln('Setting: optional-zip countries -> "DE"');

        $this->configHelper->setEuCountries(['DE']);
        $output->writeln('Setting: EU countries -> "DE"');

        $this->configHelper->setTopDestinations(['DE']);
        $output->writeln('Setting: EU countries -> "DE"');

        $this->configHelper->setAllowToChooseStateIfOptionalDisabled();
        $output->writeln('Setting: allow to choose state if optional for country -> "no"');

        $this->configHelper->setTimezone('Europe/Berlin');
        $output->writeln('Setting: timezone -> "Europe/Berlin"');

        $this->configHelper->setLocale('de_DE');
        $output->writeln('Setting: locale -> "de_DE"');

        $this->configHelper->setWeightUnitToKgs();
        $output->writeln('Setting: weight unit -> "kgs"');

        $this->configHelper->setFirstWeekdayToMonday();
        $output->writeln('Setting: first day of week -> "Monday"');

        $this->configHelper->setWeekendToSaSu();
        $output->writeln('Setting: weekend days -> "Saturday, Sunday"');

        $this->configHelper->setTaxCalculationMethodBasedOnTotal();
        $output->writeln('Setting: tax calculation method based on -> "total"');

        $this->configHelper->setTaxCalculationBasedOnOrigin();
        $output->writeln('Setting: tax calculation based on -> "origin"');

        $this->configHelper->setCatalogPricesIncludeTax();
        $output->writeln('Setting: catalog prices include tax -> "yes"');

        $this->configHelper->setShippingPricesIncludeTax();
        $output->writeln('Setting: shipping prices include tax -> "yes"');

        $this->configHelper->setApplyCustomerTaxAfterDiscount();
        $output->writeln('Setting: apply customer tax after discount -> "yes"');

        $this->configHelper->setApplyDiscountOnPricesIncludingTax();
        $output->writeln('Setting: apply discount on prices incl. tax -> "yes"');

        $this->configHelper->setApplyTaxOnCustomPriceIfAvailable();
        $output->writeln('Setting: apply tax on custom price if available -> "yes"');

        $this->configHelper->setCrossBorderTradeDisabled();
        $output->writeln('Setting: border trade enabled -> "no"');

        $this->configHelper->setDefaultTaxDestinationCalculationCountry('DE');
        $output->writeln('Setting: default tax destination calculation country -> "DE"');
        $output->writeln('<comment>Please check for region and postcode setting manually</comment>');

        $this->configHelper->setDisplayProductPricesInCatalogIncludingTax();
        $output->writeln('Setting: display product prices in catalog -> "including tax"');

        $this->configHelper->setDisplayShippingPricesIncludingTax();
        $output->writeln('Setting: display shipping prices -> "including tax"');

        $this->configHelper->setCartDisplayPricesIncludingTax();
        $output->writeln('Setting: cart display prices -> "including tax"');

        $this->configHelper->setCartDisplaySubtotalIncludingTax();
        $output->writeln('Setting: cart display subtotal -> "including tax"');

        $this->configHelper->setCartDisplayShippingIncludingTax();
        $output->writeln('Setting: cart display shipping -> "including tax"');

        $this->configHelper->setCartDisplayIncludeTaxInOrderTotalEnabled();
        $output->writeln('Setting: cart display include tax in order total -> "yes"');

        $this->configHelper->setCartDisplayFullTaxSummaryEnabled();
        $output->writeln('Setting: cart display full tax summary -> "yes"');

        $this->configHelper->setCartDisplayZeroTaxSubtotalDisabled();
        $output->writeln('Setting: cart display zero tax subtotal -> "no"');

        $this->configHelper->setSalesDisplayPricesIncludingTax();
        $output->writeln('Setting: sales display prices -> "including tax"');

        $this->configHelper->setSalesDisplaySubtotalIncludingTax();
        $output->writeln('Setting: sales display subtotal -> "including tax"');

        $this->configHelper->setSalesDisplayShippingIncludingTax();
        $output->writeln('Setting: sales display shipping -> "including tax"');

        $this->configHelper->setSalesDisplayIncludeTaxInOrderTotalEnabled();
        $output->writeln('Setting: sales display include tax in order total -> "yes"');

        $this->configHelper->setSalesDisplayFullTaxSummaryEnabled();
        $output->writeln('Setting: sales display full tax summary -> "yes"');

        $this->configHelper->setSalesDisplayZeroTaxSubtotalDisabled();
        $output->writeln('Setting: sales display zero tax subtotal -> "no"');

        $this->configHelper->setCurrencyBaseToEuro();
        $output->writeln('Setting: general currency base -> "EUR"');

        $this->configHelper->setCurrencyDefaultToEuro();
        $output->writeln('Setting: general currency default -> "EUR"');
        
        $this->configHelper->setCurrencyAllowedToEuro();
        $output->writeln('Setting: general currency allowed -> ["EUR"]');

        $output->writeln('<comment>Please set "Settings -> General -> General -> Store Information"</comment>');

    }
    
}