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
         *
         * @param authorType
         * @param authorName
         * @param message
         */
        appendMessage: function(authorType, authorName, message) {
            var currentDate = new Date();
            var dateTime = currentDate.toUTCString();
            var userDate, userName, userMessage, adminDate, adminName, adminMessage;

            if (authorType === '1') {
                userDate = $("<p class='user-question user-date'></p>").text(dateTime);
                userName = $("<p class='user-question user-name'></p>").text(authorName);
                userMessage = $("<p class='user-question user-message'></p>").text(message);

                $('.messages-list').append(userDate, userName, userMessage).show();
            } else {
                adminDate = $("<p class='admin-question admin-date'></p>").text(dateTime);
                adminName = $("<p class='admin-question admin-name'></p>").text(authorName);
                adminMessage = $("<p class='admin-question admin-message'></p>").text(message);

                $('.messages-list').append(adminDate, adminName, adminMessage).show();
            }

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

                    this.appendMessage(formData.get('authorType'), formData.get('authorName'), formData.get('message'));

                    $("#sashakh-chat-form")[0].reset();

                   alert({
                        title: $.mage.__('Success'),
                        content: response.message
                    });
                },

                /** @inheritdoc */
                error: function () {
                    $('body').trigger('processStop');
                    alert({
                        title: $.mage.__('Error'),
                        /*eslint max-len: ["error", { "ignoreStrings": true }]*/
                        content: $.mage.__('Your preferences can\'t be saved. Please, contact us if you see this message.')
                    });
                },

            });
        },
    });

    return $.sashakhChat.form;
});