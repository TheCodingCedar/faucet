<?php

class The99Bitcoins_BtcFaucet_ClaimRules_BaseExchangeRate extends The99Bitcoins_BtcFaucet_ClaimRules_Base
{
    public function lottery()
    {
        $prize = $this->doLottery();

        if (!empty($this->config['exchange_rate'])) {
            $prize = 1 / $this->config['exchange_rate'] * $prize * $this->currency()->size();
        } else {
            $prize = 0;
        }

        return ceil($prize);
    }
}
