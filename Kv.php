<?php

class The99Bitcoins_BtcFaucet_Tool_Kv
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
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        if ($value) {
            $this->db->query($this->db->prepare("
                    INSERT INTO
                        {$this->config['db_prefix']}kv
                        (id, stamp, payload)
                    VALUES
                        (%s, %d, %s)
                    ON DUPLICATE KEY UPDATE
                        stamp = %d,
                        payload = %s
                ",
                $key, time(), $value,
                time(), $value
            ));
        } else {
            $this->db->delete("{$this->config['db_prefix']}kv", array(
                'id' => $key,
            ));
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if (property_exists($this->db, 'srtm') && empty($this->db->srtm)) {
            $this->db->srtm = array(
                "{$this->config['db_prefix']}kv" => true,
            );
        } elseif (property_exists($this->db, 'srtm') && is_array($this->db->srtm) && empty($this->db->srtm["{$this->config['db_prefix']}kv"])) {
            $this->db->srtm["{$this->config['db_prefix']}kv"] = true;
        }
        return $this->db->get_var($this->db->prepare("SELECT payload FROM {$this->config['db_prefix']}kv WHERE id = %s", $key));
    }

    /**
     * @param int $stamp
     */
    public function prune($stamp)
    {
        $this->db->query($this->db->prepare("DELETE FROM {$this->config['db_prefix']}kv WHERE stamp < %d", $stamp));
    }
}
