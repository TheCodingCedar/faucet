<?php
/**
 * @var The99Bitcoins_BtcFaucet_Plugin $this
 * @var The99Bitcoins_BtcFaucet_Currency_Base $currency
 *
 * @var string $notice_message
 * @var string $notice_css_class
 */
?>

    <?php if (!empty($notice_message)): ?>
        <div class="<?= $notice_css_class ?>">
            <p><?= $notice_message ?></p>
        </div>
    <?php endif ?>

    <form method="post" novalidate="novalidate">
        <h2><?= esc_html__('Reset seniority', '99btc-bf') ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="reset_seniority[address]"><?= esc_html__('Enter Bitcoin address', '99btc-bf') ?></label>
                </th>
                <td>
                    <input class="regular-text" type="text" name="reset_seniority[address]" id="reset_seniority[address]" value="" placeholder="<?= esc_attr__('Bitcoin address', '99btc-bf') ?>">
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <form method="post" novalidate="novalidate">
        <h2><?= esc_html__('Move balance', '99btc-bf') ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="move_balance[from]"><?= esc_html__('From address', '99btc-bf') ?></label>
                </th>
                <td>
                    <input class="regular-text" type="text" name="move_balance[from]" id="move_balance[from]" value="" placeholder="<?= esc_attr__('Bitcoin address', '99btc-bf') ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="move_balance[to]"><?= esc_html__('To address', '99btc-bf') ?></label></th>
                <td>
                    <input class="regular-text" type="text" name="move_balance[to]" id="move_balance[to]" value="" placeholder="<?= esc_attr__('Bitcoin address', '99btc-bf') ?>">
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <form method="post" novalidate="novalidate">
        <h2><?= esc_html__('Add bonus / penalty', '99btc-bf') ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="balance[address]"><?= esc_html__('Address', '99btc-bf') ?></label>
                </th>
                <td>
                    <input class="regular-text" type="text" name="balance[address]" id="balance[address]" value="" placeholder="<?= esc_attr__('Bitcoin address', '99btc-bf') ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="balance[amount]"><?= esc_html__('Amount of ' . $currency->satoshi(), '99btc-bf') ?></label><br>
                    <small>(<?= esc_html__('use minus symbol for penalty', '99btc-bf') ?>)</small>
                </th>
                <td>
                    <input class="regular-text" type="text" name="balance[amount]" id="balance[amount]" value="" placeholder="<?= esc_attr__('Amount of ' . $currency->satoshi(), '99btc-bf') ?>">
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <form method="post" novalidate="novalidate">
        <h2><?= esc_html__('Cron', '99btc-bf') ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="cron_install"><?= esc_html__('Reinstall cron schedule', '99btc-bf') ?></label>
                </th>
                <td>
                    <input type="checkbox" name="cron_install" id="cron_install" value="1">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cron_run"><?= esc_html__('Run cron manually', '99btc-bf') ?></label>
                </th>
                <td>
                    <input type="checkbox" name="cron_run" id="cron_run" value="1">
                </td>
            </tr>
        </table>
        <?php submit_button(__('Submit', '99btc-bf')) ?>
    </form>

    <form method="post" novalidate="novalidate" data-id="reset" data-confirm="<?= esc_attr__('All data about faucet, addresses, payments etc will be removed', '99btc-bf') ?>">
        <h2><?= esc_html__('Reset', '99btc-bf') ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="reset"><?= esc_html__('Delete all faucet data', '99btc-bf') ?></label>
                </th>
                <td>
                    <input type="checkbox" name="reset" id="reset" value="1">
                </td>
            </tr>
        </table>
        <?php submit_button(__('Reset', '99btc-bf'), 'primary', 'submit', true, array(
            'disabled' => true,
        )); ?>
    </form>
</div>
