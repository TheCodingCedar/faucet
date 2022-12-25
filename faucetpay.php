<?php

class The99Bitcoins_BtcFaucet_Wallet_faucetpay implements The99Bitcoins_BtcFaucet_Wallet_WalletInterface
{
    public $limit = 5;

    public $fee = 0;

    public $min = 1;

    protected $apiKey = '';

    public $errorData = null;

    public $errors = array();

    /** @var The99Btcfaucetpay */
    protected $client = null;

    /** @var array */
    protected $supports = array();

    protected $currency = 'BTC';

    protected $currencies = array('BCH', 'BLK', 'BTC', 'BTX', 'DASH', 'DOGE', 'ETH', 'LTC', 'POT', 'PPC', 'XPM');

    /**
     * @inheritDoc
     */
    public function __construct($config, $currency = 'BTC')
    {
        $this->apiKey = $config['api_key'];
        $this->supports = $config['supports'];
        $this->currency = $currency;
    }

    /**
     * @return The99Btcfaucetpay
     */
    protected function client()
    {
        if (!$this->client) {
            $this->client = new The99Btcfaucetpay($this->apiKey, $this->currency);
        }
        return $this->client;
    }

    /**
     * @inheritDoc
     */
    public function isAccessible()
    {
        if (!in_array($this->currency, $this->currencies)) {
            return false;
        }
        if (!$this->apiKey) {
            return false;
        }

        try {
            $data = $this->client()->getBalance();
        } catch (Exception $exception) {
            $data = array(
                'status' => 500,
            );
        }
        return isset($data['status']) && $data['status'] == 200;
    }

    /**
     * @inheritDoc
     */
    public function getBalance()
    {
        $this->errorData = null;
        try {
            $data = $this->client()->getBalance();
        } catch (Exception $exception) {
            $data = array(
                'status' => 500,
            );
            $this->errorData = $exception;
            $this->errorData->reason = 'balance';
        }
        if (isset($data['status']) && $data['status'] == 200) {
            return $data['balance'];
        } else {
            $this->errorData = new Exception('Can not fetch balance');
            $this->errorData->reason = 'balance';
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function validateAddress($address)
    {
        $return = !!$address;
        return $return;
    }

    /**
     * @inheritDoc
     */
    public function purchase(array $set)
    {
        $this->errorData = null;
        $return = array();

        foreach ($set as $address => $satoshi) {
            try {
                $data = $this->client()->send($address, $satoshi, true);
                if (!empty($data['success'])) {
                    $return[$address] = 'faucetpay.io';
                } else {
                    $return[$address] = '';
                    if (!empty($data['r'])) {
                        $response = json_decode($data['r'], true);
                        $exception = new Exception($response['message'], $response['code']);
                        if ($exception->getCode() == 402) {
                            $exception->reason = 'balance';
                        } elseif($exception->getCode() == 456)  {
	                        $exception->reason = 'address';
                        }
	                    throw $exception;
                    } elseif (!empty($data['response'])) {
                        $response = json_decode($data['response'], true);
                        $exception = new Exception($response['message'], $response['status']);
                        if ($exception->getCode() == 402) {
                            $exception->reason = 'balance';
                        } elseif($exception->getCode() == 456)  {
	                        $exception->reason = 'address';
                        }
	                    throw $exception;
                    } elseif (!$this->errorData) {
                        $this->errorData = $data;
                    }
                }
            } catch (Exception $exception) {
            	$this->errors[$address] = isset($exception->reason) ? $exception->reason : 'unknown';
	            if (!empty($exception->reason) && $exception->reason === 'balance') {
		            $this->errorData = $exception;
	            } elseif (!$this->errorData) {
                    $this->errorData = $exception;
                }
            }
        }
        return $return;
    }
}
