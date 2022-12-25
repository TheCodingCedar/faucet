<div id="general-urls" class="the99tab" style="display: none">
    <p><?= esc_html__('Please enter the URLs of your faucet page and address checker page here', '99btc-bf') ?></p>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="config[urls_main]"><?= esc_html__('Faucet page', '99btc-bf') ?></label>
            </th>
            <td>
                <input class="regular-text" type="text" name="config[urls_main]" id="config[urls_main]" value="<?= esc_attr($config['urls_main']) ?>" placeholder="<?= esc_html__('Faucet page', '99btc-bf') ?>">
                <p><?= esc_html__('If you want to use homepage just put / here.', '99btc-bf')?></p>
                <p><?= esc_html__('/, /some/path/to/faucet, /?page=N are valid values', '99btc-bf')?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="config[urls_check]"><?= esc_html__('Check address page', '99btc-bf') ?></label>
            </th>
            <td>
                <input class="regular-text" type="text" name="config[urls_check]" id="config[urls_check]" value="<?= esc_attr($config['urls_check']) ?>" placeholder="<?= esc_html__('Check address page', '99btc-bf') ?>">
                <p><?= esc_html__('If you want to use homepage just put / here.', '99btc-bf')?></p>
                <p><?= esc_html__('/, /some/path/to/check, /?page=N are valid values', '99btc-bf')?></p>
            </td>
        </tr>
    </table>
</div>
