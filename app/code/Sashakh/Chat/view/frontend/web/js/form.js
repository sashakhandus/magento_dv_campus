define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/modal/modal'
], function ($, alert) {
    'use strict';

    $.widget('sashakhChat.form', {
        options: {
            action: ''
        },

        /**
         * @private
         */
        _create: function () {
            this.modal = $(this.element).modal({
                buttons: []
            });

            $(this.element).on('submit.sashakh_chat', $.proxy(this.saveMessages, this));
        },

        _destroy: function () {
            this.modal.closeModal();
            $(this.element).off('submit.sashakh_chat');
            this.modal.destroy();
        },

        saveMessages: function () {
            if (!this.validateForm()) {
                return;
            }

            this.ajaxSubmit();
        },

        /**
         * Validate request form
         */
        validateForm: function () {
            return $(this.element).validation().valid();
        },

        /**
         * Submit request via AJAX. Add form key to the post data.
         */
        ajaxSubmit: function () {
            var formData = new FormData($(this.element).get(0));

            formData.append('form_key', $.mage.cookies.get('form_key'));
            formData.append('isAjax', 1);

            $.ajax({
                url: this.options.action,
                data: formData,
                processData: false,
                contentType: false,
                type: 'post',
                dataType: 'json',
                context: this,

                /** @inheritdoc */
                beforeSend: function () {
                    $('body').trigger('processStart');
                },

                /** @inheritdoc */
                success: function (response) {
                    $('body').trigger('processStop');
                    // @TODO: show new preferences


                    $('.messages-list').append(formData.get('name'));
                    $('.messages-list').append(formData.get('message'));
                    $('.messages-list').show();

                   /*alert({
                        title: $.mage.__('Success'),
                        content: $.mage.__(response.message)
                    });*/


                },

                /** @inheritdoc */
                error: function () {
                    $('body').trigger('processStop');
                    console.log('Error');
                    alert({
                        title: $.mage.__('Error'),
                        /*eslint max-len: ["error", { "ignoreStrings": true }]*/
                        content: $.mage.__('Your preferences can\'t be saved. Please, contact us if you see this message.')
                    });
                }
            });
        },
    });

    return $.sashakhChat.form;
});