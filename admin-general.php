<?php
/**
 * @var The99Bitcoins_BtcFaucet_Plugin $this
 * @var array $config
 * @var array $claim_rules
 * @var array $seniority_rules
 *
 * @var string $notice_message
 * @var string $notice_css_class
 */
?>


    <form method="post" style="margin-right: 500px;" novalidate="novalidate" enctype="multipart/form-data">

        <h2 class="nav-tab-wrapper the99tabs">
            <a href="#" class="nav-tab nav-tab-active" data-layer="general-claims"><?= esc_html__('Claims', '99btc-bf') ?></a>
            <a href="#" class="nav-tab" data-layer="general-security"><?= esc_html__('Security', '99btc-bf') ?></a>
            <a href="#" class="nav-tab" data-layer="general-urls"><?= esc_html__('Urls', '99btc-bf') ?></a>
            <a href="#" class="nav-tab" data-layer="general-system"><?= esc_html__('System configuration', '99btc-bf') ?></a>
            <a href="#" class="nav-tab" data-layer="general-access"><?= esc_html__('Access management', '99btc-bf') ?></a>
            <a href="#" class="nav-tab" data-layer="general-seniority"><?= esc_html__('Seniority', '99btc-bf') ?></a>
            <a href="#" class="nav-tab" data-layer="general-captcha"><?= esc_html__('Captcha', '99btc-bf') ?></a>
        </h2>

        <?php include __DIR__ . '/admin-general/claims.php' ?>
        <?php include __DIR__ . '/admin-general/security.php' ?>
        <?php include __DIR__ . '/admin-general/urls.php' ?>
        <?php include __DIR__ . '/admin-general/system.php' ?>
        <?php include __DIR__ . '/admin-general/access.php' ?>
        <?php include __DIR__ . '/admin-general/seniority.php' ?>
        <?php include __DIR__ . '/admin-general/captcha.php' ?>

        <?php submit_button(); ?>
    </form>
</div>
