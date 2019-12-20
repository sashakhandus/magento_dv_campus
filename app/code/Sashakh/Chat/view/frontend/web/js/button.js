define([
    'jquery',
    'jquery/ui',
    'mage/translate'
], function ($) {
    'use strict';

    $.widget('sashakhChat.button', {
        options: {
            customerPreferences: '#sashakh-chat',
            customerPreferencesEditButton: '#sashakh-chat-edit-button',
            customerPreferencesList: '#sashakh-chat-list'
        },

        _create: function () {
            $(this.element).click($.proxy(this.openChat, this));
        },

        openChat: function () {
            alert('Click event works fine');
        }
    });

    return $.sashakhChat.button;
});