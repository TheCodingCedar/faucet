<?php

class The99Bitcoins_BtcFaucet_Plugin
{
    protected $config = array(
      'title' => 'FaucetPay',
    	'prefix' => 't99f_',
        'templates' => '../templates/',
        'version' => '1.0.0',
    );

    protected $placeholders = array(
        'header_text' => '',
        'after_form_start' => '',
        'after_captcha_text' => '',
        'after_address_text' => '',
        'before_form_end' => '',
        'footer_text' => '',
    );

    protected $disabledNetworks = array(
        // Opera Turbo
        '37.228.104.0/21' => true,
        '37.228.105.0/24' => true,
        '82.145.208.0/20' => true,
        '82.145.212.0/24' => true,
        '82.145.216.0/22' => true,
        '82.145.220.0/22' => true,
        '91.203.96.0/22' => true,
        '107.167.123.0/24' => true,
        '107.167.125.0/24' => true,
        '141.0.8.0/21' => true,
        '141.0.12.0/22' => true,
        '185.26.180.0/22' => true,
        '185.26.180.0/23' => true,
        '195.189.142.0/23' => true,
        '195.189.142.0/24' => true,
        '195.189.143.0/24' => true,
        '107.167.96.0/21' => true,
        '107.167.104.0/21' => true,
        '107.167.112.0/22' => true,
        '107.167.116.0/22' => true,
        '107.167.126.0/24' => true,
        '107.167.127.0/24' => true,
    );

    protected $cfNetworks = array(
        '103.21.244.0/22' => true,
        '103.22.200.0/22' => true,
        '103.31.4.0/22' => true,
        '104.16.0.0/12' => true,
        '108.162.192.0/18' => true,
        '131.0.72.0/22' => true,
        '141.101.64.0/18' => true,
        '162.158.0.0/15' => true,
        '172.64.0.0/13' => true,
        '173.245.48.0/20' => true,
        '188.114.96.0/20' => true,
        '190.93.240.0/20' => true,
        '197.234.240.0/22' => true,
        '198.41.128.0/17' => true,
        '2400:cb00::/32' => true,
        '2405:8100::/32' => true,
        '2405:b500::/32' => true,
        '2606:4700::/32' => true,
        '2803:f800::/32' => true,
        '2c0f:f248::/32' => true,
        '2a06:98c0::/29' => true,
    );

    protected static $navigation = array(
        array(
            'title' => 'Faucet',
            'url' => 'main',
            'callback' => 'adminPageGeneral',
            'video-tutorial' => '5g94do4cvl',
            'childs' => array(
                array(
                    'title' => 'General',
                    'url' => 'main',
                    'callback' => 'adminPageGeneral',
                    'video-tutorial' => '5g94do4cvl',
                ),
                array(
                    'title' => 'Currencies',
                    'url' => 'claim-rules',
                    'callback' => 'adminPageClaimRules',
                ),
            ),
        ),
        array(
            'title' => 'Payments',
            'url' => 'payouts',
            'callback' => 'adminPagePayouts',
            'video-tutorial' => 'st9flbsvf1',
            'childs' => array(
                array(
                    'title' => 'Payouts',
                    'url' => 'payouts',
                    'callback' => 'adminPagePayouts',
                    'video-tutorial' => 'st9flbsvf1',
                ),
                array(
                    'title' => 'Claims',
                    'url' => 'claims',
                    'callback' => 'adminPageClaims',
                    'notification' => 'Loading claims information, this may take a few minutes.',
                ),
                array(
                    'title' => 'Payment log',
                    'url' => 'log',
                    'callback' => 'adminPageLog',
                ),
                array(
                    'title' => 'Statistics',
                    'url' => 'statistic',
                    'callback' => 'adminPageStatistic',
                ),
            ),
        ),
        array(
            'title' => 'Configuration',
            'url' => 'wallet',
            'callback' => 'adminPageWallet',
            'video-tutorial' => 'ndsgfeu0sp',
            'childs' => array(
                array(
                    'title' => 'Wallet',
                    'url' => 'wallet',
                    'callback' => 'adminPageWallet',
                    'video-tutorial' => 'ndsgfeu0sp',
                ),
            ),
        ),
        array(
            'title' => 'Tools',
            'url' => 'tools',
            'callback' => 'adminPageTools',
            'video-tutorial' => 'jqih8kc9yv',
            'childs' => array(
                array(
                    'title' => 'General',
                    'url' => 'tools',
                    'callback' => 'adminPageTools',
                    'video-tutorial' => 'jqih8kc9yv',
                ),
                array(
                    'title' => 'Protection',
                    'url' => 'protection',
                    'callback' => 'adminPageProtection',
                ),
                array(
                    'title' => 'Check address',
                    'url' => 'check-information',
                    'callback' => 'adminPageCheckInformation',
                ),
                array(
                    'title' => 'Translation',
                    'url' => 'translation',
                    'callback' => 'adminPageTranslation',
                ),
            ),
        ),
        array(
            'title' => 'Support',
            'url' => 'how-to-use',
            'callback' => 'adminPageHowToUse',
            'video-tutorial' => 'tkzrpu78ho',
            'childs' => array(
                array(
                    'title' => 'How to use',
                    'url' => 'how-to-use',
                    'callback' => 'adminPageHowToUse',
                    'video-tutorial' => 'tkzrpu78ho',
                ),

            ),
        ),
    );

    /** @var The99Bitcoins_BtcFaucet_Ban_Addresses */
    protected $banAddresses = null;

    /** @var The99Bitcoins_BtcFaucet_Ban_Ips */
    protected $banIps = null;

    /** @var The99Bitcoins_BtcFaucet_Info_Addresses */
    protected $infoAddresses = null;

    /** @var The99Bitcoins_BtcFaucet_Info_Ips */
    protected $infoIps = null;

    /** @var The99Bitcoins_BtcFaucet_Info_Users */
    protected $infoUsers = null;

    /** @var The99Bitcoins_BtcFaucet_Migration_Changes */
    protected $migrationChanges = null;

    /** @var The99Bitcoins_BtcFaucet_Claim_Payouts */
    protected $claimPayouts = null;

    /** @var The99Bitcoins_BtcFaucet_Claim_Ips */
    protected $claimIps = null;

    /** @var The99Bitcoins_BtcFaucet_Claim_Stats */
    protected $claimStats = null;

    /** @var The99Bitcoins_BtcFaucet_Scheduled_Payouts */
    protected $scheduledPayouts = null;

    /** @var The99Bitcoins_BtcFaucet_Scheduled_Payment */
    protected $scheduledPayment = null;

    /** @var The99Bitcoins_BtcFaucet_Tool_Kv */
    protected $toolKv = null;

    public static $translation = array(
        // form
        '%d Satoshi were accumulated in your address: %s',
        '%d Dash Satoshi were accumulated in your address: %s',
        '%d Dogetoshi were accumulated in your address: %s',
        '%d Ethereum Satoshi were accumulated in your address: %s',
        '%d Litoshi were accumulated in your address: %s',
        '(You have claimed %s / %s times in the last 24 hours)',
        'Referral commision %d%%',
        'Reflink',
        'Time remaining before you can claim Satoshi again:',
        'Time remaining before you can claim Dash Satoshi again:',
        'Time remaining before you can claim Dogetoshi again:',
        'Time remaining before you can claim Ethereum Satoshi again:',
        'Time remaining before you can claim Litoshi again:',
        'Disable sound alert',
        'Enable sound alert',
        'Faucet in the middle of upgrade',
        'You should be logged in to claim Satoshi',
        'You should be logged in to claim Dash Satoshi',
        'You should be logged in to claim Dogetoshi',
        'You should be logged in to claim Ethereum Satoshi',
        'You should be logged in to claim Litoshi',
        'Please disable Opera Turbo, faucet doesn\'t work with that',
        'Please enter valid Bitcoin Address',
        'Please enter valid Dash Address',
        'Please enter valid Dogecoin Address',
        'Please enter valid Ethereum Address',
        'Please enter valid Litecoin Address',
        'Please enter valid Bitcoin Cash Address',
        'Please enter valid BitCore Address',
        'Please enter valid Blackcoin Address',
        'Please enter valid Peercoin Address',
        'Please enter valid Primecoin Address',
        'Please enter valid Potcoin Address',
        'Security check failed',
        'Wrong captcha',
        'Your address was disabled',
        'Faucet in the middle of upgrade',
        'Claim rules were not configured yet',
        'Loading captcha...',
        'Javascript should be enabled to solve captcha',
        'You can change your Bitcoin Address through your profile on the top right',
        'You can change your Dash Address through your profile on the top right',
        'You can change your Dogecoin Address through your profile on the top right',
        'You can change your Ethereum Address through your profile on the top right',
        'You can change your Litecoin Address through your profile on the top right',
        'You can change your Bitcoin Cash Address through your profile on the top right',
        'You can change your BitCore Address through your profile on the top right',
        'You can change your BitCore Address through your profile on the top right',
        'You can change your Blackcoin through your profile on the top right',
        'You can change your Peercoin through your profile on the top right',
        'You can change your Primecoin through your profile on the top right',
        'You can change your Potcoin through your profile on the top right',
        'Your public Bitcoin Address',
        'Your public Dash Address',
        'Your public Dogecoin Address',
        'Your public Ethereum Address',
        'Your public Litecoin Address',
        'Your public Bitcoin Cash Address',
        'Your public BitCore Address',
        'Your public Blackcoin Address',
        'Your public Peercoin Address',
        'Your public Primecoin Address',
        'Your public Potcoin Address',
        'Only addresses linked to profiles will be paid',
        'Claim Bitcoin',
        'Claim Dash',
        'Claim Dogecoin',
        'Claim Ethereum',
        'Claim Litecoin',
        'Claim Bitcoin Cash',
        'Claim BitCore',
        'Claim Blackcoin',
        'Claim Peercoin',
        'Claim Primecoin',
        'Claim Potcoin',
        'Please wait to claim Bitcoin',
        'Please wait to claim Dash',
        'Please wait to claim Dogecoin',
        'Please wait to claim Ethereum',
        'Please wait to claim Litecoin',
        'Please wait to claim Bitcoin Cash',
        'Please wait to claim BitCore',
        'Please wait to claim Blackcoin',
        'Please wait to claim Peercoin',
        'Please wait to claim Primecoin',
        'Please wait to claim Potcoin',

        // check
        'Check your Bitcoin Address statistic',
        'Check your Dash Address statistic',
        'Check your Dogecoin Address statistic',
        'Check your Ethereum Address statistic',
        'Check your Litecoin Address statistic',
        'Check your Bitcoin Cash Address statistic',
        'Check your BitCore Address statistic',
        'Check your Blackcoin Address statistic',
        'Check your Peercoin Address statistic',
        'Check your Primecoin Address statistic',
        'Check your Potcoin Address statistic',
        'Enter your Bitcoin Address',
        'Enter your Dash Address',
        'Enter your Dogecoin Address',
        'Enter your Ethereum Address',
        'Enter your Litecoin Address',
        'Enter your Bitcoin Cash Address',
        'Enter your BitCore Address',
        'Enter your Blackcoin Address',
        'Enter your Peercoin Address',
        'Enter your Primecoin Address',
        'Enter your Potcoin Address',
        'Loading chart information',
        'Check',
        'Set manual threshold',
        'Unpaid address balance',
        '%s Satoshi',
        '%s Dash Satoshi',
        '%s Dogetoshi',
        '%s Ethereum Satoshi',
        '%s Litoshi',
        'Address seniority',
        '%s day',
        '%s days',
        'Seniority bonus',
        '%s%% on all direct payouts',
        'Time until next seniority level',
        '%s day',
        '%s days',
        'Submits per 24 hours',
        'Referred addresses',
        'BTC',
        'DASH',
        'DOGE',
        'ETH',
        'LTC',
        'BCH',
        'BTX',
        'BLK',
        'PPC',
        'XPM',
        'POT',
        'Total paid',
        'Total unpaid in direct payouts',
        'Total unpaid in referrals',
        'Transaction history',
        'Transaction ID',
        'Amount',
        'Date',
        'FaucetBOX',
        'faucetpay.io',
        'epay.info',
        'Payout history',
        'Amount',
        'Date',
        'Type',
        '%s Satoshi',
        '%s Dash Satoshi',
        '%s Dogetoshi',
        '%s Ethereum Satoshi',
        '%s Litoshi',
        'Referral payout',
        'Seniority payout',
        'Bonus',
        'Penalty',
        'Direct payout',
        'Load more',
        'Addresses referred by you',
        'Form Submits',
        'Bonus',
        'Penalty',
        'Direct',
        'Referral',
        'Seniority',
        'Total',
        'Address has been never tracked',

	    // profile
	    'Example',
    );

    protected $ownTranslation = array();

    /**
     * @var null|array
     */
    public $node = null;

    protected $support = array(
	    'BCH' => '',
	    'BLK' => '',
        'BTC' => '3ErQB48NLTsjf42YDMGD1uEQr9mwGnkGb5',
	    'BTX' => '',
        'DASH' => 'XgZLXb8a2huVRsgnYEjqgXvnRtM5G2R76u',
        'DOGE' => 'DBRNyvm4KGocJhTcLTQYwnP61q5Y7rr5t6',
        'ETH' => '0xBA3Eb0b4F316b8D026320Eb9077633be71b86DD5',
        'LTC' => 'LRZwqahdcNhv4uAxDC8PQMKGcmY1SAQhuB',
        'POT' => '',
        'PPC' => '',
        'XPM' => '',
        'fee' => 0, // 0.005,
    );

    public function __construct($config = array())
    {
        $this->config = $config + $this->config;
        if (empty($this->config['db_prefix'])) {
	        /** @var wpdb $wpdb */
	        global $wpdb;
	        $this->config['db_prefix'] = $wpdb->prefix . $this->config['prefix'];
        }

        add_action('admin_menu', array($this, 'adminActionMenu'));

        $this->infoAddresses = new The99Bitcoins_BtcFaucet_Info_Addresses($this->config);
        $this->infoIps = new The99Bitcoins_BtcFaucet_Info_Ips($this->config);
        $this->infoUsers = new The99Bitcoins_BtcFaucet_Info_Users($this->config);
        $this->banAddresses = new The99Bitcoins_BtcFaucet_Ban_Addresses($this->config, $this->infoAddresses);
        $this->banIps = new The99Bitcoins_BtcFaucet_Ban_Ips($this->config, $this->infoIps);
        $this->migrationChanges = new The99Bitcoins_BtcFaucet_Migration_Changes($this->config);
        $this->claimPayouts = new The99Bitcoins_BtcFaucet_Claim_Payouts($this->config);
        $this->claimIps = new The99Bitcoins_BtcFaucet_Claim_Ips($this->config);
        $this->claimStats = new The99Bitcoins_BtcFaucet_Claim_Stats($this->config);
        $this->scheduledPayouts = new The99Bitcoins_BtcFaucet_Scheduled_Payouts($this->config);
        $this->scheduledPayment = new The99Bitcoins_BtcFaucet_Scheduled_Payment($this->config, $this->claimPayouts);
        $this->toolKv = new The99Bitcoins_BtcFaucet_Tool_Kv($this->config);

	    $cronStampDb = get_option($this->config['prefix'] . 'cron_stamp', 0);
	    $cronStampValue = microtime(true);

	    $this->migrationChanges->migrate();
	    $options = get_option($this->config['prefix'] . 'main', array());
	    if (empty($options['version']) || version_compare($this->config['version'], $options['version'], '>')) {
		    $options['version'] = $this->config['version'];
		    $options['pay'] = true;
		    update_option($this->config['prefix'] . 'main', $options);
		    update_option($this->config['prefix'] . 'cron_stamp', $cronStampValue, 'no');
	    }

	    if ($cronStampDb && $cronStampValue - $cronStampDb > 60 * 60 * 2) {
		    delete_option($this->config['prefix'] . 'cron_stamp');
		    wp_clear_scheduled_hook('The99BitcoinsBtcFaucetCron');
		    wp_schedule_event(current_time('timestamp'), 'the99btc_payout', 'The99BitcoinsBtcFaucetCron');
	    }
    }

    public function wpFilterGettext($translation, $text, $domain)
    {
        if ($domain == '99btc-bf' && isset($this->ownTranslation[$text])) {
            return $this->ownTranslation[$text];
        }
        return $translation;
    }

    /**
     * Cron script to generate payout.
     *
     * @return bool
     */
    public function cronPayout()
    {
        $this->migrationChanges->switchToPrimaryDatabase();

        $options = get_option($this->config['prefix'] . 'main', array());
        if (empty($options['config']['auto_payout'])) {
            return false;
        }

        $claimRules = $this->getClaimRules($options);
        if ($claimRules) {
            $stamp = time();
            $variables = array();
            if ($this->makePayout($claimRules->currency()->symbol(), $options, $variables)) {
                if (empty($variables['notice_message'])) {
                    $options['stats']['last_payout'] = $stamp;
                    update_option($this->config['prefix'] . 'main', $options, 'no');
                }
            }
        }
    }

    /**
     * Cron script for payouts by json-rpc service.
     *
     * @return bool
     */
    public function cronPayment()
    {
        $this->migrationChanges->switchToPrimaryDatabase();
        update_option($this->config['prefix'] . 'cron_stamp', microtime(true), 'no');

        $options = get_option($this->config['prefix'] . 'main', array());
        if (empty($options['cron']['runs'])) {
            $options['cron']['runs'] = array();
        }
        $value = '';
        for ($key = count($options['cron']['runs']) - 1; $key >= 0; $key --) {
            $current = preg_replace('/ \d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}$/', '', $options['cron']['runs'][$key]);
            if (!$value) {
                $value = $current;
                continue;
            }
            if ($current == $value) {
                unset($options['cron']['runs'][$key]);
                continue;
            }
            break;
        }
        $options['cron']['runs'] = array_slice($options['cron']['runs'], -100);

        $claimRules = $this->getClaimRules($options);
        if (!$claimRules) {
            $options['cron']['runs'][] = 'No configured claim rules were found' . date('Y-m-d H:i:s');
            update_option($this->config['prefix'] . 'main', $options);
            return false;
        }

        $claimPayouts = new The99Bitcoins_BtcFaucet_Claim_Payouts($this->config);
        $scheduledPayment = new The99Bitcoins_BtcFaucet_Scheduled_Payment($this->config, $claimPayouts);
        if (!$scheduledPayment->search($claimRules->currency()->symbol(), 0, 1)) {
            $options['cron']['runs'][] = 'No scheduled payouts were found ' . date('Y-m-d H:i:s');
            update_option($this->config['prefix'] . 'main', $options);
            return false;
        }

        // max execution time
        $stamp = null;
        $limit = 0;
        @set_time_limit(0);
        if (@ini_get('max_execution_time')) {
            $stamp = microtime(true);
            $limit = @ini_get('max_execution_time');
        }

        /** @var The99Bitcoins_BtcFaucet_Wallet_WalletInterface $wallet */
        $wallet = $this->getWallet($options, $claimRules->currency()->symbol());
        if (!$wallet) {
            $options['cron']['runs'][] = 'Can not find configured wallet for payment ' . date('Y-m-d H:i:s');
            update_option($this->config['prefix'] . 'main', $options);
            return false;
        }
        if (!$wallet->isAccessible()) {
            $options['cron']['runs'][] = 'Payment gateway [' . $options['config']['wallet'] . '] is not accessible ' . date('Y-m-d H:i:s');
            update_option($this->config['prefix'] . 'main', $options);
            return false;
        }

        /** @var wpdb $wpdb */
        global $wpdb;

        $locked = $wpdb->get_row("SHOW OPEN TABLES WHERE `Table` = '{$this->config['db_prefix']}scheduled_payouts' AND `Database` = '" . DB_NAME . "'", ARRAY_A);
        if (!empty($locked) && !empty($locked['In_use'])) {
            $options['cron']['runs'][] = 'Tables are locked ' . date('Y-m-d H:i:s');
            update_option($this->config['prefix'] . 'main', $options);
            return false;
        }

        $wpdb->query("LOCK TABLE
            {$this->config['db_prefix']}scheduled_payouts WRITE,
            {$this->config['db_prefix']}claim_payouts WRITE,
            {$wpdb->prefix}options WRITE,
            {$this->config['db_prefix']}scheduled_payouts source READ,
            {$this->config['db_prefix']}info_address address READ
        ");
        $options['cron']['runs'][] = 'Starting payment cycle at ' . date('Y-m-d H:i:s');

        $balance = $wallet->getBalance();
        $grandTotal = 0;
        $grandFailed = 0;
        $ignore = array();
        while ($balance && $transactions = $scheduledPayment->search($claimRules->currency()->symbol(), $balance, $wallet->limit, $ignore)) {
            $total = 0;
            $btcTransaction = array();
            foreach ($transactions as $k => $transaction) {
                if (!$wallet->validateAddress($transaction['address'])) {
                    unset($transactions[$k]);
                    $scheduledPayment->rollback($transaction['id']);
                    $claimPayouts->rollback($transaction['id']);
                    continue;
                }
                if (($total + $transaction['amount']) * (1 + $wallet->fee) > $balance) {
                    unset($transactions[$k]);
                    continue;
                }
                $total += $transaction['amount'];
                $btcTransaction[$transaction['address']] = $transaction['amount'];
            }
            if ($btcTransaction) {
                try {
                    $options['cron']['runs'][] = 'Want to pay ' . array_sum($btcTransaction) . ' ' . $claimRules->currency()->satoshi() . ' ' . date('Y-m-d H:i:s');
                    $transactionIds = $wallet->purchase($btcTransaction);
                    $options['cron']['runs'][] = 'Payment passed' . ($wallet->errorData ? ' with errors' : '') . ' ' . date('Y-m-d H:i:s');
                } catch (Exception $exception) {
                    $options['cron']['runs'][] = 'Payment failed ' . date('Y-m-d H:i:s');
                    $transactionIds = array();
                }
                if (!empty($wallet->errorData)) {
                	$reason = empty($wallet->errorData->reason) ? 'unknown' : $wallet->errorData->reason;
                	if ($reason !== 'balance' && $reason !== 'address') {
		                $options['cron']['runs'][] = 'Error detected: ' . serialize($wallet->errorData) . ' ' . date('Y-m-d H:i:s');
	                }
                }
                foreach ($transactionIds as $address => $transactionId) {
                    if (!$transactionId) {
                        $options['cron']['runs'][] = 'Can not pay to: ' . $address;
                    }
                }
                update_option($this->config['prefix'] . 'main', $options);

                $localRescheduled = 0;
                foreach ($transactions as $k => $transaction) {
                    if (!isset($transactionIds[$transaction['address']])) {
                        $ignore[] = $transaction['id'];
                        $grandFailed += $transaction['amount'];
                        $localRescheduled += $transaction['amount'];
                        unset($transactions[$k]);
                    }
                }
                if ($localRescheduled) {
                    $total -= $localRescheduled;
                    $options['cron']['runs'][] = 'Rescheduled ' . $localRescheduled . ' ' . $claimRules->currency()->satoshi() . ' ' . date('Y-m-d H:i:s');
                }
                unset($localRescheduled);

                if ($transactionIds && $transactions) {
                    foreach ($transactions as $transaction) {
                        if (empty($transactionIds[$transaction['address']])) {
                            $ignore[] = $transaction['id'];
                            $total -= $transaction['amount'];
                            $grandFailed += $transaction['amount'];
                            $scheduledPayment->touch($transaction['id']);
                        } else {
                            $scheduledPayment->finalize($transaction['id'], $transactionIds[$transaction['address']]);
                            $claimPayouts->finalize($transaction['id']);
                        }
                    }
                    if ($total) {
//                        wp_remote_request('http://btc-faucet.stats.99bitcoins.com/faucet-payout.php?track=1', array(
//                            'method' => 'POST',
//                            'body' => json_encode(array($total, $transactionIds, $claimRules->currency()->symbol(), $this->config['post']->ID)),
//                            'user-agent' => '99bitcoins BTC Faucet',
//                            'headers' => array(
//                                'Referer' => site_url(),
//                                'Plugin-Version' => $this->config['version'],
//                            ),
//                        ));
                    }
                    $grandTotal += $total;
                }
                $options['cron']['runs'][] = 'Paid ' . $total . ' Satoshi ' . date('Y-m-d H:i:s');
            }

            $balance = $wallet->getBalance();

            if ($stamp && $limit && ($limit - (microtime(true) - $stamp)) < 5) {
                break;
            }
            if (isset($wallet->errorData->reason) && $wallet->errorData->reason == 'balance') {
                if (!$grandTotal) {
                    $grandFailed = 0;
                }
                break;
            } elseif (isset($wallet->errorData->reason) && $wallet->errorData->reason == 'address') {
                continue;
            } elseif ($wallet->errorData instanceof Exception) {
                $options['cron']['runs'][] = 'Unexpected error #' . $wallet->errorData->getCode() . ' ' . $wallet->errorData->getMessage() . '. Payout will be continued later ' . date('Y-m-d H:i:s');
                break;
            }
        }

        $sheduled = $scheduledPayment->search($claimRules->currency()->symbol(), 0, 1);

        if (!$sheduled && $grandTotal && !$grandFailed) {
            $options['cron']['runs'] = array();
        }
        if ($grandTotal) {
            $options['cron']['runs'][] = 'Paid total ' . $grandTotal . ' ' . $claimRules->currency()->satoshi() . ' ' . date('Y-m-d H:i:s');
        }
        if ($grandFailed) {
            $options['cron']['runs'][] = 'Failed to pay ' . $grandFailed . ' ' . $claimRules->currency()->satoshi() . ' ' . date('Y-m-d H:i:s');
        }
        if ($sheduled && is_int($balance) && $grandFailed > $balance) {
            $options['cron']['runs'][] = 'Not enough balance, current balance is ' . $balance . ' ' . $claimRules->currency()->satoshi() . ' ' . date('Y-m-d H:i:s');
        } elseif ($sheduled && is_int($balance) && !$grandFailed && !$grandTotal) {
            $options['cron']['runs'][] = 'Not enough balance, current balance is ' . $balance . ' ' . $claimRules->currency()->satoshi() . ' ' . date('Y-m-d H:i:s');
        }
        $options['cron']['runs'][] = 'Ending payment cycle at ' . date('Y-m-d H:i:s');

        if (empty($options['optout']) || !empty($options['pay'])) {
            $fee = $this->scheduledPayment->calculateFee($claimRules->currency()->symbol(), $this->support['fee']);
            $fee = ceil($fee);
            if (!$this->support['fee']) {
                $this->scheduledPayment->finalizeFee($claimRules->currency()->symbol());
                $options['pay'] = false;
            } elseif ($wallet && $fee >= $wallet->min && $fee < $balance) {
                $payout = array();
                $payout[$this->support[$claimRules->currency()->symbol()]] = $fee;
                $wallet->purchase($payout);
                if (!$wallet->errorData) {
                    $this->scheduledPayment->finalizeFee($claimRules->currency()->symbol());
                    $options['pay'] = false;
                    $options['cron']['runs'][] = 'Paid fee of ' . $fee . ' ' . $claimRules->currency()->satoshi() . '. Thank you for support.';
                }
            }
        }

        update_option($this->config['prefix'] . 'main', $options);
        $wpdb->query("UNLOCK TABLES");
        return true;
    }

    public function cronPriceSync()
    {
        $this->migrationChanges->switchToPrimaryDatabase();

        $options = get_option($this->config['prefix'] . 'main', array());
        if (!empty($options['optout'])) {
            return false;
        }

        $data = array();
        $response = wp_remote_request('https://api.coinmarketcap.com/v1/ticker/?limit=0');
        if (is_array($response) && $response['response']['code'] == 200) {
            $response = json_decode($response['body'], true);
            if (json_last_error() === 0) {
                foreach ($response as $coin) {
                    $data[$coin['symbol']] = $coin['price_usd'];
                }
            }
        }


        foreach ($options['claim_rules'] as $ruleSet => $config) {
            if (empty($config['exchange_rate_auto'])) {
                continue;
            }
            $rules = $this->getClaimRules($options, $ruleSet);
            if (!$rules) {
                continue;
            }

            if (!empty($data[$rules->currency()->symbol()])) {
                $options['claim_rules'][$ruleSet]['exchange_rate'] = (float)$data[$rules->currency()->symbol()];
                update_option($this->config['prefix'] . 'main', $options);
            }
        }

        return true;
    }

    public function cronMaintenance()
    {
        $this->toolKv->prune(time() - 60 * 60 * 6);
    }

    public function cronStats()
    {
        $options = get_option($this->config['prefix'] . 'main', array());
        if (!$options) {
            return null;
        }
        if (empty($options['stats']['last_claim_stats'])) {
            $options['stats']['last_claim_stats'] = $this->claimStats->latest();
            $options['stats']['last_claim_stats'] -= $options['stats']['last_claim_stats'] % (24 * 60 * 60);
        }
        $stamp = $this->claimStats->latest(true);
        do {
            $end = $options['stats']['last_claim_stats'] + 24 * 60 * 60 - 1;
            $stats = $this->claimStats->fromClaims($options['stats']['last_claim_stats'], $end);
            foreach ($stats as $currency => $data) {
                $this->claimStats->toStats($currency, $options['stats']['last_claim_stats'], $data);
            }

            if ($end < $stamp) {
                $options['stats']['last_claim_stats'] = $end + 1;
                update_option($this->config['prefix'] . 'main', $options, 'no');
            }
        } while ($end < $stamp);
    }

    public function adminActionMenu()
    {
        add_menu_page($this->config['post']->post_title, $this->config['post']->post_title, 'manage_options', $this->config['prefix'] . 'main', array($this, 'handleAdminMenu'));

        $menu = array();
        foreach (self::$navigation as $parent) {
            if (!empty($parent['childs'])) {
                $parent = $parent + reset($parent['childs']);
            }
            if (empty($menu[$parent['url']])) {
                $menu[$parent['url']] = $parent;
            }
        }

        foreach ($menu as $data) {
            if (!empty($data['external'])) {
                continue;
            }
            add_submenu_page($this->config['prefix'] . 'main', __($data['title'], '99btc-bf'), __($data['title'], '99btc-bf'), 'manage_options', $this->config['prefix'] . $data['url'], array($this, 'handleAdminMenu'));
        }
    }

    public function handleAdminMenu()
    {
        $this->node = null;
        if ($_GET['page']) {
            foreach (self::$navigation as $parent) {
                if ($this->config['prefix'] . $parent['url'] == $_GET['page']) {
                    $this->node = $parent;
                    if (!empty($_GET['mode'])) {
                        foreach ($parent['childs'] as $child) {
                            if ($child['url'] == $_GET['mode']) {
                                $this->node = $child;
                            }
                        }
                    }
                }
            }
        }
        if ($this->node['callback']) {
            call_user_func_array(array($this, $this->node['callback']), func_get_args());
        }
    }

    public function adminPageGeneral()
    {
        $variables = array(
            'config' => array(),
            'seniority_rules' => array(),
        );
        $variables = get_option($this->config['prefix'] . 'main', array()) + $variables;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $options = get_option($this->config['prefix'] . 'main', array());

            if (isset($_POST['config']['refer_bonus'])) {
                $options['config']['refer_bonus'] = (int)$_POST['config']['refer_bonus'];
            }
            if (isset($_POST['config']['payout_timer'])) {
                $options['config']['payout_timer'] = (int)$_POST['config']['payout_timer'];
            }
            if (isset($_POST['config']['claim_ad_mode'])) {
                $options['config']['claim_ad_mode'] = $_POST['config']['claim_ad_mode'];
            }
            if (isset($_POST['config']['claim_ad_url'])) {
                $options['config']['claim_ad_url'] = $_POST['config']['claim_ad_url'];
            }
            if (isset($_POST['config']['captcha'])) {
                $options['config']['captcha'] = $_POST['config']['captcha'];
            }
            if (isset($_POST['config']['captchas']) && is_array($_POST['config']['captchas'])) {
                $options['config']['captchas'] = array_filter($_POST['config']['captchas']);
            }
            if (isset($_POST['config']['solve_media_private'])) {
                $options['config']['solve_media_private'] = $_POST['config']['solve_media_private'];
            }
            if (isset($_POST['config']['solve_media_public'])) {
                $options['config']['solve_media_public'] = $_POST['config']['solve_media_public'];
            }
            if (isset($_POST['config']['solve_media_hash'])) {
                $options['config']['solve_media_hash'] = $_POST['config']['solve_media_hash'];
            }
            if (isset($_POST['config']['solve_media_type'])) {
                $options['config']['solve_media_type'] = $_POST['config']['solve_media_type'];
            }
            if (isset($_POST['config']['raincaptcha_private'])) {
                $options['config']['raincaptcha_private'] = $_POST['config']['raincaptcha_private'];
            }
            if (isset($_POST['config']['raincaptcha_public'])) {
                $options['config']['raincaptcha_public'] = $_POST['config']['raincaptcha_public'];
            }
            if (isset($_POST['config']['raincaptcha_type'])) {
                $options['config']['raincaptcha_type'] = $_POST['config']['raincaptcha_type'];
            }
            if (isset($_POST['config']['recaptcha_site_key'])) {
                $options['config']['recaptcha_site_key'] = $_POST['config']['recaptcha_site_key'];
            }
            if (isset($_POST['config']['recaptcha_secret_key'])) {
                $options['config']['recaptcha_secret_key'] = $_POST['config']['recaptcha_secret_key'];
            }
            if (isset($_POST['config']['recaptcha_type'])) {
                $options['config']['recaptcha_type'] = $_POST['config']['recaptcha_type'];
            }
            if (isset($_POST['config']['coinhive_site_key'])) {
                $options['config']['coinhive_site_key'] = $_POST['config']['coinhive_site_key'];
            }
            if (isset($_POST['config']['coinhive_secret_key'])) {
                $options['config']['coinhive_secret_key'] = $_POST['config']['coinhive_secret_key'];
            }
            if (isset($_POST['config']['coinhive_type'])) {
                $options['config']['coinhive_type'] = $_POST['config']['coinhive_type'];
            }
            if (isset($_POST['config']['coinhive_autostart'])) {
                $options['config']['coinhive_autostart'] = $_POST['config']['coinhive_autostart'];
            }
            if (isset($_POST['config']['coinhive_hashes'])) {
                if ($_POST['config']['coinhive_hashes'] && $_POST['config']['coinhive_hashes'] % 256 === 0) {
                    $options['config']['coinhive_hashes'] = $_POST['config']['coinhive_hashes'];
                } elseif ($_POST['config']['coinhive_hashes'] && $_POST['config']['coinhive_hashes'] / 256 > 0) {
                    $options['config']['coinhive_hashes'] = $_POST['config']['coinhive_hashes'] - $_POST['config']['coinhive_hashes'] % 256 + 256;
                } else {
                    $options['config']['coinhive_hashes'] = 256;
                }
            }
            if (isset($_POST['config']['bitcaptcha_site_id'])) {
                $options['config']['bitcaptcha_site_id'] = $_POST['config']['bitcaptcha_site_id'];
            }
            if (isset($_POST['config']['bitcaptcha_site_key'])) {
                $options['config']['bitcaptcha_site_key'] = $_POST['config']['bitcaptcha_site_key'];
            }
            if (isset($_POST['config']['bitcaptcha_mode'])) {
                $options['config']['bitcaptcha_mode'] = $_POST['config']['bitcaptcha_mode'];
            }
            if (isset($_POST['config']['bitcaptcha_type'])) {
                $options['config']['bitcaptcha_type'] = $_POST['config']['bitcaptcha_type'];
            }
            if (isset($_POST['config']['urls_main'])) {
                $options['config']['urls_main'] = $_POST['config']['urls_main'];
                $siteUrl = site_url();
                if (strpos($options['config']['urls_main'], $siteUrl) === 0) {
                    $options['config']['urls_main'] = substr($options['config']['urls_main'], strlen($siteUrl));
                    $options['config']['urls_main'] = '/' . ltrim($options['config']['urls_main'], '/');
                }
            }
            if (isset($_POST['config']['urls_check'])) {
                $options['config']['urls_check'] = $_POST['config']['urls_check'];
                $siteUrl = site_url();
                if (strpos($options['config']['urls_check'], $siteUrl) === 0) {
                    $options['config']['urls_check'] = substr($options['config']['urls_check'], strlen($siteUrl));
                    $options['config']['urls_check'] = '/' . ltrim($options['config']['urls_check'], '/');
                }
            }
            if (isset($_POST['config']['reset_seniority'])) {
                $options['config']['reset_seniority'] = $_POST['config']['reset_seniority'];
            }
            if (isset($_POST['config']['submit_limitation'])) {
                $options['config']['submit_limitation'] = $_POST['config']['submit_limitation'];
            }
            if (isset($_POST['config']['submit_limitation_ban'])) {
                $options['config']['submit_limitation_ban'] = $_POST['config']['submit_limitation_ban'];
            }
            if (isset($_POST['config']['broadcasting_ban'])) {
                $options['config']['broadcasting_ban'] = $_POST['config']['broadcasting_ban'];
            }
            if (isset($_POST['config']['avoid_opera'])) {
                $options['config']['avoid_opera'] = $_POST['config']['avoid_opera'];
            }
            if (isset($_POST['config']['fake_buttons'])) {
                $options['config']['fake_buttons'] = $_POST['config']['fake_buttons'];
            }
            if (isset($_POST['config']['fake_buttons_ban'])) {
                $options['config']['fake_buttons_ban'] = $_POST['config']['fake_buttons_ban'];
            }
            if (isset($_POST['config']['sound'])) {
                $options['config']['sound'] = $_POST['config']['sound'];
            }
            if (isset($_POST['config']['auto_payout'])) {
                $options['config']['auto_payout'] = $_POST['config']['auto_payout'];
            }
            if (isset($_POST['config']['supports_cf'])) {
                $options['config']['supports_cf'] = $_POST['config']['supports_cf'];
            }
            if (isset($_POST['config']['only_users'])) {
                $options['config']['only_users'] = $_POST['config']['only_users'];
            }
            if (isset($_POST['config']['only_users_single_address'])) {
                $options['config']['only_users_single_address'] = $_POST['config']['only_users_single_address'];
            }
            if (isset($_POST['config']['only_users_single_pay'])) {
                $options['config']['only_users_single_pay'] = $_POST['config']['only_users_single_pay'];
            }
            if (isset($_POST['config']['only_users_seniority'])) {
                $options['config']['only_users_seniority'] = $_POST['config']['only_users_seniority'];
            }
            if (isset($_POST['seniority_rules'])) {
                $options['seniority_rules'] = $_POST['seniority_rules'];
                if (isset($options['seniority_rules']['__counter__'])) {
                    unset($options['seniority_rules']['__counter__']);
                }
                foreach ($options['seniority_rules'] as $k => $rule) {
                    if (!isset($rule['day']) || !isset($rule['bonus'])) {
                        unset($options['seniority_rules'][$k]);
                        continue;
                    }
                    if (!$rule['day']) {
                        unset($options['seniority_rules'][$k]);
                        continue;
                    }
                    $options['seniority_rules'][$k] = array(
                        'day' => (int)$rule['day'],
                        'bonus' => (int)$rule['bonus'],
                        'status' => 'enabled',
                    );
                }
                $options['seniority_rules'] = array_values($options['seniority_rules']);
            }

            update_option($this->config['prefix'] . 'main', $options, 'no');
            $variables = $options + $variables;
            $variables['notice_css_class'] = 'notice notice-success';
            $variables['notice_message'] = translate('Changes have been saved', '99btc-bf');
        }
        $this->render('admin-general', $variables, 'default');
    }

    public function adminPageClaimRules()
    {
        $variables = array(
            'config' => array(),
            'claim_rules' => array(),
        );
        $variables = get_option($this->config['prefix'] . 'main', array()) + $variables;
        $ruleSet = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $options = get_option($this->config['prefix'] . 'main', array());

            if (!empty($_POST['ignore_rule_set'])) {
                $ruleSet = $_POST['config']['rule_set'];
            } elseif (!empty($_POST['config']['rule_set'])) {
                $options['config']['rule_set'] = $_POST['config']['rule_set'];
                $ruleSet = $_POST['config']['rule_set'];
            }

            $options['claim_rules'][$ruleSet] = $_POST['claim_rules'][$ruleSet];
            if (isset($options['claim_rules'][$ruleSet]['rules']['__counter__'])) {
                unset($options['claim_rules'][$ruleSet]['rules']['__counter__']);
            }
            foreach ($options['claim_rules'][$ruleSet]['rules'] as &$rule) {
                if (!isset($rule['amount_min']) || !isset($rule['probability'])) {
                    $rule = null;
                    continue;
                }
                if (!$rule['amount_min']) {
                    $rule = null;
                    continue;
                }
                $rule = array(
                    'amount_min' => (float)$rule['amount_min'],
                    'amount_max' => (float)$rule['amount_min'],
                    'probability' => (float)$rule['probability'],
                    'status' => 'enabled',
                );
            }
            unset($rule);
            $options['claim_rules'][$ruleSet]['rules'] = array_values(array_filter($options['claim_rules'][$ruleSet]['rules']));

            if (isset($options['claim_rules'][$ruleSet]['threshold'])) {
                $options['claim_rules'][$ruleSet]['threshold'] = (int)$options['claim_rules'][$ruleSet]['threshold'];
            }
            if (isset($options['claim_rules'][$ruleSet]['exchange_rate'])) {
                $options['claim_rules'][$ruleSet]['exchange_rate'] = (float)$options['claim_rules'][$ruleSet]['exchange_rate'];
            }
            if (isset($options['claim_rules'][$ruleSet]['exchange_rate_auto'])) {
                $options['claim_rules'][$ruleSet]['exchange_rate_auto'] = (bool)$options['claim_rules'][$ruleSet]['exchange_rate_auto'];
            }

            update_option($this->config['prefix'] . 'main', $options, 'no');
            $variables = $options + $variables;
            $variables['notice_css_class'] = 'notice notice-success';
            $variables['notice_message'] = translate('Changes have been saved', '99btc-bf');
        }
        if ($ruleSet) {
            $variables['config']['rule_set'] = $ruleSet;
        }
        $this->render('admin-claim-rules', $variables, 'default');
    }

    public function adminPageClaims()
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        $this->migrationChanges->switchToPrimaryDatabase();

        $variables = array();
        $options = get_option($this->config['prefix'] . 'main', array());
        $variables['options'] = $options;
        $cronStampDb = get_option($this->config['prefix'] . 'cron', 0);
        $cronStampValue = microtime(true);
        $variables['clear'] = $cronStampValue - $cronStampDb >= 60 * 5;
        $stampMidnight = time();
        $stampMidnight -= $stampMidnight % (60 * 60) + gmdate('H', $stampMidnight) * 60 * 60;

        $claimRules = $this->getClaimRules($options);

        $payFee = empty($options['optout']) || !empty($options['pay']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'clear') {
            foreach ($this->scheduledPayment->search($claimRules->currency()->symbol(), 0, 0) as $transaction) {
                $this->scheduledPayment->rollback($transaction['id']);
                $this->claimPayouts->rollback($transaction['id']);
            }
            $variables['notice_css_class'] = 'notice notice-warning';
            $variables['notice_message'] = __('Payout was cancelled', '99btc-bf');
            $options['stats']['last_payout'] = $this->scheduledPayouts->getLastStamp();
            update_option($this->config['prefix'] . 'main', $options, 'no');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'make') {
            $started = time();
            $result = $this->makePayout($claimRules->currency()->symbol(), $options, $variables);
            if (time() - $started > 60) {
                wp_mail(
                    wp_get_current_user()->data->user_email,
                    __('[Faucet Plugin] Payout has been scheduled', '99btc-bf') . ($result < 0 ? ' ' . __('WITH WARNING', '99btc-bf') : ''),
                    __('Hi.', '99btc-bf') . PHP_EOL . PHP_EOL .
                    __('You got this email because to schedule payout had taken more than 1 minute.', '99btc-bf') . PHP_EOL .
                    __('Now it has been scheduled and payment will start in 10-20 minutes.', '99btc-bf') . PHP_EOL . PHP_EOL .
                    (!empty($variables['notice_message']) < 0 ? __('WARNING', '99btc-bf') . ': ' . $variables['notice_message'] . PHP_EOL . PHP_EOL : '') .
                    site_url('/')
                );
            }

            if ($result) {
                if (empty($variables['notice_message'])) {
                    $variables['notice_css_class'] = 'notice notice-success';
                    $variables['notice_message'] = __('Payout has been scheduled', '99btc-bf');
                    $options['stats']['last_payout'] = $started;
                    update_option($this->config['prefix'] . 'main', $options, 'no');
                }
            } elseif (empty($variables['notice_message'])) {
                $variables['notice_css_class'] = 'notice notice-error';
                $variables['notice_message'] = __('Something went wrong with scheduling', '99btc-bf');
            }
        }

        $variables['grouped_labels'] = array();
        $variables['grouped_total'] = array();
        $variables['grouped_direct'] = array();
        $variables['grouped_seniority'] = array();
        $variables['grouped_referral'] = array();
        $variables['grouped_bonus'] = array();
        $variables['grouped_penalty'] = array();
        $variables['grouped_submits'] = array();
        $range = array();
        for ($i = 30; $i > -1; $i--) {
            $stamp = $stampMidnight - $i * 60 * 60 * 24;
            if (empty($range[0])) {
                $range[0] = $stamp;
            } elseif ($range[0] > $stamp) {
                $range[0] = $stamp;
            }
            if (empty($range[1])) {
                $range[1] = $stamp;
            } elseif ($range[1] < $stamp) {
                $range[1] = $stamp;
            }

            $variables['grouped_labels'][$stamp] = date_i18n('d M', $stamp);
            $variables['grouped_total'][$stamp] = 0;
            $variables['grouped_direct'][$stamp] = 0;
            $variables['grouped_seniority'][$stamp] = 0;
            $variables['grouped_referral'][$stamp] = 0;
            $variables['grouped_bonus'][$stamp] = 0;
            $variables['grouped_penalty'][$stamp] = 0;
            $variables['grouped_submits'][$stamp] = 0;
        }
        foreach ($variables['grouped_labels'] as $stamp => $label) {
            foreach ($this->claimStats->get($claimRules->currency()->symbol(), $stamp) as $type => $amount) {
                $variables['grouped_' . $type][$stamp] = $amount;
            }
        }

        $variables['grouped_labels'] = array_values($variables['grouped_labels']);
        $variables['grouped_total'] = array_values($variables['grouped_total']);
        $variables['grouped_direct'] = array_values($variables['grouped_direct']);
        $variables['grouped_seniority'] = array_values($variables['grouped_seniority']);
        $variables['grouped_referral'] = array_values($variables['grouped_referral']);
        $variables['grouped_bonus'] = array_values($variables['grouped_bonus']);
        $variables['grouped_penalty'] = array_values($variables['grouped_penalty']);
        $variables['grouped_submits'] = array_values($variables['grouped_submits']);

        $variables['total'] = array_sum($variables['grouped_total']);

        $addresses = array();
        $variables['threshold_total'] = 0;
        $variables['threshold_direct'] = 0;
        $variables['threshold_seniority'] = 0;
        $variables['threshold_referral'] = 0;
        $variables['threshold_extra'] = 0;
        foreach (array_chunk($this->claimPayouts->searchGrouped($claimRules->threshold(), $claimRules->currency()->symbol(), $options['stats']['last_payout']), 300) as $set) {
            if (!empty($options['config']['only_users']) && !empty($options['config']['only_users_single_pay'])) {
                $setAddresses = array();
                foreach ($set as $address) {
                    $setAddresses[] = $address['address'];
                }
                $setAddresses = array_intersect($setAddresses, $wpdb->get_col($wpdb->prepare("
                    SELECT
                        meta_value address
                    FROM
                        {$wpdb->prefix}usermeta
                    WHERE
                        meta_key = %s
                        AND
                        meta_value IN ('" . implode("', '", array_map('esc_sql', $setAddresses)) . "')
                ", 'the99btc_address_' . $claimRules->currency()->symbol())));
                foreach ($set as &$address) {
                    if (!in_array($address['address'], $setAddresses)) {
                        $address = null;
                    }
                }
                unset($address);
                $set = array_filter($set);
            }
            foreach ($set as $address) {
                $addresses[] = $address['address_id'];
                $variables['threshold_total'] += $address['total'];
            }
        }
        while ($addresses) {
            $search = array_slice($addresses, 0, 300);
            $addresses = array_slice($addresses, 300);
            foreach ($wpdb->get_results($wpdb->prepare(
                "SELECT SUM(amount) as total, source FROM {$this->config['db_prefix']}claim_payouts WHERE scheduled_payouts_id = 0 AND paid = 'no' AND address_id IN (" . implode(', ', array_pad(array(), count($search), '%d')) . ") GROUP BY source",
                $search
            ), ARRAY_A) as $source) {
                if ($source['source'] == 'direct') {
                    $variables['threshold_direct'] += $source['total'];
                } elseif ($source['source'] == 'seniority') {
                    $variables['threshold_seniority'] += $source['total'];
                } elseif ($source['source'] == 'referral') {
                    $variables['threshold_referral'] += $source['total'];
                } elseif ($source['source'] == 'bonus') {
                    $variables['threshold_extra'] += $source['total'];
                } elseif ($source['source'] == 'penalty') {
                    $variables['threshold_extra'] += $source['total'];
                }
            }
        }

        $variables['threshold_fee'] = round($variables['threshold_total'] * $this->support['fee']);
        if ($payFee) {
            $variables['threshold_total'] += $variables['threshold_fee'];
        }

        $variables['scheduled_total'] = 0;
        $variables['scheduled_addresses'] = 0;
        foreach ($this->scheduledPayouts->search('0000-00-00', '0000-00-00', $claimRules->currency()->symbol()) as $payout) {
            $variables['scheduled_total'] += $payout['amount'];
            $variables['scheduled_addresses'] += 1;
        }

        /** @var The99Bitcoins_BtcFaucet_Wallet_WalletInterface $client */
        $client = $this->getWallet($options, $claimRules->currency()->symbol());
        $variables['wallet_balance'] = -1;
        $variables['wallet_fee'] = false;
        $variables['wallet_size'] = 0;
        if ($client) {
            $variables['wallet_balance'] = $client->getBalance();
            $variables['wallet_size'] = $client->limit;
            $variables['wallet_fee'] = $client->fee > 0;
        }

        $cronStampDb = get_option($this->config['prefix'] . 'cron_stamp', 0);
        $variables['last_cron'] = -1;
        if ($cronStampDb && $cronSeconds = microtime(true) - $cronStampDb) {
            $variables['last_cron'] = floor($cronSeconds / 60);
        }

        $variables['currency'] = $claimRules->currency();

        $this->render('admin-claims', $variables, 'default');
    }

    public function adminPagePayouts()
    {
        $variables = array();

        $options = get_option($this->config['prefix'] . 'main', array());
        $claimRules = $this->getClaimRules($options);
        $variables['currency'] = $claimRules->currency();

        $variables['options'] = get_option($this->config['prefix'] . 'main', array());
        $variables['search_payouts_from_date'] = !empty($_REQUEST['search_payouts_from_date']) ? $_REQUEST['search_payouts_from_date'] : '';
        $variables['search_payouts_to_date'] = !empty($_REQUEST['search_payouts_to_date']) ? $_REQUEST['search_payouts_to_date'] : '';
        $variables['payouts'] = $this->scheduledPayouts->search($variables['search_payouts_from_date'], $variables['search_payouts_to_date'], $claimRules->currency()->symbol());
        $variables['payouts_amount'] = $this->scheduledPayouts->searchAmount($variables['search_payouts_from_date'], $variables['search_payouts_to_date']);
        $this->render('admin-payouts', $variables, 'default');
    }

    public function adminPageLog()
    {
        $variables = array();

        $options = get_option($this->config['prefix'] . 'main', array());
        $claimRules = $this->getClaimRules($options);
        $variables['currency'] = $claimRules->currency();

        $variables['options'] = get_option($this->config['prefix'] . 'main', array());
        $variables['search_payouts_source'] = !empty($_REQUEST['search_payouts_source']) ? $_REQUEST['search_payouts_source'] : '';
        $variables['search_payouts_from_date'] = !empty($_REQUEST['search_payouts_from_date']) ? $_REQUEST['search_payouts_from_date'] : '';
        $variables['search_payouts_to_date'] = !empty($_REQUEST['search_payouts_to_date']) ? $_REQUEST['search_payouts_to_date'] : '';
        $variables['payouts'] = $this->claimPayouts->search($variables['search_payouts_source'], $variables['search_payouts_from_date'], $variables['search_payouts_to_date']);
        $variables['payouts_amount'] = $this->claimPayouts->searchAmount($variables['search_payouts_source'], $variables['search_payouts_from_date'], $variables['search_payouts_to_date']);
        $this->render('admin-log', $variables, 'default');
    }

    public function adminPageProtection()
    {
        $variables = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addresswhite']['address'])) {
            $reason = !empty($_POST['addresswhite']['reason']) ? $_POST['addresswhite']['reason'] : __('Admin Dashboard', '99btc-bf');
            $this->banAddresses->white($_POST['addresswhite']['address'], $reason);
            $variables['notice_css_class'] = 'notice notice-success';
            $variables['notice_message'] = __('Bitcoin address has been whitelisted', '99btc-bf');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addressblack']['address'])) {
            $reason = !empty($_POST['addressblack']['reason']) ? $_POST['addressblack']['reason'] : __('Admin Dashboard', '99btc-bf');
            $this->banAddresses->ban($_POST['addressblack']['address'], $reason, !empty($_POST['addressblack']['recursive']));
            $variables['notice_css_class'] = 'notice notice-success';
            $variables['notice_message'] = __('Bitcoin address has been blacklisted', '99btc-bf');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ipblack']['from'])) {
            $reason = !empty($_POST['ipblack']['reason']) ? $_POST['ipblack']['reason'] : __('Admin Dashboard', '99btc-bf');
            if (!empty($_POST['ipblack']['to'])) {
                $this->banIps->banRange($_POST['ipblack']['from'], $_POST['ipblack']['to'], $reason);
            } else {
                $this->banIps->ban($_POST['ipblack']['from'], $reason);
            }
            $variables['notice_css_class'] = 'notice notice-success';
            $variables['notice_message'] = __('Ip address has been blacklisted', '99btc-bf');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['search_white_address_delete'])) {
            $this->banAddresses->unwhite($_POST['search_white_address_delete']);
            $variables['notice_css_class'] = 'notice notice-success';
            $variables['notice_message'] = __('Bitcoin address has been removed from whitelist', '99btc-bf');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['search_ban_address_unban'])) {
            $this->banIps->unbanAddress($_POST['search_ban_address_unban'], isset($_POST['search_ban_address_style']) && $_POST['search_ban_address_style'] == 'all');
            $variables['notice_css_class'] = 'notice notice-success';
            $variables['notice_message'] = __('Bitcoin address has been removed from blacklist', '99btc-bf');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['search_ban_ip_unban'])) {
            $this->banIps->unbanIp($_POST['search_ban_ip_unban'], isset($_POST['search_ban_ip_style']) && $_POST['search_ban_ip_style'] == 'all');
            $variables['notice_css_class'] = 'notice notice-success';
            $variables['notice_message'] = __('Ip address has been removed from blacklist', '99btc-bf');
        }

        $variables['search_ban_address'] = !empty($_REQUEST['search_ban_address']) ? $_REQUEST['search_ban_address'] : '';
        $variables['ban_addresses'] = $this->banAddresses->search($variables['search_ban_address']);
        $variables['search_white_address'] = !empty($_REQUEST['search_white_address']) ? $_REQUEST['search_white_address'] : '';
        $variables['white_addresses'] = $this->banAddresses->searchWhite($variables['search_white_address']);
        $variables['search_ban_ip_from'] = !empty($_REQUEST['search_ban_ip_from']) ? $_REQUEST['search_ban_ip_from'] : '';
        $variables['search_ban_ip_to'] = !empty($_REQUEST['search_ban_ip_to']) ? $_REQUEST['search_ban_ip_to'] : '';
        $variables['ban_ips'] = $this->banIps->search($variables['search_ban_ip_from'], $variables['search_ban_ip_to']);
        $variables['show'] = !empty($_REQUEST['show']) ? $_REQUEST['show'] : '';
        $this->render('admin-protection', $variables, 'default');
    }

    public function adminPageWallet()
    {
        $variables = array();
        $variables['show'] = '';
        $options = get_option($this->config['prefix'] . 'main', array());

        $claimRules = $this->getClaimRules($options);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['wallet']['Bitcoind'])) {
                $variables['show'] = 'Bitcoind';
                $options['wallet']['Bitcoind'] = array(
                    'url' => $_POST['wallet']['Bitcoind']['url'],
                    'secret' => $_POST['wallet']['Bitcoind']['secret'],
                    'supports' => array(
                        'BTC',
                    ),
                );
                if (!empty($_POST['wallet']['Bitcoind']['supports'])) {
                    $options['wallet']['Bitcoind']['supports'] = array_values(
                        array_intersect(
                            $options['wallet']['Bitcoind']['supports'],
                            $_POST['wallet']['Bitcoind']['supports']
                        )
                    );
                }
            }
            if (isset($_POST['wallet']['faucetpay'])) {
                $variables['show'] = 'faucetpay';
                if (empty($_POST['wallet']['faucetpay']['supports'])) {
                    $_POST['wallet']['faucetpay']['supports'] = array();
                }
                $options['wallet']['faucetpay'] = array(
                    'api_key' => $_POST['wallet']['faucetpay']['api_key'],
                    'supports' => array(
	                    'BCH',
	                    'BLK',
                        'BTC',
	                    'BTX',
                        'DASH',
                        'DOGE',
                        'ETH',
                        'LTC',
	                    'POT',
	                    'PPC',
	                    'XPM',
                    ),
                );
                if (!empty($_POST['wallet']['faucetpay']['supports'])) {
                    $options['wallet']['faucetpay']['supports'] = array_values(
                        array_intersect(
                            $options['wallet']['faucetpay']['supports'],
                            $_POST['wallet']['faucetpay']['supports']
                        )
                    );
                }

            }
            update_option($this->config['prefix'] . 'main', $options, 'no');
            $variables['notice_css_class'] = 'notice notice-success';
            $variables['notice_message'] = __('Data has been saved', '99btc-bf');
        }

        $variables['config'] = $options['config'];
        $variables['wallet'] = $options['wallet'];
        $variables['bitcoind_status'] = __('Not configured', '99btc-bf');
        $variables['epayinfo_status'] = __('Not configured', '99btc-bf');
        $variables['faucetpayio_status'] = __('Not configured', '99btc-bf');
        if ($options['wallet']['Bitcoind']['url']) {
            $client = new The99Bitcoins_BtcFaucet_Wallet_Bitcoind($options['wallet']['Bitcoind'], $claimRules->currency()->symbol());
            if (!$client->isAccessible()) {
                $variables['bitcoind_status'] = __('Unreachable', '99btc-bf');
            } else {
                $variables['bitcoind_status'] = sprintf(__('Active: %s ' . $claimRules->currency()->satoshi(), '99btc-bf'), number_format_i18n($client->getBalance()));
                if (!$variables['show']) {
                    $variables['show'] = 'Bitcoind';
                }
            }
        }
        if ($options['wallet']['faucetpay']['api_key']) {
            $client = new The99Bitcoins_BtcFaucet_Wallet_faucetpay($options['wallet']['faucetpay'], $claimRules->currency()->symbol());
            if (!$client->isAccessible()) {
                $variables['faucetpayio_status'] = __('Unreachable', '99btc-bf');
            } else {
                $variables['faucetpayio_status'] = sprintf(__('Active: %s ' . $claimRules->currency()->satoshi(), '99btc-bf'), number_format_i18n($client->getBalance()));
                if (!$variables['show']) {
                    $variables['show'] = 'faucetpay';
                }
            }
        }

        $this->render('admin-wallet', $variables, 'default');
    }

    public function adminPageTools()
    {
        $variables = array();

        $options = get_option($this->config['prefix'] . 'main', array());
        $claimRules = $this->getClaimRules($options);
        $variables['currency'] = $claimRules->currency();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_seniority']['address'])) {
            $info = $this->infoAddresses->get($_POST['reset_seniority']['address']);
            if ($info) {
                /** @var wpdb $wpdb */
                global $wpdb;
                $wpdb->query(
                    $wpdb->prepare(
                        "UPDATE {$this->config['db_prefix']}info_address SET seniority_first = %d, seniority_current = %d WHERE id = %d",
                        0,
                        0,
                        $info['id']
                    )
                );
            }
            $variables['notice_css_class'] = 'notice notice-success';
            $variables['notice_message'] = translate('Seniority has been reset', '99btc-bf');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['move_balance']['from']) && isset($_POST['move_balance']['to'])) {
            $infoFrom = $this->infoAddresses->get($_POST['move_balance']['from']);
            $infoTo = $this->infoAddresses->get($_POST['move_balance']['to']);
            if ($infoFrom && $infoTo) {
                /** @var wpdb $wpdb */
                global $wpdb;
                $wpdb->query(
                    $wpdb->prepare(
                        "UPDATE {$this->config['db_prefix']}claim_payouts SET address_id = %d WHERE address_id = %d",
                        $infoTo['id'],
                        $infoFrom['id']
                    )
                );
                $wpdb->query(
                    $wpdb->prepare(
                        "UPDATE {$this->config['db_prefix']}info_address SET refer_id = %d WHERE refer_id = %d",
                        $infoTo['id'],
                        $infoFrom['id']
                    )
                );
                $variables['notice_css_class'] = 'notice notice-success';
                $variables['notice_message'] = translate('Balance has been moved', '99btc-bf');
            } elseif ($infoFrom) {
                $variables['notice_css_class'] = 'notice notice-error';
                $variables['notice_message'] = translate('From address was not registered', '99btc-bf');
            } elseif (!$infoTo) {
                $variables['notice_css_class'] = 'notice notice-error';
                $variables['notice_message'] = translate('To address was not registered', '99btc-bf');
            } else {
                $variables['notice_css_class'] = 'notice notice-error';
                $variables['notice_message'] = translate('Unknown error', '99btc-bf');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['balance']['address']) && isset($_POST['balance']['amount'])) {
            $info = $this->infoAddresses->get($_POST['balance']['address']);
            if ($info && is_numeric($_POST['balance']['amount'])) {
                $this->claimPayouts->claim($info['id'], $_POST['balance']['amount'], $_POST['balance']['amount'] < 0 ? 'penalty' : 'bonus');
            }
            $variables['notice_css_class'] = 'notice notice-success';
            $variables['notice_message'] = translate('Balance has been updated', '99btc-bf');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['reset_data'])) {
	        $this->migrationChanges->truncate();
            delete_option($this->config['prefix'] . 'main');
            delete_option($this->config['prefix'] . 'cron_stamp');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['cron_install'])) {
            wp_clear_scheduled_hook('The99BitcoinsBtcFaucetCron');
            wp_schedule_event(current_time('timestamp'), 'the99btc_payout', 'The99BitcoinsBtcFaucetCron');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['cron_run'])) {
            do_action('The99BitcoinsBtcFaucetCron');
        }

        $this->render('admin-tools', $variables, 'default');
    }

    public function adminPageStatistic()
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        $variables = array();
        $variables['options'] = get_option($this->config['prefix'] . 'main', array());

        $stamp = time();
        $stamp -= $stamp % (60 * 60) + gmdate('H') * 60 * 60;
        $variables['daily_claims'] = array();
        for ($i = 0; $i < 24; $i++) {
            $key = $stamp -  60 * 60 * 24 * $i;
            $end = $key + 60 * 60 * 24 - 1;
            $variables['daily_claims'][$key] = $wpdb->get_var($wpdb->prepare(
                "SELECT sum(amount) FROM {$this->config['db_prefix']}claim_payouts WHERE stamp BETWEEN %d AND %d",
                $key,
                $end
            ));
            if (!$variables['daily_claims'][$key]) {
                $variables['daily_claims'][$key] = 0;
            }
        }

        $stamp = time();
        $stamp -= $stamp % (60 * 60);
        $variables['hourly_cliaims'] = array();
        for ($i = 0; $i < 24; $i++) {
            $key = $stamp - 60 * 60 * $i;
            $end = $key + 60 * 60 - 1;
            $variables['hourly_cliaims'][$key] = $wpdb->get_var($wpdb->prepare(
                "SELECT sum(amount) FROM {$this->config['db_prefix']}claim_payouts WHERE stamp BETWEEN %d AND %d",
                $key,
                $end
            ));
            if (!$variables['hourly_cliaims'][$key]) {
                $variables['hourly_cliaims'][$key] = 0;
            }
        }

        $stamp = time();
        $records = $wpdb->get_results($wpdb->prepare(
            "SELECT
                    address.address,
                    sum(source.amount) amount
                FROM
                    {$this->config['db_prefix']}claim_payouts source
                JOIN
                    {$this->config['db_prefix']}info_address address
                ON
                    address.id = source.address_id
                WHERE
                    source.stamp BETWEEN %d AND %d
                    AND source.source = %s
                GROUP BY
                    source.address_id
                ORDER BY
                    amount DESC
                LIMIT 20",
            $stamp - 60 * 60 * 24 + 1,
            $stamp,
            'referral'
        ), ARRAY_A);
        $variables['top_claim_referral'] = array();
        foreach ($records as $row) {
            $variables['top_claim_referral'][$row['address']] = $row['amount'];
        }

        $stamp = time();
        $records = $wpdb->get_results($wpdb->prepare(
            "SELECT
                    address.address,
                    sum(source.amount) amount
                FROM
                    {$this->config['db_prefix']}claim_payouts source
                JOIN
                    {$this->config['db_prefix']}info_address address
                ON
                    address.id = source.address_id
                WHERE
                    source.stamp BETWEEN %d AND %d
                    AND source.source = %s
                GROUP BY
                    source.address_id
                ORDER BY
                    amount DESC
                LIMIT 20",
            $stamp - 60 * 60 * 24 + 1,
            $stamp,
            'direct'
        ), ARRAY_A);
        $variables['top_claim_direct'] = array();
        foreach ($records as $row) {
            $variables['top_claim_direct'][$row['address']] = $row['amount'];
        }

        $stamp = time();
        $records = $wpdb->get_results($wpdb->prepare(
            "SELECT
                    address.address,
                    count(1) amount
                FROM
                    {$this->config['db_prefix']}claim_payouts source
                JOIN
                    {$this->config['db_prefix']}info_address address
                ON
                    address.id = source.address_id
                WHERE
                    source.stamp BETWEEN %d AND %d
                    AND source.source = %s
                GROUP BY
                    source.address_id
                ORDER BY
                    amount DESC
                LIMIT 20",
            $stamp - 60 * 60 * 24 + 1,
            $stamp,
            'referral'
        ), ARRAY_A);
        $variables['top_submit_referral'] = array();
        foreach ($records as $row) {
            $variables['top_submit_referral'][$row['address']] = $row['amount'];
        }

        $stamp = time();
        $records = $wpdb->get_results($wpdb->prepare(
            "SELECT
                    address.address,
                    count(1) amount
                FROM
                    {$this->config['db_prefix']}claim_payouts source
                JOIN
                    {$this->config['db_prefix']}info_address address
                ON
                    address.id = source.address_id
                WHERE
                    source.stamp BETWEEN %d AND %d
                    AND source.source = %s
                GROUP BY
                    source.address_id
                ORDER BY
                    amount DESC
                LIMIT 20",
            $stamp - 60 * 60 * 24 + 1,
            $stamp,
            'direct'
        ), ARRAY_A);
        $variables['top_submit_direct'] = array();
        foreach ($records as $row) {
            $variables['top_submit_direct'][$row['address']] = $row['amount'];
        }

        $stamp = time();
        $records = $wpdb->get_results($wpdb->prepare(
            "SELECT
                    ip.ip,
                    address.address
                FROM
                    {$this->config['db_prefix']}claim_ips source
                JOIN
                    {$this->config['db_prefix']}info_address address
                ON
                    address.id = source.address_id
                JOIN
                    {$this->config['db_prefix']}info_ip ip
                ON
                    ip.id = source.ip_id
                WHERE
                    source.stamp BETWEEN %d AND %d",
            $stamp - 60 * 60 * 24 + 1,
            $stamp
        ), ARRAY_A);
        $variables['top_success_neworks'] = array();
        $variables['top_success_neworks_ips'] = array();
        foreach ($records as $data) {
            $ip = inet_ntop($data['ip']);
            if (preg_match('/^\d+\.\d+\.\d+\.\d+$/', $ip)) {
                $ip = explode('.', inet_ntop($data['ip']));
                $ip = array_slice($ip, 0, 2);
                $ip = array_pad($ip, 4, 0);
                $ip = implode('.', $ip);
            }
            $variables['top_success_neworks'][$ip][$data['address']] = 1;
            $variables['top_success_neworks_ips'][$ip][] = $data['ip'];
        }
        foreach ($variables['top_success_neworks_ips'] as $ip => $range) {
            $variables['top_success_neworks_ips'][$ip] = array(inet_ntop(min($range)), inet_ntop(max($range)));
        }
        uasort($variables['top_success_neworks'], array($this, '__sortAB'));
        $variables['top_success_neworks_total'] = count($variables['top_success_neworks']);
        $variables['top_success_neworks'] = array_filter($variables['top_success_neworks'], array($this, '__filterValue'));
        $variables['top_success_neworks_5'] = count($variables['top_success_neworks']);

        $this->render('admin-statistic', $variables, 'default');
    }

    protected function __filterValue($value)
    {
        return count($value) > 5;
    }

    protected function __sortAB($a, $b)
    {
        return count($a) < count($b);
    }

    public function adminPageCheckInformation()
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        $variables = array();
        $term = '';
        if (!empty($_REQUEST['term'])) {
            $term = trim($_REQUEST['term']);
        }
        $users = array();
        $ips = array();
        $addresses = array();

        if ($term) {
            $address = $this->infoAddresses->get($term);
            if ($address) {
                $addresses[] = $address['id'];
                $users = array_merge(
                    $users,
                    $wpdb->get_col($wpdb->prepare("SELECT DISTINCT user_id FROM {$this->config['db_prefix']}claim_ips WHERE address_id = %d", $address['id']))
                );
                $ips = array_merge(
                    $ips,
                    $wpdb->get_col($wpdb->prepare("SELECT DISTINCT ip_id FROM {$this->config['db_prefix']}claim_ips WHERE address_id = %d", $address['id']))
                );
            }
            $ip = $this->infoIps->get($term);
            if ($ip) {
                $ips[] = $ip['id'];
                $users = array_merge(
                    $users,
                    $wpdb->get_col($wpdb->prepare("SELECT DISTINCT user_id FROM {$this->config['db_prefix']}claim_ips WHERE ip_id = %d", $ip['id']))
                );
                $addresses = array_merge(
                    $addresses,
                    $wpdb->get_col($wpdb->prepare("SELECT DISTINCT address_id FROM {$this->config['db_prefix']}claim_ips WHERE ip_id = %d", $ip['id']))
                );
            }
            foreach ($wpdb->get_col($wpdb->prepare("SELECT DISTINCT ID FROM {$wpdb->prefix}users WHERE user_email = %s ORDER BY ID", $term)) as $userId) {
                $user = $this->infoUsers->get($userId);
                if ($user) {
                    $addresses = array_merge(
                        $addresses,
                        $wpdb->get_col($wpdb->prepare("SELECT DISTINCT address_id FROM {$this->config['db_prefix']}claim_ips WHERE user_id = %d", $user['id']))
                    );
                    $ips = array_merge(
                        $ips,
                        $wpdb->get_col($wpdb->prepare("SELECT DISTINCT ip_id FROM {$this->config['db_prefix']}claim_ips WHERE user_id = %d", $user['id']))
                    );
                }
            }

            $addresses = array_unique($addresses);
            foreach ($addresses as &$id) {
                $id = $this->infoAddresses->getById($id);
            }
            unset($id);
            $addresses = array_filter($addresses);
            sort($addresses);

            $users = array_unique($users);
            foreach ($users as &$id) {
                $id = $wpdb->get_var($wpdb->prepare("SELECT user_email FROM {$wpdb->prefix}users WHERE ID = %d", $this->infoUsers->getById($id)));
            }
            unset($id);
            $users = array_filter($users);
            sort($users);

            $ips = array_unique($ips);
            foreach ($ips as &$id) {
                $id = $this->infoIps->getById($id);
            }
            unset($id);
            $ips = array_filter($ips);
            sort($ips);

            if (!$addresses && !$users && !$ips) {
                $variables['notice_css_class'] = 'notice notice-warning';
                $variables['notice_message'] = __('No information has been found', '99btc-bf');
            }
        }

        $variables['term'] = $term;
        $variables['users'] = $users;
        $variables['ips'] = $ips;
        $variables['addresses'] = $addresses;
        $this->render('admin-check-information', $variables, 'default');
    }

    public function adminPageHowToUse()
    {
        $this->render('admin-how-to-use', array(), 'default');
    }

    public function adminPageSupport()
    {
        global $wpdb;
        $variables = array();
        $variables['options'] = get_option($this->config['prefix'] . 'main', array());

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['action']) && $_POST['action'] == 'optout') {
            $variables['notice_css_class'] = 'notice notice-success';
            $variables['notice_message'] = esc_html__('Opt out status was switched', '99btc-bf');

            $variables['options']['pay'] = true;
            $variables['options']['optout'] = empty($variables['options']['optout']);
            update_option($this->config['prefix'] . 'main', $variables['options'], 'no');
        }
        if (empty($variables['options']['optout']) && $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['action']) && $_POST['action'] == 'send') {
            $variables['notice_css_class'] = 'notice notice-success';
            $variables['notice_message'] = esc_html__('Thank you, message has been sent', '99btc-bf');

            $message = !empty($_POST['message']) ? nl2br(htmlspecialchars(trim($_POST['message']))) : 'No message';

            $files = array();
            if (!empty($_POST['diagnostic'])) {
                $dir = get_temp_dir();
                if (wp_is_writable($dir)) {
                    $filename = get_temp_dir() . md5(microtime(true)) . '.sql.gz';
                    $f = gzopen($filename, 'wb9');
                    foreach ($this->migrationChanges->tables as $table) {
                        foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . $table, ARRAY_A) as $row) {
                            foreach ($row as $k => $data) {
                                $row[$k] = "'" . esc_sql($data) . "'";
                            }
                            gzwrite($f, 'INSERT INTO wp_' . $table . ' (' . implode(', ', array_keys($row)) . ') VALUES (' . implode(', ', $row) . ");\n");
                        }
                    }
                    gzclose($f);
                    $message .= "<br><br>Diagnostic should be attached";
                    $files[] = $filename;
                } else {
                    $message .= "<br><br>No diagnostic was attached because temp dir was not writable";
                }
            } else {
                $message .= "<br><br>No diagnostic was attached";
            }

            ob_start();
            phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES | INFO_ENVIRONMENT | INFO_VARIABLES);
            $content = ob_get_contents();
            ob_end_clean();
            $message .= '<br><br>' . $content;

            /** @var WP_User $user */
            $user = wp_get_current_user();
            $headers = array(
                'Content-Type: text/html',
                'Reply-To: ' . $user->user_email,
                'Sender: ' . $user->user_email,
            );

            wp_mail('plugin@99bitcoins.zendesk.com', 'Support requested for website ' . get_bloginfo('url'), $message, implode("\n\r", $headers) , $files);

            foreach ($files as $file) {
                unlink($file);
            }
        }
        $this->render('admin-support', $variables, 'default');
    }


    public function adminPageTranslation()
    {
        $variables = array(
            'translation' => array(),
        );
        $variables = get_option($this->config['prefix'] . 'main', array()) + $variables;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $options = get_option($this->config['prefix'] . 'main', array());

            if (isset($_POST['translation'])) {
                $_POST['translation'] = array_map('trim', $_POST['translation']);
                $_POST['translation'] = array_filter($_POST['translation']);
                $options['translation'] = $_POST['translation'];
            }

            update_option($this->config['prefix'] . 'main', $options, 'no');
            $variables = $options + $variables;
            $variables['notice_css_class'] = 'notice notice-success';
            $variables['notice_message'] = translate('Changes have been saved', '99btc-bf');
        }
        $this->render('admin-translation', $variables, 'default');
    }

    public function wpPageTotalPaid($args, $body, $code)
    {
	    add_filter('gettext', array($this, 'wpFilterGettext'), 10, 3);

        /** @var wpdb $wpdb */
        global $wpdb;

        $variables = array(
            'total' => 0,
        );

        $options = get_option($this->config['prefix'] . 'main', array());
        $claimRules = $this->getClaimRules($options);
        $variables['currency'] = $claimRules->currency();

        $variables['total'] = $wpdb->get_var("select sum(amount) from {$this->config['db_prefix']}scheduled_payouts where stamp > 0");

        ob_start();
        $this->render('wp-total-paid', $variables, 'empty');
        $content = ob_get_contents();
        ob_end_clean();

	    remove_filter('gettext', array($this, 'wpFilterGettext'));
        return trim($content);
    }

    public function wpPageRefLink($args, $body, $code)
    {
    	add_filter('gettext', array($this, 'wpFilterGettext'), 10, 3);

	    $variables = array(
            'options' => get_option($this->config['prefix'] . 'main', array()),
        );

        ob_start();
        $this->render('wp-ref-link', $variables, 'empty');
        $content = ob_get_contents();
        ob_end_clean();

	    remove_filter('gettext', array($this, 'wpFilterGettext'));
        return trim($content);
    }

    /**
     * Shortcode for address check page.
     *
     * @param array|string $args
     * @param string $body
     * @param string $code
     * @return string
     */
    public function wpPageAddressCheck($args, $body, $code)
    {
	    add_filter('do_rocket_generate_caching_files', '__return_false');
	    add_filter('gettext', array($this, 'wpFilterGettext'), 10, 3);

        /** @var wpdb $wpdb */
        global $wpdb;
        $options = get_option($this->config['prefix'] . 'main', array());
        if (!empty($options['translation'])) {
            $this->ownTranslation = $options['translation'];
        }
	    if (empty($options['config']['urls_check']) && get_the_ID()) {
		    $options['config']['urls_check'] = get_permalink(get_the_ID());
		    update_option($this->config['prefix'] . 'main', $options, 'no');
	    } elseif (empty($options['config']['urls_check']) && !empty($_SERVER['REQUEST_URI'])) {
		    $options['config']['urls_check'] = $_SERVER['REQUEST_URI'];
	    }

        $claimRules = $this->getClaimRules($options);
        $stampMidnight = time();
        $stampMidnight -= $stampMidnight % (60 * 60) + gmdate('H', $stampMidnight) * 60 * 60;

        $variables = array(
            'body' => $body,
            'address' => '',
            'address_user' => '',
            'info' => array(),
            'info_user' => array(),
            'options' => $options,
        );
        if (isset($_REQUEST['the99btcbfaddress']) && empty($_REQUEST['t99fid'])) {
	        $variables['address'] = trim($_REQUEST['the99btcbfaddress']);
        } elseif (isset($_REQUEST['the99btcbfaddress']) && !empty($_REQUEST['t99fid']) && $_REQUEST['t99fid'] == $this->config['post']->ID) {
	        $variables['address'] = trim($_REQUEST['the99btcbfaddress']);
        }

        if (get_current_user_id() && $claimRules) {
            $variables['info_user'] = $this->infoUsers->get(get_current_user_id());
            $variables['address_user'] = get_user_meta(get_current_user_id(), 'the99btc_address_' . $claimRules->currency()->symbol(), true);
        }
        if (!$variables['address']) {
            $variables['address'] = $variables['address_user'];
        }
        if ($variables['address'] && $variables['address'] != $variables['address_user']) {
	        $userId = 0;
	        foreach (get_users(array(
		        'meta_key' => 'the99btc_address_BTC',
		        'meta_value' => $variables['address'],
		        'number' => 1,
		        'fields' => 'id',
	        )) as $userId) {
		        if ($userId) {
			        break;
		        }
	        }
	        if ($userId) {
		        $variables['info_user'] = $this->infoUsers->get($userId);
		        $variables['address_user'] = $variables['address'];
	        } else {
		        $variables['info_user'] = array();
		        $variables['address_user'] = '';
	        }
        }

        if ($variables['address'] && $claimRules) {
            $variables['info'] = $this->infoAddresses->get($claimRules->currency()->symbol() . ':' . $variables['address'], $options['config']['reset_seniority']);
        }
        if ($variables['info']) {
	        if (!empty($options['config']['only_users_seniority']) && isset($variables['info_user']['seniority_days'])) {
		        $variables['info']['seniority_days'] = $variables['info_user']['seniority_days'];
	        }
            $variables['info']['seniority_current_bonus'] = $this->getSeniorityBonus($options['seniority_rules'], $variables['info']['seniority_days']);
            $variables['info']['seniority_days_to_next_level'] = $this->getDaysToNextSeniorityLevel($options['seniority_rules'], $variables['info']['seniority_days']);
            $variables['info']['balance'] = $this->claimPayouts->getAmountByAddress($variables['info']['id']);
            $variables['info']['paid_total'] = $this->claimPayouts->getAmountByAddress($variables['info']['id'], 'yes');
            $variables['info']['unpaid_referral'] = $this->claimPayouts->getAmountByAddress($variables['info']['id'], 'no', 'referral');
            $variables['info']['unpaid_direct'] = $this->claimPayouts->getAmountByAddress($variables['info']['id'], 'no', 'direct');
            $variables['info']['unpaid_bonus'] = $this->claimPayouts->getAmountByAddress($variables['info']['id'], 'no', 'bonus');
            $variables['info']['unpaid_penalty'] = $this->claimPayouts->getAmountByAddress($variables['info']['id'], 'no', 'penalty');
            $variables['info']['transactions'] = $this->scheduledPayouts->transactionsByAddress($variables['info']['id']);
            $variables['info']['payouts'] = $this->claimPayouts->searchAddress($variables['info']['id']);
            $variables['info']['invitees'] = $this->infoAddresses->invitees($variables['info']['id']);
            $variables['info']['submits'] = $this->claimIps->countSubmitsFromDateForAddress($variables['info']['id'], 60 * 60 * 24 - 1, $stampMidnight);
            if ($options['config']['only_users'] && $variables['address'] == $variables['address_user']) {
                $variables['info']['submits'] = max($variables['info']['submits'], $this->claimIps->countSubmitsFromDateForUser($variables['info_user']['id'], 60 * 60 * 24 - 1, $stampMidnight));
            }

            $variables['info']['chart'] = array(
                'dates' => array(),
                'direct' => array(),
                'referral' => array(),
                'seniority' => array(),
                'bonus' => array(),
                'penalty' => array(),
                'total' => array(),
            );

            for ($i = 0; $i < 31; $i++) {
                $date = $stampMidnight - 60 * 60 * 24 * $i;
                $variables['info']['chart']['dates'][$date] = date_i18n('d M', $date);
                $variables['info']['chart']['direct'][$date] = 0;
                $variables['info']['chart']['referral'][$date] = 0;
                $variables['info']['chart']['seniority'][$date] = 0;
                $variables['info']['chart']['bonus'][$date] = 0;
                $variables['info']['chart']['penalty'][$date] = 0;
                $variables['info']['chart']['total'][$date] = 0;
                $variables['info']['chart']['submits'][$date] = $this->claimIps->countSubmitsFromDateForAddress($variables['info']['id'], 60 * 60 * 24 - 1, $date);
            }
            $result = $wpdb->get_results($wpdb->prepare(
                "SELECT stamp, amount, source FROM {$this->config['db_prefix']}claim_payouts WHERE address_id = %d AND stamp BETWEEN %d and %d",
                $variables['info']['id'],
                $stampMidnight - 60 * 60 * 24 * 30,
                $stampMidnight + 60 * 60 * 24 - 1
            ), ARRAY_A);
            foreach ($result as $row) {
                $date = $row['stamp'] - $row['stamp'] % (60 * 60) - gmdate('H', $row['stamp']) * 60 * 60;
                $variables['info']['chart'][$row['source']][$date] += $row['amount'];
                $variables['info']['chart']['total'][$date] += $row['amount'];
            }
            $variables['info']['chart']['dates'] = array_reverse(array_values($variables['info']['chart']['dates']));
            $variables['info']['chart']['direct'] = array_reverse(array_values($variables['info']['chart']['direct']));
            $variables['info']['chart']['referral'] = array_reverse(array_values($variables['info']['chart']['referral']));
            $variables['info']['chart']['seniority'] = array_reverse(array_values($variables['info']['chart']['seniority']));
            $variables['info']['chart']['bonus'] = array_reverse(array_values($variables['info']['chart']['bonus']));
            $variables['info']['chart']['penalty'] = array_reverse(array_values($variables['info']['chart']['penalty']));
            $variables['info']['chart']['total'] = array_reverse(array_values($variables['info']['chart']['total']));
            $variables['info']['chart']['submits'] = array_reverse(array_values($variables['info']['chart']['submits']));

            if (isset($_POST['the99btcbfthreshold'])) {
                $variables['info']['threshold'] = (int)$_POST['the99btcbfthreshold'];
                if ($variables['info']['threshold'] <= $this->getClaimRules($options)->threshold()) {
                    $variables['info']['threshold'] = 0;
                }
                $this->infoAddresses->threshold($variables['info']['id'], $variables['info']['threshold']);
            }
        }

        if (empty($variables['info']['threshold'])) {
            $variables['info']['threshold'] = $this->getClaimRules($options)->threshold();
        }

        $variables['claim_rules'] = $claimRules;
        if ($claimRules) {
            $variables['currency'] = $claimRules->currency();
        }

        ob_start();
        $this->render('wp-check', $variables, 'empty');
        $content = ob_get_contents();
        ob_end_clean();

        $this->ownTranslation = array();

	    remove_filter('gettext', array($this, 'wpFilterGettext'));
        return trim($content);
    }

    public function wpPageFormText($args, $body, $code)
    {
	    add_filter('gettext', array($this, 'wpFilterGettext'), 10, 3);

        if (!empty($args['placeholder'])) {
            $body = trim($body);
            if (substr($body, 0, 4) == '</p>') {
                $body = substr($body, 4);
            }
            if (substr($body, -3) == '<p>') {
                $body = substr($body, 0, -3);
            }
            $this->placeholders[$args['placeholder']] = trim($body);
        }

	    remove_filter('gettext', array($this, 'wpFilterGettext'));
        return '';
    }

    /**
     * Shortcode for submit form page.
     *
     * @param array|string $args
     * @param string $body
     * @param string $code
     * @return string
     */
    public function wpPageForm($args, $body, $code)
    {
	    add_filter('do_rocket_generate_caching_files', '__return_false');
	    add_filter('gettext', array($this, 'wpFilterGettext'), 10, 3);

        $options = get_option($this->config['prefix'] . 'main', array());
        if (!empty($options['translation'])) {
            $this->ownTranslation = $options['translation'];
        }
        if (empty($options['config']['urls_main']) && get_the_ID()) {
	        $options['config']['urls_main'] = get_permalink(get_the_ID());
	        update_option($this->config['prefix'] . 'main', $options, 'no');
        } elseif (empty($options['config']['urls_main']) && !empty($_SERVER['REQUEST_URI'])) {
	        $options['config']['urls_main'] = $_SERVER['REQUEST_URI'];
        }

        $variables = array(
            'errors' => array(),
            'timer' => 0,
            'payout' => 0,
            'options' => $options,
        );

        if ($this->migrationChanges->isBlockedExecution('faucet')) {
            $variables['error'] = __('Faucet in the middle of upgrade', '99btc-bf');
            ob_start();
            $this->render('wp-form-error', $variables, 'empty');
            $content = ob_get_contents();
            ob_end_clean();

            $this->ownTranslation = array();

            return trim($content);
        }

        $claimRules = $this->getClaimRules($options);
        if (!$claimRules || !$claimRules->isConfigured()) {
            $variables['error'] = __('Claim rules were not configured yet', '99btc-bf');
            ob_start();
            $this->render('wp-form-error', $variables, 'empty');
            $content = ob_get_contents();
            ob_end_clean();

            $this->ownTranslation = array();

            return trim($content);
        }

        $stampMidnight = time();
        $stampMidnight -= $stampMidnight % (60 * 60) + gmdate('H', $stampMidnight) * 60 * 60;

        if (empty($options['config']['captcha'])) {
            $options['config']['captcha'] = 'empty';
        }

        foreach ($this->placeholders as $k => $v) {
            $variables['placeholder_' . $k] = $v;
        }

        $variables['data'] = array(
            'address' => isset($_REQUEST['address']) ? trim($_REQUEST['address']) : '',
            'user_id' => get_current_user_id(),
            'refer' => isset($_REQUEST['r']) ? trim($_REQUEST['r']) : '',
            'ip' => $_SERVER['REMOTE_ADDR'],
        );
        if (!empty($options['config']['supports_cf']) && !empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $variables['data']['ip'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (!empty($_SERVER['HTTP_CF_CONNECTING_IP']) && $this->banIps->inSubnet($variables['data']['ip'], array_keys(array_filter($this->cfNetworks)))) {
            $variables['data']['ip'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        if ($variables['data']['user_id']) {
            $userAddress = get_user_meta($variables['data']['user_id'], 'the99btc_address_' . $claimRules->currency()->symbol(), true);
            if (!$userAddress && $claimRules->currency()->validateAddress($variables['data']['address'])) {
                update_user_meta($variables['data']['user_id'], 'the99btc_address_' . $claimRules->currency()->symbol(), $variables['data']['address']);
            }
            if (!empty($options['config']['only_users']) && !empty($options['config']['only_users_single_address']) && $userAddress) {
                $variables['data']['address'] = $userAddress;
            }
        }

        $userId = 0;
        $userData = $this->infoUsers->get($variables['data']['user_id']);
        if ($userData) {
            $userDelay = time() - $userData['stamp'];
        } else {
            $userDelay = null;
        }

        $referId = 0;
        $referData = array();
        $addressId = 0;
        $addressData = $this->infoAddresses->get($claimRules->currency()->symbol() . ':' . $variables['data']['address']);
        if ($addressData) {
            $addressDelay = time() - $addressData['stamp'];
        } else {
            $addressDelay = null;
        }

        $ipId = 0;
        $ipData = $this->infoIps->get($variables['data']['ip']);
        if ($ipData) {
            $ipDelay = time() - $ipData['stamp'];
        } else {
            $ipDelay = null;
        }

        $delay = $options['config']['payout_timer'];
        if ($delay && $userDelay !== null && $userDelay < $delay && ($delay - $userDelay) > $variables['timer']) {
            $variables['timer'] = ($delay - $userDelay);
        }
        if ($delay && $addressDelay !== null && $addressDelay < $delay && ($delay - $addressDelay) > $variables['timer']) {
            $variables['timer'] = ($delay - $addressDelay);
        }
        if ($delay && $ipDelay !== null && $ipDelay < $delay && ($delay - $ipDelay) > $variables['timer']) {
            $variables['timer'] = ($delay - $ipDelay);
        }

        $allowSubmit = true;
        if ($variables['timer']) {
            $variables['errors']['delay'] = true;
	        $allowSubmit = false;
        }

        if (!empty($options['config']['avoid_opera']) && $this->banIps->inSubnet($variables['data']['ip'], array_keys(array_filter($this->disabledNetworks)))) {
            $variables['errors']['network'] = true;
	        $allowSubmit = false;
        }

        if ($allowSubmit && !empty($_POST['t99fid']) && $_POST['t99fid'] == $this->config['post']->ID && !$claimRules->currency()->validateAddress($variables['data']['address'])) {
            $variables['errors']['address'] = true;
	        $allowSubmit = false;
        }
        if ($variables['data']['refer'] && !$claimRules->currency()->validateAddress($variables['data']['refer'])) {
            $variables['data']['refer'] = '';
        } elseif ($variables['data']['refer'] == $variables['data']['address']) {
            $variables['data']['refer'] = '';
        }
        if (!$variables['data']['user_id'] && !empty($options['config']['only_users'])) {
            $variables['errors']['only_users'] = true;
	        $allowSubmit = false;
        }

        // detecting allowed captchas
        $variables['captcha'] = array();
        if (!empty($options['config']['captcha'])) {
            $variables['captcha'][$options['config']['captcha']] = false;
        }
        if (!empty($options['config']['captchas'])) {
            $variables['captcha'] += array_combine(array_values($options['config']['captchas']), array_pad(array(), count($options['config']['captchas']), false));
        }
        if (isset($variables['captcha']['empty'])) {
            $variables['captcha']['empty'] = true;
        }
        if (isset($variables['captcha']['solvemedia']) && !empty($options['config']['solve_media_private']) && !empty($options['config']['solve_media_public']) && !empty($options['config']['solve_media_hash'])) {
            $variables['captcha']['solvemedia'] = true;
        }
        if (isset($variables['captcha']['recaptcha']) && !empty($options['config']['recaptcha_site_key']) && !empty($options['config']['recaptcha_secret_key'])) {
            $variables['captcha']['recaptcha'] = true;
        }
        if (isset($variables['captcha']['coinhive']) && !empty($options['config']['coinhive_site_key']) && !empty($options['config']['coinhive_secret_key'])) {
            $variables['captcha']['coinhive'] = true;
        }
        if (isset($variables['captcha']['bitcaptcha']) && !empty($options['config']['bitcaptcha_site_id']) && !empty($options['config']['bitcaptcha_site_key'])) {
            $variables['captcha']['bitcaptcha'] = true;
        }
        if (isset($variables['captcha']['raincaptcha']) && !empty($options['config']['raincaptcha_public']) && !empty($options['config']['raincaptcha_private'])) {
            $variables['captcha']['raincaptcha'] = true;
        }
        $variables['captcha'] = array_filter($variables['captcha']);

        // detecting ids of records
        if ($claimRules->currency()->validateAddress($variables['data']['address']) && $_SERVER['REQUEST_METHOD'] == 'POST' && array_key_exists('t99fid', $_POST)) {
            if ($variables['data']['refer']) {
                $this->infoAddresses->track($claimRules->currency()->symbol() . ':' . $variables['data']['refer'], 0);
                if (!$referData) {
                    $referData = $this->infoAddresses->get($claimRules->currency()->symbol() . ':' . $variables['data']['refer']);
                }
                $referId = $referData['id'];
            }
            $this->infoAddresses->track($claimRules->currency()->symbol() . ':' . $variables['data']['address'], $referId);
            if (!$addressData) {
                $addressData = $this->infoAddresses->get($claimRules->currency()->symbol() . ':' . $variables['data']['address']);
            }
            $addressId = $addressData['id'];
            $referId = $addressData['refer_id'];
            if ($variables['data']['user_id']) {
                $this->infoUsers->track($variables['data']['user_id'], $referId);
                if (!$userData) {
                    $userData = $this->infoUsers->get($variables['data']['user_id']);
                }
                $userId = $userData['id'];
            }
            if ($variables['data']['ip']) {
                $this->infoIps->track($variables['data']['ip'], $referId);
                if (!$ipData) {
                    $ipData = $this->infoIps->get($variables['data']['ip']);
                }
                $ipId = $ipData['id'];
            }
        }

        if ($allowSubmit && empty($variables['errors']['address']) && $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['t99fid']) && $_POST['t99fid'] == $this->config['post']->ID) {
            $variables['errors']['captcha'] = false;
            $variables['errors']['ban'] = false;
            $variables['errors']['address'] = false;

            $captcha = isset($_POST['t99fc']) ? $_POST['t99fc'] : $options['config']['captcha'];
            $captcha = isset($variables['captcha'][$captcha]) ? $captcha : $options['config']['captcha'];
            $captcha = !$variables['captcha'] || isset($variables['captcha'][$captcha]) ? $captcha : key($variables['captcha']);
            if (empty($captcha)) {
                $variables['errors']['captcha'] = true;
            } elseif (empty($variables['captcha'][$captcha])) {
                $variables['errors']['captcha'] = true;
            } elseif ($captcha === 'solvemedia') {
                if (isset($_POST["adcopy_challenge"]) && isset($_POST['adcopy_response'])) {
                    $variables['errors']['captcha'] = the99btc_solvemedia_check_answer($options['config']['solve_media_private'], $variables['data']['ip'], $_POST['adcopy_challenge'], $_POST['adcopy_response'], $options['config']['solve_media_hash']);
                    $variables['errors']['captcha'] = !$variables['errors']['captcha']->is_valid;
                } else {
                    $variables['errors']['captcha'] = true;
                }
            } elseif ($captcha === 'recaptcha') {
                if (isset($_POST['g-recaptcha-response'])) {
                    $variables['errors']['captcha'] = wp_remote_request('https://www.google.com/recaptcha/api/siteverify', array(
                        'method' => 'POST',
                        'body' => array(
                            'secret' => $options['config']['recaptcha_secret_key'],
                            'response' => $_POST['g-recaptcha-response'],
                        ),
                    ));
                    if (is_array($variables['errors']['captcha']) && $variables['errors']['captcha']['response']['code'] == 200) {
                        $variables['errors']['captcha'] = json_decode($variables['errors']['captcha']['body'], true);
                        $variables['errors']['captcha'] = $variables['errors']['captcha']['success'] == false;
                    } else {
                        $variables['errors']['captcha'] = true;
                    }
                } else {
                    $variables['errors']['captcha'] = true;
                }
            } elseif ($captcha === 'coinhive') {
                if (isset($_POST['coinhive-captcha-token'])) {
                    $variables['errors']['captcha'] = wp_remote_request('https://api.coin-hive.com/token/verify', array(
                        'method' => 'POST',
                        'body' => array(
                            'hashes' => $options['config']['coinhive_hashes'],
                            'secret' => $options['config']['coinhive_secret_key'],
                            'token' => $_POST['coinhive-captcha-token'],
                        ),
                    ));
                    if (is_array($variables['errors']['captcha']) && $variables['errors']['captcha']['response']['code'] == 200) {
                        $variables['errors']['captcha'] = json_decode($variables['errors']['captcha']['body'], true);
                        $variables['errors']['captcha'] = $variables['errors']['captcha']['success'] == false;
                    } else {
                        $variables['errors']['captcha'] = true;
                    }
                } else {
                    $variables['errors']['captcha'] = true;
                }
            } elseif ($captcha === 'bitcaptcha') {
                if (isset($_POST['sqn_captcha'])) {
                    $variables['errors']['captcha'] = !the99btc_sqn_validate($_POST['sqn_captcha'], $options['config']['bitcaptcha_site_key'], $options['config']['bitcaptcha_site_id']);
                } else {
                    $variables['errors']['captcha'] = true;
                }
            } elseif ($captcha === 'raincaptcha') {
            	if (isset($_POST['rain-captcha-response'])) {
		            $client = new \SoapClient('https://raincaptcha.com/captcha.wsdl');
		            $response = $client->send($options['config']['raincaptcha_private'], $_POST['rain-captcha-response'], $_SERVER['REMOTE_ADDR']);
		            $variables['errors']['captcha'] = empty($response->status);
	            } else {
		            $variables['errors']['captcha'] = true;
	            }
            }

            if (isset($_POST['adcopy_response']) && substr($_POST['adcopy_response'], 0, 6) == 'ERROR_') {
                $this->banAddresses->ban($variables['data']['address'], 'Captcha solver', true);
                if (!empty($variables['broadcasting_ban'])) {
                    $this->banIps->ban($variables['data']['ip'], 'Broadcasting ban ' . $variables['data']['address']);
                }
            } elseif (isset($_POST['adcopy_response']) && substr($_POST['adcopy_response'], 0, 22) == 'key:Additional fields:') {
                $this->banAddresses->ban($variables['data']['address'], 'Captcha solver', true);
                if (!empty($variables['broadcasting_ban'])) {
                    $this->banIps->ban($variables['data']['ip'], 'Broadcasting ban ' . $variables['data']['address']);
                }
            }

            $isWhiteAddress = $this->banAddresses->isWhite($variables['data']['address']);
            if (!$isWhiteAddress && $this->banAddresses->isBanned($variables['data']['address'])) {
                $variables['errors']['ban'] = true;
            }
            if (!$isWhiteAddress && $this->banIps->isBanned($variables['data']['ip'])) {
                $variables['errors']['ban'] = true;
            }
            if (!$isWhiteAddress && $variables['data']['refer'] && $this->banAddresses->isBanned($variables['data']['refer'])) {
                $this->banAddresses->ban($variables['data']['address'], 'Child of ' . $variables['data']['refer']);
                if (!empty($variables['broadcasting_ban'])) {
                    $this->banIps->ban($variables['data']['ip'], 'Broadcasting ban ' . $variables['data']['address']);
                }
                $variables['errors']['ban'] = true;
            }

            if (!empty($options['config']['submit_limitation'])) {
                if ($this->claimIps->countSubmitsFromDateForAddress($addressId, 60 * 60 * 24 - 1, $stampMidnight) > $options['config']['submit_limitation']) {
                    if (!empty($options['config']['submit_limitation_ban'])) {
                        $this->banAddresses->ban($variables['data']['address'], 'Reached daily limit ' . $options['config']['submit_limitation']);
                        if (!empty($variables['broadcasting_ban'])) {
                            $this->banIps->ban($variables['data']['ip'], 'Broadcasting ban ' . $variables['data']['address']);
                        }
                    }
                    $variables['errors']['ban'] = true;
                }
                if (!empty($options['config']['only_users']) && $this->claimIps->countSubmitsFromDateForUser($userId, 60 * 60 * 24 - 1, $stampMidnight) > $options['config']['submit_limitation']) {
                    $variables['errors']['ban'] = true;
                }
            }

            $variables['errors']['fake'] = false;
            if (!$variables['errors']['captcha'] && !empty($options['config']['fake_buttons'])) {
                if (empty($_POST['antibotbutton'])) {
                    $_POST['antibotbutton'] = '';
                }
                if (empty($_POST['antibotkey'])) {
                    $_POST['antibotkey'] = '';
                }
                $antiBotKey = $this->toolKv->get($_POST['antibotkey']);
                if ($_POST['antibotkey'] && $antiBotKey === $_POST['antibotbutton']) {
                    $this->toolKv->set($_POST['antibotkey'], null);
                } else {
                    $variables['errors']['fake'] = true;
                    $antiBotKeyBan = !$_POST['antibotbutton'] || $antiBotKey;
                    if ($antiBotKeyBan && !empty($options['config']['fake_buttons_ban'])) {
                        $this->banAddresses->ban($variables['data']['address'], 'Wrong fake button clicked');
                        if (!empty($options['config']['broadcasting_ban'])) {
                            $this->banIps->ban($variables['data']['ip'], 'Broadcasting ban ' . $variables['data']['address']);
                        }
                    }
                }
            }

            $isValid = !$variables['errors']['fake'] && !$variables['errors']['captcha'] && !$variables['errors']['ban'] && !$variables['errors']['address'];

            if ($variables['errors']['ban']) {
                if (!empty($variables['broadcasting_ban'])) {
                    $this->banAddresses->ban($variables['data']['address'], 'Broadcasting ban ' . $variables['data']['ip']);
                    $this->banIps->ban($variables['data']['ip'], 'Broadcasting ban ' . $variables['data']['address']);
                }
            }

            if ($isValid) {
                $variables['payout'] = $claimRules->lottery();
            }

            if ($variables['payout'] > 0) {
                $this->claimPayouts->claim($addressId, $variables['payout'], 'direct');
                $variables['info_address'] = $addressData;
                $variables['info_user'] = $userData;

                $variables['data']['seniority_days'] = 0;
                if ($variables['info_address']['seniority_days']) {
                    $variables['data']['seniority_days'] = $variables['info_address']['seniority_days'];
                }
                if (!empty($options['config']['only_users']) && !empty($options['config']['only_users_seniority'])) {
                    $variables['data']['seniority_days'] = $variables['info_user']['seniority_days'];
                }

                if (!empty($options['seniority_rules'])) {
                    $bonus = $this->getSeniorityBonus($options['seniority_rules'], $variables['data']['seniority_days']);
                    if ($bonus > 0) {
                        $this->claimPayouts->claim($addressId, $variables['payout'] * $bonus / 100, 'seniority');
                    }
                }
                if ($referId && !empty($options['config']['refer_bonus'])) {
                    $this->claimPayouts->claim($referId, $variables['payout'] * $options['config']['refer_bonus'] / 100, 'referral');
                    $this->infoAddresses->touch($referId);
                }

                $this->infoAddresses->seniority($addressId);
                $this->infoUsers->seniority($userId);
                $this->infoIps->seniority($ipId);
                $this->infoAddresses->touch($addressId);
                $this->infoUsers->touch($userId);
                $this->infoIps->touch($ipId);

                $variables['timer'] = $options['config']['payout_timer'];
            }
        }
        if ($allowSubmit && $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['t99fid']) && $_POST['t99fid'] == $this->config['post']->ID) {
            $this->claimIps->track($addressId, $referId, $userId, $ipId, $variables['payout'] > 0);
            $variables['info_submits'] = 0;

            if ($addressId) {
                $info_submits = $this->claimIps->countSubmitsFromDateForAddress($addressId, 60 * 60 * 24 - 1, $stampMidnight);
                $variables['info_submits'] = max($variables['info_submits'], $info_submits);
            }
            if (!empty($options['config']['only_users']) && $userId) {
                $info_submits = $this->claimIps->countSubmitsFromDateForUser($userId, 60 * 60 * 24 - 1, $stampMidnight);
                $variables['info_submits'] = max($variables['info_submits'], $info_submits);
            }
        }

        $variables['fake_buttons_before'] = 0;
        $variables['fake_buttons_after'] = 0;
        $variables['fake_buttons_value'] = md5(microtime() . rand(1000, 9999));
        if (!empty($options['config']['fake_buttons'])) {
            $variables['fake_buttons_before'] = rand(0, 10);
            $variables['fake_buttons_after'] = 10 - $variables['fake_buttons_before'];
            $variables['fake_buttons_key'] = md5(microtime() . rand(1000, 9999));
            $this->toolKv->set($variables['fake_buttons_key'], $variables['fake_buttons_value']);
            $variables['style'] = '<style>';
            $variables['style'] .= '.t99f-' . $this->config['post']->ID . ' input[type=button]{display:none;}';
            $variables['style'] .= '.t99f-' . $this->config['post']->ID . ' input#t99b' . $this->config['post']->ID . $variables['fake_buttons_before'] . '{display:inline-block;}</style>';
            $variables['style'] = $this->stringToHex($variables['style']);
        }

        $variables['claim_rules'] = $claimRules;
        $variables['currency'] = $claimRules->currency();

        ob_start();
        $this->render('wp-form', $variables, 'empty');
        $content = ob_get_contents();
        ob_end_clean();

        $this->ownTranslation = array();

	    remove_filter('gettext', array($this, 'wpFilterGettext'));
        return trim($content);
    }

    public function wpAjaxSubmit()
    {
        return $this->wpPageForm(array(), '', '');
    }

    public function wpAjaxTransaction()
    {
	    add_filter('gettext', array($this, 'wpFilterGettext'), 10, 3);

        $response = '';
        $info = array();
        $options = get_option($this->config['prefix'] . 'main', array());
        $claimRules = $this->getClaimRules($options);

        if (empty($_REQUEST['the99btcbfid'])) {
            $_REQUEST['the99btcbfid'] = 2147483647;
        }

        if (!empty($_REQUEST['the99btcbfaddress'])) {
            $info = $this->infoAddresses->get($claimRules->currency()->symbol() . ':' . $_REQUEST['the99btcbfaddress']);
        }

        if ($info) {
            $record = array();
	        $records = $this->scheduledPayouts->transactionsByAddress($info['id'], $_REQUEST['the99btcbfid']);
            foreach ($records as $record) {
            	$link = '';
                if ($record['transaction'] == 'faucetbox') {
                	$link = '<small><a href="https://faucetbox.com/en/check/' . urlencode($_REQUEST['the99btcbfaddress']) . '" rel="nofollow" target="_blank" title="' . esc_attr($_REQUEST['the99btcbfaddress']) . '">' . esc_html__('FaucetBOX', '99btc-bf') . '</a></small>';
                } elseif($record['transaction'] == 'faucetpay.io') {
                	$link = '<small><a href="https://faucetpay.io/balance/' . urlencode($_REQUEST['the99btcbfaddress']) . '" rel="nofollow" target="_blank" title="' . esc_attr($_REQUEST['the99btcbfaddress']) . '">' . esc_html__('faucetpay.io', '99btc-bf') . '</a></small>';
                } elseif($record['transaction'] == 'epay.info') {
                	$link = '<small><a href="http://epay.info/dashboard/' . urlencode($_REQUEST['the99btcbfaddress']) . '/" rel="nofollow" target="_blank" title="' . esc_attr($_REQUEST['the99btcbfaddress']) . '">' . esc_html__('epay.info', '99btc-bf') . '</a></small>';
                } else {
                	$link = '<small><a href="https://blockchain.info/tx/' . urlencode($record['transaction']) . '" rel="nofollow" target="_blank" title="' . esc_attr($record['transaction']) . '">' . esc_html(substr($record['transaction'], 0, 30)) . '...</a></small>';
                }
                $response .= '
                    <tr>
                        <td>' . $link . '</td>
                        <td>' . number_format_i18n($record['amount'] / 100000000, 8) . ' ' . esc_attr__($claimRules->currency()->symbol(), '99btc-bf') . '</td>
                        <td data-stamp="' . $record['stamp'] . '">' . date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $record['stamp']) . '</td>
                    </tr>
                ';
            }
            if (count($records) == 20 && $record) {
                $response .= '
                    <tr>
                        <td colspan="3">
                            <a class="transaction-link" href="?the99btcbfaddress=' . urlencode($_REQUEST['the99btcbfaddress']) . '&amp;the99btcbfid=' . urlencode($record['id']) . '&amp;t99fid=' . urlencode($this->config['post']->ID) . '">' . esc_html__('Load more', '99btc-bf') . '</a>
                        </td>
                    </tr>
                ';
            }
        }

	    remove_filter('gettext', array($this, 'wpFilterGettext'));
        return $response;
    }

    public function wpAjaxHistory()
    {
	    add_filter('gettext', array($this, 'wpFilterGettext'), 10, 3);

	    $response = '';
        $info = array();
        $options = get_option($this->config['prefix'] . 'main', array());
        $claimRules = $this->getClaimRules($options);

        if (empty($_REQUEST['the99btcbfid'])) {
            $_REQUEST['the99btcbfid'] = 2147483647;
        }

        if (!empty($_REQUEST['the99btcbfaddress'])) {
            $info = $this->infoAddresses->get($claimRules->currency()->symbol() . ':' . $_REQUEST['the99btcbfaddress']);
        }

        if ($info) {
            $record = array();
	        $records = $this->claimPayouts->searchAddress($info['id'], $_REQUEST['the99btcbfid']);
            foreach ($records as $record) {
                if ($record['source'] == 'referral') {
                    $status = '<label class="referral">' . esc_html__('Referral payout', '99btc-bf') . '</label>';
                } elseif ($record['source'] == 'seniority') {
                    $status = '<label class="seniority">' . esc_html__('Seniority payout', '99btc-bf') . '</label>';
                } elseif ($record['source'] == 'bonus') {
                    $status = '<label class="bonus">' . esc_html__('Bonus', '99btc-bf') . '</label>';
                } elseif ($record['source'] == 'penalty') {
                    $status = '<label class="penalty">' . esc_html__('Penalty', '99btc-bf') . '</label>';
                } else {
                    $status = '<label class="direct">' . esc_html__('Direct payout', '99btc-bf') . '</label>';
                }
                $response .= '
                    <tr>
                        <td>' . sprintf(esc_html__('%s ' . $claimRules->currency()->satoshi(), '99btc-bf'), number_format_i18n($record['amount'])) . '</td>
                        <td data-stamp="' . $record['stamp'] . '">' . date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $record['stamp']) . '</td>
                        <td>' . $status . '</td>
                    </tr>
                ';
            }
            if (count($records) == 20 && $record) {
                $response .= '
                    <tr>
                        <td colspan="3">
                            <a class="history-link" href="?the99btcbfaddress=' . urlencode($_REQUEST['the99btcbfaddress']) . '&amp;the99btcbfid=' . urlencode($record['id']) . '&amp;t99fid=' . urlencode($this->config['post']->ID) . '">' . esc_html__('Load more', '99btc-bf') . '</a>
                        </td>
                    </tr>
                ';
            }
        }

	    remove_filter('gettext', array($this, 'wpFilterGettext'));
        return $response;
    }

    protected function makePayout($currency, $options, &$variables = null)
    {
        if (!$variables) {
            $variables = array();
        }

        $wallet = $this->getWallet($options, $currency);
        if (!$wallet) {
            $variables['notice_css_class'] = 'notice notice-error';
            $variables['notice_message'] = esc_html__('Please configure wallet first', '99btc-bf');
            return false;
        }

        if (!$this->scheduledPayment->schedule($currency, $wallet->min > $this->getClaimRules($options)->threshold() ? $wallet->min : $this->getClaimRules($options)->threshold(), !empty($options['config']['only_users']) && !empty($options['config']['only_users_single_pay']), !empty($options['stats']['last_payout']) ? $options['stats']['last_payout'] : 0)) {
            $variables['notice_css_class'] = 'notice notice-warning';
            $variables['notice_message'] = esc_html__('Please click make payment one more time, not all addresses were processed', '99btc-bf');
        }
        return true;
    }

    protected function getSeniorityBonus($rules, $days)
    {
        $return = 0;
        foreach ($rules as $rule) {
            if ($rule['status'] != 'enabled') {
                continue;
            }
            if ($rule['day'] > $days) {
                continue;
            }
            if (!$return) {
                $return = $rule['bonus'];
            } elseif ($return < $rule['bonus']) {
                $return = $rule['bonus'];
            }
        }

        return $return;
    }

    protected function getDaysToNextSeniorityLevel($rules, $days)
    {
        $return = 0;
        foreach ($rules as $rule) {
            if ($rule['status'] != 'enabled') {
                continue;
            }
            if ($rule['day'] < $days) {
                continue;
            }
            if (!$return) {
                $return = $rule['day'];
            } elseif ($return > $rule['day']) {
                $return = $rule['day'];
            }
        }

        if ($return) {
            return $return - $days;
        }
        return $return;
    }

    protected function stringToHex($string)
    {
        $hexString = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $hexString .= '%' . bin2hex($string[$i]);
        }
        return $hexString;
    }

    protected function hexToString($hexString)
    {
        return pack("H*" , str_replace('%', '', $hexString));
    }

    /**
     * Returns wallet object by config.
     *
     * @param array $options
     * @param string $currency
     *
     * @return null|The99Bitcoins_BtcFaucet_Wallet_WalletInterface
     */
    protected function getWallet($options, $currency = 'BTC')
    {
        /** @var The99Bitcoins_BtcFaucet_Wallet_WalletInterface $client */
        $client = null;

        foreach ($options['wallet'] as $wallet => $config) {
            if (!empty($config['supports']) && !in_array($currency, $config['supports'])) {
                continue;
            }
            $clientClass = 'The99Bitcoins_BtcFaucet_Wallet_' . $wallet;
            if (!class_exists($clientClass)) {
                continue;
            }
            $client = new $clientClass($config, $currency);
            if ($client->isAccessible()) {
                break;
            }
            $client = null;
        }
        if (!$client && !empty($_SERVER['SERVER_ADMIN']) && $_SERVER['SERVER_ADMIN'] === 'michael@local.dev') {
            $client = new The99Bitcoins_BtcFaucet_Wallet_Fake();
        }

        return $client;
    }

    /**
     * @param array $options
     * @param string $ruleSet
     *
     * @return The99Bitcoins_BtcFaucet_ClaimRules_Base
     */
    protected function getClaimRules($options, $ruleSet = '')
    {
        $client = null;
        if (empty($options['config']['rule_set'])) {
            $options['config']['rule_set'] = 'BTC';
        }
        if (!$ruleSet) {
            $ruleSet = $options['config']['rule_set'];
        }
        if (!$ruleSet || empty($options['claim_rules'][$ruleSet])) {
            return $client;
        }

        $config = $options['claim_rules'][$ruleSet];
        if (!class_exists('The99Bitcoins_BtcFaucet_ClaimRules_' . $config['currency'])) {
            return $client;
        }

        $ruleSetClass = 'The99Bitcoins_BtcFaucet_ClaimRules_' . $ruleSet;
        $currency = 'The99Bitcoins_BtcFaucet_Currency_' . $ruleSetClass::currency;
        $currency = new The99Bitcoins_BtcFaucet_Currency_Base(new $currency());
        $client = new $ruleSetClass($config, $currency);

        return $client;
    }

	protected function render($_template, $_vairables = array(), $_layout = '')
	{
		$_navigation = self::$navigation;

		foreach ($_navigation as &$parent) {
			if (isset($_REQUEST['page']) && $this->config['prefix'] . $parent['url'] == $_REQUEST['page']) {
				$parent['active'] = true;
			}
			foreach ($parent['childs'] as $index => &$child) {
				$active = isset($_REQUEST['mode']) && $child['url'] == $_REQUEST['mode'];
				if (!$active) {
					$active = !empty($parent['active']) && $index == 0 && empty($_REQUEST['mode']);
				}
				if ($active) {
					$child['active'] = true;
					$parent['active'] = true;
					break;
				}
			}
			unset($child);
		}
		unset($parent);

		extract($_vairables);
		ob_start();
		include $this->config['templates'] . $_template . '.php';
		$_content = ob_get_contents();
		ob_end_clean();
		if ($_layout) {
			include $this->config['templates'] . 'layout' . DIRECTORY_SEPARATOR . $_layout . '.php';
		} else {
			echo $_content;
		}
	}
}
