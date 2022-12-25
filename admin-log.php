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
            <label class="screen-reader-text" for="user-search-input"><?= esc_html__('Search log:', '99btc-bf') ?></label>
            <select name="search_payouts_source">
                <option value=""><?= esc_html__('All payouts', '99btc-bf') ?></option>
                <option value="direct"<?php if ($search_payouts_source == 'direct'): ?> selected="selected"<?php endif ?>><?= esc_html__('Only direct', '99btc-bf') ?></option>
                <option value="referral"<?php if ($search_payouts_source == 'referral'): ?> selected="selected"<?php endif ?>><?= esc_html__('Only referral', '99btc-bf') ?></option>
                <option value="seniority"<?php if ($search_payouts_source == 'seniority'): ?> selected="selected"<?php endif ?>><?= esc_html__('Only seniority', '99btc-bf') ?></option>
            </select>
            <input type="datetime" name="search_payouts_from_date" value="<?= esc_attr($search_payouts_from_date) ?>" placeholder="<?= esc_attr__('From date', '99btc-bf') ?>">
            <input type="datetime" name="search_payouts_to_date" value="<?= esc_attr($search_payouts_to_date) ?>" placeholder="<?= esc_attr__('To date', '99btc-bf§') ?>">
            <input type="submit" id="search-submit" class="button" value="<?= esc_attr__('Search log', '99btc-bf') ?>">
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
                <th><?= esc_html__('Payout type', '99btc-bf') ?></th>
                <th><?= esc_html__('Payout status', '99btc-bf') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if ($payouts): ?>
                <?php foreach ($payouts as $payout): ?>
                    <tr>
                        <td><a href="<?= esc_attr(add_query_arg('the99btcbfaddress', $payout['address'], get_site_url(null, $options['config']['urls_check']))) ?>" target="_blank"><?= esc_html($payout['address']) ?></a></td>
                        <td>
                            <?= sprintf(esc_html__('%s ' . $currency->satoshi(), '99btc-bf'), number_format_i18n($payout['amount'])) ?>
                        </td>
                        <td><?= date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $payout['stamp']) ?></td>
                        <td>
                            <?php if ($payout['source'] == 'referral'): ?>
                                <span class="label label-referral"><?= esc_html__('referral', '99btc-bf') ?></span>
                            <?php elseif ($payout['source'] == 'seniority'): ?>
                                <span class="label label-seniority"><?= esc_html__('seniority', '99btc-bf') ?></span>
                            <?php elseif ($payout['source'] == 'bonus'): ?>
                                <span class="label label-bonus"><?= esc_html__('bonus', '99btc-bf') ?></span>
                            <?php elseif ($payout['source'] == 'penalty'): ?>
                                <span class="label label-penalty"><?= esc_html__('penalty', '99btc-bf') ?></span>
                            <?php else: ?>
                                <span class="label label-default"><?= esc_html__('direct', '99btc-bf') ?></span>
                            <?php endif ?>
                        </td>
                        <td>
                            <?php if ($payout['paid'] == 'yes'): ?>
                                <span class="label label-success"><?= esc_html__('paid', '99btc-bf') ?></span>
                            <?php else: ?>
                                <span class="label label-default"><?= esc_html__('not paid', '99btc-bf') ?></span>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">
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
                <th><?= esc_html__('Payout type', '99btc-bf') ?></th>
                <th><?= esc_html__('Payout status', '99btc-bf') ?></th>
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
