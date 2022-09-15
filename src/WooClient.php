<?php

namespace Shreejalmaharjan27\Wooclient;

use FFI\Exception;

class WooClient {

    private string $key;
    private string $secret;
    public string $store;
    public string $wp;
    protected bool $dev = false;
    public mixed $error;


    /**
     * Set Consumer Key, Secret Key and Wordpress URL
     *
     * @param string $key WooCommerce REST API Key (ck_xxxxxxxxxxxxx)
     * @param string $secret WooCommerce REST API Secret Key (cs_xxxxxxxxxx)
     * @param string $store Wordpress Home URL (https://wordpress.example.com/)
     */
    public function __construct(string $key, string $secret, string $store)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->wp = $store;
        $this->store = $store."wp-json/wc/v3";
    }


    /**
     * Enables Developement Mode
     *
     * @param boolean $bool 
     *
     * @return void
     */
    public function devMode(bool $bool): void
    {
        $this->dev = $bool;
    }


    /**
     * Make a Request to the WooCommerce API
     *
     * @param string $endpoint 
     * @param array $json
     * @param string $type Type of request GET|POST
     *
     * @return array
     */
    public function request(string $endpoint, array $json = null, string $type = 'get'): array
    {
        $url = $this->store.$endpoint;
        $auth = base64_encode("{$this->key}:{$this->secret}");
        $headers = [
            "Content-Type: application/json",
            "Authorization: Basic $auth",
        ];
        $data = json_encode($json,JSON_PRETTY_PRINT);

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        // if json is set then set type to post request
        if($json ?? false) {
            $type = 'post';
        }
    
        switch (strtolower($type)) {
            case 'post':
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            
            default:
                // nothing needed for now
                break;
        }

        // bypasses selfsigend ssl (for developmenet purpose only)
        if ($this->dev) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }

        $resp = curl_exec($curl);

        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        $data = json_decode($resp,true);

        if (!in_array($http_status, [200, 201])) {
            $this->error = $data;
            throw new Exception($data['message']);
        }

        return $data;
    }
}