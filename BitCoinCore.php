<?php

/**
 * Class BitCoinCore
 *
 * @package The99Bitcoins\BtcFaucet\Client
 * @method array getinfo()
 * @method float getbalance(string $account = '', float $minconf = 1)
 * @method array listaccounts()
 * @method array validateaddress(string $address)
 * @method string sendtoaddress(string $bitCoinAddress, float $amount, string $comment = '', string $commentTo = '')
 * @method walletpassphrase(string $secret, int $timeout)
 * @method string getaccountaddress(string $account)
 * @method bool move(string $fromaccount, string $toaccount, float $amount, float $minconf = 1, string $comment = '')
 * @method string sendmany(string $fromaccount, array $transactions, float $minconf = 1, string $comment = '', $addresses = array())
 */
class The99Bitcoins_BtcFaucet_Client_BitCoinCore
{
    /**
     * Debug state
     *
     * @var boolean
     */
    private $debug;

    /**
     * The server URL
     *
     * @var string
     */
    private $url;
    /**
     * The request id
     *
     * @var integer
     */
    private $id;
    /**
     * If true, notifications are performed instead of requests
     *
     * @var boolean
     */
    private $notification = false;

    /**
     * Takes the connection parameters
     *
     * @param string $url
     * @param boolean $debug
     */
    public function __construct($url, $debug = false)
    {
        // server URL
        $this->url = $url;
        // proxy
        empty($proxy) ? $this->proxy = '' : $this->proxy = $proxy;
        // message id
        $this->id = 1;
    }

    /**
     * Sets the notification state of the object. In this state, notifications are performed, instead of requests.
     *
     * @param boolean $notification
     */
    public function setRPCNotification($notification)
    {
        empty($notification) ? $this->notification = false : $this->notification = true;
    }

    /**
     * Performs a jsonRCP request and gets the results as an array
     *
     * @param string $method
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function __call($method, $params)
    {
    	switch ($method) {
    		case 'getinfo' :
	            $method = 'getblockchaininfo';
	            break;
	    }

        // check
        if (!is_scalar($method)) {
            throw new Exception('Method name has no scalar value');
        }

        // check
        if (is_array($params)) {
            // no keys
            $params = array_values($params);
        } else {
            throw new Exception('Params must be given as array');
        }

        // sets notification or request task
        $currentId = $this->notification ? rand(10000, 99999) : $this->id;

        // prepares the request
        $request = json_encode(array(
            'method' => $method,
            'params' => $params,
            'id' => $currentId,
        ));

        $data = parse_url($this->url);
        if (empty($data['scheme'])) {
            $data['scheme'] = 'http';
        }
        if (empty($data['port']) && $data['scheme'] == 'http') {
            $data['port'] = 80;
        }
        if (empty($data['port']) && $data['scheme'] == 'https') {
            $data['port'] = 443;
        }
        if (empty($data['user'])) {
            $data['user'] = '';
        }
        if (empty($data['pass'])) {
            $data['pass'] = '';
        }
        if (empty($data['path'])) {
            $data['path'] = '/';
        }
        if ($curl = curl_init($data['scheme'] . '://' . $data['host'] . ':' . $data['port'] . $data['path'])) {
            $options = array(
                CURLOPT_TIMEOUT => 5,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $request,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($request),
                ),
            );
            if ($data['user']) {
                $options[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
                $options[CURLOPT_USERPWD] = $data['user'] . ':' . $data['pass'];
            }
            curl_setopt_array($curl, $options);
            $result = curl_exec($curl);
            $error = curl_errno($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            if ($error || $status != 200) {
                $response = json_decode($result, true);
                if (!empty($response['error']['code']) && $response['error']['code'] == -6) {
                    $exception = new Exception($response['error']['message'], $response['error']['code']);
                    $exception->reason = 'balance';
                } else {
                    if (!empty($response['error'])) {
                        $exception = new Exception($response['error']['message'], $response['error']['code']);
                    } else {
                        $exception = new Exception('Unable to communicate with ' . $this->url . ', error #' . $error . ' with status ' . $status);
                    }
                    $exception->reason = 'error';
                    $exception->request_params = $params;
                    $exception->request_url = $data['scheme'] . '://' . $data['host'] . ':' . $data['port'] . $data['path'];
                    $exception->request_method = $method;
                    $exception->request_options = $options;
                    $exception->request_error = $error;
                    $exception->request_status = $status;
                    $exception->request_response = $result;
                }
                throw $exception;
            } else {
                $response = json_decode($result, true);
                if (function_exists('json_last_error') && json_last_error()) {
                    throw new Exception('Unable to decode response from ' . $this->url . ', ' . $result);
                }
            }
        } else {
            throw new Exception('Unable to connect to ' . $this->url . ', can not init curl');
        }

        // final checks and return
        if (!$this->notification) {
            // check
            if ($response['id'] != $currentId) {
                throw new Exception(
                    'Incorrect response id (request id: ' . $currentId . ', response id: ' . $response['id'] . ')'
                );
            }
            if (!is_null($response['error'])) {
                throw new Exception('Request error: ' . json_encode($response['error']));
            }

            return $response['result'];
        } else {
            return true;
        }
    }
}
