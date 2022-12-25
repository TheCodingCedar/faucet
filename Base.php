<?php

class The99Bitcoins_BtcFaucet_Currency_Base
{
    /** @var The99Bitcoins_BtcFaucet_Currency_BTC  */
    protected $currency = null;

    public function __construct($currency)
    {
        $this->currency = $currency;
    }

    public function symbol()
    {
        return $this->currency->symbol;
    }

    public function name()
    {
        return $this->currency->name;
    }

    public function satoshi()
    {
        return $this->currency->satoshi;
    }

    public function size()
    {
        return $this->currency->size;
    }

    public function validateAddress($address)
    {
        return $this->currency->validateAddress($address);
    }
}
