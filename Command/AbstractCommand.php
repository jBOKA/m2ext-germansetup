<?php namespace Ohjunge\GermanSetup\Command;

use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;

class AbstractCommand extends Command
{

    public function __construct(
        State $state
    ) {
        try {
            $state->getAreaCode();
        } catch (\Exception $e) {
            $state->setAreaCode('adminhtml');
        }
        parent::__construct();
    }

    protected function configure()
    {
        parent::configure();
    }
    
}