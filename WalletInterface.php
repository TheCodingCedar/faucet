<?php

/**
 * Interface The99Bitcoins_BtcFaucet_Wallet_WalletInterface
 *
 * @property int $limit max amount of addresses for 1 transaction
 * @property float $fee amount of fee which is taken by wallet
 * @property int min min amount of Satoshi for transaction
 * @property mixed $errorData
 */
interface The99Bitcoins_BtcFaucet_Wallet_WalletInterface
{
    /**
     * @return bool
     */
    public function isAccessible();

    /**
     * @return int|bool Satoshi
     */
    public function getBalance();

    /**
     * @param string $address
     * @return bool
     */
    public function validateAddress($address);

    /**
     * @param array $set address => Satoshi
     * @return array address => transaction id or empty string on failure
     */
    public function purchase(array $set);
}
