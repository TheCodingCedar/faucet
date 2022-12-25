<?php

class The99Bitcoins_BtcFaucet_Wallet_Fake implements The99Bitcoins_BtcFaucet_Wallet_WalletInterface
{
    public $limit = 250;

    public $fee = 0.05;

    public $min = 1;

    protected $url = '';

    protected $secret = '';

    public $errorData = null;

    /** @var The99Bitcoins_BtcFaucet_Client_BitCoinCore */
    protected $client = null;

    /**
     * @inheritDoc
     */
    public function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    public function isAccessible()
    {
        $return = true;
        return $return;
    }

    /**
     * @inheritDoc
     */
    public function getBalance()
    {
        $return = 100000000;
        return $return;
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

        foreach ($set as $address => $_) {
            $return[$address] = 0; // rand(100000000, 999999999);
        }
        return $return;
    }
}
