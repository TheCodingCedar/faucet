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
    <input type="radio" name="config[captcha]" value="raincaptcha" id="config[captcha][raincaptcha]"<?php if (empty($config['captcha']) || $config['captcha'] == 'raincaptcha'): ?> checked="checked"<?php endif ?>> <a href="http://raincaptcha.com/" target="_blank"><?= esc_html__('RainCAPTCHA', '99btc-bf') ?></a><br>
    <input type="checkbox" name="config[captchas][]" value="raincaptcha" id="config[captchas][raincaptcha]" style="position: relative; top: 2px"<?php if (!empty($config['captchas']) && in_array('raincaptcha', $config['captchas'])): ?> checked="checked"<?php endif ?>> <label for="config[captchas][raincaptcha]"><small><?= esc_html__('Allow as an option', '99btc-bf') ?></small></label><br>
</h4>
<table class="form-table">
    <tr>
        <th scope="row">
            <label for="config[raincaptcha_private]"><?= esc_html__('Private key', '99btc-bf') ?></label>
        </th>
        <td>
            <input class="regular-text" type="text" name="config[raincaptcha_private]" id="config[raincaptcha_private]" value="<?= esc_attr($config['raincaptcha_private']) ?>" placeholder="<?= esc_html__('Private key', '99btc-bf') ?>">
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="config[raincaptcha_public]"><?= esc_html__('Public key', '99btc-bf') ?></label>
        </th>
        <td>
            <input class="regular-text" type="text" name="config[raincaptcha_public]" id="config[raincaptcha_public]" value="<?= esc_attr($config['raincaptcha_public']) ?>" placeholder="<?= esc_html__('Public key', '99btc-bf') ?>">
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="config[raincaptcha_type]"><?= esc_html__('Load type', '99btc-bf') ?></label>
        </th>
        <td>
            <select name="config[raincaptcha_type]" id="config[raincaptcha_type]">
                <option value=""<?= selected(empty($config['raincaptcha_type'])) ?>><?= esc_html__('Regular', '99btc-bf') ?></option>
                <option value="lazy"<?= selected(!empty($config['raincaptcha_type']) && $config['raincaptcha_type'] == 'lazy') ?>><?= esc_html__('Lazy', '99btc-bf') ?></option>
            </select>
        </td>
    </tr>
</table>
