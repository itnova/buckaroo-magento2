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

use \TIG\Buckaroo\Model\ConfigProvider;

/**
 * @method string getLocationLiveWeb()
 * @method string getLocationTestWeb()
 * @method string getWsdlLiveWeb()
 * @method string getWsdlTestWeb()
 */
class Predefined extends AbstractConfigProvider
{

    /**
     * XPATHs to configuration values for tig_buckaroo_predefined
     */
    const XPATH_PREDEFINED_LOCATION_LIVE_WEB = 'tig_buckaroo/predefined/location_live_web';
    const XPATH_PREDEFINED_LOCATION_TEST_WEB = 'tig_buckaroo/predefined/location_test_web';
    const XPATH_PREDEFINED_WSDL_LIVE_WEB     = 'tig_buckaroo/predefined/wsdl_live_web';
    const XPATH_PREDEFINED_WSDL_TEST_WEB     = 'tig_buckaroo/predefined/wsdl_test_web';

    /**
     * {@inheritdoc}
     */
    public function getConfig($store = null)
    {
        $config = [
            'location_live_web' => $this->getLocationLiveWeb($store),
            'location_test_web' => $this->getLocationTestWeb($store),
            'wsdl_live_web'     => $this->getWsdlLiveWeb($store),
            'wsdl_test_web'     => $this->getWsdlTestWeb($store),
        ];
        return $config;
    }
}
