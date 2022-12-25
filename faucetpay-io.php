<?php
/**
 * @var string $faucetpayio_status
 * @var array $wallet
 */
?>
<form method="post" novalidate="novalidate" id="FaucetPay" style="display: none">
    <table class="form-table">
        <tr>
            <th scope="row">
                <?= esc_html__('Status', '99btc-bf') ?>
            </th>
            <td>
                <?= esc_html($faucetpayio_status) ?>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="wallet[faucetpay][api_key]"><?= esc_html__('API key', '99btc-bf') ?></label>
            </th>
            <td>
                <input class="regular-text" type="text" name="wallet[faucetpay][api_key]" id="wallet[faucetpay][api_key]" value="<?= esc_attr($wallet['faucetpay']['api_key']) ?>" placeholder="<?= esc_attr__('API key', '99btc-bf') ?>" autocomplete="off">
            </td>
        </tr>
    </table>
    <?php submit_button(); ?>
    <p>
        <small><a href="https://faucetpay.io/page/faucet-admin" target="_blank" rel="nofollow"><?= esc_html__('faucetpay.io', '99btc-bf') ?></a></small><br>
        <small style="color: red"><?= esc_html__('Please ask your visitors to create an account on faucetpay.io first to receive payouts', '99btc-bf') ?></small><br>
    </p>
</form>
