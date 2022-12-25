<?php

class The99Bitcoins_BtcFaucet_Info_Ips
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
     * Return information about specified ip or empty array.
     *
     * @param string $ip
     * @param int $limit
     * @return array
     */
    public function get($ip, $limit = 0)
    {
        $ip = trim($ip);
        if (!$ip) {
            return array();
        }
        $info = $this->db->get_row($this->db->prepare(
            "SELECT * FROM {$this->config['db_prefix']}info_ip WHERE ip = %s",
            inet_pton($ip)
        ), ARRAY_A);
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
                $this->db->update("{$this->config['db_prefix']}info_ip", array(
                    'seniority_first' => $info['seniority_first'],
                    'seniority_current' => $info['seniority_current'],
                ), array(
                    'id' => $info['id'],
                ));
            } else {
                $info['seniority_days'] = $info['seniority_days_first'];
            }
        }

        return $info;
    }

    public function getById($id)
    {
        $ip = $this->db->get_var($this->db->prepare("SELECT ip FROM {$this->config['db_prefix']}info_ip WHERE id = %d", $id));
        return $ip ? inet_ntop($ip) : '';
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
            "SELECT ip FROM {$this->config['db_prefix']}info_ip WHERE refer_id = %d",
            trim($referId)
        ));
    }

    /**
     * Touches related record.
     *
     * @param int $ipId
     * @return bool
     */
    public function touch($ipId)
    {
        if (!$ipId) {
            return false;
        }

        return $this->db->update("{$this->config['db_prefix']}info_ip", array(
            'touch' => time(),
        ), array(
            'id' => $ipId,
        ));
    }

    /**
     * Updates information about ip on submit.
     *
     * @param string $ip
     * @param int $referId
     * @return bool
     */
    public function track($ip, $referId = 0)
    {
        $ip = trim($ip);
        if (!$ip) {
            return false;
        }

        $data = array(
            'ip' => inet_pton($ip),
            'refer_id' => $referId,
        );

        if ($info = $this->get($ip)) {
            if ($info['refer_id']) {
                $data['refer_id'] = $info['refer_id'];
            }
            if (!array_diff_assoc($data, $info)) {
                return true;
            }
            return $this->db->update("{$this->config['db_prefix']}info_ip", $data, array(
                'id' => $data['id'],
            ));
        }
        return $this->db->insert("{$this->config['db_prefix']}info_ip", $data);
    }

    /**
     * Sets seniority dates.
     *
     * @param int $ipId
     * @param string $stamp
     * @return bool
     */
    public function seniority($ipId, $stamp = null)
    {
        if ($stamp === null) {
            $stamp = time();
        }

        $info = $this->get($this->getById($ipId));
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
        return $this->db->update("{$this->config['db_prefix']}info_ip", $data, array(
            'id' => $info['id'],
        ));
    }
}
