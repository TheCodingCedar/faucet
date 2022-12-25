<?php
/**
 * @var The99Bitcoins_BtcFaucet_Plugin $this
 * @var array $config
 * @var array $claim_rules
 * @var array $seniority_rules
 *
 * @var string $notice_message
 * @var string $notice_css_class
 */
?>
<h4>
    <input type="radio" name="config[captcha]" value="recaptcha" id="config[captcha][recaptcha]"<?php if (!empty($config['captcha']) && $config['captcha'] == 'recaptcha'): ?> checked="checked"<?php endif ?>> <a href="https://www.google.com/recaptcha/" target="_blank"><?= esc_html__('Recaptcha', '99btc-bf') ?></a><br>
    <input type="checkbox" name="config[captchas][]" value="recaptcha" id="config[captchas][recaptcha]" style="position: relative; top: 2px"<?php if (!empty($config['captchas']) && in_array('recaptcha', $config['captchas'])): ?> checked="checked"<?php endif ?>> <label for="config[captchas][recaptcha]"><small><?= esc_html__('Allow as an option', '99btc-bf') ?></small></label><br>
</h4>
<table class="form-table">
    <tr>
        <th scope="row">
            <label for="config[recaptcha_site_key]"><?= esc_html__('Site key', '99btc-bf') ?></label>
        </th>
        <td>
            <input class="regular-text" type="text" name="config[recaptcha_site_key]" id="config[recaptcha_site_key]" value="<?= esc_attr($config['recaptcha_site_key']) ?>" placeholder="<?= esc_html__('Site key', '99btc-bf') ?>">
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="config[recaptcha_secret_key]"><?= esc_html__('Secret key', '99btc-bf') ?></label>
        </th>
        <td>
            <input class="regular-text" type="text" name="config[recaptcha_secret_key]" id="config[recaptcha_secret_key]" value="<?= esc_attr($config['recaptcha_secret_key']) ?>" placeholder="<?= esc_html__('Secret key', '99btc-bf') ?>">
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="config[recaptcha_type]"><?= esc_html__('Load type', '99btc-bf') ?></label>
        </th>
        <td>
            <select name="config[recaptcha_type]" id="config[recaptcha_type]">
                <option value=""<?= selected(empty($config['recaptcha_type'])) ?>><?= esc_html__('Regular', '99btc-bf') ?></option>
                <option value="lazy"<?= selected(!empty($config['recaptcha_type']) && $config['recaptcha_type'] == 'lazy') ?>><?= esc_html__('Lazy', '99btc-bf') ?></option>
            </select>
        </td>
    </tr>
</table>
