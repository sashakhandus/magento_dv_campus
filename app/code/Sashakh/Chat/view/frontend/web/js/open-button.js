define([
    'jquery',
    'jquery/ui',
    'mage/translate',
    'sashakh_chat_form'
], function ($) {
    'use strict';

    $.widget('sashakhChat.openButton', {
        options: {
            hideButton: true,
            chatList: '#sashakh-chat-list',
            form: '#sashakh-chat-form'
        },

        /**
         * @private
         */
        _create: function () {
            $(this.element).on('click.sashakh_chat', $.proxy(this.editChat, this));
        },

        /**
         * jQuery(jQuery('.sashakh-chat-open-button').get(0)).data('sashakhChatOpenButton').destroy()
         * @private
         */
        _destroy: function () {
            $(this.element).off('click.sashakh_chat');

        },


        /**
         * Open popup with the form to edit preferences
         */
        editChat: function () {
            $(this.options.form).data('mage-modal').openModal();
        }
    });

    return $.sashakhChat.openButton;
});