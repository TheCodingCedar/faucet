<table class="form-table the99tab" id="general-access" style="display: none">
    <tr>
        <th scope="row">
            <label for="config[only_users]"><?= esc_html__('Allow only registered users', '99btc-bf') ?></label></th>
        <td>
            <input type="hidden" name="config[only_users]" value="0">
            <input type="checkbox" name="config[only_users]" id="config[only_users]" value="1"<?php if (!empty($config['only_users'])): ?> checked="checked"<?php endif ?>>
        </td>
    </tr>
    <tr class="the99btc_only_users"<?php if (empty($config['only_users'])): ?> style="display: none"<?php endif ?>>
        <th scope="row">
            <label for="config[only_users_single_address]"><?= esc_html__('Force users to specify one Bitcoin address', '99btc-bf') ?></label></th>
        <td>
            <input type="hidden" name="config[only_users_single_address]" value="0">
            <input type="checkbox" name="config[only_users_single_address]" id="config[only_users_single_address]" value="1"<?php if (!empty($config['only_users_single_address'])): ?> checked="checked"<?php endif ?>>
        </td>
    </tr>
    <tr class="the99btc_only_users"<?php if (empty($config['only_users'])): ?> style="display: none"<?php endif ?>>
        <th scope="row">
            <label for="config[only_users_single_pay]"><?= esc_html__('Pay only to addresses linked to accounts', '99btc-bf') ?></label></th>
        <td>
            <input type="hidden" name="config[only_users_single_pay]" value="0">
            <input type="checkbox" name="config[only_users_single_pay]" id="config[only_users_single_pay]" value="1"<?php if (!empty($config['only_users_single_pay'])): ?> checked="checked"<?php endif ?>>
        </td>
    </tr>
    <tr class="the99btc_only_users"<?php if (empty($config['only_users'])): ?> style="display: none"<?php endif ?>>
        <th scope="row">
            <label for="config[only_users_seniority]"><?= esc_html__('Seniority is based on user instead of address', '99btc-bf') ?></label></th>
        <td>
            <input type="hidden" name="config[only_users_seniority]" value="0">
            <input type="checkbox" name="config[only_users_seniority]" id="config[only_users_seniority]" value="1"<?php if (!empty($config['only_users_seniority'])): ?> checked="checked"<?php endif ?>>
        </td>
    </tr>
</table>
