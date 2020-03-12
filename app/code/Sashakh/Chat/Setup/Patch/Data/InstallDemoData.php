<?php

declare(strict_types=1);

namespace Sashakh\Chat\Setup\Patch\Data;

use Magento\Framework\Component\ComponentRegistrar;

class InstallDemoData implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @var \Magento\Framework\File\Csv $csv
     */
    private $csv;

    /**
     * @var \Magento\Framework\Component\ComponentRegistrar $componentRegistrar
     */
    private $componentRegistrar;

    /**
     * UpgradeData constructor.
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup
     * @param \Magento\Framework\Component\ComponentRegistrar $componentRegistrar
     * @param \Magento\Framework\File\Csv $csv
     */
    public function __construct(
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Framework\Component\ComponentRegistrar $componentRegistrar,
        \Magento\Framework\File\Csv $csv
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->componentRegistrar = $componentRegistrar;
        $this->csv = $csv;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function apply(): void
    {
        $connection = $this->moduleDataSetup->getConnection();
        $filePath = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, 'Sashakh_Chat')
            . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'data.csv';
        $tableName = $this->moduleDataSetup->getTable('sashakh_chat');
        $csvData = $this->csv->getData($filePath);

        try {
            $connection->beginTransaction();
            $columns = [
                'author_type',
                'author_name',
                'message',
                'author_id'
            ];
            foreach ($csvData as $rowNumber => $data) {
                $insertedData = array_combine($columns, $data);
                $connection->insertOnDuplicate(
                    $tableName,
                    $insertedData,
                    $columns
                );
            }
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            throw $e;
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }
}
