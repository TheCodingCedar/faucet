<?php

class The99Bitcoins_BtcFaucet_Migration_Changes
{
	protected $config = array();

    protected $steps = array(
        'base',
        'ipv6',
        'stamps',
        'ip2id',
        'address2id',
        'multiCurrencies',
        'multiWallets',
        'multiCurrenciesDatabase',
        'kv',
        'eth',
        'touch',
        'stats',
        'doge',
        'bchbtxblkppcxpmpot',
        'solveMediaNoLazy',
    );

    public $tables = array(
        'ban_address',
        'ban_ip',
        'claim_ips',
        'claim_payouts',
        'info_address',
        'info_ip',
        'info_user',
        'kv',
        'scheduled_payouts',
        'stats',
        'white_address',
    );

	public function __construct($config)
	{
		$this->config = $config + $this->config;
	}

	public function truncate()
	{
		/** @var wpdb $wpdb */
		global $wpdb;

		foreach ($this->tables as $table) {
			$wpdb->query('TRUNCATE ' . $this->config['db_prefix'] . $table);
		}
	}

	public function migrate()
    {
        $options = get_option($this->config['prefix'] . 'main', array());
        if (!$options) {
            return false;
        }
        if (empty($options['migrations'])) {
            $options['migrations'] = array();
        }

        $needLock = true;
        foreach ($this->steps as $step) {
            if (in_array($step, $options['migrations'])) {
                continue;
            }
            if ($needLock && !$this->blockExecution('faucet')) {
                return false;
            }
            $needLock = false;
            @set_time_limit(0);
            if ($this->$step($options)) {
                $options['migrations'][] = $step;
                update_option($this->config['prefix'] . 'main', $options);
            }
        }
        if (!$needLock) {
            $this->unblockExecution('faucet');
        }

        return true;
    }

    public function switchToPrimaryDatabase()
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        if (isset($wpdb->srtm) && $wpdb->srtm !== true) {
            if (!is_array($wpdb->srtm)) {
                $wpdb->srtm = array();
            }
            foreach ($this->tables as $table) {
                $wpdb->srtm[$this->config['db_prefix'] . $table] = true;
            }
            $wpdb->srtm[$wpdb->prefix . 'options'] = true;
        }
    }

    public function isBlockedExecution($name, $expiration = false)
    {
        $timeValue = microtime(true);
        $lock = get_option($this->config['prefix'] . 'block_' . $name, '');
        $timeDb = get_option($this->config['prefix'] . 'block_' . $name . '_time', 0);
        $timeStamp = get_option($this->config['prefix'] . 'block_' . $name . '_stamp', $timeValue);
        if ($timeValue - $timeStamp > 60 * 60 * 4) {
            $timeDb = 0;
        }
        if ($lock && $timeDb < 0) {
            return $expiration ? $timeDb : $timeStamp;
        }
        if ($timeDb > 0 && $timeDb > $timeValue) {
            return $expiration ? $timeDb : $timeStamp;
        }

        return $expiration ? $timeDb : 0;
    }

    public function blockExecution($name, $timeout = 0)
    {
        if ($this->isBlockedExecution($name)) {
            return false;
        }

        $blockValue = md5(microtime(true) . rand(1000, 9999));

        /** @var wpdb $wpdb */
        global $wpdb;
        $lockValue = $wpdb->get_var($wpdb->prepare(
            "SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=%s LIMIT 1",
	        $this->config['prefix'] . 'block_' . $name
        ));

        $wpdb->query("START TRANSACTION");
        if ($lockValue) {
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->prefix}options SET option_value=%s WHERE option_name=%s AND option_value=%s LIMIT 1",
                $blockValue,
	            $this->config['prefix'] . 'block_' . $name,
                $lockValue
            ));
        } else {
            update_option($this->config['prefix'] . 'block_' . $name, $blockValue, 'no');
        }
        $wpdb->query("COMMIT");
        sleep(1);

        $blockDb = $wpdb->get_var($wpdb->prepare(
            "SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=%s LIMIT 1",
	        $this->config['prefix'] . 'block_' . $name
        ));

        if ($blockValue != $blockDb) {
            return false;
        }

        $timeValue = microtime(true);
        update_option($this->config['prefix'] . 'block_' . $name . '_time', $timeout ? ($timeValue + $timeout) : -1, 'no');
        update_option($this->config['prefix'] . 'block_' . $name . '_stamp', $timeValue, 'no');
        $wpdb->query("COMMIT");

        return true;
    }

    public function unblockExecution($name)
    {
        delete_option($this->config['prefix'] . 'block_' . $name);
        delete_option($this->config['prefix'] . 'block_' . $name . '_time');
        delete_option($this->config['prefix'] . 'block_' . $name . '_stamp');

        /** @var wpdb $wpdb */
        global $wpdb;
        $wpdb->query("COMMIT");
    }

    protected function base()
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        /** @var wpdb $wpdb */
        global $wpdb;

        $tables = array(
            'ban_address' => '(
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `stamp` datetime NOT NULL,
                    `address` varchar(34) COLLATE utf8_bin NOT NULL DEFAULT \'\',
                    `reason` varchar(250) COLLATE utf8_bin NOT NULL DEFAULT \'\',
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `address` (`address`)
                ) DEFAULT CHARSET=utf8 COLLATE=utf8_bin',
            'ban_ip' => '(
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `stamp` datetime NOT NULL,
                    `ip` int(10) unsigned NOT NULL,
                    `ip_to` int(10) unsigned NOT NULL,
                    `reason` varchar(250) COLLATE utf8_bin NOT NULL DEFAULT \'\',
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `ip` (`ip`,`ip_to`)
                ) DEFAULT CHARSET=utf8 COLLATE=utf8_bin',
            'claim_ips' => '(
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `stamp` datetime NOT NULL,
                    `address` varchar(34) COLLATE utf8_bin NOT NULL DEFAULT \'\',
                    `refer` varchar(34) COLLATE utf8_bin NOT NULL DEFAULT \'\',
                    `user_id` int(10) unsigned NOT NULL DEFAULT \'0\',
                    `ip` int(10) unsigned NOT NULL DEFAULT \'0\',
                    `success` enum(\'yes\',\'no\') COLLATE utf8_bin NOT NULL DEFAULT \'no\',
                    PRIMARY KEY (`id`),
                    KEY `stamp` (`stamp`),
                    KEY `address` (`address`),
                    KEY `refer` (`refer`),
                    KEY `user_id` (`user_id`),
                    KEY `ip` (`ip`)
                ) DEFAULT CHARSET=utf8 COLLATE=utf8_bin',
            'claim_payouts' => '(
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `stamp` datetime NOT NULL,
                    `address` varchar(34) COLLATE utf8_bin NOT NULL DEFAULT \'\',
                    `amount` int(10) NOT NULL,
                    `source` enum(\'direct\',\'referral\',\'seniority\',\'bonus\',\'penalty\') COLLATE utf8_bin NOT NULL DEFAULT \'direct\',
                    `paid` enum(\'yes\',\'no\') COLLATE utf8_bin NOT NULL DEFAULT \'no\',
                    `scheduled_payouts_id` int(10) NOT NULL,
                    PRIMARY KEY (`id`),
                    KEY `scheduled_payouts` (`scheduled_payouts_id`, `paid`, `address`),
                    KEY `stamp` (`stamp`),
                    KEY `source` (`source`,`stamp`),
                    KEY `address` (`address`, `paid`)
                ) DEFAULT CHARSET=utf8 COLLATE=utf8_bin',
            'info_address' => '(
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `stamp` datetime NOT NULL,
                    `address` varchar(34) COLLATE utf8_bin NOT NULL DEFAULT \'\',
                    `refer` varchar(34) COLLATE utf8_bin NOT NULL DEFAULT \'\',
                    `seniority_first` datetime NOT NULL,
                    `seniority_current` datetime NOT NULL,
                    `threshold` int(10) unsigned NOT NULL DEFAULT 0,
                    `submits` int(10) NOT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `address` (`address`)
                ) DEFAULT CHARSET=utf8 COLLATE=utf8_bin',
            'info_ip' => '(
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `stamp` datetime NOT NULL,
                    `ip` int(10) unsigned NOT NULL,
                    `refer` varchar(34) COLLATE utf8_bin NOT NULL DEFAULT \'\',
                    `seniority_first` datetime NOT NULL,
                    `seniority_current` datetime NOT NULL,
                    `submits` int(10) NOT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `ip` (`ip`)
                ) DEFAULT CHARSET=utf8 COLLATE=utf8_bin',
            'info_user' => '(
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `stamp` datetime NOT NULL,
                    `user_id` int(10) unsigned NOT NULL,
                    `refer` varchar(34) COLLATE utf8_bin NOT NULL DEFAULT \'\',
                    `seniority_first` datetime NOT NULL,
                    `seniority_current` datetime NOT NULL,
                    `submits` int(10) NOT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `user_id` (`user_id`)
                ) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;',
            'scheduled_payouts' => '(
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `stamp` datetime NOT NULL,
                    `address` varchar(34) COLLATE utf8_bin NOT NULL DEFAULT \'\',
                    `amount` int(10) NOT NULL,
                    `transaction` varchar(250) COLLATE utf8_bin NOT NULL DEFAULT \'\',
                    `fee` enum(\'yes\',\'no\') COLLATE utf8_bin NOT NULL DEFAULT \'no\',
                    PRIMARY KEY (`id`),
                    KEY `stamp` (`stamp`)
                ) DEFAULT CHARSET=utf8 COLLATE=utf8_bin',
            'white_address' => '(
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `stamp` datetime NOT NULL,
                    `address` varchar(34) COLLATE utf8_bin NOT NULL DEFAULT \'\',
                    `reason` varchar(250) COLLATE utf8_bin NOT NULL DEFAULT \'\',
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `address` (`address`)
                ) DEFAULT CHARSET=utf8 COLLATE=utf8_bin',
        );

        foreach ($tables as $table => $definition) {
            dbDelta('CREATE TABLE ' . $this->config['db_prefix'] . $table . ' ' . $definition);
        }

        return true;
    }

    protected function ipv6()
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        $wpdb->query("START TRANSACTION");

        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}ban_ip
            CHANGE
                ip ip VARBINARY(16) NOT NULL DEFAULT '',
            CHANGE
                ip_to ip_to VARBINARY(16) NOT NULL DEFAULT ''
        ");
        $id = 0;
        while ($set = $wpdb->get_results("SELECT id, ip, ip_to FROM {$this->config['db_prefix']}ban_ip WHERE id > {$id} ORDER BY id ASC LIMIT 200", ARRAY_A)) {
            foreach ($set as $item) {
                $id = $item['id'];
                $update = false;
                if (is_numeric($item['ip'])) {
                    $update = true;
                    $item['ip'] = inet_pton(long2ip($item['ip']));
                }
                if (is_numeric($item['ip_to'])) {
                    $update = true;
                    $item['ip_to'] = inet_pton(long2ip($item['ip_to']));
                }
                if ($update) {
                    $wpdb->update("{$this->config['db_prefix']}ban_ip", array(
                        'ip' => $item['ip'],
                        'ip_to' => $item['ip_to'],
                    ), array(
                        'id' => $item['id'],
                    ));
                }
            }
        }

        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}claim_ips
            CHANGE
                ip ip VARBINARY(16) NOT NULL DEFAULT ''
        ");
        $id = 0;
        while ($set = $wpdb->get_results("SELECT id, ip FROM {$this->config['db_prefix']}claim_ips WHERE id > {$id} ORDER BY id ASC LIMIT 200", ARRAY_A)) {
            foreach ($set as $item) {
                $id = $item['id'];
                $update = false;
                if (is_numeric($item['ip'])) {
                    $update = true;
                    $item['ip'] = inet_pton(long2ip($item['ip']));
                }
                if ($update) {
                    $wpdb->update("{$this->config['db_prefix']}claim_ips", array(
                        'ip' => $item['ip'],
                    ), array(
                        'id' => $item['id'],
                    ));
                }
            }
        }

        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}info_ip
            CHANGE
                ip ip VARBINARY(16) NOT NULL DEFAULT ''
        ");
        $id = 0;
        while ($set = $wpdb->get_results("SELECT id, ip FROM {$this->config['db_prefix']}info_ip WHERE id > {$id} ORDER BY id ASC LIMIT 200", ARRAY_A)) {
            foreach ($set as $item) {
                $id = $item['id'];
                $update = false;
                if (is_numeric($item['ip'])) {
                    $update = true;
                    $item['ip'] = inet_pton(long2ip($item['ip']));
                }
                if ($update) {
                    $wpdb->update("{$this->config['db_prefix']}info_ip", array(
                        'ip' => $item['ip'],
                    ), array(
                        'id' => $item['id'],
                    ));
                }
            }
        }

        $wpdb->query("COMMIT");
        return true;
    }

    public function stamps()
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        $wpdb->query("START TRANSACTION");

        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}ban_address
            CHANGE
                stamp stamp CHAR(19) NOT NULL DEFAULT '0'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}ban_address
            SET
                stamp = '0'
            WHERE
                stamp = '0000-00-00 00:00:00'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}ban_address
            SET
                stamp = IF(CONCAT('',stamp * 1) = stamp, stamp, UNIX_TIMESTAMP(stamp))
            WHERE
                stamp != '0'
        ");
        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}ban_address
            CHANGE
                stamp stamp INT(10) UNSIGNED NOT NULL DEFAULT 0
        ");

        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}ban_ip
            CHANGE
                stamp stamp CHAR(19) NOT NULL DEFAULT '0'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}ban_ip
            SET
                stamp = '0'
            WHERE
                stamp = '0000-00-00 00:00:00'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}ban_ip
            SET
                stamp = IF(CONCAT('',stamp * 1) = stamp, stamp, UNIX_TIMESTAMP(stamp))
            WHERE
                stamp != '0'
        ");
        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}ban_ip
            CHANGE
                stamp stamp INT(10) UNSIGNED NOT NULL DEFAULT 0
        ");

        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}claim_ips
            CHANGE
                stamp stamp CHAR(19) NOT NULL DEFAULT '0'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}claim_ips
            SET
                stamp = '0'
            WHERE
                stamp = '0000-00-00 00:00:00'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}claim_ips
            SET
                stamp = IF(CONCAT('',stamp * 1) = stamp, stamp, UNIX_TIMESTAMP(stamp))
            WHERE
                stamp != '0'
        ");
        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}claim_ips
            CHANGE
                stamp stamp INT(10) UNSIGNED NOT NULL DEFAULT 0
        ");

        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}claim_payouts
            CHANGE
                stamp stamp CHAR(19) NOT NULL DEFAULT '0'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}claim_payouts
            SET
                stamp = '0'
            WHERE
                stamp = '0000-00-00 00:00:00'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}claim_payouts
            SET
                stamp = IF(CONCAT('',stamp * 1) = stamp, stamp, UNIX_TIMESTAMP(stamp))
            WHERE
                stamp != '0'
        ");
        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}claim_payouts
            CHANGE
                stamp stamp INT(10) UNSIGNED NOT NULL DEFAULT 0
        ");

        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}info_address
            CHANGE
                stamp stamp CHAR(19) NOT NULL DEFAULT '0',
            CHANGE
                seniority_first seniority_first CHAR(19) NOT NULL DEFAULT '0',
            CHANGE
                seniority_current seniority_current CHAR(19) NOT NULL DEFAULT '0'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_address
            SET
                stamp = '0'
            WHERE
                stamp = '0000-00-00 00:00:00'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_address
            SET
                seniority_first = '0'
            WHERE
                seniority_first = '0000-00-00 00:00:00'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_address
            SET
                seniority_current = '0'
            WHERE
                seniority_current = '0000-00-00 00:00:00'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_address
            SET
                stamp = IF(CONCAT('',stamp * 1) = stamp, stamp, UNIX_TIMESTAMP(stamp))
            WHERE
                stamp != '0'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_address
            SET
                seniority_first = IF(CONCAT('',seniority_first * 1) = seniority_first, seniority_first, UNIX_TIMESTAMP(seniority_first))
            WHERE
                seniority_first != '0'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_address
            SET
                seniority_current = IF(CONCAT('',seniority_current * 1) = seniority_current, seniority_current, UNIX_TIMESTAMP(seniority_current))
            WHERE
                seniority_current != '0'
        ");
        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}info_address
            CHANGE
                stamp stamp INT(10) UNSIGNED NOT NULL DEFAULT 0,
            CHANGE
                seniority_first seniority_first INT(10) UNSIGNED NOT NULL DEFAULT 0,
            CHANGE
                seniority_current seniority_current INT(10) UNSIGNED NOT NULL DEFAULT 0
        ");

        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}info_ip
            CHANGE
                stamp stamp CHAR(19) NOT NULL DEFAULT '0',
            CHANGE
                seniority_first seniority_first CHAR(19) NOT NULL DEFAULT '0',
            CHANGE
                seniority_current seniority_current CHAR(19) NOT NULL DEFAULT '0'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_ip
            SET
                stamp = '0'
            WHERE
                stamp = '0000-00-00 00:00:00'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_ip
            SET
                seniority_first = '0'
            WHERE
                seniority_first = '0000-00-00 00:00:00'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_ip
            SET
                seniority_current = '0'
            WHERE
                seniority_current = '0000-00-00 00:00:00'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_ip
            SET
                stamp = IF(CONCAT('',stamp * 1) = stamp, stamp, UNIX_TIMESTAMP(stamp))
            WHERE
                stamp != '0'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_ip
            SET
                seniority_first = IF(CONCAT('',seniority_first * 1) = seniority_first, seniority_first, UNIX_TIMESTAMP(seniority_first))
            WHERE
                seniority_first != '0'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_ip
            SET
                seniority_current = IF(CONCAT('',seniority_current * 1) = seniority_current, seniority_current, UNIX_TIMESTAMP(seniority_current))
            WHERE
                seniority_current != '0'
        ");
        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}info_ip
            CHANGE
                stamp stamp INT(10) UNSIGNED NOT NULL DEFAULT 0,
            CHANGE
                seniority_first seniority_first INT(10) UNSIGNED NOT NULL DEFAULT 0,
            CHANGE
                seniority_current seniority_current INT(10) UNSIGNED NOT NULL DEFAULT 0
        ");

        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}info_user
            CHANGE
                stamp stamp CHAR(19) NOT NULL DEFAULT '0',
            CHANGE
                seniority_first seniority_first CHAR(19) NOT NULL DEFAULT '0',
            CHANGE
                seniority_current seniority_current CHAR(19) NOT NULL DEFAULT '0'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_user
            SET
                stamp = '0'
            WHERE
                stamp = '0000-00-00 00:00:00'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_user
            SET
                seniority_first = '0'
            WHERE
                seniority_first = '0000-00-00 00:00:00'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_user
            SET
                seniority_current = '0'
            WHERE
                seniority_current = '0000-00-00 00:00:00'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_user
            SET
                stamp = IF(CONCAT('',stamp * 1) = stamp, stamp, UNIX_TIMESTAMP(stamp))
            WHERE
                stamp != '0'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_user
            SET
                seniority_first = IF(CONCAT('',seniority_first * 1) = seniority_first, seniority_first, UNIX_TIMESTAMP(seniority_first))
            WHERE
                seniority_first != '0'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}info_user
            SET
                seniority_current = IF(CONCAT('',seniority_current * 1) = seniority_current, seniority_current, UNIX_TIMESTAMP(seniority_current))
            WHERE
                seniority_current != '0'
        ");
        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}info_user
            CHANGE
                stamp stamp INT(10) UNSIGNED NOT NULL DEFAULT 0,
            CHANGE
                seniority_first seniority_first INT(10) UNSIGNED NOT NULL DEFAULT 0,
            CHANGE
                seniority_current seniority_current INT(10) UNSIGNED NOT NULL DEFAULT 0
        ");

        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}scheduled_payouts
            CHANGE
                stamp stamp CHAR(19) NOT NULL DEFAULT '0'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}scheduled_payouts
            SET
                stamp = '0'
            WHERE
                stamp = '0000-00-00 00:00:00'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}scheduled_payouts
            SET
                stamp = IF(CONCAT('',stamp * 1) = stamp, stamp, UNIX_TIMESTAMP(stamp))
            WHERE
                stamp != '0'
        ");
        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}scheduled_payouts
            CHANGE
                stamp stamp INT(10) UNSIGNED NOT NULL DEFAULT 0
        ");

        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}white_address
            CHANGE
                stamp stamp CHAR(19) NOT NULL DEFAULT '0'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}white_address
            SET
                stamp = '0'
            WHERE
                stamp = '0000-00-00 00:00:00'
        ");
        $wpdb->query("
            UPDATE
                {$this->config['db_prefix']}white_address
            SET
                stamp = IF(CONCAT('',stamp * 1) = stamp, stamp, UNIX_TIMESTAMP(stamp))
            WHERE
                stamp != '0'
        ");
        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}white_address
            CHANGE
                stamp stamp INT(10) UNSIGNED NOT NULL DEFAULT 0
        ");

        $wpdb->query("COMMIT");
        return true;
    }

    public function ip2id()
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        $wpdb->query("START TRANSACTION");

        foreach ($wpdb->get_col("
            SELECT
	            DISTINCT {$this->config['db_prefix']}claim_ips.ip
            FROM
	            {$this->config['db_prefix']}claim_ips
            LEFT JOIN
	            {$this->config['db_prefix']}info_ip
            ON
	            {$this->config['db_prefix']}info_ip.ip = {$this->config['db_prefix']}claim_ips.ip
            WHERE
	            {$this->config['db_prefix']}info_ip.ip IS NULL
        ") as $ip) {
            $wpdb->insert("{$this->config['db_prefix']}info_ip", array(
                'stamp' => time(),
                'ip' => $ip,
            ));
        }
        $wpdb->query("
            UPDATE
	            {$this->config['db_prefix']}claim_ips
            SET
	            {$this->config['db_prefix']}claim_ips.ip = COALESCE((
	                SELECT
	                    id
                    FROM
                        {$this->config['db_prefix']}info_ip
                    WHERE
                        {$this->config['db_prefix']}info_ip.ip = {$this->config['db_prefix']}claim_ips.ip
                    LIMIT 1
                ), 0)
        ");
        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}claim_ips
            CHANGE
                ip ip_id INT(10) UNSIGNED NOT NULL DEFAULT 0
        ");
        $wpdb->query("DELETE FROM {$this->config['db_prefix']}claim_ips WHERE ip_id = 0");

        $wpdb->query("COMMIT");
        return true;
    }

    public function address2id()
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        $wpdb->query("START TRANSACTION");

        $wpdb->get_results("ALTER TABLE {$this->config['db_prefix']}info_address ADD INDEX (refer)");
        $wpdb->get_results("ALTER TABLE {$this->config['db_prefix']}info_ip ADD INDEX (refer)");
        $wpdb->get_results("ALTER TABLE {$this->config['db_prefix']}info_user ADD INDEX (refer)");
        $wpdb->get_results("ALTER TABLE {$this->config['db_prefix']}scheduled_payouts ADD INDEX (address)");

        foreach ($wpdb->get_col("
            SELECT DISTINCT
                source.address
            FROM
                {$this->config['db_prefix']}claim_ips source
            LEFT JOIN
                {$this->config['db_prefix']}info_address refered
            ON
                refered.address = source.address
            WHERE
                refered.id IS NULL
        ") as $address) {
            $wpdb->insert("{$this->config['db_prefix']}info_address", array(
                'address' => $address,
            ));
        }
        foreach ($wpdb->get_col("
            SELECT DISTINCT
                source.refer
            FROM
                {$this->config['db_prefix']}claim_ips source
            LEFT JOIN
                {$this->config['db_prefix']}info_address refered
            ON
                refered.address =  source.refer
            WHERE
                source.refer != ''
                AND refered.id IS NULL
        ") as $address) {
            $wpdb->insert("{$this->config['db_prefix']}info_address", array(
                'address' => $address,
            ));
        }
        foreach ($wpdb->get_col("
            SELECT DISTINCT
                source.address
            FROM
                {$this->config['db_prefix']}claim_payouts source
            LEFT JOIN
                {$this->config['db_prefix']}info_address refered
            ON
                refered.address =  source.address
            WHERE
                refered.id IS NULL
        ") as $address) {
            $wpdb->insert("{$this->config['db_prefix']}info_address", array(
                'address' => $address,
            ));
        }
        foreach ($wpdb->get_col("
            SELECT DISTINCT
                source.refer
            FROM
                {$this->config['db_prefix']}info_address source
            LEFT JOIN
                {$this->config['db_prefix']}info_address refered
            ON
                source.refer = refered.address
            WHERE
                source.refer != ''
                AND refered.id IS NULL
        ") as $address) {
            $wpdb->insert("{$this->config['db_prefix']}info_address", array(
                'address' => $address,
            ));
        }
        foreach ($wpdb->get_col("
            SELECT DISTINCT
                source.refer
            FROM
                {$this->config['db_prefix']}info_ip source
            LEFT JOIN
                {$this->config['db_prefix']}info_address refered
            ON
                source.refer = refered.address
            WHERE
                source.refer != ''
                AND refered.id IS NULL
        ") as $address) {
            $wpdb->insert("{$this->config['db_prefix']}info_address", array(
                'address' => $address,
            ));
        }
        foreach ($wpdb->get_col("
            SELECT DISTINCT
                source.refer
            FROM
                {$this->config['db_prefix']}info_user source
            LEFT JOIN
                {$this->config['db_prefix']}info_address refered
            ON
                source.refer = refered.address
            WHERE
                source.refer != ''
                AND refered.id IS NULL
        ") as $address) {
            $wpdb->insert("{$this->config['db_prefix']}info_address", array(
                'address' => $address,
            ));
        }
        foreach ($wpdb->get_col("
            SELECT DISTINCT
                source.address
            FROM
                {$this->config['db_prefix']}scheduled_payouts source
            LEFT JOIN
                {$this->config['db_prefix']}info_address refered
            ON
                source.address = refered.address
            WHERE
                refered.id IS NULL
        ") as $address) {
            $wpdb->insert("{$this->config['db_prefix']}info_address", array(
                'address' => $address,
            ));
        }

        $id = 0;
        while ($set = $wpdb->get_results("SELECT id, address FROM {$this->config['db_prefix']}info_address WHERE id > {$id} ORDER BY id ASC LIMIT 200", ARRAY_A)) {

            $address = array();
            $refer = array();
            $delete = array();
            foreach ($set as $item) {
                $id = $item['id'];
                if (!The99Bitcoins_BtcFaucet_Currency_BTC::validateAddress($item['address'])) {
                    $item['id'] = 0;
                    $delete[] = $id;
                }
                $address[esc_sql($item['address'])] = $wpdb->prepare('WHEN address = %s THEN %s', $item['address'], $item['id']);
                $refer[esc_sql($item['address'])] = $wpdb->prepare('WHEN refer = %s THEN %s', $item['address'], $item['id']);
            }
            if ($delete) {
                $wpdb->query("DELETE FROM {$this->config['db_prefix']}info_address WHERE id IN (" . implode(", ", $delete) . ")");
            }

            $wpdb->query("
                UPDATE
                    {$this->config['db_prefix']}claim_ips
                SET
                    address = CASE " . implode(' ', $address) . " END
                WHERE
                    address IN ('" . implode("', '", array_keys($address)) . "')
            ");
            $wpdb->query("
                UPDATE
                    {$this->config['db_prefix']}claim_payouts
                SET
                    address = CASE " . implode(' ', $address) . " END
                WHERE
                    address IN ('" . implode("', '", array_keys($address)) . "')
            ");
            $wpdb->query("
                UPDATE
                    {$this->config['db_prefix']}info_address
                SET
                    refer = CASE " . implode(' ', $refer) . " END
                WHERE
                    refer IN ('" . implode("', '", array_keys($refer)) . "')
            ");
            $wpdb->query("
                UPDATE
                    {$this->config['db_prefix']}info_ip
                SET
                    refer = CASE " . implode(' ', $refer) . " END
                WHERE
                    refer IN ('" . implode("', '", array_keys($refer)) . "')
            ");
            $wpdb->query("
                UPDATE
                    {$this->config['db_prefix']}info_user
                SET
                    refer = CASE " . implode(' ', $refer) . " END
                WHERE
                    refer IN ('" . implode("', '", array_keys($refer)) . "')
            ");
            $wpdb->query("
                UPDATE
                    {$this->config['db_prefix']}scheduled_payouts
                SET
                    address = CASE " . implode(' ', $address) . " END
                WHERE
                    address IN ('" . implode("', '", array_keys($address)) . "')
            ");
        }

        $wpdb->get_results("ALTER TABLE {$this->config['db_prefix']}info_address DROP INDEX refer");
        $wpdb->get_results("ALTER TABLE {$this->config['db_prefix']}info_ip DROP INDEX refer");
        $wpdb->get_results("ALTER TABLE {$this->config['db_prefix']}info_user DROP INDEX refer");
        $wpdb->get_results("ALTER TABLE {$this->config['db_prefix']}scheduled_payouts DROP INDEX address");

        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}claim_ips
            CHANGE
                address address_id INT(10) UNSIGNED NOT NULL DEFAULT 0,
            CHANGE
                refer refer_id INT(10) UNSIGNED NOT NULL DEFAULT 0
        ");
        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}claim_payouts
            CHANGE
                address address_id INT(10) UNSIGNED NOT NULL DEFAULT 0
        ");
        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}info_address
            CHANGE
                refer refer_id INT(10) UNSIGNED NOT NULL DEFAULT 0
        ");
        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}info_ip
            CHANGE
                refer refer_id INT(10) UNSIGNED NOT NULL DEFAULT 0
        ");
        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}info_user
            CHANGE
                refer refer_id INT(10) UNSIGNED NOT NULL DEFAULT 0
        ");
        $wpdb->query("
            ALTER TABLE
                {$this->config['db_prefix']}scheduled_payouts
            CHANGE
                address address_id INT(10) UNSIGNED NOT NULL DEFAULT 0
        ");

        $wpdb->query("COMMIT");
        return true;
    }

    public function multiCurrencies(&$options)
    {
        $claim_rules = array(
            'BTC' => array(
                'currency' => 'BTC',
                'threshold' => 0,
                'rules' => array(),
            ),
            'DASH' => array(
                'currency' => 'DASH',
                'threshold' => 0,
                'rules' => array(),
            ),
            'LTC' => array(
                'currency' => 'LTC',
                'threshold' => 0,
                'rules' => array(),
            ),
            'USDBTC' => array(
                'currency' => 'USDBTC',
                'threshold' => 0,
                'exchange_rate' => 0,
                'exchange_rate_auto' => 0,
                'rules' => array(),
            ),
            'USDDASH' => array(
                'currency' => 'USDDASH',
                'threshold' => 0,
                'exchange_rate' => 0,
                'exchange_rate_auto' => 0,
                'rules' => array(),
            ),
            'USDLTC' => array(
                'currency' => 'USDLTC',
                'threshold' => 0,
                'exchange_rate' => 0,
                'exchange_rate_auto' => 0,
                'rules' => array(),
            ),
        );

        if (!empty($options['claim_rules'])) {
            $claim_rules['BTC']['rules'] = $options['claim_rules'];
        }
        if (!empty($options['config']['payout_threshold'])) {
            $claim_rules['BTC']['threshold'] = $options['config']['payout_threshold'];
        }
        if (!empty($options['USDBTC_claim_rules'])) {
            $claim_rules['USDBTC']['rules'] = $options['USDBTC_claim_rules'];
        }
        if (!empty($options['config']['USDBTC_payout_threshold'])) {
            $claim_rules['USDBTC']['threshold'] = $options['config']['USDBTC_payout_threshold'];
        }
        if (!empty($options['config']['USDBTC_exchange_rate'])) {
            $claim_rules['USDBTC']['exchange_rate'] = $options['config']['USDBTC_exchange_rate'];
        }
        if (!empty($options['config']['USDBTC_exchange_rate_auto'])) {
            $claim_rules['USDBTC']['exchange_rate_auto'] = $options['config']['USDBTC_exchange_rate_auto'];
        }

        if (isset($options['claim_rules'])) {
            unset($options['claim_rules']);
        }
        if (isset($options['config']['payout_threshold'])) {
            unset($options['config']['payout_threshold']);
        }
        if (isset($options['USDBTC_claim_rules'])) {
            unset($options['USDBTC_claim_rules']);
        }
        if (isset($options['config']['USDBTC_payout_threshold'])) {
            unset($options['config']['USDBTC_payout_threshold']);
        }
        if (isset($options['config']['USDBTC_exchange_rate'])) {
            unset($options['config']['USDBTC_exchange_rate']);
        }
        if (isset($options['config']['USDBTC_exchange_rate_auto'])) {
            unset($options['config']['USDBTC_exchange_rate_auto']);
        }

        $options['claim_rules'] = $claim_rules;
        if (empty($options['config']['rule_set'])) {
            $options['config']['rule_set'] = 'BTC';
        }

        return true;
    }

    public function multiWallets(&$options)
    {
        $options['wallet'] = array(
            'Bitcoind' => array(
                'url' => '',
                'secret' => '',
                'supports' => array(
                    'BTC',
                ),
            ),
            'faucetpay' => array(
                'api_key' => '',
                'supports' => array(
                    'BTC',
                    'DASH',
                    'LTC',
                ),
            ),
        );

        if (!empty($options['config']['bitcoind_url'])) {
            $options['wallet']['Bitcoind']['url'] = $options['config']['bitcoind_url'];
        }
        if (!empty($options['config']['bitcoind_secret'])) {
            $options['wallet']['Bitcoind']['secret'] = $options['config']['bitcoind_secret'];
        }
        if (!empty($options['config']['faucetpayio_api_key'])) {
            $options['wallet']['faucetpay']['api_key'] = $options['config']['faucetpayio_api_key'];
        }

        if (isset($options['config']['wallet'])) {
            unset($options['config']['wallet']);
        }
        if (isset($options['config']['bitcoind_url'])) {
            unset($options['config']['bitcoind_url']);
        }
        if (isset($options['config']['bitcoind_secret'])) {
            unset($options['config']['bitcoind_secret']);
        }
        if (isset($options['config']['epayinfo_api_key'])) {
            unset($options['config']['epayinfo_api_key']);
        }
        if (isset($options['config']['faucetpayio_api_key'])) {
            unset($options['config']['faucetpayio_api_key']);
        }

        return true;
    }

    public function multiCurrenciesDatabase()
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        $wpdb->query("ALTER TABLE {$this->config['db_prefix']}info_address ADD currency ENUM('BTC','DASH','LTC') NOT NULL DEFAULT 'BTC' AFTER stamp");
        $wpdb->query("ALTER TABLE {$this->config['db_prefix']}info_address DROP INDEX address");
        $wpdb->query("ALTER TABLE {$this->config['db_prefix']}info_address ADD UNIQUE INDEX (address, currency)");
        $wpdb->query("UPDATE {$wpdb->prefix}usermeta SET meta_key='the99btc_address_BTC' WHERE meta_key='the99btc_address'");

        return true;
    }

    public function kv()
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        /** @var wpdb $wpdb */
        global $wpdb;

        dbDelta("CREATE TABLE {$this->config['db_prefix']}kv (
            `id` varchar(36) COLLATE utf8_bin NOT NULL DEFAULT '',
            `stamp` int(10) unsigned NOT NULL,
            `payload` text NOT NULL,
            PRIMARY KEY (`id`),
            KEY `stamp` (`stamp`)
        ) CHARSET=utf8 COLLATE=utf8_bin");

        return true;
    }

    public function eth(&$options)
    {
        /** @var wpdb $wpdb */
        global $wpdb;
        $wpdb->query("ALTER TABLE {$this->config['db_prefix']}ban_address CHANGE address address varchar(42) COLLATE utf8_bin NOT NULL DEFAULT ''");
        $wpdb->query("ALTER TABLE {$this->config['db_prefix']}white_address CHANGE address address varchar(42) COLLATE utf8_bin NOT NULL DEFAULT ''");
        $wpdb->query("ALTER TABLE {$this->config['db_prefix']}info_address CHANGE currency currency ENUM('BTC','DASH','LTC','ETH') NOT NULL DEFAULT 'BTC' AFTER stamp");
        $wpdb->query("ALTER TABLE {$this->config['db_prefix']}info_address CHANGE address address varchar(42) COLLATE utf8_bin NOT NULL DEFAULT ''");

        $options['claim_rules']['ETH'] = array(
            'currency' => 'ETH',
            'threshold' => 0,
            'rules' => array(),
        );
        $options['claim_rules']['USDETH'] = array(
            'currency' => 'USDETH',
            'threshold' => 0,
            'exchange_rate' => 0,
            'exchange_rate_auto' => 0,
            'rules' => array(),
        );
        $options['wallet']['faucetpay']['supports'] = array(
            'BTC',
            'DASH',
            'ETH',
            'LTC',
        );

        return true;
    }

    public function touch(&$options)
    {
        $stamp = time();

        global $wpdb;

        $wpdb->query("ALTER TABLE {$this->config['db_prefix']}scheduled_payouts ADD touch INT UNSIGNED NOT NULL DEFAULT 0 AFTER stamp");
        $wpdb->query("ALTER TABLE {$this->config['db_prefix']}scheduled_payouts ADD INDEX touch (touch, amount)");
        $wpdb->query("UPDATE {$this->config['db_prefix']}scheduled_payouts SET touch = stamp WHERE stamp > 0");
        $wpdb->query("UPDATE {$this->config['db_prefix']}scheduled_payouts SET touch = {$stamp} WHERE touch = 0");

        $wpdb->query("ALTER TABLE {$this->config['db_prefix']}info_address ADD touch INT UNSIGNED NOT NULL DEFAULT 0 AFTER stamp");
        $wpdb->query("ALTER TABLE {$this->config['db_prefix']}info_address ADD INDEX touch (touch,currency)");
        $wpdb->query("UPDATE {$this->config['db_prefix']}info_address SET touch = seniority_current WHERE seniority_current > 0");
        $wpdb->query("UPDATE {$this->config['db_prefix']}info_address SET touch = {$stamp} WHERE touch = 0");

        $wpdb->query("ALTER TABLE {$this->config['db_prefix']}info_ip ADD touch INT UNSIGNED NOT NULL DEFAULT 0 AFTER stamp");
        $wpdb->query("UPDATE {$this->config['db_prefix']}info_ip SET touch = seniority_current WHERE seniority_current > 0");
        $wpdb->query("UPDATE {$this->config['db_prefix']}info_ip SET touch = {$stamp} WHERE touch = 0");

        $wpdb->query("ALTER TABLE {$this->config['db_prefix']}info_user ADD touch INT UNSIGNED NOT NULL DEFAULT 0 AFTER stamp");
        $wpdb->query("UPDATE {$this->config['db_prefix']}info_user SET touch = seniority_current WHERE seniority_current > 0");
        $wpdb->query("UPDATE {$this->config['db_prefix']}info_user SET touch = {$stamp} WHERE touch = 0");

        $options['stats']['last_payout'] = $wpdb->get_var("SELECT MAX(stamp) FROM {$this->config['db_prefix']}scheduled_payouts");

        return true;
    }

    public function stats(&$options)
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        /** @var wpdb $wpdb */
        global $wpdb;

        $tables = array(
            'stats' => '(
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `currency` enum(\'BTC\',\'DASH\',\'LTC\',\'ETH\') NOT NULL DEFAULT \'BTC\',
                `stamp` int(10) unsigned NOT NULL DEFAULT \'0\',
                `total` int(10) unsigned NOT NULL DEFAULT \'0\',
                `direct` int(10) unsigned NOT NULL DEFAULT \'0\',
                `referral` int(10) unsigned NOT NULL DEFAULT \'0\',
                `seniority` int(10) unsigned NOT NULL DEFAULT \'0\',
                `bonus` int(10) unsigned NOT NULL DEFAULT \'0\',
                `penalty` int(10) unsigned NOT NULL DEFAULT \'0\',
                `submits` int(10) unsigned NOT NULL DEFAULT \'0\',
                PRIMARY KEY (`id`),
                UNIQUE KEY `stamp` (`stamp`,`currency`)
            ) DEFAULT CHARSET=utf8 COLLATE=utf8_bin',
        );

        foreach ($tables as $table => $definition) {
            dbDelta('CREATE TABLE ' . $this->config['db_prefix'] . $table . ' ' . $definition);
        }

        return true;
    }

    public function doge(&$options)
    {
	    $options['claim_rules']['DOGE'] = array(
		    'currency' => 'DOGE',
		    'threshold' => 0,
		    'rules' => array(),
	    );
	    $options['claim_rules']['USDDOGE'] = array(
		    'currency' => 'USDDOGE',
		    'threshold' => 0,
		    'exchange_rate' => 0,
		    'exchange_rate_auto' => 0,
		    'rules' => array(),
	    );

	    $options['wallet']['faucetpay']['supports'] = array(
		    'BTC',
		    'DASH',
		    'DOGE',
		    'LTC',
	    );

	    /** @var wpdb $wpdb */
	    global $wpdb;
	    $wpdb->query("ALTER TABLE {$this->config['db_prefix']}info_address CHANGE currency currency ENUM('BTC','DASH','DOGE', 'ETH', 'LTC') NOT NULL DEFAULT 'BTC'");
	    $wpdb->query("ALTER TABLE {$this->config['db_prefix']}stats CHANGE currency currency ENUM('BTC','DASH','DOGE', 'ETH', 'LTC') NOT NULL DEFAULT 'BTC'");

	    return true;
    }

    public function bchbtxblkppcxpmpot(&$options)
    {
	    $options['claim_rules']['BCH'] = array(
		    'currency' => 'BCH',
		    'threshold' => 0,
		    'rules' => array(),
	    );
	    $options['claim_rules']['USDBCH'] = array(
		    'currency' => 'USDBCH',
		    'threshold' => 0,
		    'exchange_rate' => 0,
		    'exchange_rate_auto' => 0,
		    'rules' => array(),
	    );

	    $options['claim_rules']['BTX'] = array(
		    'currency' => 'BTX',
		    'threshold' => 0,
		    'rules' => array(),
	    );
	    $options['claim_rules']['USDBTX'] = array(
		    'currency' => 'USDBTX',
		    'threshold' => 0,
		    'exchange_rate' => 0,
		    'exchange_rate_auto' => 0,
		    'rules' => array(),
	    );

	    $options['claim_rules']['BLK'] = array(
		    'currency' => 'BLK',
		    'threshold' => 0,
		    'rules' => array(),
	    );
	    $options['claim_rules']['USDBLK'] = array(
		    'currency' => 'USDBLK',
		    'threshold' => 0,
		    'exchange_rate' => 0,
		    'exchange_rate_auto' => 0,
		    'rules' => array(),
	    );

	    $options['claim_rules']['PPC'] = array(
		    'currency' => 'PPC',
		    'threshold' => 0,
		    'rules' => array(),
	    );
	    $options['claim_rules']['USDPPC'] = array(
		    'currency' => 'USDPPC',
		    'threshold' => 0,
		    'exchange_rate' => 0,
		    'exchange_rate_auto' => 0,
		    'rules' => array(),
	    );

	    $options['claim_rules']['XPM'] = array(
		    'currency' => 'XPM',
		    'threshold' => 0,
		    'rules' => array(),
	    );
	    $options['claim_rules']['USDXPM'] = array(
		    'currency' => 'USDXPM',
		    'threshold' => 0,
		    'exchange_rate' => 0,
		    'exchange_rate_auto' => 0,
		    'rules' => array(),
	    );

	    $options['claim_rules']['POT'] = array(
		    'currency' => 'POT',
		    'threshold' => 0,
		    'rules' => array(),
	    );
	    $options['claim_rules']['USDPOT'] = array(
		    'currency' => 'USDPOT',
		    'threshold' => 0,
		    'exchange_rate' => 0,
		    'exchange_rate_auto' => 0,
		    'rules' => array(),
	    );

	    $options['wallet']['faucetpay']['supports'] = array(
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
	    );

	    /** @var wpdb $wpdb */
	    global $wpdb;
	    $wpdb->query("ALTER TABLE {$this->config['db_prefix']}info_address CHANGE currency currency ENUM('BCH', 'BLK', 'BTC', 'BTX', 'DASH', 'DOGE', 'ETH', 'LTC', 'POT', 'PPC', 'XPM') NOT NULL DEFAULT 'BTC'");
	    $wpdb->query("ALTER TABLE {$this->config['db_prefix']}stats        CHANGE currency currency ENUM('BCH', 'BLK', 'BTC', 'BTX', 'DASH', 'DOGE', 'ETH', 'LTC', 'POT', 'PPC', 'XPM') NOT NULL DEFAULT 'BTC'");

	    return true;
    }

    public function solveMediaNoLazy(&$options)
    {
        if (!empty($options['config']['solve_media_type']) && $options['config']['solve_media_type'] === 'lazy') {
            $options['config']['solve_media_type'] = 'ajax';
        }

        return true;
    }
}
