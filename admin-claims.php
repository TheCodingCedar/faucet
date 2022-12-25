<?php
/**
 * @var The99Bitcoins_BtcFaucet_Plugin $this
 * @var The99Bitcoins_BtcFaucet_Currency_Base $currency
 *
 * @var string $notice_message
 * @var string $notice_css_class
 *
 * @var float $total
 * @var float $threshold_total
 * @var float $scheduled_total
 * @var int $scheduled_addresses
 * @var float $threshold_direct
 * @var float $threshold_seniority
 * @var float $threshold_referral
 * @var float $threshold_extra
 * @var float $threshold_fee
 *
 * @var float $wallet_balance
 * @var bool $wallet_fee
 * @var int $wallet_size
 *
 * @var array $grouped_labels
 * @var array $grouped_total
 *
 * @var int $last_cron
 * @var float $payout_frozen
 *
 * @var bool $clear
 */
?>

    <?php if (!empty($notice_message)): ?>
        <div class="<?= $notice_css_class ?>">
            <p><?= $notice_message ?></p>
        </div>
    <?php endif ?>

    <table class="form-table">
        <tr>
            <th scope="row"><?php esc_html_e('Total claims for 30 days', '99btc-bf') ?></th>
            <td>
                <?= number_format_i18n($total) ?>
                <?php esc_html_e($currency->satoshi(), '99btc-bf') ?>
            </td>
        </tr>
        <tr>
            <th colspan="2">
                <hr>
            </th>
        </tr>
        <tr>
            <th class="row"><?php esc_html_e($currency->satoshi() . ' to be paid', '99btc-bf') ?></th>
            <td>
                <?= number_format_i18n($threshold_total) ?>
                <?php esc_html_e($currency->satoshi(), '99btc-bf') ?>
            </td>
        </tr>
        <?php if ($threshold_direct): ?>
            <tr>
                <td class="row"><?php esc_html_e('Unpaid in directs', '99btc-bf') ?></td>
                <td>
                    <?= number_format_i18n($threshold_direct) ?>
                    <?php esc_html_e($currency->satoshi(), '99btc-bf') ?>
                </td>
            </tr>
        <?php endif ?>
        <?php if ($threshold_seniority): ?>
            <tr>
                <td class="row"><?php esc_html_e('Unpaid in seniority', '99btc-bf') ?></td>
                <td>
                    <?= number_format_i18n($threshold_seniority) ?>
                    <?php esc_html_e($currency->satoshi(), '99btc-bf') ?>
                </td>
            </tr>
        <?php endif ?>
        <?php if ($threshold_referral): ?>
            <tr>
                <td class="row"><?php esc_html_e('Unpaid in referrals', '99btc-bf') ?></td>
                <td>
                    <?= number_format_i18n($threshold_referral) ?>
                    <?php esc_html_e($currency->satoshi(), '99btc-bf') ?>
                </td>
            </tr>
        <?php endif ?>
        <?php if ($threshold_extra): ?>
            <tr>
                <td class="row"><?php esc_html_e('Bonuses / Penalties', '99btc-bf') ?></td>
                <td>
                    <?= number_format_i18n($threshold_extra) ?>
                    <?php esc_html_e($currency->satoshi(), '99btc-bf') ?>
                </td>
            </tr>
        <?php endif ?>
        <?php if ($threshold_fee && !empty($this->support['fee']) && (empty($options['optout']) || !empty($options['pay']))): ?>
            <tr>
                <td class="row the99btcbfsupport">
                    <?php esc_html_e('Plugin support fee', '99btc-bf') ?><a><sup>?</sup></a>
                    <div>
                        This is a fee of <?= number_format_i18n($this->support['fee'] * 100, 1) ?>% from your total payouts paid to the plugin author in order to help maintain and support the plugin.
                        You can opt out of paying this fee but will then not be eligible for automatic future plugin updates or any support requests.
                        In order to opt out go to the support tab.
                    </div>
                </td>
                <td class="the99btcbfsupport">
                    <?= number_format_i18n($threshold_fee) ?>
                    <?php esc_html_e($currency->satoshi(), '99btc-bf') ?><?php if (!empty($options['optout'])): ?><a><sup>?</sup></a><?php endif ?>
                    <?php if (!empty($options['optout'])): ?>
                        <div>
                            You have disabled plugin support.
                            After the next support payment you will no longer pay for plugin support.
                        </div>
                    <?php endif ?>
                </td>
            </tr>
        <?php endif ?>
        <tr>
            <th colspan="2">
                <hr>
            </th>
        </tr>
        <tr>
            <th class="row"><?php esc_html_e('Scheduled payment amount', '99btc-bf') ?></th>
            <td>
                <?= number_format_i18n($scheduled_total / $currency->size(), 8) ?>
                <?php esc_html_e($currency->symbol(), '99btc-bf') ?>
            </td>
        </tr>
        <?php if ($wallet_fee && $scheduled_addresses): ?>
            <tr>
                <th class="row"><?php esc_html_e('Fee (' . $currency->satoshi() . ' per byte)', '99btc-bf') ?></th>
                <td>
                    <input type="number" data-limit="<?= esc_attr($wallet_size) ?>" data-addresses="<?= esc_attr($scheduled_addresses) ?>" data-fee="value" value="0" style="width: 100px;">
                </td>
            </tr>
        <?php endif ?>
        <?php if ($wallet_balance !== -1): ?>
            <tr>
                <th class="row"><?php esc_html_e('Need to load funds', '99btc-bf') ?></th>
                <td>
                    <span data-fee="funds" data-scheduled="<?= esc_attr($scheduled_total) ?>" data-balance="<?= esc_attr($wallet_balance) ?>"><?= $scheduled_total - $wallet_balance > 0 ? number_format_i18n(($scheduled_total - $wallet_balance) / $currency->size(), 8) : 0 ?></span>
                    <?php esc_html_e($currency->symbol(), '99btc-bf') ?>
                </td>
            </tr>
        <?php endif ?>
    </table>
    <form method="post" data-confirm="<?= esc_attr__('Are you sure?', '99btc-bf') ?>" data-disable="<?= esc_attr__('Please wait, this operation may take a long time', '99btc-bf') ?>">
        <div class="btn-group">
            <?php $makeAttrs = array(
                'data-overlay' => '1',
            ) ?>
            <?php if (!$threshold_total) $makeAttrs['disabled'] = 'disabled' ?>
            <?php $clearAttrs = array() ?>
            <?php if (!$clear) $clearAttrs['disabled'] = 'disabled' ?>
            <?php submit_button(__('Make payment', '99btc-bf'), 'primary', 'make', false, $makeAttrs) ?>
            <?php if ($scheduled_total): ?>
                &nbsp;&nbsp;&nbsp;
                <?php submit_button(__('Cancel scheduled payment', '99btc-bf'), '', 'clear', false, $clearAttrs) ?>
            <?php endif ?>
            <div class="the99btcoverlay" style="display: none" data-container="overlay">
                <div>
                    <?= esc_html__('Processing payment, this may take a few minutes.', '99btc-bf') ?><br>
                    <?= esc_html__('Please do not close this window.', '99btc-bf') ?><br>
                </div>
            </div>
        </div>
        <p>
            <div><?= esc_html__('Please take in mind that payouts are not instant.', '99btc-bf') ?></div>
            <div><?= esc_html__('They are made every 20 minutes.', '99btc-bf') ?></div>
            <?php if ($last_cron == 1): ?>
                <div><?= sprintf(esc_html__('Last cron run was %s minute ago.', '99btc-bf'), $last_cron) ?></div>
            <?php elseif ($last_cron >= 0 && $last_cron <= 60): ?>
                <div><?= sprintf(esc_html__('Last cron run was %s minutes ago.', '99btc-bf'), $last_cron) ?></div>
            <?php elseif ($last_cron > 60): ?>
                <div><?= esc_html__('Please use tools section to reinstall cron.', '99btc-bf') ?></div>
            <?php endif ?>
        </p>
        <div><?= esc_html__('Payment status:', '99btc-bf') ?></div>
        <?php if (empty($options['cron']['runs'])): ?>
            <div><?= esc_html__('Payouts were not ever run. Please check cron configuration if you see that for long time.', '99btc-bf') ?></div>
        <?php else: ?>
            <div style="overflow-y: scroll;height: 150px;">
                <?php foreach (array_reverse($options['cron']['runs'], true) as $stamp => $status): ?>
                    <div><small><?= __($status, '99btc-bf') ?></small></div>
                <?php endforeach ?>
            </div>
        <?php endif ?>
        <input type="hidden" name="action" value="">
    </form>
    <hr>
    <div class="row">
        <canvas id="chart-information"></canvas>
    </div>
</div>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
<script>
    jQuery(document).ready(function($) {
        var myLineChart = new Chart($("#chart-information"), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($grouped_labels) ?>,
                datasets: [
                    {
                        label: '<?= esc_js(__('Form Submits', '99btc-bf')) ?>',

                        fill: true,
                        backgroundColor: "rgba(0, 0, 0, 0.1)",
                        borderWidth: 1,
                        borderColor: "black",

                        data: <?php echo json_encode($grouped_submits) ?>
                    },
                    <?php if (array_sum($grouped_bonus)): ?>
                    {
                        label: '<?= esc_js(__('Bonus', '99btc-bf')) ?>',

                        fill: true,
                        backgroundColor: "rgba(204, 120, 50, 0.1)",
                        borderWidth: 1,
                        borderColor: "rgb(204, 120, 50)",

                        data: <?php echo json_encode($grouped_bonus) ?>
                    },
                    <?php endif ?>
                    <?php if (array_sum($grouped_penalty)): ?>
                    {
                        label: '<?= esc_js(__('Penalty', '99btc-bf')) ?>',

                        fill: true,
                        backgroundColor: "rgba(255, 0, 0, 0.1)",
                        borderWidth: 1,
                        borderColor: "rgb(255, 0, 0)",

                        data: <?php echo json_encode($grouped_penalty) ?>
                    },
                    <?php endif ?>
                    <?php if ($grouped_direct): ?>
                    {
                        label: '<?= esc_js(__('Direct', '99btc-bf')) ?>',

                        fill: true,
                        backgroundColor: "rgba(194, 194, 194, 0.1)",
                        borderWidth: 1,
                        borderColor: "rgb(194, 194, 194)",

                        data: <?php echo json_encode($grouped_direct) ?>
                    },
                    <?php endif ?>
                    <?php if (array_sum($grouped_referral)): ?>
                    {
                        label: '<?= esc_js(__('Referral', '99btc-bf')) ?>',

                        fill: true,
                        backgroundColor: "rgba(3, 15, 137, 0.1)",
                        borderWidth: 1,
                        borderColor: "rgb(3, 15, 137)",

                        data: <?php echo json_encode($grouped_referral) ?>
                    },
                    <?php endif ?>
                    <?php if (array_sum($grouped_seniority)): ?>
                    {
                        label: '<?= esc_js(__('Seniority', '99btc-bf')) ?>',

                        fill: true,
                        backgroundColor: "rgba(9, 99, 11, 0.1)",
                        borderWidth: 1,
                        borderColor: "rgb(9, 99, 11)",

                        data: <?php echo json_encode($grouped_seniority) ?>
                    },
                    <?php endif ?>
                    {
                        label: '<?= esc_js(__('Total', '99btc-bf')) ?>',

                        fill: true,
                        backgroundColor: "rgba(0, 0, 0, 0.1)",
                        borderWidth: 1,
                        borderColor: "black",

                        data: <?php echo json_encode($grouped_total) ?>
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    });
</script>
