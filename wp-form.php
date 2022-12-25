<?php
/**
 * @var The99Bitcoins_BtcFaucet_Plugin $this
 * @var The99Bitcoins_BtcFaucet_Currency_Base $currency
 *
 * @var array $options
 * @var array $captcha
 * @var array $errors
 * @var array $data
 * @var string $style
 * @var int $fake_buttons_before
 * @var int $fake_buttons_after
 * @var string $fake_buttons_value
 *
 * @var int $payout
 * @var int $timer
 * @var int $info_submits
 *
 * @var string $placeholder_header_text
 * @var string $placeholder_after_form_start
 * @var string $placeholder_after_captcha_text
 * @var string $placeholder_after_address_text
 * @var string $placeholder_before_form_end
 * @var string $placeholder_footer_text
 */
$formVariables = array();
$formUrl = parse_url($options['config']['urls_main']);
$formUrl = empty($formUrl['host']) ? get_site_url(null, $options['config']['urls_main']) : $options['config']['urls_main'];

$checkUrl = parse_url($options['config']['urls_check']);
$checkUrl = empty($checkUrl['host']) ? get_site_url(null, $options['config']['urls_check']) : $options['config']['urls_check'];

if (strpos($formUrl, '?') !== false) {
    $formUrlData = parse_url($formUrl);
    parse_str($formUrlData['query'], $formVariables);
}
$formDisableButton = false;
?>
<?php if (!empty($style)): ?>
    <script>(function() {var d = document; var h = 'head'; var i = 'innerHTML'; d[h][i] = d[h][i] + unescape('<?= $style ?>');})();</script>
<?php endif ?>
<?= $placeholder_header_text ?>
<?php if ($payout && !empty($options['config']['claim_ad_mode']) && $options['config']['claim_ad_mode'] === 'redirect' && !empty($options['config']['claim_ad_url'])): ?>
<script type="text/javascript">
setTimeout(function() {
    document.location = '<?= esc_js($options['config']['claim_ad_url']) ?>';
}, 5000);
</script>
<?php elseif ($payout && !empty($options['config']['claim_ad_mode']) && $options['config']['claim_ad_mode'] === 'popup' && !empty($options['config']['claim_ad_url'])): ?>
<script type="text/javascript">
(function() {
    var popup = window.open('<?= esc_js($options['config']['claim_ad_url']) ?>', '_blank', 'fullscreen=yes');
    if (popup && popup.opener) {
        popup.opener = null;
    }
})();
</script>
<?php endif ?>
<div class="the99btc-bf t99f-<?= esc_attr($this->config['post']->ID) ?> form">
    <form action="<?= esc_attr($formUrl) ?>" method="post">
        <?= $placeholder_after_form_start ?>
        <?php if ($payout): ?>
            <div class="message message-success">
                <?= sprintf(__('%d ' . $currency->satoshi() . ' were accumulated in your address: %s', '99btc-bf'), $payout, '<a href="' . esc_attr(add_query_arg(array('the99btcbfaddress' => $data['address'], 't99fid' => $this->config['post']->ID), $checkUrl)) . '">' . esc_html($data['address']) . '</a>')?>
                <?php if (!empty($options['config']['submit_limitation'])): ?>
                    <br><?= sprintf(esc_html__('(You have claimed %s / %s times in the last 24 hours)', '99btc-bf'), $info_submits, $options['config']['submit_limitation']) ?>
                <?php endif ?>
            </div>
            <?php if ($options['config']['refer_bonus']): ?>
                <div class="message message-success"><?= sprintf(__('Referral commision %d%%', '99btc-bf'), $options['config']['refer_bonus']) ?><br><?= esc_html__('Reflink', '99btc-bf') ?>: <?= esc_html(add_query_arg('r', $data['address'], $formUrl)) ?></div>
            <?php endif ?>
        <?php endif ?>
        <?php if ($timer): ?>
            <div class="message message-<?= empty($errors['delay']) ? 'success' : 'error' ?>"><?= esc_html__('Time remaining before you can claim ' . $currency->satoshi() . ' again:', '99btc-bf') ?> </div>
            <div class="timer" data-seconds="<?= esc_attr($timer) ?>"<?php if (!empty($options['config']['sound'])): ?> data-sound="<?= $this->config['plugin_url'] . 'assets/beep.wav' ?>"<?php endif ?>><?= floor($timer / 60) ?>:<?= (($temp = $timer % 60) < 10) ? '0' . $temp : $temp ?></div>
            <?php if (!empty($options['config']['sound'])): ?>
            <div class="sound-controls">
                <a class="faucet-sound-off" data-status="off" data-date="<?= date('l, d-M-Y H:i:s T', strtotime('+1 year')) ?>" style="display: none"><?= esc_html__('Disable sound alert', '99btc-bf') ?></a>
                <a class="faucet-sound-on" data-status="on" data-date="<?= date('l, d-M-Y H:i:s T', strtotime('-1 year')) ?>" style="display: none"><?= esc_html__('Enable sound alert', '99btc-bf') ?></a>
            </div>
            <?php endif ?>
        <?php endif ?>
        <?php if (!empty($errors['disabled'])): ?>
            <div class="message message-error"><?= esc_html__('Faucet in the middle of upgrade', '99btc-bf') ?></div>
        <?php endif ?>
        <?php if (!empty($errors['only_users'])): ?>
            <div class="message message-error"><?= esc_html__('You should be logged in to claim ' . $currency->satoshi(), '99btc-bf') ?></div>
        <?php endif ?>
        <?php if (!empty($errors['network'])): ?>
            <div class="message message-error"><?= esc_html__('Please disable Opera Turbo, faucet doesn\'t work with that', '99btc-bf') ?></div>
        <?php endif ?>
        <?php if (!empty($errors['address'])): ?>
            <div class="message message-error"><?= esc_html__('Please enter valid ' . $currency->name() . ' address', '99btc-bf') ?></div>
        <?php endif ?>
        <?php if (!empty($errors['fake'])): ?>
            <div class="message message-error"><?= esc_html__('Security check failed', '99btc-bf') ?></div>
        <?php elseif (!empty($errors['captcha'])): ?>
            <div class="message message-error"><?= esc_html__('Wrong captcha', '99btc-bf') ?></div>
        <?php elseif (!empty($errors['ban'])): ?>
            <div class="message message-error"><?= esc_html__('Your address was disabled', '99btc-bf') ?></div>
        <?php endif ?>

        <?php if (count($captcha) > 1): ?>
            <div class="the99btc-captcha-options-container">
                <select name="t99fc" class="the99btc-captcha-options">
                    <?php foreach ($captcha as $name => $flag): ?>
                        <option value="<?= esc_attr($name) ?>"<?php if ($name === $options['config']['captcha']): ?> selected<?php endif ?>><?= esc_html__($name, '99btc-bf') ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        <?php else: ?>
            <input type="hidden" name="t99fc" value="<?= esc_attr(key($captcha)) ?>">
        <?php endif ?>

        <?php if (!empty($captcha['solvemedia'])): ?>
            <div class="captcha captcha-solvemedia"<?php if ($options['config']['captcha'] === 'solvemedia'): ?> style="display: block"<?php endif ?>>
                <?php if (empty($options['config']['solve_media_type'])): ?>
                  <script type="text/javascript" src="<?= empty($_SERVER['HTTPS']) ? THE99BTC_ADCOPY_API_SERVER : THE99BTC_ADCOPY_API_SECURE_SERVER ?>/papi/challenge.script?k=<?= esc_attr($options['config']['solve_media_public']) ?>"></script>
                  <noscript>
                      <iframe src="<?= empty($_SERVER['HTTPS']) ? THE99BTC_ADCOPY_API_SERVER : THE99BTC_ADCOPY_API_SECURE_SERVER ?>/papi/challenge.noscript?k=<?= esc_attr($options['config']['solve_media_public']) ?>" height="300" width="500" frameborder="0"></iframe><br>
                      <textarea name="adcopy_challenge" rows="3" cols="40"></textarea>
                      <input type="hidden" name="adcopy_response" value="manual_challenge">
                  </noscript>
                <?php elseif ($options['config']['solve_media_type'] === 'ajax'): ?>
                    <div id="solvemedia-captcha-<?= esc_attr($this->config['post']->ID) ?>" class="the99btc-bf-solvemedia" data-key="<?= esc_attr($options['config']['solve_media_public']) ?>"><?= esc_html__('Loading captcha...', '99btc-bf') ?></div>
                    <script type="text/javascript" src="<?= empty($_SERVER['HTTPS']) ? THE99BTC_ADCOPY_API_SERVER : THE99BTC_ADCOPY_API_SECURE_SERVER ?>/papi/challenge.ajax" async></script>
                <?php else: ?>
                    <script type="text/javascript" lazy-src="<?= empty($_SERVER['HTTPS']) ? THE99BTC_ADCOPY_API_SERVER : THE99BTC_ADCOPY_API_SECURE_SERVER ?>/papi/challenge.script?k=<?= esc_attr($options['config']['solve_media_public']) ?>" lazy-hide=".lazy-message-solvemedia">
                        document.write('<div class="lazy-message lazy-message-solvemedia"><?= esc_js(esc_html__('Loading captcha...', '99btc-bf')) ?></div>');
                    </script>
                    <noscript>
                        <iframe src="<?= empty($_SERVER['HTTPS']) ? THE99BTC_ADCOPY_API_SERVER : THE99BTC_ADCOPY_API_SECURE_SERVER ?>/papi/challenge.noscript?k=<?= esc_attr($options['config']['solve_media_public']) ?>" height="300" width="500" frameborder="0"></iframe><br>
                        <textarea name="adcopy_challenge" rows="3" cols="40"></textarea>
                        <input type="hidden" name="adcopy_response" value="manual_challenge">
                    </noscript>
                <?php endif ?>
            </div>
        <?php endif ?>

        <?php if (!empty($captcha['raincaptcha'])): ?>
            <div class="captcha captcha-raincaptcha"<?php if ($options['config']['captcha'] === 'raincaptcha'): ?> style="display: block"<?php endif ?>>
                <div id="rain-captcha" data-key="<?= esc_attr($options['config']['raincaptcha_public']) ?>"></div>
                <?php if (empty($options['config']['raincaptcha_type'])): ?>
                    <script src="//raincaptcha.com/base.js" type="application/javascript" async></script>
                    <noscript>
                        <?= esc_html__('Javascript should be enabled to solve captcha', '99btc-bf') ?>
                    </noscript>
                <?php else: ?>
                    <script type="text/javascript" lazy-src="//raincaptcha.com/base.js" lazy-hide=".lazy-message-raincaptcha">
                        document.write('<div class="lazy-message lazy-message-raincaptcha"><?= esc_js(esc_html__('Loading captcha...', '99btc-bf')) ?></div>');
                    </script>
                    <noscript>
                        <?= esc_html__('Javascript should be enabled to solve captcha', '99btc-bf') ?>
                    </noscript>
                <?php endif ?>
            </div>
        <?php endif ?>

        <?php if (!empty($captcha['recaptcha'])): ?>
            <div class="captcha captcha-recaptcha"<?php if ($options['config']['captcha'] === 'recaptcha'): ?> style="display: block"<?php endif ?>>
                <div class="g-recaptcha" data-sitekey="<?= esc_attr($options['config']['recaptcha_site_key']) ?>"></div>
                <?php if (empty($options['config']['recaptcha_type'])): ?>
                    <script type="text/javascript" src="//www.google.com/recaptcha/api.js" async></script>
                    <noscript>
                        <?= esc_html__('Javascript should be enabled to solve captcha', '99btc-bf') ?>
                    </noscript>
                <?php else: ?>
                    <script type="text/javascript" lazy-src="//www.google.com/recaptcha/api.js" lazy-hide=".lazy-message-recaptcha">
                        document.write('<div class="lazy-message lazy-message-recaptcha"><?= esc_js(esc_html__('Loading captcha...', '99btc-bf')) ?></div>');
                    </script>
                    <noscript>
                        <?= esc_html__('Javascript should be enabled to solve captcha', '99btc-bf') ?>
                    </noscript>
                <?php endif ?>
            </div>
        <?php endif ?>

        <?php if (!empty($captcha['coinhive'])): ?>
            <div class="captcha captcha-coinhive"<?php if ($options['config']['captcha'] === 'coinhive'): ?> style="display: block"<?php endif ?>>
                <div class="coinhive-captcha" data-autostart="<?php if ($options['config']['coinhive_autostart']): ?>true<?php else: ?>false<?php endif ?>" data-hashes="<?= esc_attr($options['config']['coinhive_hashes']) ?>" data-key="<?= esc_attr($options['config']['coinhive_site_key']) ?>"></div>
                <?php if (empty($options['config']['coinhive_type'])): ?>
                    <script type="text/javascript" src="//coin-hive.com/lib/captcha.min.js" async></script>
                    <noscript>
                        <?= esc_html__('Javascript should be enabled to solve captcha', '99btc-bf') ?>
                    </noscript>
                <?php else: ?>
                    <script type="text/javascript" lazy-src="//coin-hive.com/lib/captcha.min.js" lazy-hide=".lazy-message-coinhive">
                        document.write('<div class="lazy-message lazy-message-coinhive"><?= esc_js(esc_html__('Loading captcha...', '99btc-bf')) ?></div>');
                    </script>
                    <noscript>
                        <?= esc_html__('Javascript should be enabled to solve captcha', '99btc-bf') ?>
                    </noscript>
                <?php endif ?>
            </div>
        <?php endif ?>

        <?php if (!empty($captcha['bitcaptcha'])): ?>
            <div class="captcha captcha-bitcaptcha"<?php if ($options['config']['captcha'] === 'bitcaptcha'): ?> style="display: block"<?php endif ?>>
                <?php $bitcaptchaButton = '' ?>
                <?php if (empty($options['config']['bitcaptcha_mode'])): ?>
                    <div id="SQNView">
                        <div id="SQNContainer" sqn-height="40">
                            <div id="SQN-load-bg"></div>
                            <div class="SQN-init">
                                <div class="lazy-message lazy-message-bitcaptcha"><?= esc_html__('Loading captcha...', '99btc-bf') ?></div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php $bitcaptchaButton = 't99b' . $this->config['page']->ID . $fake_buttons_before ?>
                    <?php $formDisableButton = true ?>
                <?php endif ?>

                <?php if (empty($options['config']['coinhive_type'])): ?>
                    <script type="text/javascript" src="//static.shenqiniao.net/sqn.js?id=<?= esc_attr($options['config']['bitcaptcha_site_id']) ?>&btn=<?= esc_attr($bitcaptchaButton) ?>" async></script>
                    <noscript>
                        <?= esc_html__('Javascript should be enabled to solve captcha', '99btc-bf') ?>
                    </noscript>
                <?php else: ?>
                    <script type="text/javascript" lazy-src="//static.shenqiniao.net/sqn.js?id=<?= esc_attr($options['config']['bitcaptcha_site_id']) ?>&btn=<?= esc_attr($bitcaptchaButton) ?>" lazy-hide=".lazy-message-bitcaptcha"></script>
                    <noscript>
                        <?= esc_html__('Javascript should be enabled to solve captcha', '99btc-bf') ?>
                    </noscript>
                <?php endif ?>
            </div>
        <?php endif ?>

        <?= $placeholder_after_captcha_text ?>
        <div class="address">
            <?php if (!empty($options['config']['only_users']) && !empty($options['config']['only_users_single_address']) && $data['address']): ?>
                <?= esc_html($data['address']) ?><br>
                <small><?= esc_html__('You can change your ' . $currency->name() . ' Address through your profile on the top right', '99btc-bf') ?></small>
            <?php else: ?>
            <input type="text" name="address" placeholder="<?= esc_attr__('Your public ' . $currency->name() . ' Address', '99btc-bf') ?>" value="<?= esc_attr($data['address']) ?>">
            <?php endif ?>
            <?php if (!empty($options['config']['only_users']) && !empty($options['config']['only_users_single_pay'])): ?>
                <br><small><?= esc_html__('Only addresses linked to profiles will be paid', '99btc-bf') ?></small>
            <?php endif ?>
        </div>
        <?= $placeholder_after_address_text ?>
        <div class="button">
            <?php $attrs = '' ?>
            <?php $attrs = $attrs . ($formDisableButton ? ' disabled' : '') ?>
            <?php $attrs = $attrs . (!empty($options['config']['claim_ad_mode']) && $options['config']['claim_ad_mode'] === 'click' && !empty($options['config']['claim_ad_url']) ? ' data-url="' . esc_attr($options['config']['claim_ad_url']) . '"' : '') ?>
            <?php for ($i = 0; $i < $fake_buttons_before; $i ++): ?>
                <?= '<input' . $attrs . ' name="claim_coins" id="t99b' . $this->config['post']->ID . ($i) . '" type="button" value="' . esc_attr__(($formDisableButton ? 'Please wait to claim ' : 'Claim ') . $currency->name(), '99btc-bf') . '" data-value="' . md5(microtime(true) . rand(1000, 9999)) . '">' ?>
            <?php endfor ?>
            <?= '<input' . $attrs . ' name="claim_coins" id="t99b' . $this->config['post']->ID . ($fake_buttons_before) . '" type="button" value="' . esc_attr__(($formDisableButton ? 'Please wait to claim ' : 'Claim ') . $currency->name(), '99btc-bf') . '" data-value="' . $fake_buttons_value . '">' ?>
            <?php for ($i = 0; $i < $fake_buttons_after; $i ++): ?>
                <?= '<input' . $attrs . ' name="claim_coins" id="t99b' . $this->config['post']->ID . ($fake_buttons_before + 1 + $i) . '" type="button" value="' . esc_attr__(($formDisableButton ? 'Please wait to claim ' : 'Claim ') . $currency->name(), '99btc-bf') . '" data-value="' . md5(microtime(true) . rand(1000, 9999)) . '">' ?>
            <?php endfor ?>
        </div>
        <?= $placeholder_before_form_end ?>
        <input type="hidden" name="t99fid" value="<?= esc_attr($this->config['post']->ID) ?>">
        <?php if ($options['config']['refer_bonus']): ?>
            <input type="hidden" name="r" value="<?= esc_attr($data['refer']) ?>">
        <?php endif ?>
        <?php if (!empty($fake_buttons_key)): ?>
            <input type="hidden" name="antibotbutton" value="">
            <input type="hidden" name="antibotkey" value="<?= $fake_buttons_key ?>">
        <?php endif ?>
        <?php foreach ($formVariables as $name => $value): ?>
            <?php if (is_array($value)) continue; ?>
            <input type="hidden" name="<?= esc_attr($name) ?>" value="<?= esc_attr($value) ?>">
        <?php endforeach ?>
    </form>
</div>
<?= $placeholder_footer_text ?>
