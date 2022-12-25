<?php
/**
 * @var The99Bitcoins_BtcFaucet_Plugin $this
 * @var string $error
 *
 * @var string $placeholder_header_text
 * @var string $placeholder_after_form_start
 * @var string $placeholder_after_captcha_text
 * @var string $placeholder_after_address_text
 * @var string $placeholder_before_form_end
 * @var string $placeholder_footer_text
 */

?>
<?php if (!empty($style)): ?>
    <script xmlns="http://www.w3.org/1999/html">document.write(unescape('<?= $style ?>'));</script>
<?php endif ?>
<?= $placeholder_header_text ?>
<div class="the99btc-bf form">
    <?= $placeholder_after_form_start ?>
    <div class="message message-error"><?= esc_html($error) ?></div>
    <?= $placeholder_after_captcha_text ?>
    <?= $placeholder_after_address_text ?>
    <?= $placeholder_before_form_end ?>
</div>
<?= $placeholder_footer_text ?>
