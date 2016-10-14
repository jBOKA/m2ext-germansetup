<?php namespace Ohjunge\GermanSetup\Command;

use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;

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
            $this->_appState->setAreaCode('adminhtml');
        }
    }

}