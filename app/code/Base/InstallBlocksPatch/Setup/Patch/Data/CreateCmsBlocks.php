<?php

declare(strict_types=1);

namespace Base\InstallBlocksPatch\Setup\Patch\Data;

use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Store\Model\Store;

class CreateCmsBlocks implements DataPatchInterface, PatchRevertableInterface
{
    const CMS_BLOCKS_DATA = [
        [
            'title' => 'Popup general',
            'identifier' => 'popup-general',
            'content' => '<h2 class="popup-general">Simple text for general popup</h2>'
        ],
        [
            'title' => 'Popup sale',
            'identifier' => 'popup-sale',
            'content' => '<h2 class="popup-sale">Simple text for sale popup</h2>'
        ],
        [
            'title' => 'Popup special price',
            'identifier' => 'popup-special-price',
            'content' => '<h2 class="popup-special-price">Simple text for special price popup</h2>'
        ],
        [
            'title' => 'Popup black friday',
            'identifier' => 'popup-black-friday',
            'content' => '<h2 class="popup-black-friday">Simple text for black friday popup</h2>'
        ],
        [
            'title' => 'Popup christmas',
            'identifier' => 'popup-christmas',
            'content' => '<h2 class="popup-christmas">Simple text for christmas popup</h2>'
        ],
    ];
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var BlockFactory
     */
    private $blockFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param BlockFactory $blockFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        BlockFactory $blockFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockFactory = $blockFactory;
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        foreach (self::CMS_BLOCKS_DATA as $blockData) {
            $this->blockFactory->create()
                ->setTitle($blockData['title'])
                ->setIdentifier($blockData['identifier'])
                ->setIsActive(true)
                ->setContent($blockData['content'])
                ->setStores([Store::DEFAULT_STORE_ID])
                ->save();
        }

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function revert()
    {
        foreach (self::CMS_BLOCKS_DATA as $blockData) {
            $sampleCmsBlock = $this->blockFactory
                ->create()
                ->load($blockData['identifier'], 'identifier');

            if ($sampleCmsBlock->getId()) {
                $sampleCmsBlock->delete();
            }
        }
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }
}
