<?php
/**
 * @var array $claim_rules
 */
?>
<form method="post" novalidate="novalidate" id="DASH" style="display: none">
    <input type="hidden" name="config[rule_set]" value="DASH">
    <input type="hidden" name="claim_rules[DASH][currency]" value="DASH">
    <h2><?php esc_attr_e('Common', '99btc-bf') ?></h2>
    <div><?= esc_html__('Visitors will be claiming Dash Satoshi based on payout rules below', '99btc-bf') ?></div>
    <div><?= esc_html__('1 Dash Satoshi = 0.00000001 DASH', '99btc-bf') ?></div>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="claim_rules[DASH][threshold]"><?= esc_html__('Set payment threshold (in Dash Satoshi)', '99btc-bf') ?></label></th>
            <td>
                <input class="regular-text" type="number" name="claim_rules[DASH][threshold]" id="claim_rules[DASH][threshold]" value="<?= esc_attr($claim_rules['DASH']['threshold']) ?>" placeholder="<?= esc_html__('Set payment threshold (in Dash Satoshi)', '99btc-bf') ?>" pattern="\d*" step="1">
            </td>
        </tr>
    </table>

    <h2><?php esc_attr_e('Payout', '99btc-bf') ?></h2>
    <table>
        <thead>
        <tr>
            <th style="width: 50%;">
                <h4><?= esc_html__('Payout (in Dash Satoshi)', '99btc-bf') ?></h4>
            </th>
            <th style="width: 50%;" colspan="2">
                <h4><?= esc_html__('Odds of winning', '99btc-bf') ?></h4>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr class="prototype" data-count="<?php echo count($claim_rules['DASH']['rules']) ?>" style="display: none">
            <td>
                <input class="short-text" type="number" name="claim_rules[DASH][rules][__counter__][amount_min]" placeholder="<?php esc_attr_e('Dash Satoshi', '99btc-bf') ?>" pattern="\d*" step="1">
            </td>
            <td>
                <input class="short-text" type="number" name="claim_rules[DASH][rules][__counter__][probability]" placeholder="<?php esc_attr_e('Probability', '99btc-bf') ?>" pattern="\d*" step="0.00001">
            </td>
            <td>
                <button type="button" class="btn-remove-row" data-click-confirm="<?= esc_attr__('Are you sure?', '99btc-bf') ?>">&times;</button>
            </td>
        </tr>
        <?php foreach ($claim_rules['DASH']['rules'] as $k => $rule): ?>
            <tr>
                <td>
                    <input class="short-text" type="number" name="claim_rules[DASH][rules][<?php echo $k ?>][amount_min]" value="<?php echo htmlspecialchars($rule['amount_min']) ?>" placeholder="<?php esc_attr_e('Dash Satoshi', '99btc-bf') ?>" pattern="\d*" step="1">
                </td>
                <td>
                    <input class="short-text" type="number" name="claim_rules[DASH][rules][<?php echo $k ?>][probability]" value="<?php echo htmlspecialchars($rule['probability']) ?>" placeholder="<?php esc_attr_e('Probability', '99btc-bf') ?>" pattern="\d*" step="0.00001">
                </td>
                <td>
                    <button type="button" class="btn-remove-row" data-click-confirm="<?= esc_attr__('Are you sure?', '99btc-bf') ?>">&times;</button>
                </td>
            </tr>
        <?php endforeach ?>
        <tr>
            <td>
                <input class="short-text" type="number" name="claim_rules[DASH][rules][<?php echo count($claim_rules['DASH']['rules']) ?>][amount_min]" placeholder="<?php esc_attr_e('Dash Satoshi', '99btc-bf') ?>" pattern="\d*" step="1">
            </td>
            <td>
                <input class="short-text" type="number" name="claim_rules[DASH][rules][<?php echo count($claim_rules['DASH']['rules']) ?>][probability]" placeholder="<?php esc_attr_e('Probability', '99btc-bf') ?>" pattern="\d*" step="0.00001">
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
