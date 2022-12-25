<?php
/**
 * @var The99Bitcoins_BtcFaucet_Plugin $this
 *
 * @var string $notice_message
 * @var string $notice_css_class
 *
 * @var string $search_ban_address
 * @var array $ban_addresses
 * @var string $search_ban_ip_from
 * @var string $search_ban_ip_to
 * @var array $ban_ips
 */
?>

    <?php if (!empty($notice_message)): ?>
        <div class="<?= $notice_css_class ?>">
            <p><?= $notice_message ?></p>
        </div>
    <?php endif ?>

    <div>
        <a href="" class="show-form" data-id="addresswhite"><?= esc_html__('Add bitcoin address to whitelist', '99btc-bf') ?></a>
        <form method="post" novalidate="novalidate" id="addresswhite" style="display: none">
            <h2><?= esc_html__('Add bitcoin address to whitelist', '99btc-bf') ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="addresswhite[address]"><?php esc_html_e('Bitcoin address', '99btc-bf') ?></label>
                    </th>
                    <td>
                        <input class="regular-text" type="text" name="addresswhite[address]" id="addresswhite[address]" value="" placeholder="<?= esc_html__('Bitcoin address', '99btc-bf') ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="addresswhite[reason]"><?php esc_html_e('Reason', '99btc-bf') ?></label></th>
                    <td>
                        <input class="regular-text" type="text" name="addresswhite[reason]" id="addresswhite[reason]" value="" placeholder="<?= esc_html__('Reason', '99btc-bf') ?>">
                    </td>
                </tr>
            </table>
            <?php submit_button(esc_attr__('Add bitcoin address')); ?>
        </form>
    </div>

    <div>
        <a href="" class="show-form" data-id="addressblack"><?= esc_html__('Add bitcoin address to blacklist', '99btc-bf') ?></a>
        <form method="post" novalidate="novalidate" id="addressblack" style="display: none">
            <h2><?= esc_html__('Add bitcoin address to blacklist', '9btc-bf') ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="addressblack[address]"><?php esc_html_e('Bitcoin address', '99btc-bf') ?></label>
                    </th>
                    <td>
                        <input class="regular-text" type="text" name="addressblack[address]" id="addressblack[address]" value="" placeholder="<?= esc_html__('Bitcoin address', '99btc-bf') ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="addressblack[reason]"><?php esc_html_e('Reason', '99btc-bf') ?></label>
                    </th>
                    <td>
                        <input class="regular-text" type="text" name="addressblack[reason]" id="addressblack[reason]" value="" placeholder="<?= esc_html__('Reason', '99btc-bf') ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="addressblack[recursive]"><?= esc_html__('Ban children addresses', '99btc-bf') ?></label>
                    </th>
                    <td colspan="2">
                        <input type="hidden" name="addressblack[recursive]" value="0">
                        <input type="checkbox" name="addressblack[recursive]" id="addressblack[recursive]" value="1">
                    </td>
                </tr>
            </table>
            <?php submit_button(esc_attr__('Add bitcoin address')); ?>
        </form>
    </div>

    <div>
        <a href="" class="show-form" data-id="ipblack"><?= esc_html__('Add ip address / network to blacklist', '9btc-bf') ?></a>
        <form method="post" novalidate="novalidate" id="ipblack" style="display: none">
            <h2><?= esc_html__('Add ip address / network to blacklist', '99btc-bf') ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="ipblack[from]"><?php esc_html_e('Enter first IP address', '99btc-bf') ?></label>
                    </th>
                    <td>
                        <input class="regular-text" type="text" name="ipblack[from]" id="ipblack[from]" value="" placeholder="<?= esc_html__('Enter first IP address', '99btc-bf') ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="ipblack[to]"><?php esc_html_e('Enter last IP address', '99btc-bf') ?></label>
                    </th>
                    <td>
                        <input class="regular-text" type="text" name="ipblack[to]" id="ipblack[to]" value="" placeholder="<?= esc_html__('Enter last IP address', '99btc-bf') ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="ipblack[reason]"><?php esc_html_e('Reason', '99btc-bf') ?></label></th>
                    <td>
                        <input class="regular-text" type="text" name="ipblack[reason]" id="ipblack[reason]" value="" placeholder="<?= esc_html__('Reason', '99btc-bf') ?>">
                    </td>
                </tr>
            </table>
            <?php submit_button(esc_attr__('Add ip address / network')); ?>
        </form>
    </div>

    <div>
        <a name="white_addresses"></a>
        <a href="" class="show-form" data-id="addresswhitelist"<?php if ($show == 'addresswhitelist'): ?> style="display: none"<?php endif ?>><?= esc_html__('Whitelisted bitcoin addresses', '99btc-bf') ?></a>
        <form method="post" id="addresswhitelist" action="#white_addresses"<?php if ($show != 'addresswhitelist'): ?> style="display: none"<?php endif ?>>
            <h2><?= esc_html__('Whitelisted bitcoin addresses', '99btc-bf') ?></h2>
            <input type="hidden" name="page" value="<?= esc_attr(!empty($_GET['page']) ? $_GET['page'] : '') ?>">
            <input type="hidden" name="mode" value="<?= esc_attr(!empty($_GET['mode']) ? $_GET['mode'] : '') ?>">
            <p class="search-box">
                <label class="screen-reader-text" for="user-search-input"><?= esc_html__('Search:', '99btc-bf') ?></label>
                <input type="text" name="search_white_address" value="<?= esc_attr($search_white_address) ?>" placeholder="<?= esc_attr__('Enter Bitcoin address', '99btc-bf') ?>">
                <input type="submit" class="button" value="<?= esc_attr__('Search', '99btc-bf') ?>">
            </p>
            <table class="wp-list-table widefat fixed striped users">
                <thead>
                <tr>
                    <th><?= esc_html__('Address', '99btc-bf') ?></th>
                    <th><?= esc_html__('Reason', '99btc-bf') ?></th>
                    <th><?= esc_html__('Date', '99btc-bf') ?></th>
                    <th><?= esc_html__('Action', '99btc-bf') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if ($white_addresses): ?>
                    <?php foreach ($white_addresses as $address): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($address['address']) ?></td>
                            <td><?php echo htmlspecialchars($address['reason']) ?></td>
                            <td>
                                <?php if ($address['stamp']): ?>
                                    <?= date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $address['stamp']) ?>
                                <?php else: ?>
                                    <?= esc_html__('No date', '99btc-bf') ?>
                                <?php endif ?>
                            </td>
                            <td>
                                <button class="btn btn-xs btn-danger" data-address="<?php echo htmlspecialchars($address['address']) ?>" data-button="whitelist"><?= esc_html__('Delete', '99btc-bf') ?></button>
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
                    <th><?= esc_html__('Reason', '99btc-bf') ?></th>
                    <th><?= esc_html__('Date', '99btc-bf') ?></th>
                    <th><?= esc_html__('Action', '99btc-bf') ?></th>
                </tr>
                </tfoot>
            </table>
            <input type="hidden" name="search_white_address_delete" value="">
            <input type="hidden" name="show" value="addresswhitelist">
        </form>
    </div>

    <div>
        <a name="black_addresses"></a>
        <a href="" class="show-form" data-id="addressblacklist"<?php if ($show == 'addressblacklist'): ?> style="display: none"<?php endif ?>><?= esc_html__('Blacklisted bitcoin addresses', '99btc-bf') ?></a>
        <form method="post" id="addressblacklist" action="#black_addresses"<?php if ($show != 'addressblacklist'): ?> style="display: none"<?php endif ?>>
            <h2><?= esc_html__('Blacklisted bitcoin addresses', '99btc-bf') ?></h2>
            <input type="hidden" name="page" value="<?= esc_attr(!empty($_GET['page']) ? $_GET['page'] : '') ?>">
            <input type="hidden" name="mode" value="<?= esc_attr(!empty($_GET['mode']) ? $_GET['mode'] : '') ?>">
            <p class="search-box">
                <label class="screen-reader-text" for="user-search-input"><?= esc_html__('Search:', '99btc-bf') ?></label>
                <input type="text" name="search_ban_address" value="<?= esc_attr($search_ban_address) ?>" placeholder="<?= esc_attr__('Enter Bitcoin address', '99btc-bf') ?>">
                <input type="submit" class="button" value="<?= esc_attr__('Search', '99btc-bf') ?>">
            </p>
            <table class="wp-list-table widefat fixed striped users">
                <thead>
                <tr>
                    <th><?= esc_html__('Address', '99btc-bf') ?></th>
                    <th><?= esc_html__('Reason', '99btc-bf') ?></th>
                    <th><?= esc_html__('Date', '99btc-bf') ?></th>
                    <th><?= esc_html__('Action', '99btc-bf') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if ($ban_addresses): ?>
                    <?php foreach ($ban_addresses as $address): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($address['address']) ?></td>
                            <td><?php echo htmlspecialchars($address['reason']) ?></td>
                            <td>
                                <?php if ($address['stamp']): ?>
                                    <?= date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $address['stamp']) ?>
                                <?php else: ?>
                                    <?= esc_html__('No date', '99btc-bf') ?>
                                <?php endif ?>
                            </td>
                            <td>
                                <button class="btn btn-xs btn-danger" data-address="<?php echo htmlspecialchars($address['address']) ?>" data-button="address"><?= esc_html__('Unban', '99btc-bf') ?></button>
                                <button class="btn btn-xs btn-danger" data-address="<?php echo htmlspecialchars($address['address']) ?>" data-button="address" data-style="all"><?= esc_html__('Unban all', '99btc-bf') ?></button>
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
                    <th><?= esc_html__('Reason', '99btc-bf') ?></th>
                    <th><?= esc_html__('Date', '99btc-bf') ?></th>
                    <th><?= esc_html__('Action', '99btc-bf') ?></th>
                </tr>
                </tfoot>
            </table>
            <input type="hidden" name="search_ban_address_unban" value="">
            <input type="hidden" name="search_ban_address_style" value="">
            <input type="hidden" name="show" value="addressblacklist">
        </form>
    </div>

    <div>
        <a name="ban_ips"></a>
        <a href="" class="show-form" data-id="ipblacklist"<?php if ($show == 'ipblacklist'): ?> style="display: none"<?php endif ?>><?= esc_html__('Blacklisted ip addresses', '99btc-bf') ?></a>
        <form method="post" id="ipblacklist" action="#ban_ips"<?php if ($show != 'ipblacklist'): ?> style="display: none"<?php endif ?>>
            <h2><?= esc_html__('Blacklisted ip addresses', '99btc-bf') ?></h2>
            <input type="hidden" name="page" value="<?= esc_attr(!empty($_GET['page']) ? $_GET['page'] : '') ?>">
            <input type="hidden" name="mode" value="<?= esc_attr(!empty($_GET['mode']) ? $_GET['mode'] : '') ?>">
            <p class="search-box">
                <label class="screen-reader-text" for="user-search-input"><?= esc_html__('Search:', '99btc-bf') ?></label>
                <input type="text" name="search_ban_ip_from" value="<?= esc_attr($search_ban_ip_from) ?>" placeholder="<?= esc_attr__('Enter IP address', '99btc-bf') ?>">
                <input type="text" name="search_ban_ip_to" value="<?= esc_attr($search_ban_ip_to) ?>" placeholder="<?= esc_attr__('Enter IP address', '99btc-bf') ?>">
                <input type="submit" class="button" value="<?= esc_attr__('Search', '99btc-bf') ?>">
            </p>
            <table class="wp-list-table widefat fixed striped users">
                <thead>
                <tr>
                    <th><?= esc_html__('First ip address', '99btc-bf') ?></th>
                    <th><?= esc_html__('Last ip address', '99btc-bf') ?></th>
                    <th><?= esc_html__('Reason', '99btc-bf') ?></th>
                    <th><?= esc_html__('Date', '99btc-bf') ?></th>
                    <th><?= esc_html__('Action', '99btc-bf') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if ($ban_ips): ?>
                    <?php foreach ($ban_ips as $ip): ?>
                        <tr>
                            <td><a href="http://ipinfo.io/<?php echo inet_ntop($ip['ip']) ?>"><?php echo inet_ntop($ip['ip']) ?></a></td>
                            <td><a href="http://ipinfo.io/<?php echo inet_ntop($ip['ip_to']) ?>"><?php echo inet_ntop($ip['ip_to']) ?></a></td>
                            <td><?php echo htmlspecialchars($ip['reason']) ?></td>
                            <td>
                                <?php if ($ip['stamp']): ?>
                                    <?= date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $ip['stamp']) ?>
                                <?php else: ?>
                                    <?= esc_html__('No date', '99btc-bf') ?>
                                <?php endif ?>
                            </td>
                            <td>
                                <button class="btn btn-xs btn-danger" data-ip="<?= inet_ntop($ip['ip']) ?>" data-button="ip"><?= esc_html__('Unban', '99btc-bf') ?></button>
                                <button class="btn btn-xs btn-danger" data-ip="<?= inet_ntop($ip['ip']) ?>" data-button="ip" data-style="all"><?= esc_html__('Unban all', '99btc-bf') ?></button>
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
                    <th><?= esc_html__('First ip address', '99btc-bf') ?></th>
                    <th><?= esc_html__('Last ip address', '99btc-bf') ?></th>
                    <th><?= esc_html__('Reason', '99btc-bf') ?></th>
                    <th><?= esc_html__('Date', '99btc-bf') ?></th>
                    <th><?= esc_html__('Action', '99btc-bf') ?></th>
                </tr>
                </tfoot>
            </table>
            <input type="hidden" name="search_ban_ip_unban" value="">
            <input type="hidden" name="search_ban_ip_style" value="">
            <input type="hidden" name="show" value="ipblacklist">
        </form>
    </div>

</div>
<script>
    jQuery(document).ready(function($) {
        $('a.show-form').click(function(event) {
            var element = $(event.target);
            element.hide();
            $('#' + element.attr('data-id')).show();
            return false;
        });
        jQuery(document).on('click', 'button[data-button=whitelist]', function(event) {
            var button = jQuery(event.target);
            if (confirm('<?= esc_js(__('Are you sure?', '99btc-bf')) ?>')) {
                jQuery('input[name=search_white_address_delete]', '#addresswhitelist').val(button.attr('data-address'));
                jQuery('#addresswhitelist').submit();
            }
            return false;
        });
        jQuery(document).on('click', 'button[data-button=address]', function(event) {
            var button = jQuery(event.target);
            if (confirm('<?= esc_js(__('Are you sure?', '99btc-bf')) ?>')) {
                jQuery('input[name=search_ban_address_unban]', '#addressblacklist').val(button.attr('data-address'));
                jQuery('input[name=search_ban_address_style]', '#addressblacklist').val(button.attr('data-style'));
                jQuery('#addressblacklist').submit();
            }
            return false;
        });
        jQuery(document).on('click', 'button[data-button=ip]', function(event) {
            var button = jQuery(event.target);
            if (confirm('<?= esc_js(__('Are you sure?', '99btc-bf')) ?>')) {
                jQuery('input[name=search_ban_ip_unban]', '#ipblacklist').val(button.attr('data-ip'));
                jQuery('input[name=search_ban_ip_style]', '#ipblacklist').val(button.attr('data-style'));
                jQuery('#ipblacklist').submit();
            }
            return false;
        });
    });
</script>
