<?php

namespace SMSGateway\Gateway;

class Mimsms implements GatewayInterface
{
    protected $defaultConfig = array(
        'api_id'    => '',
        'auth_key'    => '',
        'sender_id'    => '',
        'type'   => 'sms',
        'url' => 'https://www.mimsms.com.bd/smsAPI',
    );

    protected $apikey;
    protected $apitoken;
    protected $senderid;
    protected $type;
    protected $url;

    public function __construct ($config = array()) 
    {
        extract(mergeArray($this->defaultConfig, $config));
        $this->apikey = $api_id;
        $this->apitoken = $auth_key;
        $this->senderid = $sender_id;
        $this->url =  $url;
    }

    public function send($to, $message) 
    {
        $to = '88'.preg_replace('/88/', '', str_replace(array('+',''), '', $to), 1); 
        $postData = array(
            'sendsms' => $this->apikey,
            'apikey' => $this->apikey,
            'apitoken' => $this->apitoken,
            'type' => $this->type,
            'from' => $this->senderid,
            'to' => $to,
            'text' => $message,
        );

        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
        ));


        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public function deliveryStatus($groupid)
    {
        $postData = array(
            'groupstatus' => '',
            'apikey' => $this->apikey,
            'apitoken' => $this->apitoken,
            'groupid' => $groupid,
        );

        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
        ));


        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);


        //get response
        $output = curl_exec($ch);

        if(curl_errno($ch))
        {
            echo 'error:' . curl_error($ch);
        }

        curl_close($ch);
        return $output;
    }

    public function getBalance()
    {
        $postData = array(
            'balance' => '',
            'apikey' => $this->apikey,
            'apitoken' => $this->apitoken,
        );

        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
        ));


        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);


        //get response
        $output = curl_exec($ch);

        if(curl_errno($ch))
        {
            return 'error:' . curl_error($ch);
        }

        curl_close($ch);
        $output = json_decode($output,true);
        return isset($output['balance']) ? floor($output['balance']) : 'Error!';
    }
}