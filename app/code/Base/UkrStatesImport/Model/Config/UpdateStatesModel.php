<?php
declare(strict_types=1);

namespace Base\UkrStatesImport\Model\Config;

use Base\UkrStatesImport\Api\UpdateStatesInterface;
use Base\UkrStatesImport\Model\StateFactory;
use Magento\Framework\HTTP\Client\Curl;

class UpdateStatesModel implements UpdateStatesInterface
{
    private const URL = 'https://decentralization.gov.ua/graphql?query={areas{title,id,square,population,local_community_count,percent_communities_from_area,sum_communities_square}}';
    /**
     * @var StateFactory
     * @var Curl
     */
    private $stateFactory;
    private $curl;

    /**
     * @param StateFactory $stateFactory
     * @param Curl $curl
     */
    public function __construct(StateFactory $stateFactory, Curl $curl)
    {
        $this->stateFactory = $stateFactory;
        $this->curl = $curl;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function updateStates() : void {
        $url = self::URL;
        $this->curl->post($url, '');
        $response = $this->curl->getBody();
        $data = json_decode($response, true);

        if (isset($data['data']['areas'])) {
            foreach ($data['data']['areas'] as $area) {
                $state = $this->stateFactory->create();

                if ($stateId = $state->load($area['id'])) {
                    $stateId->setState($area['title']);
                } else {
                    $state->setState($area['title']);
                }
                $state->save();
            }
        }
    }
}
