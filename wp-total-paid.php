<?php
/**
 * @var int $total
 * @var The99Bitcoins_BtcFaucet_Currency_Base $currency
 */
?>
<?= esc_html(sprintf(__('%s ' . $currency->satoshi(), '99btc-bf'), number_format_i18n($total))) ?>
