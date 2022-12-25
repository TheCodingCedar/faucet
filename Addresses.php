<?php

class The99Bitcoins_BtcFaucet_Info_Addresses
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
     * Return information about specified address or empty array.
     *
     * @param string $address prefixed by currency BTC:123
     * @param int $limit
     * @return array
     */
    public function get($address, $limit = 0)
    {
        $currency = '';
        if (strpos($address, ':') !== false) {
            list($currency, $address) = explode(':', $address, 2);
        }
        if (!$address) {
            return array();
        }

        if ($currency && $address) {
            $info = $this->db->get_row($this->db->prepare(
                "SELECT * FROM {$this->config['db_prefix']}info_address WHERE address = %s AND currency = %s LIMIT 1",
                $address,
                $currency
            ), ARRAY_A);
        } else {
            $info = $this->db->get_row($this->db->prepare(
                "SELECT * FROM {$this->config['db_prefix']}info_address WHERE address = %s LIMIT 1",
                $address
            ), ARRAY_A);
        }
        if (!$info) {
            return array();
        }

        $info['seniority_days'] = 0;
        $info['seniority_days_current'] = 0;
        $info['seniority_days_first'] = 0;
        if ($info['seniority_first'] && $info['seniority_current']) {
            $info['seniority_days_current'] = floor((time() - $info['seniority_current']) / (60 * 60 * 24));
            $info['seniority_days_first'] = floor((time() - $info['seniority_first']) / (60 * 60 * 24));
            if ($limit && $info['seniority_days_current'] > $limit) {
                $info['seniority_days'] = 0;
                $info['seniority_first'] = 0;
                $info['seniority_current'] = 0;
                $this->db->update("{$this->config['db_prefix']}info_address", array(
                    'seniority_first' => $info['seniority_first'],
                    'seniority_current' => $info['seniority_current'],
                ), array(
                    'id' => $info['id'],
                ));
            } else {
                $info['seniority_days'] = $info['seniority_days_first'];
            }
        }
        if ($info['refer_id'] && $info['id'] == $info['refer_id']) {
            $info['refer_id'] = 0;
            $this->db->update("{$this->config['db_prefix']}info_address", array(
                'refer_id' => 0,
            ), array(
                'id' => $info['id'],
            ));
        }

        return $info;
    }

    public function getById($id)
    {
        return $this->db->get_var($this->db->prepare("SELECT concat(currency, ':', address) FROM {$this->config['db_prefix']}info_address WHERE id = %d", $id));
    }

    /**
     * Returns list of invitees of specified address.
     *
     * @param int $referId
     * @return string[]
     */
    public function invitees($referId)
    {
        return $this->db->get_col($this->db->prepare(
            "SELECT address FROM {$this->config['db_prefix']}info_address WHERE refer_id = %d",
            $referId
        ));
    }

    /**
     * Touches related record.
     *
     * @param int $addressId
     * @return bool
     */
    public function touch($addressId)
    {
        if (!$addressId) {
            return false;
        }

        return $this->db->update("{$this->config['db_prefix']}info_address", array(
            'touch' => time(),
        ), array(
            'id' => $addressId,
        ));
    }

    /**
     * Updates information about address on submit.
     *
     * @param string $address
     * @param int $referId
     * @return int
     */
    public function track($address, $referId = 0)
    {
        list($currency, $address) = explode(':', $address, 2);
        if (!$address) {
            return false;
        }

        $data = array(
            'currency' => $currency,
            'address' => $address,
            'refer_id' => $referId,
        );

        if ($info = $this->get($currency . ':' . $address)) {
            if ($info['refer_id']) {
                $data['refer_id'] = $info['refer_id'];
            }
            if (!array_diff_assoc($data, $info)) {
                return true;
            }
            return $this->db->update("{$this->config['db_prefix']}info_address", $data, array(
                'id' => $info['id']
            ));
        }

        return $this->db->insert("{$this->config['db_prefix']}info_address", $data);
    }

    /**
     * Sets seniority dates.
     *
     * @param int $addressId
     * @param int $stamp
     * @return bool
     */
    public function seniority($addressId, $stamp = null)
    {
        if ($stamp === null) {
            $stamp = time();
        }

        $info = $this->get($this->getById($addressId));
        if (!$info) {
            return false;
        }

        $data = array(
            'stamp' => $stamp,
            'seniority_first' => $info['seniority_first'],
            'seniority_current' => $stamp,
            'submits' => $info['submits'] + 1,
        );
        if (!$data['seniority_first']) {
            $data['seniority_first'] = $stamp;
        }
        return $this->db->update("{$this->config['db_prefix']}info_address", $data, array(
            'id' => $info['id'],
        ));
    }

    public function threshold($addressId, $threshold = 0)
    {
        return $this->db->update("{$this->config['db_prefix']}info_address", array(
            'threshold' => $threshold,
        ), array(
            'id' => $addressId,
        ));
    }
}
