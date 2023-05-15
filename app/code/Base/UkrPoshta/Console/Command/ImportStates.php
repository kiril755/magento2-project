<?php

namespace Base\UkrPoshta\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Base\UkrPoshta\Model\StateFactory;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Console\Cli;

class ImportStates extends Command
{
    private $stateFactory;
    private $curl;

    public function __construct(StateFactory $stateFactory, Curl $curl)
    {
        parent::__construct();
        $this->stateFactory = $stateFactory;
        $this->curl = $curl;
    }

    protected function configure()
    {
        $this->setName('import:ua:states');
        $this->setDescription('Import ua states to database');
        parent::configure();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = 'https://decentralization.gov.ua/graphql?query={areas{title,id,square,population,local_community_count,percent_communities_from_area,sum_communities_square}}';
        $this->curl->post($url, '');
        $response = $this->curl->getBody();
        $data = json_decode($response, true);

        if (isset($data['data']['areas'])) {
            foreach ($data['data']['areas'] as $area) {
                $state = $this->stateFactory->create();

                if ($state->load($area['id'])) {
                    $state->load($area['id'])->setState($area['title']);
                } else {
                    $state->setState($area['title']);
                }
                $state->save();
            }
        }

        return Cli::RETURN_SUCCESS;
    }
}
