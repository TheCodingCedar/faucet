<?php
/**
 * @var The99Bitcoins_BtcFaucet_Plugin $this
 * @var The99Bitcoins_BtcFaucet_Currency_Base $currency
 *
 * @var string $notice_message
 * @var string $notice_css_class
 *
 * @var array $payouts
 * @var int $payouts_amount
 * @var string $search_payouts_source
 * @var string $search_payouts_from_date
 * @var string $search_payouts_to_date
 */
?>

    <?php if (!empty($notice_message)): ?>
        <div class="<?= $notice_css_class ?>">
            <p><?= $notice_message ?></p>
        </div>
    <?php endif ?>

    <form method="get">
        <input type="hidden" name="page" value="<?= esc_attr(!empty($_GET['page']) ? $_GET['page'] : '') ?>">
        <input type="hidden" name="mode" value="<?= esc_attr(!empty($_GET['mode']) ? $_GET['mode'] : '') ?>">
        <p class="search-box">
            <label class="screen-reader-text" for="user-search-input"><?= esc_html__('Search payouts:', '99btc-bf') ?></label>
            <input type="datetime" name="search_payouts_from_date" value="<?= esc_attr($search_payouts_from_date) ?>" placeholder="<?= esc_attr__('From date', '99btc-bf') ?>">
            <input type="datetime" name="search_payouts_to_date" value="<?= esc_attr($search_payouts_to_date) ?>" placeholder="<?= esc_attr__('To date', '99btc-bf§') ?>">
            <input type="submit" id="search-submit" class="button" value="<?= esc_attr__('Search payouts', '99btc-bf') ?>">
        </p>
        <div class="tablenav top">
            <div class="tablenav-pages one-page">
                <span class="displaying-num"><?= sprintf(esc_html__('Total payouts: %s ' . $currency->satoshi(), '99btc-bf'), number_format_i18n($payouts_amount)) ?></span>
            </div>
            <br class="clear">
        </div>
        <table class="wp-list-table widefat fixed striped users">
            <thead>
                <tr>
                    <th><?= esc_html__('Address', '99btc-bf') ?></th>
                    <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                    <th><?= esc_html__('Date', '99btc-bf') ?></th>
                    <th><?= esc_html__('Transaction', '99btc-bf') ?></th>
                </tr>
            </thead>
            <tbody>
            <?php if ($payouts): ?>
                <?php foreach ($payouts as $payout): ?>
                    <tr>
                        <td><a href="<?= esc_attr(add_query_arg('the99btcbfaddress', $payout['address'], get_site_url(null, $options['config']['urls_check']))) ?>" target="_blank"><?= esc_html($payout['address']) ?></a></td>
                        <td>
                            <?= number_format_i18n($payout['amount']) ?>
                            <?= esc_html__($currency->satoshi(), '99btc-bf') ?>
                        </td>
                        <td><?= date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $payout['stamp']) ?></td>
                        <td>
                            <?php if ($payout['transaction'] == 'faucetbox'): ?>
                                <small><a href="https://faucetbox.com/en/check/<?= urlencode($payout['address']) ?>" rel="nofollow" target="_blank" title="<?= esc_attr__($payout['transaction']) ?>"><?= esc_html__('FaucetBOX', '99btc-bf') ?></a></small>
                            <?php elseif ($payout['transaction'] == 'faucetpay.io'): ?>
                                <small><a href="https://faucetpay.io/balance/<?= urlencode($payout['address']) ?>" rel="nofollow" target="_blank" title="<?= esc_attr__($payout['transaction']) ?>"><?= esc_html__('faucetpay.io', '99btc-bf') ?></a></small>
                            <?php elseif ($payout['transaction'] == 'epay.info'): ?>
                                <small><a href="http://epay.info/dashboard/<?= urlencode($payout['address']) ?>/" rel="nofollow" target="_blank" title="<?= esc_attr__($payout['transaction']) ?>"><?= esc_html__('epay.info', '99btc-bf') ?></a></small>
                            <?php else: ?>
                                <small><a href="https://blockchain.info/tx/<?= urlencode($payout['transaction']) ?>" rel="nofollow" target="_blank" title="<?= esc_attr__($payout['transaction']) ?>"><?= esc_html__(substr($payout['transaction'], 0, 30)) ?>...</a></small>
                            <?php endif ?>

                        </td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">
                        <?= esc_html__('No data', '99btc-bf') ?>
                    </td>
                </tr>
            <?php endif ?>
            </tbody>
            <tfoot>
                <tr>
                    <th><?= esc_html__('Address', '99btc-bf') ?></th>
                    <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                    <th><?= esc_html__('Date', '99btc-bf') ?></th>
                    <th><?= esc_html__('Transaction', '99btc-bf') ?></th>
                </tr>
            </tfoot>
        </table>
        <div class="tablenav bottom">
            <div class="tablenav-pages one-page">
                <span class="displaying-num"><?= sprintf(esc_html__('Total payouts: %s ' . $currency->satoshi(), '99btc-bf'), number_format_i18n($payouts_amount)) ?></span>
            </div>
            <br class="clear">
        </div>
    </form>
    <br class="clear">
</div>
<script>
    jQuery(document).ready(function ($) {
        $('input[type=datetime]').datepicker({
            dateFormat : 'yy-mm-dd'
        });
    });
</script>
