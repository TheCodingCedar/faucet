<?php
/**
 * @var The99Bitcoins_BtcFaucet_Plugin $this
 * @var array $top_claim_referral
 * @var array $top_claim_direct
 * @var array $top_submit_referral
 * @var array $top_submit_direct
 * @var array $daily_claims
 * @var array $hourly_cliaims
 * @var array $options
 * @var array $top_success_neworks
 * @var array $top_success_neworks_ips
 * @var int $top_success_neworks_5
 * @var int $top_success_neworks_total
 */
?>

    <table width="100%">
        <tr>
            <td width="50%">
                <h2><?= esc_html__('Top 20 claims of referrers in 24 hours', '99btc-bf') ?></h2>
                <table class="wp-list-table widefat fixed striped users">
                    <thead>
                    <tr>
                        <th><?= esc_html__('Address', '99btc-bf') ?></th>
                        <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($top_claim_referral): ?>
                        <?php foreach ($top_claim_referral as $k => $v): ?>
                            <tr>
                                <td><a href="<?= esc_attr(add_query_arg('the99btcbfaddress', $k, get_site_url(null, $options['config']['urls_check']))) ?>" target="_blank"><?= esc_html($k) ?></a></td>
                                <td><?= number_format_i18n($v, 0) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">
                                <?= esc_html__('No data', '99btc-bf') ?>
                            </td>
                        </tr>
                    <?php endif ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th><?= esc_html__('Address', '99btc-bf') ?></th>
                        <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                    </tr>
                    </tfoot>
                </table>
            </td>
            <td width="50%">
                <h2><?= esc_html__('Top 20 claims of directs in 24 hours', '99btc-bf') ?></h2>
                <table class="wp-list-table widefat fixed striped users">
                    <thead>
                    <tr>
                        <th><?= esc_html__('Address', '99btc-bf') ?></th>
                        <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($top_claim_direct): ?>
                        <?php foreach ($top_claim_direct as $k => $v): ?>
                            <tr>
                                <td><a href="<?= esc_attr(add_query_arg('the99btcbfaddress', $k, get_site_url(null, $options['config']['urls_check']))) ?>" target="_blank"><?= esc_html($k) ?></a></td>
                                <td><?= number_format_i18n($v, 0) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">
                                <?= esc_html__('No data', '99btc-bf') ?>
                            </td>
                        </tr>
                    <?php endif ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th><?= esc_html__('Address', '99btc-bf') ?></th>
                        <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                    </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <h2><?= esc_html__('Top 20 submits of referrers in 24 hours', '99btc-bf') ?></h2>
                <table class="wp-list-table widefat fixed striped users">
                    <thead>
                    <tr>
                        <th><?= esc_html__('Address', '99btc-bf') ?></th>
                        <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($top_submit_referral): ?>
                        <?php foreach ($top_submit_referral as $k => $v): ?>
                            <tr>
                                <td><a href="<?= esc_attr(add_query_arg('the99btcbfaddress', $k, get_site_url(null, $options['config']['urls_check']))) ?>" target="_blank"><?= esc_html($k) ?></a></td>
                                <td><?= number_format_i18n($v, 0) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">
                                <?= esc_html__('No data', '99btc-bf') ?>
                            </td>
                        </tr>
                    <?php endif ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th><?= esc_html__('Address', '99btc-bf') ?></th>
                        <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                    </tr>
                    </tfoot>
                </table>
            </td>
            <td>
                <h2><?= esc_html__('Top 20 submits of directs in 24 hours', '99btc-bf') ?></h2>
                <table class="wp-list-table widefat fixed striped users">
                    <thead>
                    <tr>
                        <th><?= esc_html__('Address', '99btc-bf') ?></th>
                        <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($top_submit_direct): ?>
                        <?php foreach ($top_submit_direct as $k => $v): ?>
                            <tr>
                                <td><a href="<?= esc_attr(add_query_arg('the99btcbfaddress', $k, get_site_url(null, $options['config']['urls_check']))) ?>" target="_blank"><?= esc_html($k) ?></a></td>
                                <td><?= number_format_i18n($v, 0) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">
                                <?= esc_html__('No data', '99btc-bf') ?>
                            </td>
                        </tr>
                    <?php endif ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th><?= esc_html__('Address', '99btc-bf') ?></th>
                        <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                    </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <h2><?= esc_html__('Past 24 days', '99btc-bf') ?></h2>
                <table class="wp-list-table widefat fixed striped users">
                    <thead>
                    <tr>
                        <th><?= esc_html__('Date', '99btc-bf') ?></th>
                        <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($daily_claims): ?>
                        <?php foreach ($daily_claims as $k => $v): ?>
                            <tr>
                                <td><?= date_i18n(get_option('date_format'), $k) ?></td>
                                <td><?= number_format_i18n($v, 0) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">
                                <?= esc_html__('No data', '99btc-bf') ?>
                            </td>
                        </tr>
                    <?php endif ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th><?= esc_html__('Date', '99btc-bf') ?></th>
                        <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                    </tr>
                    </tfoot>
                </table>
            </td>
            <td>
                <h2><?= esc_html__('Past 24 hours', '99btc-bf') ?></h2>
                <table class="wp-list-table widefat fixed striped users">
                    <thead>
                    <tr>
                        <th><?= esc_html__('Date', '99btc-bf') ?></th>
                        <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($hourly_cliaims): ?>
                        <?php foreach ($hourly_cliaims as $k => $v): ?>
                            <tr>
                                <td><?= date_i18n(get_option('time_format'), $k) ?></td>
                                <td><?= number_format_i18n($v, 0) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">
                                <?= esc_html__('No data', '99btc-bf') ?>
                            </td>
                        </tr>
                    <?php endif ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th><?= esc_html__('Date', '99btc-bf') ?></th>
                        <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                    </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h2><?= sprintf(esc_html__('Top ip networks %s of %s', '99btc-bf'), number_format_i18n($top_success_neworks_5), number_format_i18n($top_success_neworks_total)) ?></h2>
                <table class="wp-list-table widefat fixed striped users">
                    <thead>
                    <tr>
                        <th><?= esc_html__('Ip', '99btc-bf') ?></th>
                        <th><?= esc_html__('Addresses', '99btc-bf') ?></th>
                        <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($top_success_neworks): ?>
                        <?php foreach ($top_success_neworks as $k => $v): ?>
                            <tr>
                                <td>
                                    <a href="http://ipinfo.io/<?= urlencode($k) ?>" target="_blank" rel="nofollow"><?= esc_html($k) ?></a>
                                    <?php if (1 || $top_success_neworks_ips[$k][0] != $top_success_neworks_ips[$k][1]): ?>
                                    <small>(<a href="http://ipinfo.io/<?= urlencode($top_success_neworks_ips[$k][0]) ?>" target="_blank" rel="nofollow"><?= esc_html($top_success_neworks_ips[$k][0]) ?></a> - <a href="http://ipinfo.io/<?= urlencode($top_success_neworks_ips[$k][1]) ?>" target="_blank" rel="nofollow"><?= esc_html($top_success_neworks_ips[$k][1]) ?></a>)</small>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <a class="show-address" style="cursor: pointer;" data-ip="<?= $k ?>"><?= esc_html__('show addresses', '99btc-bf') ?></a>
                                    <div data-ip="<?= $k ?>" style="display: none">
                                        <?php foreach (array_chunk($v, ceil(count($v) / 3), true) as $addresses): ?>
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                                                <?php foreach ($addresses as $address => $amount): ?>
                                                    <a href="<?= esc_attr(add_query_arg('the99btcbfaddress', $address, get_site_url(null, $options['config']['urls_check']))) ?>" target="_blank"><?= esc_html($address) ?></a>
                                                <?php endforeach ?>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </td>
                                <td><?= number_format_i18n(count($v)) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">
                                <?= esc_html__('No data', '99btc-bf') ?>
                            </td>
                        </tr>
                    <?php endif ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th><?= esc_html__('Ip', '99btc-bf') ?></th>
                        <th><?= esc_html__('Addresses', '99btc-bf') ?></th>
                        <th><?= esc_html__('Amount', '99btc-bf') ?></th>
                    </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
    </table>
</div>
<script>
    jQuery(document).ready(function($) {
        $('a.show-address').click(function(event) {
            var target = $(event.target);
            $('a[data-ip="' + target.attr('data-ip') + '"]').hide();
            $('div[data-ip="' + target.attr('data-ip') + '"]').show();
        });
    });
</script>
