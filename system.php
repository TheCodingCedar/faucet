<table class="form-table the99tab" id="general-system" style="display: none">
    <tr>
        <th scope="row">
            <label for="config[auto_payout]"><?= esc_html__('Automatically run payout with cron run', '99btc-bf') ?></label></th>
        <td>
            <input type="hidden" name="config[auto_payout]" value="0">
            <input type="checkbox" name="config[auto_payout]" id="config[auto_payout]" value="1"<?php if (!empty($config['auto_payout'])): ?> checked="checked"<?php endif ?>>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="config[supports_cf]"><?= esc_html__('Website is protected by www.cloudflare.com', '99btc-bf') ?></label></th>
        <td>
            <input type="hidden" name="config[supports_cf]" value="0">
            <input type="checkbox" name="config[supports_cf]" id="config[supports_cf]" value="1"<?php if (!empty($config['supports_cf'])): ?> checked="checked"<?php endif ?>>
        </td>
    </tr>
</table>
