<?php namespace Ohjunge\GermanSetup\Command;

use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;

class AbstractCommand extends Command
{

    public function __construct(
        State $state
    ) {
        try {
           if (!$state->getAreaCode()) {
                $state->setAreaCode('adminhtml');
           }
        } catch (\Exception $e) {
            // just let it flow
        }
        parent::__construct();
    }

    protected function configure()
    {
        parent::configure();
    }
    
}