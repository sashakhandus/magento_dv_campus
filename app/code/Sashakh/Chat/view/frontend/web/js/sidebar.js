define([
    'jquery',
    'jquery/ui',
    'mage/translate',
    'sashakh_chat_form'
], function ($) {
    'use strict';

    $.widget('sashakhChat.sidebar', {
        options: {
            sidebarOpenButton: '.sashakh-chat-open-button',
            editButton: '#sashakh-chat-edit-button',
            closeSidebar: '#sashakh-chat-close-sidebar-button',
            chatList: '#sashakh-chat-list',
            form: '#sashakh-chat-form'
        },

        /**
         * @private
         */
        _create: function () {
            $(document).on('sashakh_Chat_openChat.sashakh_chat', $.proxy(this.openChat, this));
            $(this.options.closeSidebar).on('click.sashakh_chat', $.proxy(this.closeChat, this));
            $(this.options.editButton).on('click.sashakh_chat', $.proxy(this.editChat, this));

            // make the hidden form visible after the styles are initialized
            $(this.element).show();
        },

        /**
         * @private
         */
        _destroy: function () {
            $(document).off('sashakh_Chat_openChat.sashakh_chat');
            $(this.options.closeSidebar).off('click.sashakh_chat');
            $(this.options.editButton).off('click.sashakh_chat');
        },

        /**
         * Open chat sidebar
         */
        openChat: function () {
            $(this.element).addClass('active');
        },

        /**
         * Close chat sidebar
         */
        closeChat: function () {
            $(this.element).removeClass('active');
            $(this.options.sidebarOpenButton).trigger('sashakh_Chat_closeChat');
        },

        /**
         * Open popup with the form to edit preferences
         */
        editChat: function () {
            $(this.options.form).data('mage-modal').openModal();
        }
    });

    return $.sashakhChat.sidebar;
});