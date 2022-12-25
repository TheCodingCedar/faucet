<?php

class The99Bitcoins_BtcFaucet_Scheduled_Payouts
{
    /** @var wpdb */
    protected $db;

	/** @var array */
	protected $config = array();

	public function __construct($config)
	{
		$this->config = $config + $this->config;
        $this->db = $GLOBALS['wpdb'];
    }

    /**
     * Returns list of claimed payouts.
     *
     * @param string $fromDate
     * @param string $toDate
     * @param string $currency
     * @return array
     */
    public function search($fromDate = '', $toDate = '', $currency = 'BTC')
    {
        $where = array("transaction != ''");
        if ($fromDate == '0000-00-00' && $toDate == '0000-00-00') {
            $where = array("source.stamp = 0");
        } elseif ($fromDate && $toDate) {
            $fromDate = DateTime::createFromFormat('Y-m-d', $fromDate)
                ->setTime(0,0, 0)->getTimestamp();
            $toDate = DateTime::createFromFormat('Y-m-d', $toDate)
                ->setTime(0, 0, 0)->getTimestamp();
            $where[] = "source.stamp >= " . $fromDate;
            $where[] = "source.stamp < " . $toDate;
        } elseif ($fromDate) {
            $fromDate = DateTime::createFromFormat('Y-m-d', $fromDate)
                ->setTime(0, 0, 0)->getTimestamp();
            $where[] = "source.stamp >= " . $fromDate;
        } elseif ($toDate) {
            $toDate = DateTime::createFromFormat('Y-m-d', $toDate)
                ->setTime(0, 0, 0)->getTimestamp();
            $where[] = "source.stamp < " . $toDate;
        }
        if ($where) {
            $where = 'WHERE ' . implode(' AND ', $where);
        } else {
            $where = '';
        }
        return $this->db->get_results("
            SELECT
                source.*,
                address.address
            FROM
                {$this->config['db_prefix']}scheduled_payouts source
            JOIN
                {$this->config['db_prefix']}info_address address
            ON
                address.id = source.address_id
                AND address.currency = '" . esc_sql($currency) . "'
                {$where}
            ORDER BY
                source.stamp DESC,
                source.id DESC
            LIMIT 1000
        ", ARRAY_A);
    }

    /**
     * Returns sum of claimed payouts.
     *
     * @param string $fromDate
     * @param string $toDate
     * @return int
     */
    public function searchAmount($fromDate = '', $toDate = '')
    {
        $where = array("transaction != ''");
        if ($fromDate && $toDate) {
            $fromDate = DateTime::createFromFormat('Y-m-d', $fromDate)
                ->setTime(0, 0, 0)->getTimestamp();
            $toDate = DateTime::createFromFormat('Y-m-d', $toDate)
                ->setTime(0, 0, 0)->getTimestamp();
            $where[] = "stamp >= " . $fromDate;
            $where[] = "stamp < " . $toDate;
        } elseif ($fromDate) {
            $fromDate = DateTime::createFromFormat('Y-m-d', $fromDate)
                ->setTime(0, 0, 0)->getTimestamp();
            $where[] = "stamp >= " . $fromDate;
        } elseif ($toDate) {
            $toDate = DateTime::createFromFormat('Y-m-d', $toDate)
                ->setTime(0, 0, 0)->getTimestamp();
            $where[] = "stamp < " . $toDate;
        }
        if ($where) {
            $where = 'WHERE ' . implode(' AND ', $where);
        } else {
            $where = '';
        }
        return $this->db->get_var("SELECT SUM(amount) FROM {$this->config['db_prefix']}scheduled_payouts {$where}");
    }

    /**
     * Returns list of finalized transactions for specified address.
     *
     * @param int $addressId
     * @param int $id
     * @return array
     */
    public function transactionsByAddress($addressId, $id = null)
    {
    	$where = array();
	    if ($id) {
	    	$where[] = 'id < ' . $id;
	    }
	    $where = $where ? 'AND ' . implode(' AND ', $where) : '';
        return $this->db->get_results($this->db->prepare(
            "SELECT * FROM {$this->config['db_prefix']}scheduled_payouts WHERE address_id = %d AND stamp > 0 $where ORDER BY stamp DESC, id DESC LIMIT 20",
            $addressId
        ), ARRAY_A);
    }

    public function getLastStamp()
    {
        return $this->db->get_var("SELECT stamp FROM {$this->config['db_prefix']}scheduled_payouts WHERE transaction <> '' ORDER BY stamp DESC LIMIT 1");
    }
}
