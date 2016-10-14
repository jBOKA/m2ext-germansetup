<?php namespace Ohjunge\GermanSetup\Command;

use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;

class AbstractCommand extends Command
{

    protected $_state;

    public function __construct(
        State $state
    ) {
        parent::__construct();
    }

    protected function setAreaCode()
    {
        try {
            $this->_state->getAreaCode();
        } catch (\Exception $e) {
            $this->_state->setAreaCode('adminhtml');
        }
    }

}