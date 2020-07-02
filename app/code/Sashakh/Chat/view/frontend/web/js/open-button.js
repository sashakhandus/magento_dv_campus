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
            openButton: '#sashakh-chat-open-button',
            destroyButton: '#sashakh-chat-destroy-button',
            chatList: '#sashakh-chat-list',
            form: '#sashakh-chat-form'
        },

        /**
         * @private
         */
        _create: function () {
            $(this.options.openButton).on('click.sashakh_chat', $.proxy(this.openChat, this));
            $(this.options.destroyButton).on('click.sashakh_chat', $.proxy(this.destroy, this));
        },

        /**
         * jQuery(jQuery('.sashakh-chat-open-block').get(0)).data('sashakhChatOpenButton').destroy()
         * @private
         */
        _destroy: function () {
            $(this.options.openButton).off('click.sashakh_chat');
            $(this.options.destroyButton).off('click.sashakh_chat');
        },

        /**
         * Open popup with the form to edit preferences
         */
        openChat: function () {
            $(this.options.form).data('mage-modal').openModal();
        },
    });

    return $.sashakhChat.openButton;
});
