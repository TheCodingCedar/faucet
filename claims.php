<table class="form-table the99tab" id="general-claims" style="display: none">
    <tr>
        <th scope="row">
            <label for="config[refer_bonus]"><?= esc_html__('Ref commission (in percent)', '99btc-bf') ?></label>
        </th>
        <td>
            <input class="regular-text" type="number" name="config[refer_bonus]" id="config[refer_bonus]" value="<?= esc_attr($config['refer_bonus']) ?>" placeholder="<?= esc_html__('Ref commission (in percent)', '99btc-bf') ?>" pattern="\d*" step="1">
            <p class="regular-text">
                <?= esc_html__('Your ref link looks like this: [FAUCET_URL]?r=[Bitcoin_address].', '99btc-bf') ?>
                <?= esc_html__('Make sure to place your faucet\'s url and the referring Bitcoin address in the right place.', '99btc-bf') ?>
                <?= esc_html__('The ref link will also show up after every claim.', '99btc-bf') ?>
            </p>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="config[payout_timer]"><?= esc_html__('Payout timer (in seconds)', '99btc-bf') ?></label></th>
        <td>
            <input class="regular-text" type="number" name="config[payout_timer]" id="config[payout_timer]" value="<?= esc_attr($config['payout_timer']) ?>" placeholder="<?= esc_html__('Payout timer (in seconds)', '99btc-bf') ?>" pattern="\d*" step="1">
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="config[claim_ad_mode]"><?= esc_html__('Extra ad on claim', '99btc-bf') ?></label>
        </th>
        <td>
            <select name="config[claim_ad_mode]" id="config[claim_ad_mode]">
                <option value=""<?= selected(empty($config['claim_ad_mode'])) ?>><?= esc_html__('Nothing', '99btc-bf') ?></option>
                <option value="click"<?= selected(!empty($config['claim_ad_mode']) && $config['claim_ad_mode'] == 'click') ?>><?= esc_html__('Open popup on claim\'s button click', '99btc-bf') ?></option>
                <option value="redirect"<?= selected(!empty($config['claim_ad_mode']) && $config['claim_ad_mode'] == 'redirect') ?>><?= esc_html__('Redirect after success', '99btc-bf') ?></option>
                <option value="popup"<?= selected(!empty($config['claim_ad_mode']) && $config['claim_ad_mode'] == 'popup') ?>><?= esc_html__('Open popup after success', '99btc-bf') ?></option>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="config[claim_ad_url]"><?= esc_html__('Extra ad url', '99btc-bf') ?></label></th>
        <td>
            <input class="regular-text" type="text" name="config[claim_ad_url]" id="config[claim_ad_url]" value="<?= esc_attr(empty($config['claim_ad_url']) ? '' : $config['claim_ad_url']) ?>" placeholder="<?= esc_html__('URL where visitor should be redirected', '99btc-bf') ?>">
        </td>
    </tr>
</table>
