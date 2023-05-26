<?php
declare(strict_types=1);

namespace Base\UkrStatesImport\Console\Command;

use Symfony\Component\Console\Command\Command;
use Base\UkrStatesImport\Model\Config\UpdateStatesModel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Console\Cli;

class ImportStates extends Command
{
    /**
     * @var UpdateStatesModel
     */
    private $updateStates;

    /**
     * @param UpdateStatesModel $updateStates
     */
    public function __construct(UpdateStatesModel $updateStates)
    {
        parent::__construct();
        $this->updateStates = $updateStates;
    }

    /**
     * @return void
     */
    public function configure() : void
    {
        $this->setName('import:ua:states');
        $this->setDescription('Import ua states to database');
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output) : int
    {
        $this->updateStates->updateStates();

        return Cli::RETURN_SUCCESS;
    }
}
