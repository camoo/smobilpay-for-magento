/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Camoo
 * @package     Camoo_Enkap
 */
define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (Component, rendererList) {
    'use strict';

    rendererList.push(
        {
            type: 'custompayment',
            component: 'Camoo_Enkap/js/view/payment/method-renderer/enkap-method'
        }
    );

    /** Add view logic here if needed */
    return Component.extend({});
});
