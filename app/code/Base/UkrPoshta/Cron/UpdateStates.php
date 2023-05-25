<?php
declare(strict_types=1);

namespace Base\UkrPoshta\Cron;

use Base\UkrPoshta\Model\Config\UpdateStatesModel;
class UpdateStates
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
        $this->updateStates = $updateStates;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function execute() : void {
        $this->updateStates->updateStates();
    }
}
