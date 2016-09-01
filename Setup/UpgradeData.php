<?php
/**
 *                  ___________       __            __
 *                  \__    ___/____ _/  |_ _____   |  |
 *                    |    |  /  _ \\   __\\__  \  |  |
 *                    |    | |  |_| ||  |   / __ \_|  |__
 *                    |____|  \____/ |__|  (____  /|____/
 *                                              \/
 *          ___          __                                   __
 *         |   |  ____ _/  |_   ____ _______   ____    ____ _/  |_
 *         |   | /    \\   __\_/ __ \\_  __ \ /    \ _/ __ \\   __\
 *         |   ||   |  \|  |  \  ___/ |  | \/|   |  \\  ___/ |  |
 *         |___||___|  /|__|   \_____>|__|   |___|  / \_____>|__|
 *                  \/                           \/
 *                  ________
 *                 /  _____/_______   ____   __ __ ______
 *                /   \  ___\_  __ \ /  _ \ |  |  \\____ \
 *                \    \_\  \|  | \/|  |_| ||  |  /|  |_| |
 *                 \______  /|__|    \____/ |____/ |   __/
 *                        \/                       |__|
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@tig.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@tig.nl for more information.
 *
 * @copyright   Copyright (c) 2015 Total Internet Group B.V. (http://www.tig.nl)
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */

namespace TIG\Buckaroo\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements \Magento\Framework\Setup\UpgradeDataInterface
{
    /**
     * @var \Magento\Sales\Setup\SalesSetupFactory
     */
    protected $salesSetupFactory;

    /**
     * @var \Magento\Quote\Setup\QuoteSetupFactory
     */
    protected $quoteSetupFactory;

    /**
     * @var \TIG\Buckaroo\Model\ResourceModel\Certificate\Collection
     */
    protected $certificateCollection;

    /**
     * @var \TIG\Buckaroo\Model\ResourceModel\Giftcard\Collection
     */
    protected $giftcardCollection;

    /**
     * @var \Magento\Framework\Encryption\Encryptor
     */
    protected $encryptor;

    /** @var array */
    protected $giftcardArray = array(
        array(
            'value' => 'babygiftcard',
            'label' => 'babygiftcard'
        ),
        array(
            'value' => 'babyparkgiftcard',
            'label' => 'Babypark Giftcard'
        ),
        array(
            'value' => 'beautywellness',
            'label' => 'Beauty Wellness'
        ),
        array(
            'value' => 'boekenbon',
            'label' => 'Boekenbon'
        ),
        array(
            'value' => 'boekenvoordeel',
            'label' => 'Boekenvoordeel'
        ),
        array(
            'value' => 'designshopsgiftcard',
            'label' => 'Designshops Giftcard'
        ),
        array(
            'value' => 'fijncadeau',
            'label' => 'Fijn Cadeau'
        ),
        array(
            'value' => 'koffiecadeau',
            'label' => 'Koffie Cadeau'
        ),
        array(
            'value' => 'kokenzo',
            'label' => 'Koken En Zo'
        ),
        array(
            'value' => 'kookcadeau',
            'label' => 'kook-cadeau'
        ),
        array(
            'value' => 'nationaleentertainmentcard',
            'label' => 'Nationale EntertainmentCard'
        ),
        array(
            'value' => 'naturesgift',
            'label' => 'Natures Gift'
        ),
        array(
            'value' => 'podiumcadeaukaart',
            'label' => 'PODIUM Cadeaukaart'
        ),
        array(
            'value' => 'shoesaccessories',
            'label' => 'Shoes Accessories'
        ),
        array(
            'value' => 'webshopgiftcard',
            'label' => 'Webshop Giftcard'
        ),
        array(
            'value' => 'wijncadeau',
            'label' => 'Wijn Cadeau'
        ),
        array(
            'value' => 'wonenzo',
            'label' => 'Wonen En Zo'
        ),
        array(
            'value' => 'yourgift',
            'label' => 'YourGift Card'
        ),
        array(
            'value' => 'fashioncheque',
            'label' => 'fashioncheque'
        ),
        array(
            'value' => 'sieradenhorlogescadeaukaart',
            'label' => 'sieradenhorlogescadeaukaart'
        ),
        array(
            'value' => 'jewellerygiftcard',
            'label' => 'JewelleryGiftcard'
        ),
        array(
            'value' => 'ebon',
            'label' => 'e-bon'
        ),
        array(
            'value' => 'voetbalshopcadeau',
            'label' => 'Voetbalshop cadeaucard'
        )
    );

    /**
     * @param \Magento\Sales\Setup\SalesSetupFactory                   $salesSetupFactory
     * @param \Magento\Quote\Setup\QuoteSetupFactory                   $quoteSetupFactory
     * @param \TIG\Buckaroo\Model\ResourceModel\Certificate\Collection $certificateCollection
     * @param \Magento\Framework\Encryption\Encryptor                  $encryptor
     */
    public function __construct(
        \Magento\Sales\Setup\SalesSetupFactory $salesSetupFactory,
        \Magento\Quote\Setup\QuoteSetupFactory $quoteSetupFactory,
        \TIG\Buckaroo\Model\ResourceModel\Giftcard\Collection $giftcardCollection,
        \TIG\Buckaroo\Model\ResourceModel\Certificate\Collection $certificateCollection,
        \Magento\Framework\Encryption\Encryptor $encryptor
    ) {
        $this->salesSetupFactory = $salesSetupFactory;
        $this->quoteSetupFactory = $quoteSetupFactory;
        $this->giftcardCollection = $giftcardCollection;
        $this->certificateCollection = $certificateCollection;
        $this->encryptor = $encryptor;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.1.1', '<')) {
            $this->installOrderStatusses($setup);
        }

        if (version_compare($context->getVersion(), '0.1.3', '<')) {
            $this->installPaymentFeeColumns($setup);
        }

        if (version_compare($context->getVersion(), '0.1.4', '<')) {
            $this->expandPaymentFeeColumns($setup);
        }

        if (version_compare($context->getVersion(), '0.1.5', '<')) {
            $this->installInvoicePaymentFeeTaxAmountColumns($setup);
        }

        if (version_compare($context->getVersion(), '0.1.6', '<')) {
            $this->installOrderPaymentFeeTaxAmountColumns($setup);
        }

        if (version_compare($context->getVersion(), '0.9.4', '<')) {
            $this->encryptCertificates();
        }

        if (version_compare($context->getVersion(), '1.3.0', '<')) {
            $this->installBaseGiftcards($setup);
        }
    }

    /**
     * @param ModuleDataSetupInterface $setup
     *
     * @return $this
     */
    protected function installOrderStatusses(ModuleDataSetupInterface $setup)
    {
        $select = $setup->getConnection()->select()
                        ->from(
                            $setup->getTable('sales_order_status'),
                            [
                                'status',
                            ]
                        )->where(
                            'status = ?',
                            'tig_buckaroo_new'
                        );

        if (count($setup->getConnection()->fetchAll($select)) == 0) {
            /**
             * Add New status and state
             */
            $setup->getConnection()->insert(
                $setup->getTable('sales_order_status'),
                [
                    'status' => 'tig_buckaroo_new',
                    'label'  => __('TIG Buckaroo New'),
                ]
            );
            $setup->getConnection()->insert(
                $setup->getTable('sales_order_status_state'),
                [
                    'status'           => 'tig_buckaroo_new',
                    'state'            => 'processing',
                    'is_default'       => 0,
                    'visible_on_front' => 1,
                ]
            );
        } else {
            // Do an update to turn on visible_on_front, since it already exists
            $bind = ['visible_on_front' => 1];
            $where = ['status = ?' => 'tig_buckaroo_new'];
            $setup->getConnection()->update($setup->getTable('sales_order_status_state'), $bind, $where);
        }

        /**
         * Add Pending status and state
         */
        $select = $setup->getConnection()->select()
                        ->from(
                            $setup->getTable('sales_order_status'),
                            [
                                'status',
                            ]
                        )->where(
                            'status = ?',
                            'tig_buckaroo_pending_payment'
                        );

        if (count($setup->getConnection()->fetchAll($select)) == 0) {
            $setup->getConnection()->insert(
                $setup->getTable('sales_order_status'),
                [
                    'status' => 'tig_buckaroo_pending_payment',
                    'label'  => __('TIG Buckaroo Pending Payment'),
                ]
            );
            $setup->getConnection()->insert(
                $setup->getTable('sales_order_status_state'),
                [
                    'status'           => 'tig_buckaroo_pending_payment',
                    'state'            => 'processing',
                    'is_default'       => 0,
                    'visible_on_front' => 1,
                ]
            );
        } else {
            // Do an update to turn on visible_on_front, since it already exists
            $bind = ['visible_on_front' => 1];
            $where = ['status = ?' => 'tig_buckaroo_pending_payment'];
            $setup->getConnection()->update($setup->getTable('sales_order_status_state'), $bind, $where);
        }

        return $this;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     *
     * @return $this
     */
    protected function installPaymentFeeColumns(ModuleDataSetupInterface $setup)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $quoteInstaller = $this->quoteSetupFactory->create(['resourceName' => 'quote_setup', 'setup' => $setup]);
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller = $this->salesSetupFactory->create(['resourceName' => 'sales_setup', 'setup' => $setup]);

        /** @noinspection PhpUndefinedMethodInspection */
        $quoteInstaller->addAttribute(
            'quote',
            'buckaroo_fee',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $quoteInstaller->addAttribute(
            'quote',
            'base_buckaroo_fee',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );

        /** @noinspection PhpUndefinedMethodInspection */
        $quoteInstaller->addAttribute(
            'quote_address',
            'buckaroo_fee',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $quoteInstaller->addAttribute(
            'quote_address',
            'base_buckaroo_fee',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );

        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'order',
            'buckaroo_fee',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'order',
            'base_buckaroo_fee',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'order',
            'buckaroo_fee_invoiced',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'order',
            'base_buckaroo_fee_invoiced',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'order',
            'buckaroo_fee_refunded',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'order',
            'base_buckaroo_fee_refunded',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );

        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'invoice',
            'base_buckaroo_fee',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'invoice',
            'buckaroo_fee',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );

        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'creditmemo',
            'base_buckaroo_fee',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'creditmemo',
            'buckaroo_fee',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );

        return $this;
    }

    protected function expandPaymentFeeColumns(ModuleDataSetupInterface $setup)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $quoteInstaller = $this->quoteSetupFactory->create(['resourceName' => 'quote_setup', 'setup' => $setup]);
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller = $this->salesSetupFactory->create(['resourceName' => 'sales_setup', 'setup' => $setup]);

        /** @noinspection PhpUndefinedMethodInspection */
        $quoteInstaller->addAttribute(
            'quote',
            'base_buckaroo_fee_incl_tax',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $quoteInstaller->addAttribute(
            'quote',
            'buckaroo_fee_incl_tax',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $quoteInstaller->addAttribute(
            'quote',
            'buckaroo_fee_base_tax_amount',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $quoteInstaller->addAttribute(
            'quote',
            'buckaroo_fee_tax_amount',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );

        /** @noinspection PhpUndefinedMethodInspection */
        $quoteInstaller->addAttribute(
            'quote_address',
            'base_buckaroo_fee_incl_tax',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $quoteInstaller->addAttribute(
            'quote_address',
            'buckaroo_fee_incl_tax',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $quoteInstaller->addAttribute(
            'quote_address',
            'buckaroo_fee_base_tax_amount',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $quoteInstaller->addAttribute(
            'quote_address',
            'buckaroo_fee_tax_amount',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );

        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'order',
            'base_buckaroo_fee_incl_tax',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'order',
            'buckaroo_fee_incl_tax',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'order',
            'buckaroo_fee_base_tax_amount',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'order',
            'buckaroo_fee_tax_amount',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );

        return $this;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     *
     * @return $this
     */
    protected function installInvoicePaymentFeeTaxAmountColumns(ModuleDataSetupInterface $setup)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller = $this->salesSetupFactory->create(['resourceName' => 'sales_setup', 'setup' => $setup]);

        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'invoice',
            'buckaroo_fee_base_tax_amount',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'invoice',
            'buckaroo_fee_tax_amount',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );

        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'creditmemo',
            'buckaroo_fee_base_tax_amount',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'creditmemo',
            'buckaroo_fee_tax_amount',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );

        return $this;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     *
     * @return $this
     */
    protected function installOrderPaymentFeeTaxAmountColumns(ModuleDataSetupInterface $setup)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller = $this->salesSetupFactory->create(['resourceName' => 'sales_setup', 'setup' => $setup]);

        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'order',
            'buckaroo_fee_base_tax_amount_invoiced',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'order',
            'buckaroo_fee_tax_amount_invoiced',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );

        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'order',
            'buckaroo_fee_base_tax_amount_refunded',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );
        /** @noinspection PhpUndefinedMethodInspection */
        $salesInstaller->addAttribute(
            'order',
            'buckaroo_fee_tax_amount_refunded',
            ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL]
        );

        return $this;
    }

    /**
     * Encrypt all previously saved, unencrypted certificates.
     *
     * @return $this
     */
    protected function encryptCertificates()
    {
        /** @var \TIG\Buckaroo\Model\Certificate $certificate */
        foreach ($this->certificateCollection as $certificate) {
            $certificate->setCertificate(
                $this->encryptor->encrypt(
                    $certificate->getCertificate()
                )
            )->setSkipEncryptionOnSave(true);

            $certificate->save();
        }

        return $this;
    }

    /**
     * Install giftcards which can be used with the Giftcards payment method
     *
     * @param ModuleDataSetupInterface $setup
     *
     * @return $this
     */
    protected function installBaseGiftcards(ModuleDataSetupInterface $setup)
    {
        foreach ($this->giftcardArray as $giftcard) {
            $foundGiftcards = $this->giftcardCollection->getItemsByColumnValue('servicecode', $giftcard['value']);

            if (count($foundGiftcards) <= 0) {
                $setup->getConnection()->insert(
                    $setup->getTable('tig_buckaroo_giftcard'),
                    [
                        'servicecode' => $giftcard['value'],
                        'label'  => $giftcard['label'],
                    ]
                );
            }
        }

        return $this;
    }
}
