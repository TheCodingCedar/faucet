<?php
/**
 * @var The99Bitcoins_BtcFaucet_Plugin $this
 * @var The99Bitcoins_BtcFaucet_Currency_Base $currency
 *
 * @var string $address
 * @var array $options
 * @var array $info
 * @var int $threshold
 */
$formVariables = array();
$formUrl = parse_url($options['config']['urls_check']);
$formUrl = empty($formUrl['host']) ? get_site_url(null, $options['config']['urls_check']) : $options['config']['urls_check'];
if (strpos($formUrl, '?') !== false) {
    $formUrlData = parse_url($formUrl);
    parse_str($formUrlData['query'], $formVariables);
}
$formVariables['t99fid'] = $this->config['post']->ID;
$chartId = 't99f' . substr(md5($formVariables['t99fid'] . microtime(true)), 0, 10);
?>
<div class="the99btc-bf t99f-<?= esc_attr($this->config['post']->ID) ?> check" data-variable="<?= esc_attr($chartId) ?>">
    <div class="row form">
        <form action="<?= esc_attr($formUrl) ?>" method="get">
            <h3><?= esc_html__('Check your ' . $currency->name() . ' Address statistic', '99btc-bf') ?></h3>
            <input type="text" name="the99btcbfaddress" value="<?= esc_attr($address) ?>" placeholder="<?= esc_attr__('Enter your ' . $currency->name() . ' Address', '99btc-bf') ?>">
            <input type="submit" value="<?= esc_attr__('Check', '99btc-bf') ?>">
            <?php foreach ($formVariables as $name => $value): ?>
                <?php if (is_array($value)) continue; ?>
                <input type="hidden" name="<?= esc_attr($name) ?>" value="<?= esc_attr($value) ?>">
            <?php endforeach ?>
        </form>
    </div>
    <?php if ($address && $info): ?>
        <div class="chart-information" style="height: 350px" data-src="<?= esc_attr($this->config['plugin_url'] . 'assets/js/Chart.min.js') ?>"><?= esc_html__('Loading chart information', '99btc-bf') ?></div>
        <div class="row threshold">
            <form action="" method="post">
                <input type="number" name="the99btcbfthreshold" value="<?= esc_attr($info['threshold']) ?>" step="1">
                <input type="submit" value="<?= esc_attr__('Set manual threshold', '99btc-bf') ?>">
            </form>
        </div>
        <div class="row info">
            <label><?= esc_html__('Unpaid address balance', '99btc-bf') ?></label>:
            <?= sprintf(esc_html__('%s ' . $currency->satoshi(), '99btc-bf'), number_format_i18n($info['balance'])) ?><br>

            <label><?= esc_html__('Address seniority', '99btc-bf') ?></label>:
            <?php if ($info['seniority_days'] == 1): ?>
                <?= sprintf(esc_html__('%s day', '99btc-bf'), number_format_i18n($info['seniority_days'])) ?><br>
            <?php else: ?>
                <?= sprintf(esc_html__('%s days', '99btc-bf'), number_format_i18n($info['seniority_days'])) ?><br>
            <?php endif ?>

            <label><?= esc_html__('Seniority bonus', '99btc-bf') ?></label>:
            <?= sprintf(esc_html__('%s%% on all direct payouts', '99btc-bf'), number_format_i18n($info['seniority_current_bonus'])) ?><br>

            <?php if ($info['seniority_days_to_next_level']): ?>
                <label><?= esc_html__('Time until next seniority level', '99btc-bf') ?></label>:
                <?php if ($info['seniority_days_to_next_level'] == 1): ?>
                    <?= sprintf(esc_html__('%s day', '99btc-bf'), number_format_i18n($info['seniority_days_to_next_level'])) ?><br>
                <?php else: ?>
                    <?= sprintf(esc_html__('%s days', '99btc-bf'), number_format_i18n($info['seniority_days_to_next_level'])) ?><br>
                <?php endif ?>
            <?php endif ?>

            <?php if (!empty($options['config']['submit_limitation'])): ?>
                <label><?= esc_html__('Submits per 24 hours', '99btc-bf') ?></label>: <?= $info['submits'] ?> / <?= esc_html($options['config']['submit_limitation']) ?><br>
            <?php endif ?>

            <?php if (!empty($info['invitees'])): ?>
                <label><?= esc_html__('Referred addresses', '99btc-bf') ?></label>: <?= number_format_i18n(count($info['invitees'])) ?><br>
            <?php endif ?>
        </div>
        <div class="row statistic">
            <table>
                <tr>
                    <td width="<?= $options['config']['refer_bonus'] ? 33 : 50 ?>%">
                        <div>
                            <strong><?= number_format_i18n($info['paid_total'] / $currency->size(), 8) ?></strong> <?= esc_html__($currency->symbol(), '99btc-bf') ?>
                        </div>
                        <div><?= esc_html__('Total paid', '99btc-bf') ?></div>
                    </td>
                    <td width="<?= $options['config']['refer_bonus'] ? 34 : 50 ?>%">
                        <div>
                            <strong><?= number_format_i18n($info['unpaid_direct'] / $currency->size(), 8) ?></strong> <?= esc_html__($currency->symbol(), '99btc-bf') ?>
                        </div>
                        <div><?= esc_html__('Total unpaid in direct payouts', '99btc-bf') ?></div>
                    </td>
                    <?php if ($options['config']['refer_bonus']): ?>
                    <td width="33%">
                        <div>
                        <strong><?= number_format_i18n($info['unpaid_referral'] / $currency->size(), 8) ?></strong> <?= esc_html__($currency->symbol(), '99btc-bf') ?>
                        </div>
                        <div><?= esc_html__('Total unpaid in referrals', '99btc-bf') ?></div>
                    </td>
                    <?php endif ?>
                </tr>
            </table>
        </div>
        <?php if ($info['transactions']): ?>
            <div class="row transacitons">
                <h4><?= esc_html__('Transaction history', '99btc-bf') ?></h4>
                <table>
                    <thead>
                    <tr>
                        <th><?= esc_html__('Transaction ID', '99btc-bf') ?></th>
                        <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                        <th><?= esc_html__('Date', '99btc-bf') ?></th>
                    </tr>
                    </thead>
                    <tbody class="transaction-table">
                    <?php foreach ($info['transactions'] as $transaction): ?>
                        <tr>
                            <td>
                                <?php if ($transaction['transaction'] == 'faucetbox'): ?>
                                    <small><a href="https://faucetbox.com/en/check/<?= urlencode($address) ?>" rel="nofollow" target="_blank" title="<?= esc_attr($address) ?>"><?= esc_html__('FaucetBOX', '99btc-bf') ?></a></small>
                                <?php elseif($transaction['transaction'] == 'faucetpay.io'): ?>
                                    <small><a href="https://faucetpay.io/balance/<?= urlencode($address) ?>" rel="nofollow" target="_blank" title="<?= esc_attr($address) ?>"><?= esc_html__('faucetpay.io', '99btc-bf') ?></a></small>
                                <?php elseif($transaction['transaction'] == 'epay.info'): ?>
                                    <small><a href="http://epay.info/dashboard/<?= urlencode($address) ?>/" rel="nofollow" target="_blank" title="<?= esc_attr($address) ?>"><?= esc_html__('epay.info', '99btc-bf') ?></a></small>
                                <?php else: ?>
                                    <small><a href="https://blockchain.info/tx/<?= urlencode($transaction['transaction']) ?>" rel="nofollow" target="_blank" title="<?= esc_attr($transaction['transaction']) ?>"><?= esc_html(substr($transaction['transaction'], 0, 30)) ?>...</a></small>
                                <?php endif ?>
                            </td>
                            <td><?= number_format_i18n($transaction['amount'] / 100000000, 8) ?> <?= esc_attr__($currency->symbol(), '99btc-bf') ?></td>
                            <td><?= date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $transaction['stamp']) ?></td>
                        </tr>
                    <?php endforeach ?>
                    <?php if (isset($transaction) && count($info['transactions']) >= 20): ?>
                        <tr>
                            <td colspan="3">
                                <a class="transaction-link" href="?the99btcbfaddress=<?= urlencode($address) ?>&amp;the99btcbfid=<?= urlencode($transaction['id']) ?>&amp;t99fid=<?= urlencode($this->config['post']->ID) ?>"><?= esc_html__('Load more', '99btc-bf') ?></a>
                            </td>
                        </tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
        <?php if ($info['payouts']): ?>
            <div class="row payouts">
                <h4><?= esc_html__('Payout history', '99btc-bf') ?></h4>
                <table>
                    <thead>
                    <tr>
                        <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                        <th><?= esc_html__('Date', '99btc-bf') ?></th>
                        <th><?= esc_html__('Type', '99btc-bf') ?></th>
                    </tr>
                    </thead>
                    <tbody class="history-table">
                    <?php foreach ($info['payouts'] as $payout): ?>
                        <tr>
                            <td><?= sprintf(esc_html__('%s ' . $currency->satoshi(), '99btc-bf'), number_format_i18n($payout['amount'])) ?></td>
                            <td data-stamp="<?= $payout['stamp'] ?>"><?= date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $payout['stamp']) ?></td>
                            <td>
                                <?php if ($payout['source'] == 'referral'): ?>
                                    <label class="referral"><?= esc_html__('Referral payout', '99btc-bf') ?></label>
                                <?php elseif ($payout['source'] == 'seniority'): ?>
                                    <label class="seniority"><?= esc_html__('Seniority payout', '99btc-bf') ?></label>
                                <?php elseif ($payout['source'] == 'bonus'): ?>
                                    <label class="bonus"><?= esc_html__('Bonus', '99btc-bf') ?></label>
                                <?php elseif ($payout['source'] == 'penalty'): ?>
                                    <label class="penalty"><?= esc_html__('Penalty', '99btc-bf') ?></label>
                                <?php else: ?>
                                    <label class="direct"><?= esc_html__('Direct payout', '99btc-bf') ?></label>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    <?php if (isset($payout) && count($info['payouts']) >= 20): ?>
                        <tr>
                            <td colspan="3">
                                <a class="history-link" href="?the99btcbfaddress=<?= urlencode($address) ?>&amp;the99btcbfid=<?= urlencode($payout['id']) ?>&amp;t99fid=<?= urlencode($this->config['post']->ID) ?>"><?= esc_html__('Load more', '99btc-bf') ?></a>
                            </td>
                        </tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
        <?php if ($info['invitees']): ?>
            <div class="row referrers">
                <h4><?= esc_html__('Addresses referred by you', '99btc-bf') ?></h4>
                <table>
                    <tbody>
                    <?php foreach ($info['invitees'] as $invitee): ?>
                        <tr>
                            <td><a href="<?= esc_attr(add_query_arg('the99btcbfaddress', $invitee, get_site_url(null, $options['config']['urls_check']))) ?>"><?= esc_html($invitee) ?></a></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
        <script type="text/javascript">
            var <?= esc_js($chartId) ?> = {
                type: 'line',
                data: {
                    labels: <?= json_encode($info['chart']['dates']) ?>,
                    datasets: [
                        {
                            label: '<?= esc_js(__('Form Submits', '99btc-bf')) ?>',

                            fill: true,
                            backgroundColor: "rgba(0, 0, 0, 0.1)",
                            borderWidth: 1,
                            borderColor: "black",

                            data: <?php echo json_encode($info['chart']['submits']) ?>
                        },
                        <?php if ($info['chart']['bonus'] && array_sum($info['chart']['bonus'])): ?>
                        {
                            label: '<?= esc_js(__('Bonus', '99btc-bf')) ?>',

                            fill: true,
                            backgroundColor: "rgba(204, 120, 50, 0.1)",
                            borderWidth: 1,
                            borderColor: "rgb(204, 120, 50)",

                            data: <?= json_encode($info['chart']['bonus']) ?>
                        },
                        <?php endif ?>
                        <?php if ($info['chart']['penalty'] && array_sum($info['chart']['penalty'])): ?>
                        {
                            label: '<?= esc_js(__('Penalty', '99btc-bf')) ?>',

                            fill: true,
                            backgroundColor: "rgba(255, 0, 0, 0.1)",
                            borderWidth: 1,
                            borderColor: "rgb(255, 0, 0)",

                            data: <?= json_encode($info['chart']['penalty']) ?>
                        },
                        <?php endif ?>
                        {
                            label: '<?= esc_js(__('Direct', '99btc-bf')) ?>',

                            fill: true,
                            backgroundColor: "rgba(194, 194, 194, 0.1)",
                            borderWidth: 1,
                            borderColor: "rgb(194, 194, 194)",

                            data: <?= json_encode($info['chart']['direct']) ?>
                        },
                        <?php if ($info['chart']['referral'] && array_sum($info['chart']['referral'])): ?>
                        {
                            label: '<?= esc_js(__('Referral', '99btc-bf')) ?>',

                            fill: true,
                            backgroundColor: "rgba(3, 15, 137, 0.1)",
                            borderWidth: 1,
                            borderColor: "rgb(3, 15, 137)",

                            data: <?= json_encode($info['chart']['referral']) ?>
                        },
                        <?php endif ?>
                        <?php if ($info['chart']['seniority'] && array_sum($info['chart']['seniority'])): ?>
                        {
                            label: '<?= esc_js(__('Seniority', '99btc-bf')) ?>',

                            fill: true,
                            backgroundColor: "rgba(9, 99, 11, 0.1)",
                            borderWidth: 1,
                            borderColor: "rgb(9, 99, 11)",

                            data: <?= json_encode($info['chart']['seniority']) ?>
                        },
                        <?php endif ?>
                        {
                            label: '<?= esc_js(__('Total', '99btc-bf')) ?>',

                            fill: true,
                            backgroundColor: "rgba(0, 0, 0, 0.1)",
                            borderWidth: 1,
                            borderColor: "rgb(0, 0, 0)",

                            data: <?= json_encode($info['chart']['total']) ?>
                        }
                    ]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                min: 0
                            }
                        }]
                    }
                }
            };
        </script>
    <?php elseif ($address && !$info): ?>
        <div class="row no-info">
            <strong><?= esc_html__('Address has been never tracked', '99btc-bf') ?></strong>
        </div>
    <?php endif ?>
</div>
