define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Customer/js/customer-data',
], function ($, ko, Component, customerData) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Sashakh_Chat/messages',
            customerMessage: customerData.get('customer_message'),
        },

    });
});