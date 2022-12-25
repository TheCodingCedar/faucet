<?php
/**
 * @var The99Bitcoins_BtcFaucet_Plugin $this
 * @var string $notice_message
 * @var string $notice_css_class
 * @var string $term
 * @var int[] $users
 * @var int[] $ips
 * @var int[] $userIps
 * @var string[] $addresses
 */
?>


    <?php if (!empty($notice_message)): ?>
        <div class="<?= $notice_css_class ?>">
            <p><?= $notice_message ?></p>
        </div>
    <?php endif ?>

    <form method="post" novalidate="novalidate">
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="term"><?= esc_html__('Address, email or ip', '99btc-bf') ?></label>
                </th>
                <td>
                    <input class="regular-text" type="text" name="term" id="term" value="<?= esc_attr($term) ?>" placeholder="<?= esc_attr__('Bitcoin address or email', '99btc-bf') ?>">
                </td>
            </tr>
        </table>
        <?php submit_button(__('Find Information', '99btc-bf')); ?>
    </form>

    <?php if ($users): ?>
        <h3><?= esc_html__('Users', '99btc-bf') ?></h3>
        <?php foreach ($users as $email): ?>
            <a href="?page=<?= esc_attr($_REQUEST['page']) ?>&amp;mode=<?= esc_attr($_REQUEST['mode']) ?>&amp;term=<?= esc_attr($email) ?>"><?= esc_html($email) ?></a><br>
        <?php endforeach ?>
    <?php endif ?>
    <?php if ($ips): ?>
        <h3><?= esc_html__('Ips', '99btc-bf') ?></h3>
        <?php foreach ($ips as $ip): ?>
            <a href="?page=<?= esc_attr($_REQUEST['page']) ?>&amp;mode=<?= esc_attr($_REQUEST['mode']) ?>&amp;term=<?= esc_attr($ip) ?>"><?= esc_html($ip) ?></a><br>
        <?php endforeach ?>
    <?php endif ?>
    <?php if ($addresses): ?>
        <h3><?= esc_html__('Addresses', '99btc-bf') ?></h3>
        <?php foreach ($addresses as $address): ?>
            <a href="?page=<?= esc_attr($_REQUEST['page']) ?>&amp;mode=<?= esc_attr($_REQUEST['mode']) ?>&amp;term=<?= esc_attr($address) ?>"><?= esc_html($address) ?></a><br>
        <?php endforeach ?>
    <?php endif ?>
</div>
