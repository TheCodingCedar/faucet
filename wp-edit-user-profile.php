<?php
/**
 * @var The99Bitcoins_BtcFaucet_Plugin $this
 * @var WP_User $record
 */
?>
<h2><?= esc_html__('FaucetPay', '99btc-bf') ?></h2>
<table class="form-table">
    <tbody>
        <tr class="user-the99btc_address-wrap">
            <th><label for="the99btc_address_BCH"><?= esc_html__('Bitcoin Cash Address', '99btc-bf') ?></label></th>
            <td><input type="text" name="the99btc_address_BCH" id="the99btc_address_BCH" value="<?= esc_attr(get_user_meta($record->ID, 'the99btc_address_BCH', true)) ?>" placeholder="<?= esc_attr__('Example', '99btc-bf') ?>: ..." class="regular-text"></td>
        </tr>
        <tr class="user-the99btc_address-wrap">
            <th><label for="the99btc_address_BLK"><?= esc_html__('Blackcoin Address', '99btc-bf') ?></label></th>
            <td><input type="text" name="the99btc_address_BLK" id="the99btc_address_BLK" value="<?= esc_attr(get_user_meta($record->ID, 'the99btc_address_BLK', true)) ?>" placeholder="<?= esc_attr__('Example', '99btc-bf') ?>: ..." class="regular-text"></td>
        </tr>
        <tr class="user-the99btc_address-wrap">
            <th><label for="the99btc_address_BTC"><?= esc_html__('Bitcoin Address', '99btc-bf') ?></label></th>
            <td><input type="text" name="the99btc_address_BTC" id="the99btc_address_BTC" value="<?= esc_attr(get_user_meta($record->ID, 'the99btc_address_BTC', true)) ?>" placeholder="<?= esc_attr__('Example', '99btc-bf') ?>: 1N48i8n7VgYh3iGgNW1rrCMkL84FthbBBx" class="regular-text"></td>
        </tr>
        <tr class="user-the99btc_address-wrap">
            <th><label for="the99btc_address_BTX"><?= esc_html__('BitCore Address', '99btc-bf') ?></label></th>
            <td><input type="text" name="the99btc_address_BTX" id="the99btc_address_BTX" value="<?= esc_attr(get_user_meta($record->ID, 'the99btc_address_BTX', true)) ?>" placeholder="<?= esc_attr__('Example', '99btc-bf') ?>: ..." class="regular-text"></td>
        </tr>
        <tr class="user-the99btc_address-wrap">
            <th><label for="the99btc_address_DASH"><?= esc_html__('Dash Address', '99btc-bf') ?></label></th>
            <td><input type="text" name="the99btc_address_DASH" id="the99btc_address_DASH" value="<?= esc_attr(get_user_meta($record->ID, 'the99btc_address_DASH', true)) ?>" placeholder="<?= esc_attr__('Example', '99btc-bf') ?>: XgZLXb8a2huVRsgnYEjqgXvnRtM5G2R76u" class="regular-text"></td>
        </tr>
        <tr class="user-the99btc_address-wrap">
            <th><label for="the99btc_address_DOGE"><?= esc_html__('Dogecoin Address', '99btc-bf') ?></label></th>
            <td><input type="text" name="the99btc_address_DOGE" id="the99btc_address_DOGE" value="<?= esc_attr(get_user_meta($record->ID, 'the99btc_address_DOGE', true)) ?>" placeholder="<?= esc_attr__('Example', '99btc-bf') ?>: DBRNyvm4KGocJhTcLTQYwnP61q5Y7rr5t6" class="regular-text"></td>
        </tr>
        <tr class="user-the99btc_address-wrap">
            <th><label for="the99btc_address_ETH"><?= esc_html__('Ethereum Address', '99btc-bf') ?></label></th>
            <td><input type="text" name="the99btc_address_ETH" id="the99btc_address_ETH" value="<?= esc_attr(get_user_meta($record->ID, 'the99btc_address_ETH', true)) ?>" placeholder="<?= esc_attr__('Example', '99btc-bf') ?>: 0xBA3Eb0b4F316b8D026320Eb9077633be71b86DD5" class="regular-text"></td>
        </tr>
        <tr class="user-the99btc_address-wrap">
            <th><label for="the99btc_address_LTC"><?= esc_html__('Litecoin Address', '99btc-bf') ?></label></th>
            <td><input type="text" name="the99btc_address_LTC" id="the99btc_address_LTC" value="<?= esc_attr(get_user_meta($record->ID, 'the99btc_address_LTC', true)) ?>" placeholder="<?= esc_attr__('Example', '99btc-bf') ?>: LRZwqahdcNhv4uAxDC8PQMKGcmY1SAQhuB" class="regular-text"></td>
        </tr>
        <tr class="user-the99btc_address-wrap">
            <th><label for="the99btc_address_POT"><?= esc_html__('Potcoin Address', '99btc-bf') ?></label></th>
            <td><input type="text" name="the99btc_address_POT" id="the99btc_address_POT" value="<?= esc_attr(get_user_meta($record->ID, 'the99btc_address_POT', true)) ?>" placeholder="<?= esc_attr__('Example', '99btc-bf') ?>: ..." class="regular-text"></td>
        </tr>
        <tr class="user-the99btc_address-wrap">
            <th><label for="the99btc_address_PPC"><?= esc_html__('Peercoin Address', '99btc-bf') ?></label></th>
            <td><input type="text" name="the99btc_address_PPC" id="the99btc_address_PPC" value="<?= esc_attr(get_user_meta($record->ID, 'the99btc_address_PPC', true)) ?>" placeholder="<?= esc_attr__('Example', '99btc-bf') ?>: ..." class="regular-text"></td>
        </tr>
        <tr class="user-the99btc_address-wrap">
            <th><label for="the99btc_address_XPM"><?= esc_html__('Primecoin Address', '99btc-bf') ?></label></th>
            <td><input type="text" name="the99btc_address_XPM" id="the99btc_address_XPM" value="<?= esc_attr(get_user_meta($record->ID, 'the99btc_address_XPM', true)) ?>" placeholder="<?= esc_attr__('Example', '99btc-bf') ?>: ..." class="regular-text"></td>
        </tr>
    </tbody>
</table>
