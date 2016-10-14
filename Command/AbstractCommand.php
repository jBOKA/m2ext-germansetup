<?php namespace Ohjunge\GermanSetup\Command;

use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Magento\Backend\App\Area\FrontNameResolver\FrontNameResolver;

class AbstractCommand extends Command
{

    protected $_appState;

    public function __construct(
        State $appState
    ) {
        $this->_appState = $appState;
        parent::__construct();
    }

    protected function setAreaCode()
    {
        try {
            $this->_appState->getAreaCode();
        } catch (\Exception $e) {
            $adminAreaCode = FrontNameResolver::AREA_CODE;
            $this->_appState->setAreaCode('adminhtml');
        }
    }

}