<?php
/**
 * @var array $claim_rules
 */
?>
<form method="post" novalidate="novalidate" id="USDPPC" style="display: none">
    <input type="hidden" name="config[rule_set]" value="USDPPC">
    <input type="hidden" name="claim_rules[USDPPC][currency]" value="USDPPC">
    <h2><?php esc_attr_e('Common', '99btc-bf') ?></h2>
    <div><?= esc_html__('Visitors will be claiming Satoshi based on payout rules below and current USD/PPC exchange rate', '99btc-bf') ?></div>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="claim_rules[USDPPC][threshold]"><?= esc_html__('Set payment threshold (in Satoshi)', '99btc-bf') ?></label></th>
            <td>
                <input class="regular-text" type="number" name="claim_rules[USDPPC][threshold]" id="claim_rules[USDPPC][threshold]" value="<?= esc_attr($claim_rules['USDPPC']['payout_threshold']) ?>" placeholder="<?= esc_html__('Set payment threshold (in Satoshi)', '99btc-bf') ?>" pattern="\d*" step="0.00001">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="claim_rules[USDPPC][exchange_rate]"><?= esc_html__('Exchange rate 1 PPC = USD', '99btc-bf') ?></label></th>
            <td>
                <input class="regular-text" type="number" name="claim_rules[USDPPC][exchange_rate]" id="claim_rules[USDPPC][exchange_rate]" value="<?= esc_attr($claim_rules['USDPPC']['exchange_rate']) ?>" placeholder="<?= esc_html__('US Dollars', '99btc-bf') ?>" pattern="\d*" step=".01">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="claim_rules[USDPPC][exchange_rate_auto]"><?= esc_html__('Update exchange rate from 99bitcoins.com', '99btc-bf') ?></label></th>
            <td>
                <?php if (empty($optout)): ?>
                    <input type="hidden" name="claim_rules[USDPPC][exchange_rate_auto]" id="claim_rules[USDPPC][exchange_rate_auto]" value="0">
                <?php endif ?>
                <input type="checkbox" name="claim_rules[USDPPC][exchange_rate_auto]" id="claim_rules[USDPPC][exchange_rate_auto]" value="1"<?php if (!empty($claim_rules['USDPPC']['exchange_rate_auto'])): ?> checked="checked"<?php endif ?><?php if (!empty($optout)): ?> disabled="disabled"<?php endif ?>>
                <?php if (!empty($optout)): ?><small><?= esc_html__('this option is available only with plugin support', '99btc-bf') ?></small><?php endif ?>
            </td>
        </tr>
    </table>

    <h2><?php esc_attr_e('Payout', '99btc-bf') ?></h2>
    <table>
        <thead>
        <tr>
            <th style="width: 50%;">
                <h4><?= esc_html__('Payout (in Dollars)', '99btc-bf') ?></h4>
            </th>
            <th style="width: 50%;" colspan="2">
                <h4><?= esc_html__('Odds of winning', '99btc-bf') ?></h4>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr class="prototype" data-count="<?php echo count($claim_rules['USDPPC']['rules']) ?>" style="display: none">
            <td>
                <input class="short-text" type="number" name="claim_rules[USDPPC][rules][__counter__][amount_min]" placeholder="<?php esc_attr_e('Dollars', '99btc-bf') ?>" pattern="\d*" step="0.00001">
            </td>
            <td>
                <input class="short-text" type="number" name="claim_rules[USDPPC][rules][__counter__][probability]" placeholder="<?php esc_attr_e('Probability', '99btc-bf') ?>" pattern="\d*" step="0.00001">
            </td>
            <td>
                <button type="button" class="btn-remove-row" data-click-confirm="<?= esc_attr__('Are you sure?', '99btc-bf') ?>">&times;</button>
            </td>
        </tr>
        <?php foreach ($claim_rules['USDPPC']['rules'] as $k => $rule): ?>
            <tr>
                <td>
                    <input class="short-text" type="number" name="claim_rules[USDPPC][rules][<?php echo $k ?>][amount_min]" value="<?php echo htmlspecialchars($rule['amount_min']) ?>" placeholder="<?php esc_attr_e('Dollars', '99btc-bf') ?>" pattern="\d*" step="0.00001">
                </td>
                <td>
                    <input class="short-text" type="number" name="claim_rules[USDPPC][rules][<?php echo $k ?>][probability]" value="<?php echo htmlspecialchars($rule['probability']) ?>" placeholder="<?php esc_attr_e('Probability', '99btc-bf') ?>" pattern="\d*" step="0.00001">
                </td>
                <td>
                    <button type="button" class="btn-remove-row" data-click-confirm="<?= esc_attr__('Are you sure?', '99btc-bf') ?>">&times;</button>
                </td>
            </tr>
        <?php endforeach ?>
        <tr>
            <td>
                <input class="short-text" type="number" name="claim_rules[USDPPC][rules][<?php echo count($claim_rules['USDPPC']['rules']) ?>][amount_min]" placeholder="<?php esc_attr_e('Dollars', '99btc-bf') ?>" pattern="\d*" step="0.00001">
            </td>
            <td>
                <input class="short-text" type="number" name="claim_rules[USDPPC][rules][<?php echo count($claim_rules['USDPPC']['rules']) ?>][probability]" placeholder="<?php esc_attr_e('Probability', '99btc-bf') ?>" pattern="\d*" step="0.00001">
            </td>
            <td>
                <button type="button" class="btn-remove-row" data-click-confirm="<?= esc_attr__('Are you sure?', '99btc-bf') ?>">&times;</button>
            </td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3">
                <button type="button" class="btn-prototype"><?= esc_html__('Add row', '99btc-bf') ?></button>
            </td>
        </tr>
        </tfoot>
    </table>

    <p>
        <?php submit_button(__('Save changes', '99btc-bf'), 'primary', 'ignore_rule_set', false) ?>
        <?php submit_button(__('Select this rule and save changes', '99btc-bf'), '', '', false) ?>
    </p>
</form>
