/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*global define*/
define(
    [
        'uiElement',
        'underscore'
    ],
    function(uiElement, _) {
        "use strict";

        var buckarooFeeConfig = window.buckarooConfig ?
            window.buckarooConfig.buckarooFee :
            window.checkoutConfig.buckarooFee;

        var provider = uiElement();

        return function(itemId) {
            return {
                itemId: itemId,
                observables: {},
                getConfigValue: function(key) {
                    return buckarooFeeConfig[key];
                },
                getPriceFormat: function() {
                    return window.buckarooConfig.priceFormat;
                },

                /**
                 * Get buckaroo fee price display mode.
                 * @returns {Boolean}
                 */
                displayBothprices: function () {
                    return !!buckarooFeeConfig.cart.displayBuckarooFeeBothPrices;
                },

                /**
                 * Get buckaroo fee price display mode.
                 * @returns {Boolean}
                 */
                displayInclTaxPrice: function () {
                    return !!buckarooFeeConfig.cart.displayBuckarooFeeInclTax;
                }
            };
        };
    }
);
