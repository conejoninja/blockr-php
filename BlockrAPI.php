<?php

class BlockrAPI {

    public function __construct($coin = "btc") {
        $this->_coin = $coin;
    }

    public function setCoin($coin) {
        $this->_coin = $coin;
    }

    public function coinInfo() {
        return $this->_doRequest('/coin/info');
    }

    public function exchangeRate() {
        return $this->_doRequest('/exchangerate/current');
    }

    public function blockInfo($block) {
        return $this->_doRequest('/block/info/' . $block);
    }

    public function blockTxs($block) {
        return $this->_doRequest('/block/txs/' . $block);
    }

    public function blockRaw($block) {
        return $this->_doRequest('/block/raw/' . $block);
    }

    public function txInfo($hash, $format = 'string') {
        return $this->_doRequest('/tx/info/' . $hash, array('amount_format' => $format));
    }

    public function txRaw($hash) {
        return $this->_doRequest('/tx/raw/' . $hash);
    }

    public function unconfirmedTxInfo($hash) {
        return $this->_doRequest('/zerotx/info/' . $hash);
    }

    public function addressInfo($address, $confirmations = 15, $format = 'string') {
        return $this->_doRequest('/address/info/' . $address, array('confirmations' => $confirmations, 'amount_format' => $format));
    }

    public function addressBalance($address, $confirmations = 15) {
        return $this->_doRequest('/address/balance/' . $address, array('confirmations' => $confirmations));
    }

    public function addressTxs($address) {
        return $this->_doRequest('/address/txs/' . $address);
    }

    public function addressUnspent($address, $unconfirmed = 0, $multisigs = 0) {
        return $this->_doRequest('/address/unspent/' . $address, array('unconfirmed' => $unconfirmed, 'multisigs' => $multisigs));
    }

    public function addressUnconfirmed($address) {
        return $this->_doRequest('/address/unconfirmed' . $address);
    }

    /**
     * Create a request to the Blockr's API
     *
     * @param $action
     * @param array $params
     * @return array (json)
     */
    private function _doRequest($action, array $params = array()) {
        $request = http_build_query($params, '', '&');

        $headers = array();
        static $curl = null;
        if(is_null($curl)) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        }
        curl_setopt($curl, CURLOPT_URL, 'http://' . $this->_coin . '.blockr.io/api/v1' . $action);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $json = curl_exec($curl);
        if($json === false) {
            return array('error' => 1, 'message' => curl_error($curl));
        }
        $data = json_decode($json, true);
        if(!$data) {
            return array('error' => 2, 'message' => 'Invalid data received, please make sure connection is working and requested API exists');
        }
        return $data;
    }

}