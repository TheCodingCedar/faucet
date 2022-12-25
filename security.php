<table class="form-table the99tab" id="general-security" style="display: none">
    <tr>
        <th scope="row">
            <label for="config[submit_limitation]"><?= esc_html__('24h submit limitation', '99btc-bf') ?></label></th>
        <td>
            <input class="regular-text" type="number" name="config[submit_limitation]" id="config[submit_limitation]" value="<?= esc_attr($config['submit_limitation']) ?>" placeholder="<?= esc_html__('24h submit limitation', '99btc-bf') ?>" pattern="\d*" step="1">
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="config[submit_limitation_ban]"><?= esc_html__('Ban address above submit limitation', '99btc-bf') ?></label></th>
        <td>
            <input type="hidden" name="config[submit_limitation_ban]" value="0">
            <input type="checkbox" name="config[submit_limitation_ban]" id="config[submit_limitation_ban]" value="1"<?php if (!empty($config['submit_limitation_ban'])): ?> checked="checked"<?php endif ?>>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="config[broadcasting_ban]"><?= esc_html__('Enable broadcasting ban', '99btc-bf') ?></label></th>
        <td>
            <input type="hidden" name="config[broadcasting_ban]" value="0">
            <input type="checkbox" name="config[broadcasting_ban]" id="config[broadcasting_ban]" value="1"<?php if (!empty($config['broadcasting_ban'])): ?> checked="checked"<?php endif ?>>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="config[avoid_opera]"><?= esc_html__('Avoid Opera Turbo users', '99btc-bf') ?></label></th>
        <td>
            <input type="hidden" name="config[avoid_opera]" value="0">
            <input type="checkbox" name="config[avoid_opera]" id="config[avoid_opera]" value="1"<?php if (!empty($config['avoid_opera'])): ?> checked="checked"<?php endif ?>>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="config[fake_buttons]"><?= esc_html__('Enable fake buttons', '99btc-bf') ?></label></th>
        <td>
            <input type="hidden" name="config[fake_buttons]" value="0">
            <input type="checkbox" name="config[fake_buttons]" id="config[fake_buttons]" value="1"<?php if (!empty($config['fake_buttons'])): ?> checked="checked"<?php endif ?>>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="config[fake_buttons_ban]"><?= esc_html__('Ban if fake button was clicked', '99btc-bf') ?></label></th>
        <td>
            <input type="hidden" name="config[fake_buttons_ban]" value="0">
            <input type="checkbox" name="config[fake_buttons_ban]" id="config[fake_buttons_ban]" value="1"<?php if (!empty($config['fake_buttons_ban'])): ?> checked="checked"<?php endif ?>>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="config[sound]"><?= esc_html__('Enable sound alert when timer passed', '99btc-bf') ?></label></th>
        <td>
            <input type="hidden" name="config[sound]" value="0">
            <input type="checkbox" name="config[sound]" id="config[sound]" value="1"<?php if (!empty($config['sound'])): ?> checked="checked"<?php endif ?>>
        </td>
    </tr>
</table>
