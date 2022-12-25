<?php
/**
 * @var The99Bitcoins_BtcFaucet_Plugin $this
 * @var string $_content
 * @var array $_navigation
 */
?>
<h2 class="nav-tab-wrapper">
    <?php foreach ($_navigation as $node): ?>
        <a href="<?= admin_url('admin.php?page=' . $this->config['prefix'] . $node['url']) ?>" class="nav-tab<?php if (!empty($node['active'])): ?> nav-tab-active<?php endif ?>"><?= esc_html__($node['title'], '99btc-bf') ?></a>
    <?php endforeach ?>
</h2>

<?php foreach ($_navigation as $parent): ?>
    <?php if (!$parent['childs']) continue ?>
    <?php if (empty($parent['active'])) continue ?>
    <h2 class="nav-tab-wrapper payments">
        <?php foreach ($parent['childs'] as $node): ?>
            <?php if (!empty($node['external'])): ?>
                <a href="<?= esc_attr($node['url']) ?>" class="nav-tab" target="_blank"><?= esc_html__($node['title'], '99btc-bf') ?></a>
            <?php else: ?>
                <a href="<?= admin_url('admin.php?page=' . $this->config['prefix'] . $parent['url'] . '&mode=' . $node['url']) ?>" class="nav-tab<?php if (!empty($node['active'])): ?> nav-tab-active<?php endif ?>"<?php if (!empty($node['notification'])): ?> data-before-click="<?= esc_attr__($node['notification'], '99btc-bf') ?>"<?php endif ?>><?= esc_html__($node['title'], '99btc-bf') ?></a>
            <?php endif ?>
        <?php endforeach ?>
    </h2>
<?php endforeach ?>

<div class="the99btcoverlay" data-container="before-click" style="display: none">
    <div>
        <?= esc_html__('Please do not close this window.', '99btc-bf') ?>
    </div>
</div>

<?php echo $_content ?>
