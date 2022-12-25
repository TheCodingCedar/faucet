<?php
$url = add_query_arg('r', '', get_site_url(null, $options['config']['urls_main']));
?>
<?php if ($options['config']['refer_bonus']): ?>
    <a href="<?= esc_attr($url . '=' . __('Your Address', '99btc-bf')) ?>" class="the99btc-bf t99fa<?= esc_attr($this->config['post']->ID) ?> link" data-link="<?= esc_attr($url) ?>="><?= esc_html($url . '=' . __('Your Address', '99btc-bf')) ?></a>
<?php endif ?>
