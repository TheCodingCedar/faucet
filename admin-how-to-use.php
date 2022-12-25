<?php
/**
 * @var The99Bitcoins_BtcFaucet_Plugin $this
 */
?>


    <p><strong>[btc-faucet-form id="<?= esc_attr($this->config['post']->ID) ?>"]</strong> - <?= esc_html__('shortcode to display faucet form.', '99btc-bf') ?></p>
    <p>
        <strong>[btc-faucet-form-text id="<?= esc_attr($this->config['post']->ID) ?>"]</strong> - <?= esc_html__('shortcode to inject text into faucet form.', '99btc-bf') ?><br>
        <?= esc_html__('Make sure to insert the text shortcodes BEFORE the faucet form shortcode.', '99btc-bf') ?><br>
        <small>[btc-faucet-form-text placeholder="header_text" id="<?= esc_attr($this->config['post']->ID) ?>"]text[/btc-faucet-form-text]</small><br>
        <small>[btc-faucet-form-text placeholder="after_form_start" id="<?= esc_attr($this->config['post']->ID) ?>"]text[/btc-faucet-form-text]</small><br>
        <small>[btc-faucet-form-text placeholder="after_captcha_text" id="<?= esc_attr($this->config['post']->ID) ?>"]text[/btc-faucet-form-text]</small><br>
        <small>[btc-faucet-form-text placeholder="after_address_text" id="<?= esc_attr($this->config['post']->ID) ?>"]text[/btc-faucet-form-text]</small><br>
        <small>[btc-faucet-form-text placeholder="before_form_end" id="<?= esc_attr($this->config['post']->ID) ?>"]text[/btc-faucet-form-text]</small><br>
        <small>[btc-faucet-form-text placeholder="footer_text" id="<?= esc_attr($this->config['post']->ID) ?>"]text[/btc-faucet-form-text]</small><br>
    </p>
    <p><strong>[btc-faucet-address-check id="<?= esc_attr($this->config['post']->ID) ?>"]</strong> - <?= esc_html__('shortcode to display address check form.', '99btc-bf') ?></p>
    <p><strong>[btc-faucet-total-paid id="<?= esc_attr($this->config['post']->ID) ?>"]</strong> - <?= esc_html__('shortcode to display total paid amount.', '99btc-bf') ?></p>
    <p><strong>[btc-faucet-ref-link id="<?= esc_attr($this->config['post']->ID) ?>"]</strong> - <?= esc_html__('shortcode to display ref link.', '99btc-bf') ?></p>
</div>
