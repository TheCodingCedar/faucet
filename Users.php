<?php

class The99Bitcoins_BtcFaucet_Info_Users
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
     * Return information about specified user or empty array.
     *
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function get($userId, $limit = 0)
    {
        if (!$userId) {
            return array();
        }

        $info = $this->db->get_row($this->db->prepare(
            "SELECT * FROM {$this->config['db_prefix']}info_user WHERE user_id = %d",
            $userId
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
                $this->db->update("{$this->config['db_prefix']}info_user", array(
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
        return $this->db->get_var($this->db->prepare("SELECT user_id FROM {$this->config['db_prefix']}info_user WHERE id = %d", $id));
    }

    /**
     * Returns list of invitees of specified address.
     *
     * @param int $referId
     * @return int[]
     */
    public function invitees($referId)
    {
        return $this->db->get_col($this->db->prepare(
            "SELECT user_id FROM {$this->config['db_prefix']}info_user WHERE refer_id = %d",
            trim($referId)
        ));
    }

    /**
     * Touches related record.
     *
     * @param int $userId
     * @return bool
     */
    public function touch($userId)
    {
        if (!$userId) {
            return false;
        }

        return $this->db->update("{$this->config['db_prefix']}info_user", array(
            'touch' => time(),
        ), array(
            'id' => $userId,
        ));
    }

    /**
     * Updates information about user on submit.
     *
     * @param int $userId
     * @param int $referId
     * @return bool
     */
    public function track($userId, $referId = 0)
    {
        if (!$userId) {
            return false;
        }

        $data = array(
            'user_id' => $userId,
            'refer_id' => $referId,
        );

        if ($info = $this->get($userId)) {
            if ($info['refer_id']) {
                $data['refer_id'] = $info['refer_id'];
            }
            if (!array_diff_assoc($data, $info)) {
                return true;
            }
            return $this->db->update("{$this->config['db_prefix']}info_user", $data, array(
                'id' => $info['id'],
            ));
        }
        return $this->db->insert("{$this->config['db_prefix']}info_user", $data);
    }

    /**
     * Sets seniority dates.
     *
     * @param int $userId
     * @param int $stamp
     * @return bool
     */
    public function seniority($userId, $stamp = null)
    {
        if ($stamp === null) {
            $stamp = time();
        }

        $info = $this->get($this->getById($userId));
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
        return $this->db->update("{$this->config['db_prefix']}info_user", $data, array(
            'id' => $info['id'],
        ));
    }
}
