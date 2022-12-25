jQuery(document).ready(function($) {
    $(document).on('submit', '.the99btc-bf.form form', function() {
        return !!$(this).data('isSubmitAllowed');
    });

    $(document).on('click', 'input[name=claim_coins]', function() {
        var element = $(this);
        var form = element.parents('form:first');
        var id = form.find('input[name="t99fid"]').val();
        if (element.attr('data-url')) {
            var popup = window.open(element.attr('data-url'), '_blank', 'fullscreen=yes');
            if (popup && popup.opener) {
                popup.opener = null;
            }
        }

        var address = $('input[name=address]', form).val();
        address = address ? address.trim() : '';
        if (address && typeof $.cookie !== 'undefined') {
            $.cookie('t99fa' + id, address, {
                expires: 30,
                path: '/'
            });
        }

        $('input[name=antibotbutton]', form).val(element.attr('data-value'));
        form.data('isSubmitAllowed', true);
        form.submit();
    });

    // load transactions
    $(document).on('click', '.the99btc-bf.check .transaction-link', function(event) {
        var link = $(event.target);
        var form = link.parents('.the99btc-bf.check:first');
        var container = link.parents('tr').eq(0);
        $('td', container).html('...');
        var url = '/wp-admin/admin-ajax.php';
        if (typeof ajaxurl !== 'undefined') {
            url = ajaxurl;
        }
        if (typeof ajax_object !== 'undefined' && typeof ajax_object.ajaxurl !== 'undefined') {
            url = ajax_object.ajaxurl;
        }
        $.get(url + link.attr('href') + '&action=t99f_transaction', function(response) {
            container.remove();
            var element = $('.transaction-table', form);
            element.html(element.html() + response);
        });
        return false;
    });

    // load claims
    $(document).on('click', '.the99btc-bf.check .history-link', function(event) {
        var link = $(event.target);
        var form = link.parents('.the99btc-bf.check:first');
        var container = link.parents('tr').eq(0);
        $('td', container).html('...');
        var url = '/wp-admin/admin-ajax.php';
        if (typeof ajaxurl !== 'undefined') {
            url = ajaxurl;
        }
        if (typeof ajax_object !== 'undefined' && typeof ajax_object.ajaxurl !== 'undefined') {
            url = ajax_object.ajaxurl;
        }
        $.get(url + link.attr('href') + '&action=t99f_history', function(response) {
            container.remove();
            var element = $('.history-table', form);
            element.html(element.html() + response);
        });
        return false;
    });

    // sound controls
    $(document).on('click', '.the99btc-bf.form .sound-controls a', function() {
        var element = $(this);
        var form = element.parents('form:first');
        var id = form.find('input[name="t99fid"]').val();

        element.hide();
        if (typeof $.cookie !== 'undefined') {
            $.cookie('t99fns' + id, 1, {
                expires: new Date(element.attr('data-date')),
                path: '/'
            });
        }
        if (element.attr('data-status') === 'off') {
            $('.sound-controls a[data-status="on"]', form).show();
        } else {
            $('.sound-controls a[data-status="off"]', form).show();
        }
        return false;
    });

    // timer
    $('.the99btc-bf.form .timer').each(function() {
        var timer = $(this);
        var form = timer.parents('form:first');
        var id = form.find('input[name="t99fid"]').val();
        var timerDelay = parseInt(timer.attr('data-seconds'));
        var timerSound = timer.attr('data-sound');
        var startTimer = new Date();
        var audio = null;
        var play = typeof $.cookie === 'undefined' || !$.cookie('t99fns' + id);
        if (play && typeof Audio !== 'undefined' && typeof timerSound !== 'undefined') {
            var audio = new Audio(timerSound);
            audio.onended = function() {
                window.location.href = location.href;
            };
            $('head').append(audio);
        }
        var offsetOld = 0;
        var timerInterval = setInterval(function() {
            var currentTimer = new Date();
            var diff = Math.round(currentTimer.getTime() / 1000 - startTimer.getTime() / 1000);
            if (diff > timerDelay) {
                clearInterval(timerInterval);
                if (audio) {
                    setTimeout(audio.onended, 5000);
                    var result = audio.play();
                    if (result && typeof result.catch === 'function') {
                        result.catch(audio.onended);
                    }
                } else {
                    window.location.href = location.href;
                }
            } else {
                var offset = timerDelay - diff;
                if (offset !== offsetOld) {
                    var min = Math.floor(offset / 60);
                    var sec = offset % 60;
                    if (sec < 10) {
                        sec = '0' + sec.toString();
                    }
                    timer.text(min + ':' + sec);
                    offsetOld = offset;
                }
            }
        }, 250);
    });

    $('script[lazy-src]').each(function(key, element) {
        element = $(element);
        var original = document.write;
        var restore = false;
        document.write = function(data) {
            if (data.indexOf('<style') !== -1) {
                $('head', document).append(data);
            } else if (data.indexOf('<script') !== -1) {
                $('head', document).append(data);
                restore = true;
            } else {
                if (restore) {
                    element.parent().html(data);
                    document.write = original;
                } else {
                    $('body').append(data);
                }
            }
        };
        var hide = element.attr('lazy-hide');
        if (hide) {
            $(hide).remove();
        }
        $('head', document).append('<script type="text/javascript" src="' + element.attr('lazy-src') + '"></script>');
    });

    $('.the99btc-bf-solvemedia').each(function(key, element) {
        element = $(element);
        var timer = setInterval(function () {
            if (typeof ACPuzzle !== 'undefined') {
                ACPuzzle.create(element.attr('data-key'), element.attr('id'), {});
                clearInterval(timer);
            }
        }, 100);
    });

    $('.the99btc-bf.check').each(function() {
        var element = $(this);
        var variable = $(this).data('variable');
        var chart = $('.chart-information', element);
        var canvas = $('<canvas>');
        chart.html('');
        chart.append(canvas);
        window.the99btcAddressChart = new window.The99BtcChart(canvas, window[variable]);
        canvas = null;
        chart = null;
    });

    var match = location.href.match(/\?.*\br=([^&]+)/i);
    if (match && typeof $.cookie !== 'undefined') {
        $.cookie('t99r', match[1], {
            expires: 30,
            path: '/'
        });
    }

    (function() {
        if (typeof $.cookie !== 'undefined') {
            var value = $.cookie('t99r');
            var input = $('input[name="r"]');
            if (value && input.length && !input.val()) {
                input.val(value);
            }
        }
    })();

    (function() {
        $('.the99btc-bf').each(function() {
            var element = $(this);
            var form = element.find('form:first');
            var id = form.find('input[name="t99fid"]').val();

            var value = typeof $.cookie !== 'undefined' ? $.cookie('t99fa' + id) : '';
            var input = $('input[name="address"]', form);
            if (value && input.length && !input.val()) {
                input.val(value);
            }
            if (value) {
                $('.the99btc-bf.link.t99fa' + id).each(function(key, element) {
                    element = $(element);
                    var link = element.attr('data-link');
                    link += encodeURIComponent(value);
                    element.text(link);
                    element.attr('href', link);
                });
            }

            if (typeof $.cookie !== 'undefined' && $.cookie('t99fns' + id)) {
                $('.sound-controls a[data-status=on]', form).show();
            } else {
                $('.sound-controls a[data-status=off]', form).show();
            }

            var captcha = element.find('[name="t99fc"]');
            if (captcha.length) {
                $('.captcha-' + captcha.val(), element).show();
                captcha.on('change', function() {
                    $('.captcha', element).hide();
                    $('.captcha-' + captcha.val(), element).show();
                    if (typeof $.cookie !== 'undefined') {
                        $.cookie('t99fc' + id, captcha.val(), {
                            expires: 30,
                            path: '/'
                        });
                    }
                });
                if (typeof $.cookie !== 'undefined' && captcha[0].nodeName ==='SELECT' && $.cookie('t99fc' + id)) {
                    captcha.val($.cookie('t99fc' + id)).change();
                }
            }
        });
    })();
});
