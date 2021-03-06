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
 * @copyright Copyright (c) Total Internet Group B.V. https://tig.nl/copyright
 * @license   http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */

namespace TIG\Buckaroo\Model\ConfigProvider;

class Factory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var array
     */
    protected $configProviders;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param array                                     $configProviders
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $configProviders = []
    ) {
        $this->objectManager = $objectManager;
        $this->configProviders = $configProviders;
    }

    /**
     * Retrieve proper transaction builder for the specified transaction type.
     *
     * @param string $providerType
     *
     * @return \Magento\Checkout\Model\ConfigProviderInterface
     * @throws \LogicException|\TIG\Buckaroo\Exception
     */
    public function get($providerType)
    {
        if (empty($this->configProviders)) {
            throw new \LogicException('ConfigProvider adapter is not set.');
        }

        $providerType = str_replace('tig_buckaroo_', '', $providerType);

        foreach ($this->configProviders as $configProviderMetaData) {
            $configProviderType = $configProviderMetaData['type'];
            if ($configProviderType == $providerType) {
                $configProviderClass = $configProviderMetaData['model'];
                break;
            }
        }

        if (!isset($configProviderClass) || empty($configProviderClass)) {
            throw new \TIG\Buckaroo\Exception(
                new \Magento\Framework\Phrase(
                    'Unknown ConfigProvider type requested: %1.',
                    [$providerType]
                )
            );
        }

        $configProvider = $this->objectManager->get($configProviderClass);
        return $configProvider;
    }

    /**
     * @param $providerType
     *
     * @return bool
     *
     * @throws \LogicException
     */
    public function has($providerType)
    {
        if (empty($this->configProviders)) {
            throw new \LogicException('ConfigProvider adapter is not set.');
        }

        foreach ($this->configProviders as $configProviderMetaData) {
            $configProviderType = $configProviderMetaData['type'];
            if ($configProviderType == $providerType) {
                return true;
            }
        }

        return false;
    }
}
