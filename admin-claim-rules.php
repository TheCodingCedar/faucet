<?php
/**
 * @var string $notice_message
 * @var string $notice_css_class
 * @var bool $optout
 *
 * @var array $config
 *
 * @var array $claim_rules
 * @var array $USDBTC_claim_rules
 */
?>


    <?php if (!empty($notice_message)): ?>
        <div class="<?= $notice_css_class ?>">
            <p><?= $notice_message ?></p>
        </div>
    <?php endif ?>

    <div><?= esc_html__('Here you can chose one of possible claim rules', '99btc-bf') ?></div>

    <h2 class="nav-tab-wrapper the99claimrules">
        <a href="#" class="nav-tab<?php if ($config['rule_set'] == 'BCH'): ?> nav-tab-active<?php endif ?>" data-layer="BCH"><?= esc_html__('BCH', '99btc-bf') ?></a>
        <a href="#" class="nav-tab<?php if (!$config['rule_set'] || $config['rule_set'] == 'BTC'): ?> nav-tab-active<?php endif ?>" data-layer="BTC"><?= esc_html__('BTC', '99btc-bf') ?></a>
        <a href="#" class="nav-tab<?php if ($config['rule_set'] == 'DASH'): ?> nav-tab-active<?php endif ?>" data-layer="DASH"><?= esc_html__('DASH', '99btc-bf') ?></a>
        <a href="#" class="nav-tab<?php if ($config['rule_set'] == 'DOGE'): ?> nav-tab-active<?php endif ?>" data-layer="DOGE"><?= esc_html__('DOGE', '99btc-bf') ?></a>
        <a href="#" class="nav-tab<?php if ($config['rule_set'] == 'ETH'): ?> nav-tab-active<?php endif ?>" data-layer="ETH"><?= esc_html__('ETH', '99btc-bf') ?></a>
        <a href="#" class="nav-tab<?php if ($config['rule_set'] == 'LTC'): ?> nav-tab-active<?php endif ?>" data-layer="LTC"><?= esc_html__('LTC', '99btc-bf') ?></a>
    </h2>
    <h2 class="nav-tab-wrapper the99claimrules">
        <a href="#" class="nav-tab<?php if ($config['rule_set'] == 'USDBCH'): ?> nav-tab-active<?php endif ?>" data-layer="USDBCH"><?= esc_html__('USD BCH', '99btc-bf') ?></a>
        <a href="#" class="nav-tab<?php if ($config['rule_set'] == 'USDBTC'): ?> nav-tab-active<?php endif ?>" data-layer="USDBTC"><?= esc_html__('USD BTC', '99btc-bf') ?></a>
        <a href="#" class="nav-tab<?php if ($config['rule_set'] == 'USDDASH'): ?> nav-tab-active<?php endif ?>" data-layer="USDDASH"><?= esc_html__('USD DASH', '99btc-bf') ?></a>
        <a href="#" class="nav-tab<?php if ($config['rule_set'] == 'USDDOGE'): ?> nav-tab-active<?php endif ?>" data-layer="USDDOGE"><?= esc_html__('USD DOGE', '99btc-bf') ?></a>
        <a href="#" class="nav-tab<?php if ($config['rule_set'] == 'USDETH'): ?> nav-tab-active<?php endif ?>" data-layer="USDETH"><?= esc_html__('USD ETH', '99btc-bf') ?></a>
        <a href="#" class="nav-tab<?php if ($config['rule_set'] == 'USDLTC'): ?> nav-tab-active<?php endif ?>" data-layer="USDLTC"><?= esc_html__('USD LTC', '99btc-bf') ?></a>
    </h2>

	<?php include __DIR__ . '/admin-claim-rules/BCH.php' ?>
    <?php include __DIR__ . '/admin-claim-rules/BTC.php' ?>
    <?php include __DIR__ . '/admin-claim-rules/DASH.php' ?>
    <?php include __DIR__ . '/admin-claim-rules/DOGE.php' ?>
    <?php include __DIR__ . '/admin-claim-rules/ETH.php' ?>
    <?php include __DIR__ . '/admin-claim-rules/LTC.php' ?>
	<?php include __DIR__ . '/admin-claim-rules/USDBCH.php' ?>
    <?php include __DIR__ . '/admin-claim-rules/USDBTC.php' ?>
	<?php include __DIR__ . '/admin-claim-rules/USDDASH.php' ?>
	<?php include __DIR__ . '/admin-claim-rules/USDDOGE.php' ?>
	<?php include __DIR__ . '/admin-claim-rules/USDETH.php' ?>
	<?php include __DIR__ . '/admin-claim-rules/USDLTC.php' ?>


</div>
