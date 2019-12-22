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
           /*$(this.element).on('click.sashakh_Chat', $.proxy(this.openChat, this));*/
            /*$(this.element).on('sashakh_Chat_closeChat.sashakh_Chat', $.proxy(this.closeChat, this));*/
            $(this.element).on('click.sashakh_chat', $.proxy(this.editChat, this));
        },

        /**
         * jQuery(jQuery('.sashakh-chat-open-button').get(0)).data('sashakhChatOpenButton').destroy()
         * @private
         */
        _destroy: function () {
            /*$(this.element).off('click.sashakh_Chat');
            *
            $(this.element).off('sashakh_Chat_closeChat.sashakh_Chat');*/
            $(this.element).off('click.sashakh_chat');

        },

        /**
         * Open chat sidebar
         */
        openChat: function () {
            $(document).trigger('sashakh_Chat_openChat');

            if (this.options.hideButton) {
                $(this.element).removeClass('active');
            }
        },

        /**
         * Close chat sidebar
         */
        closeChat: function () {
            $(this.element).addClass('active');
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