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
    <input type="radio" name="config[captcha]" value="solvemedia" id="config[captcha][solvemedia]"<?php if (empty($config['captcha']) || $config['captcha'] == 'solvemedia'): ?> checked="checked"<?php endif ?>> <a href="http://solvemedia.com" target="_blank"><?= esc_html__('Solvemedia (Captcha)', '99btc-bf') ?></a><br>
    <input type="checkbox" name="config[captchas][]" value="solvemedia" id="config[captchas][solvemedia]" style="position: relative; top: 2px"<?php if (!empty($config['captchas']) && in_array('solvemedia', $config['captchas'])): ?> checked="checked"<?php endif ?>> <label for="config[captchas][solvemedia]"><small><?= esc_html__('Allow as an option', '99btc-bf') ?></small></label><br>
</h4>
<table class="form-table">
    <tr>
        <th scope="row">
            <label for="config[solve_media_private]"><?= esc_html__('Private key', '99btc-bf') ?></label>
        </th>
        <td>
            <input class="regular-text" type="text" name="config[solve_media_private]" id="config[solve_media_private]" value="<?= esc_attr($config['solve_media_private']) ?>" placeholder="<?= esc_html__('Private key', '99btc-bf') ?>">
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="config[solve_media_public]"><?= esc_html__('Public key', '99btc-bf') ?></label>
        </th>
        <td>
            <input class="regular-text" type="text" name="config[solve_media_public]" id="config[solve_media_public]" value="<?= esc_attr($config['solve_media_public']) ?>" placeholder="<?= esc_html__('Public key', '99btc-bf') ?>">
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="config[solve_media_hash]"><?= esc_html__('Hash', '99btc-bf') ?></label>
        </th>
        <td>
            <input class="regular-text" type="text" name="config[solve_media_hash]" id="config[solve_media_hash]" value="<?= esc_attr($config['solve_media_hash']) ?>" placeholder="<?= esc_html__('Hash', '99btc-bf') ?>">
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="config[solve_media_type]"><?= esc_html__('Load type', '99btc-bf') ?></label>
        </th>
        <td>
            <select name="config[solve_media_type]" id="config[solve_media_type]">
                <option value=""<?= selected(empty($config['solve_media_type'])) ?>><?= esc_html__('Regular', '99btc-bf') ?></option>
                <option value="ajax"<?= selected(!empty($config['solve_media_type']) && $config['solve_media_type'] == 'ajax') ?>><?= esc_html__('Ajax', '99btc-bf') ?></option>
            </select>
        </td>
    </tr>
</table>
