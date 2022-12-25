<div class="the99tab" id="general-seniority" style="display: none">
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="config[reset_seniority]"><?= esc_html__('Reset seniority after days', '99btc-bf') ?></label></th>
            <td>
                <input class="regular-text" type="number" name="config[reset_seniority]" id="config[reset_seniority]" value="<?= esc_attr($config['reset_seniority']) ?>" placeholder="<?= esc_html__('Days', '99btc-bf') ?>" pattern="\d*" step="1">
            </td>
        </tr>
    </table>
    <table>
        <thead>
        <tr>
            <th style="width: 50%;"><h4><?= esc_html__('Seniority (in days)', '99btc-bf') ?></h4></th>
            <th style="width: 50%;" colspan="2"><h4><?= esc_html__('Payout bonus (in percent)', '99btc-bf') ?></h4></th>
        </tr>
        </thead>
        <tbody>
        <tr class="prototype" data-count="<?php echo count($seniority_rules) ?>" style="display: none">
            <td>
                <input class="short-text" type="number" name="seniority_rules[__counter__][day]" placeholder="<?php esc_attr_e('Days', '99btc-bf') ?>" pattern="\d*" step="1">
            </td>
            <td>
                <input class="short-text" type="number" name="seniority_rules[__counter__][bonus]" placeholder="<?php esc_attr_e('Percent', '99btc-bf') ?>" pattern="\d*" step="1">
            </td>
            <td>
                <button type="button" class="btn-remove-row" data-click-confirm="<?= esc_attr__('Are you sure?', '99btc-bf') ?>">&times;</button>
            </td>
        </tr>
        <?php foreach ($seniority_rules as $k => $rule): ?>
            <tr>
                <td>
                    <input class="short-text" type="number" name="seniority_rules[<?php echo $k ?>][day]" value="<?php echo htmlspecialchars($rule['day']) ?>" placeholder="<?php esc_attr_e('Days', '99btc-bf') ?>" pattern="\d*" step="1">
                </td>
                <td>
                    <input class="short-text" type="number" name="seniority_rules[<?php echo $k ?>][bonus]" value="<?php echo htmlspecialchars($rule['bonus']) ?>" placeholder="<?php esc_attr_e('Percent', '99btc-bf') ?>" pattern="\d*" step="1">
                </td>
                <td>
                    <button type="button" class="btn-remove-row" data-click-confirm="<?= esc_attr__('Are you sure?', '99btc-bf') ?>">&times;</button>
                </td>
            </tr>
        <?php endforeach ?>
        <tr>
            <td>
                <input class="short-text" type="number" name="seniority_rules[<?php echo count($seniority_rules) ?>][day]" placeholder="<?php esc_attr_e('Days', '99btc-bf') ?>" pattern="\d*" step="1">
            </td>
            <td>
                <input class="short-text" type="number" name="seniority_rules[<?php echo count($seniority_rules) ?>][bonus]" placeholder="<?php esc_attr_e('Percent', '99btc-bf') ?>" pattern="\d*" step="1">
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
</div>
