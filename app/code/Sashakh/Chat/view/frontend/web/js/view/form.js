define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/modal/modal'
], function ($, ko, Component, customerData, alert) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Sashakh_Chat/form',
            action: '',
            messages: []
        },

        messages: {},

        inputAuthorType: ko.observable(),
        inputAuthorName: ko.observable(),
        message: ko.observable(),

        initObservable: function () {
            var chat = customerData.get('chat')();

            this._super();

            this.messages.forEach(function(messageData) {
                messageData.value = chat[messageData['message_id']];
            });

            this.inputAuthorType.subscribe(function (typeValue) {
                console.log(typeValue);
            });

            this.inputAuthorName.subscribe(function (nameValue) {
                console.log(nameValue);
            });

            this.message.subscribe(function (messageValue) {
                console.log(messageValue);
            });

            return this;
        },

        saveMessage: function() {
            console.log("Save Message");
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

                    $("#sashakh-chat-form").get(0).reset();

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
                        content: $.mage.__('Your preferences can\'t be saved. Please, contact us if you see this message.')
                    });
                }
            });
        }
    });

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

            customerData.get('chat').subscribe(function (value) {
                //console.log(value);
            });
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
            var currentDate = new Date(),
                dateTime = currentDate.toUTCString(),
                date, name, messageHtml, authorTypeHtml, li;

            authorTypeHtml = authorType === '1' ? 'user' : 'admin';
            li = $("<li class='chat-message'></li>").addClass(authorTypeHtml);

            date = $("<p class='message-date'></p>").text(dateTime);
            name = $("<p class='message-name'></p>").text(authorName);
            messageHtml = $("<p class='message-message'></p>").text(message);

            li.append(date, name, messageHtml);

            $('.messages-list').append(li).show();
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

                    $("#sashakh-chat-form").get(0).reset();

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
                        content: $.mage.__('Your preferences can\'t be saved. Please, contact us if you see this message.')
                    });
                },

            });
        },
    });

    return $.sashakhChat.form;
});
