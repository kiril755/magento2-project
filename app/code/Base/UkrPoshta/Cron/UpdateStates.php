<?php

namespace Base\UkrPoshta\Cron;

use Base\UkrPoshta\Model\StateFactory;
use Magento\Framework\HTTP\Client\Curl;

class UpdateStates
{
    private $stateFactory;
    private $curl;

    public function __construct(StateFactory $stateFactory, Curl $curl)
    {
        $this->stateFactory = $stateFactory;
        $this->curl = $curl;
    }

    public function execute() {
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
    }
}
