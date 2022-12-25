<?php
/**
 * @var The99Bitcoins_BtcFaucet_Plugin $this
 * @var string $notice_css_class
 * @var string $notice_message
 */
?>


    <?php if (!empty($notice_message)): ?>
        <div class="<?= $notice_css_class ?>">
            <p><?= $notice_message ?></p>
        </div>
    <?php endif ?>

    <h2><?= esc_html__('Support fee', '99btc-bf') ?></h2>
    <form method="post" novalidate="novalidate"<?php if (empty($options['optout'])): ?> data-confirm="<?= esc_attr__('If you opt out of plugin support you will not be eligible for automatic future plugin updates or any support requests', '99btc-bf') ?>"<?php endif ?>>
        <p style="max-width: 450px">
            There's a fee of 0% (it was removed) from your total payouts paid to the plugin author in order to help maintain and support the plugin.
            You can opt out of paying this fee but will then not be eligible for automatic future plugin updates or any support requests.
            In order to opt out go to the support tab.
        </p>
        <?php if (empty($options['optout'])): ?>
            <?php submit_button(__('Opt out of plugin support', '99btc-bf'), 'primary', 'optout'); ?>
        <?php else: ?>
            <?php submit_button(__('Enable plugin support', '99btc-bf'), 'primary', 'optout'); ?>
        <?php endif ?>
        <input type="hidden" name="action" value="optout">
    </form>
    <br>
    <h2><?= esc_html__('Support request', '99btc-bf') ?></h2>
    <form method="post" novalidate="novalidate">
        <?php if (empty($options['optout'])): ?>
            <p style="max-width: 450px">Please make sure to supply your WP admin login details and FTP if possible in order to expedite the support process.</p>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="message"><?= esc_html__('Describe issue', '99btc-bf') ?></label>
                    </th>
                    <td>
                        <textarea name="message" id="message"></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="diagnostic"><?= esc_html__('Include diagnostic information', '99btc-bf') ?></label>
                    </th>
                    <td>
                        <input type="hidden" name="diagnostic" value="0">
                        <input type="checkbox" name="diagnostic" id="diagnostic" value="1">
                    </td>
                </tr>
            </table>
            <?php submit_button(__('Send support ticket', '99btc-bf')); ?>
            <input type="hidden" name="action" value="send">
        <?php else: ?>
            <div><?= esc_html__('Enable plugin support to submit support requests', '99btc-bf') ?></div>
        <?php endif ?>
    </form>
</div>
