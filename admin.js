jQuery(document).ready(function($) {
    "use strict";
    $(document).on('change', 'input[id="config[only_users]"]', function() {
        var element = $(this);
        if (typeof element.attr('checked') == 'undefined') {
            $('.the99btc_only_users').hide();
        } else {
            $('.the99btc_only_users').show();
        }
    });
    $(document).on('change', 'form[data-id="reset"] input[type="checkbox"]', function() {
        var element = $(this);
        $('form[data-id="reset"] input[type="submit"]').attr('disabled', typeof element.attr('checked') == 'undefined');
    });
    $(document).on('click', 'form[data-confirm] [type="submit"]', function() {
        $('input[name="action"]', $(this).parents('form:first')).val($(this).attr('name'));
    });
    $(document).on('submit', 'form[data-confirm]', function(event) {
        var form = $(this);
        var allow = !!form.data('allow');
        var disable = form.attr('data-disable');
        if (allow) {
            form.data('allow', false);
            return true;
        } else if (confirm(form.attr('data-confirm'))) {
            var button = $('input[name="' + $('input[name="action"]', form).val() + '"]', form);
            if (typeof disable != 'undefined') {
                button.val(disable);
                button.attr('disabled', true);
            }
            if (button.attr('data-overlay')) {
                $('[data-container="overlay"]', form).show();
            }
            form.data('allow', true);
            setTimeout(form.submit.bind(form), 0);
            return false;
        }
        return false;
    });

    $('.btn-prototype').live('click', function(event) {
        var button = $(event.target);
        var table = button.parents('table');
        var prototype = $('tr.prototype', table);
        var row = prototype.clone().removeClass('prototype').attr('data-count', null).show();

        var count = parseInt(prototype.attr('data-count'));
        count ++;
        $('input[name*="__counter__"]', row).each(function (key, element) {
            element = $(element);
            element.attr('name', element.attr('name').replace('__counter__', count));
        });
        prototype.attr('data-count', count);

        $('tbody', table).append(row);
        return false;
    });
    $('.btn-remove-row').live('click', function(event) {
        var button = $(event.target);
        var confirmString = button.attr('data-click-confirm');
        if (confirmString) {
            if (confirm(confirmString)) {
                button.parents('tr').remove();
            }
        } else {
            button.parents('tr').remove();
        }
        return false;
    });

    $('h2.the99btcwallets a').click(function(event) {
        var element = $(event.target);
        $('h2.the99btcwallets a').removeClass('nav-tab-active');
        $('div.the99btcwallets form').hide();
        $('#' + element.attr('data-layer')).show();
        element.addClass('nav-tab-active');
        return false;
    });
    $('h2.the99btcwallets a.nav-tab-active').click();

    $('h2.the99claimrules a').click(function(event) {
        var element = $(event.target);
        $('h2.the99claimrules a').removeClass('nav-tab-active');
        $('div.the99claimrules form').hide();
        $('#' + element.attr('data-layer')).show();
        element.addClass('nav-tab-active');
        return false;
    });
    $('h2.the99claimrules a.nav-tab-active').click();

    $('h2.the99tabs a').click(function(event) {
        var element = $(event.target);
        $('h2.the99tabs a').removeClass('nav-tab-active');
        $('.the99tab').hide();
        $('#' + element.attr('data-layer')).show();
        element.addClass('nav-tab-active');
        return false;
    });
    $('h2.the99tabs a.nav-tab-active').click();

    $('a[data-tutorial]').click(function () {
        var element = $(this);
        var video = $('div[data-tutorial="' + element.attr('data-tutorial') + '"]');
        if (element.data('active')) {
            element.data('active', false);
            video.slideUp();
        } else {
            element.data('active', true);
            video.slideDown();
        }
    });

    $('a[data-before-click]').click(function () {
        var element = $(this);
        var container = $('[data-container="before-click"]');
        $('div', container).prepend(element.attr('data-before-click') + '<br>');
        container.show();
        setTimeout(function() {
            location.href = element.attr('href');
        }, 0);
        return false;
    });

    $('input[data-fee="value"]').each(function(key, element) {
        element = $(element);
        element.val($.cookie('the99btc-fee') || 0);

        var addressesOut = parseInt(element.attr('data-addresses'));
        var addressesLimit = parseInt(element.attr('data-limit'));
        var addressesIn = addressesLimit ? 2 * Math.ceil(addressesOut / addressesLimit) : 2;
        var value = $('[data-fee="funds"]');
        var scheduled = parseInt(value.attr('data-scheduled'));
        var balance = parseInt(value.attr('data-balance'));
        var transactionSize = addressesIn * 180 + addressesOut * 34 + 10 + addressesIn;

        var calculateRequired = function () {
            var fee = parseInt(element.val()) || 0;
            var required = scheduled + transactionSize * fee - balance;
            value.text(required > 0 ? required / 100000000 : 0);
            $.cookie('the99btc-fee', fee, {
                expires: 150,
                path: '/'
            });
        };

        element.on('keyup', calculateRequired);
        element.on('change', calculateRequired);
        calculateRequired();
    });
});
