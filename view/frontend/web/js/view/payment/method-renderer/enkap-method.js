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
    'Magento_Checkout/js/view/payment/default',
    'jquery',
    'Magento_Checkout/js/model/payment/additional-validators',
    'Magento_Checkout/js/action/redirect-on-success',
    'mage/url'
], function (Component, $, additionalValidators, redirectOnSuccessAction, urlBuilder) {
    'use strict';
 
    return Component.extend({
    
        defaults: {
            template: 'Camoo_Enkap/payment/enkap'
        },
        afterPlaceOrder: function(){
            redirectOnSuccessAction.redirectUrl = urlBuilder.build('enkap/page/index');
            this.redirectAfterPlaceOrder = true;
        }
    });
});