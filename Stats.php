<?php

class The99Bitcoins_BtcFaucet_Claim_Stats
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
     * @param bool $reverse
     * @return int
     */
    public function latest($reverse = false)
    {
        return $this->db->get_var("SELECT " . ($reverse ? 'MAX' : 'MIN') . "(stamp) FROM {$this->config['db_prefix']}claim_payouts");
    }

    public function fromClaims($from, $to)
    {
        $return = array();

        foreach ($this->db->get_results($this->db->prepare("
            SELECT
                {$this->config['db_prefix']}info_address.currency,
                {$this->config['db_prefix']}claim_payouts.source,
                SUM({$this->config['db_prefix']}claim_payouts.amount) amount,
                COUNT(1) count
            FROM
                {$this->config['db_prefix']}claim_payouts
            JOIN
                {$this->config['db_prefix']}info_address
            ON
                {$this->config['db_prefix']}info_address.id = {$this->config['db_prefix']}claim_payouts.address_id 
            WHERE
                {$this->config['db_prefix']}claim_payouts.stamp BETWEEN %d AND %d
            GROUP BY
                {$this->config['db_prefix']}info_address.currency,
                {$this->config['db_prefix']}claim_payouts.source", $from, $to), ARRAY_A) as $row) {
            if (empty($return[$row['currency']][$row['source']])) {
                $return[$row['currency']][$row['source']] = 0;
            }
            $return[$row['currency']][$row['source']] += $row['amount'];
            if ($row['source'] === 'direct') {
                if (empty($return[$row['currency']]['submits'])) {
                    $return[$row['currency']]['submits'] = 0;
                }
                $return[$row['currency']]['submits'] += $row['count'];
            }
        }

        return $return;
    }

    public function toStats($currency, $stamp, $data)
    {
        $data += array(
            'direct' => 0,
            'referral' => 0,
            'seniority' => 0,
            'bonus' => 0,
            'penalty' => 0,
            'submits' => 0,
        );
        $id = $this->db->get_var($this->db->prepare("SELECT id FROM {$this->config['db_prefix']}stats WHERE currency = %s AND stamp = %d", $currency, $stamp));
        if ($id) {
            $this->db->update("{$this->config['db_prefix']}stats", array(
                'total' => $data['direct'] + $data['referral'] + $data['seniority'] + $data['bonus'] - $data['penalty'],
            ) + $data, array(
                'id' => $id,
            ));
        } else {
            $this->db->insert("{$this->config['db_prefix']}stats", array(
                'currency' => $currency,
                'stamp' => $stamp,
                'total' => $data['direct'] + $data['referral'] + $data['seniority'] + $data['bonus'] - $data['penalty'],
            ) + $data);
            $id = $this->db->insert_id;
        }

        return $id;
    }

    public function get($currency, $stamp)
    {
        $result = $this->db->get_row($this->db->prepare("SELECT total, direct, referral, seniority, bonus, penalty, submits FROM {$this->config['db_prefix']}stats WHERE currency = %s AND stamp = %d", $currency, $stamp));
        return $result ? $result : array();
    }
}
