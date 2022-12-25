<?php

class The99Bitcoins_BtcFaucet_Scheduled_Payment
{
    /** @var wpdb */
    protected $db;

    /** @var The99Bitcoins_BtcFaucet_Claim_Payouts  */
    protected $claimPayouts;

	/** @var array */
	protected $config = array();

    public function __construct($config, The99Bitcoins_BtcFaucet_Claim_Payouts $claimPayouts)
    {
	    $this->config = $config + $this->config;
        $this->db = $GLOBALS['wpdb'];
        $this->claimPayouts = $claimPayouts;
    }

    /**
     * Returns list of scheduled payouts below specified amount.
     *
     * @param string $currency
     * @param int $maxTransactionAmount
     * @param int $limit
     * @param int[] $ignore
     * @return array
     */
    public function search($currency, $maxTransactionAmount, $limit = 250, $ignore = array())
    {
        if ($limit) {
            $limit = ' LIMIT ' . $limit;
        } else {
            $limit = '';
        }
        $where = array();
        $values = array();
        if ($maxTransactionAmount) {
            $where[] = 'source.amount <= %d';
            $values[] = $maxTransactionAmount;
        }
        if ($ignore) {
            $where[] = 'source.id NOT IN (' . implode(', ', array_pad(array(), count($ignore), '%s')) . ')';
            $values = array_merge($values, $ignore);
        }
        if ($where) {
            $where = ' AND ' . implode(' AND ', $where);
        } else {
            $where = '';
        }

        return $this->db->get_results($this->db->prepare("
            SELECT
                source.*,
                address.address
            FROM
                {$this->config['db_prefix']}scheduled_payouts source
            JOIN
                {$this->config['db_prefix']}info_address address
            ON
                address.id = source.address_id
                AND address.currency = %s
            WHERE
                source.stamp = 0
                {$where}
            ORDER BY
                source.touch ASC, source.amount ASC" . $limit, array_merge(array($currency), $values)), ARRAY_A);
    }

    /**
     * Schedules amounts per address for payouts.
     *
     * @param string $currency
     * @param int $threshold
     * @param bool $activeAddress
     * @param int $fromStamp
     * @return bool
     */
    public function schedule($currency = 'BTC', $threshold = 0, $activeAddress = false, $fromStamp = 0)
    {
        $stamp = null;
        $limit = 0;
        @set_time_limit(0);
        if (@ini_get('max_execution_time')) {
            $stamp = microtime(true);
            $limit = @ini_get('max_execution_time');
        }

        $this->db->query("LOCK TABLES
            {$this->config['db_prefix']}claim_payouts WRITE,
            {$this->config['db_prefix']}scheduled_payouts WRITE,
            {$this->config['db_prefix']}claim_payouts source READ,
            {$this->config['db_prefix']}info_address address READ,
            {$this->config['db_prefix']}ban_address READ,
            {$this->config['db_prefix']}white_address READ,
            {$this->db->prefix}usermeta READ
        ");

        foreach (array_chunk($this->claimPayouts->searchGrouped($threshold, $currency, $fromStamp), 50) as $addresses) {
            if ($stamp && $limit && ($limit - (microtime(true) - $stamp)) < 5) {
                return false;
            }

            $set = array();
            foreach ($addresses as $address) {
                $set[$address['address_id']] = array(
                    'id' => $address['address_id'],
                    'address' => $address['address'],
                    'amount' => 0,
                    'transaction' => 0,
                    'ids' => array(),
                );
            }
            $addresses = $set;

            $validAddresses = array();
            foreach ($addresses as $address) {
                $validAddresses[] = $address['address'];
            }

            if ($activeAddress) {
                $set = array_map('esc_sql', $validAddresses);
                $validAddresses = $this->db->get_col($this->db->prepare(
                    "
                        SELECT
                            meta_value address
                        FROM
                            {$this->db->prefix}usermeta
                        WHERE
                            meta_key = %s
                            AND meta_value IN ('" . implode("', '", $set) . "')
                    ", 'the99btc_address_' . $currency
                ));
                foreach ($addresses as &$address) {
                    if (!in_array($address['address'], $validAddresses)) {
                        $address = null;
                    }
                }
                $addresses = array_filter($addresses);
            }
            if (!$addresses) {
                continue;
            }

            $set = array_map('esc_sql', $validAddresses);
            $query = "
                SELECT
                    {$this->config['db_prefix']}ban_address.address
                FROM
                    {$this->config['db_prefix']}ban_address
                LEFT JOIN
                    {$this->config['db_prefix']}white_address
                ON
                    {$this->config['db_prefix']}white_address.address = {$this->config['db_prefix']}ban_address.address
                WHERE
                    {$this->config['db_prefix']}ban_address.address IN ('" . implode("', '", $set) . "')
                    AND
                    {$this->config['db_prefix']}white_address.address IS NULL
            ";
            $validAddresses = $this->db->get_col($query);
            foreach ($addresses as &$address) {
                if (in_array($address['address'], $validAddresses)) {
                    $address = null;
                }
            }
            $addresses = array_filter($addresses);
            if (!$addresses) {
                continue;
            }

            $set = array_map('esc_sql', array_keys($addresses));
            $query = "
                SELECT
                    id,
                    address_id,
                    amount
                FROM
                    {$this->config['db_prefix']}claim_payouts source
                WHERE
                    scheduled_payouts_id = 0
                    AND
                    paid = 'no'
                    AND
                    address_id IN ('" . implode("', '", $set) . "')
            ";
            foreach ($this->db->get_results($query, ARRAY_A) as $item) {
                $addresses[$item['address_id']]['amount'] += $item['amount'];
                $addresses[$item['address_id']]['ids'][] = $item['id'];
            }
            foreach ($addresses as &$address) {
                if ($address['amount'] < $threshold) {
                    $address = null;
                }
            }
            unset($address);
            $addresses = array_filter($addresses);
            if (!$addresses) {
                continue;
            }

            $set = array_map('esc_sql', array_keys($addresses));
            $query = "
                SELECT
                    id,
                    threshold
                FROM
                    {$this->config['db_prefix']}info_address address
                WHERE
                    id IN ('" . implode("', '", $set) . "')
            ";
            foreach ($this->db->get_results($query, ARRAY_A) as $item) {
                if ($addresses[$item['id']]['amount'] < $item['threshold']) {
                    $addresses[$item['id']] = null;
                }
            }
            $addresses = array_filter($addresses);
            if (!$addresses) {
                continue;
            }

            $set = array_map('esc_sql', array_keys($addresses));
            $query = "
                SELECT
                    id,
                    address_id,
                    amount
                FROM
                    {$this->config['db_prefix']}scheduled_payouts
                WHERE
                    stamp = 0
                    AND
                    address_id IN ('" . implode("', '", $set) . "')
            ";
            foreach ($this->db->get_results($query, ARRAY_A) as $item) {
                if ($addresses[$item['address_id']]) {
                    $addresses[$item['address_id']]['transaction'] = $item['id'];
                    $addresses[$item['address_id']]['amount'] += $item['amount'];
                }
            }

            foreach ($addresses as &$data) {
                if ($data['transaction']) {
                    $this->db->update("{$this->config['db_prefix']}scheduled_payouts", array(
                        'amount' => $data['amount'],
                    ), array(
                        'id' => $data['transaction'],
                    ));
                } else {
                    $this->db->insert("{$this->config['db_prefix']}scheduled_payouts", array(
                        'touch' => time(),
                        'address_id' => $data['id'],
                        'amount' => $data['amount'],
                    ));
                    $data['transaction'] = $this->db->insert_id;
                }
                if ($data['transaction']) {
                    foreach (array_chunk($data['ids'], 500) as $ids) {
                        $this->db->query("
                            UPDATE
                                {$this->config['db_prefix']}claim_payouts
                            SET
                                scheduled_payouts_id = {$data['transaction']}
                            WHERE
                                id IN (" . implode(', ', $ids) . ")
                                AND
                                scheduled_payouts_id = 0
                        ");
                    }
                }
            }
            unset($data);
        }

        $this->db->query("UNLOCK TABLES");

        return true;
    }

    /**
     * Adds fee transaction.
     *
     * @param string $currency
     * @param float $fee
     * @return float
     */
    public function calculateFee($currency, $fee)
    {
        $result = $fee * $this->db->get_var($this->db->prepare("
            SELECT
                SUM(source.amount)
            FROM
                {$this->config['db_prefix']}scheduled_payouts source
            JOIN
                {$this->config['db_prefix']}info_address address
            ON
                address.id = source.address_id
                AND address.currency = %s
            WHERE
                source.stamp > 0
                AND source.fee = 'no'
            ", $currency));
        return $result;
    }

    /**
     * @param string $currency
     */
    public function finalizeFee($currency)
    {
        $this->db->query($this->db->prepare("
            UPDATE
                {$this->config['db_prefix']}scheduled_payouts
            JOIN
                {$this->config['db_prefix']}info_address address
            ON
                address.id = {$this->config['db_prefix']}scheduled_payouts.address_id
                AND address.currency = %s
            SET
                {$this->config['db_prefix']}scheduled_payouts.fee = 'yes'
            WHERE
                {$this->config['db_prefix']}scheduled_payouts.stamp > 0
                AND {$this->config['db_prefix']}scheduled_payouts.fee = 'no'
        ", $currency));
    }

    /**
     * Finalizes scheduled payout.
     *
     * @param int $id
     * @param string $transaction
     * @return bool
     */
    public function finalize($id, $transaction)
    {
        return $this->db->update("{$this->config['db_prefix']}scheduled_payouts", array(
            'stamp' => time(),
            'touch' => time(),
            'transaction' => $transaction,
        ), array(
            'id' => $id,
        ));
    }

    /**
     * @param int $id
     * @return bool
     */
    public function rollback($id)
    {
        return $this->db->delete("{$this->config['db_prefix']}scheduled_payouts", array(
            'id' => $id,
        ));
    }

    /**
     * Updates information about payout.
     *
     * @param string $transactionId
     * @return int
     */
    public function touch($transactionId)
    {
        if (!$transactionId) {
            return false;
        }

        return $this->db->update("{$this->config['db_prefix']}scheduled_payouts", array(
            'touch' => time(),
        ), array(
            'id' => $transactionId,
        ));
    }
}
