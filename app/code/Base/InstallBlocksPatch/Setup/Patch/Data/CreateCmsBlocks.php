<?php

declare(strict_types=1);

namespace Base\InstallBlocksPatch\Setup\Patch\Data;

use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Store\Model\Store;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\Filesystem\Driver\File;

class CreateCmsBlocks implements DataPatchInterface, PatchRevertableInterface
{
    const CMS_BLOCKS_DATA = [
        [
            'title' => 'Popup general',
            'identifier' => 'popup-general'
        ],
        [
            'title' => 'Popup sale',
            'identifier' => 'popup-sale'
        ],
        [
            'title' => 'Popup special price',
            'identifier' => 'popup-special-price'
        ],
        [
            'title' => 'Popup black friday',
            'identifier' => 'popup-black-friday'
        ],
        [
            'title' => 'Popup christmas',
            'identifier' => 'popup-christmas'
        ],
    ];
    /**
     * @var ModuleDataSetupInterface
     * @var BlockFactory
     * @var File
     * @var Reader
     */
    private $moduleDataSetup;
    private $blockFactory;
    private $fileDriver;
    private $moduleDirReader;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param BlockFactory $blockFactory
     * @param File $fileDriver
     * @param Reader $moduleDirReader
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        BlockFactory $blockFactory,
        File $fileDriver,
        Reader $moduleDirReader
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockFactory = $blockFactory;
        $this->fileDriver = $fileDriver;
        $this->moduleDirReader = $moduleDirReader;
    }

    /**
     * @param $filename
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    private function getContentFromFile($filename) : string
    {
        $moduleDir = $this->moduleDirReader->getModuleDir(
            \Magento\Framework\Module\Dir::MODULE_VIEW_DIR,
            'Base_InstallBlocksPatch'
        );
        $filePath = $moduleDir . '/adminhtml/web/template/' . $filename;

        if ($this->fileDriver->isExists($filePath)) {
            return $this->fileDriver->fileGetContents($filePath);
        }
        return '';
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
                ->setContent($this->getContentFromFile("$blockData[identifier].html"))
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
