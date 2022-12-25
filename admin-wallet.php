<?php
/**
 * @var The99Bitcoins_BtcFaucet_Plugin $this
 *
 * @var string $notice_message
 * @var string $notice_css_class
 *
 * @var string $show
 * @var string $bitcoind_status
 * @var string $faucetpayio_status
 * @var string $epayinfo_status
 */
?>

    <?php if (!empty($notice_message)): ?>
        <div class="<?= $notice_css_class ?>">
            <p><?= $notice_message ?></p>
        </div>
    <?php endif ?>

    <div><?= esc_html__('To understand current state please check Status field above.', '99btc-bf') ?></div>
    <div><?= esc_html__('Not configured - means wallet was not configured and nothing will be paid.', '99btc-bf') ?></div>
    <div><?= esc_html__('Unreachable - means wallet was configured but we can not reach it, usually it means you need to check data and save it one more time.', '99btc-bf') ?></div>
    <div><?= esc_html__('Active - everything is good, addresses will be paid if you have enough balance.', '99btc-bf') ?></div>

    <h2 class="nav-tab-wrapper the99btcwallets">
        <a href="#" class="nav-tab<?php if (!$show || $show == 'faucetpay'): ?> nav-tab-active<?php endif ?>" data-layer="FaucetPay"><?= esc_html__('FaucetPay.io', '99btc-bf') ?></a>

    </h2>


    <?php include __DIR__ . '/admin-wallet/faucetpay-io.php' ?>

</div>
