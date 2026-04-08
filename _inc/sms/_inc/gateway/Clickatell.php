<?php

namespace SMSGateway\Gateway;

include DIR_INCLUDE.'sms/_inc/gateway/Clickatell/src/ClickatellException.php';
include DIR_INCLUDE.'sms/_inc/gateway/Clickatell/src/Rest.php';

use Clickatell\Rest;
use Clickatell\ClickatellException;

class Clickatell implements GatewayInterface
{
	private $clickatell;

    protected $defaultConfig = array(
        'username'    => '',
        'password'    => '',
        'api_id'   => '',
        'from_no'   => '',
    );

    protected $username;
    protected $password;
    protected $api_id;
    protected $from_no;

	public function __construct ($config = array()) {
        extract(mergeArray($this->defaultConfig, $config));
        $this->username = $username;
        $this->password = $password;
        $this->api_id   = $api_id;
        $this->from_no  = store('mobile');

        $this->clickatell = new \Clickatell\Rest($this->api_id);
	}

    public function send($to, $message) 
    {
		try {

		    $result = $this->clickatell->sendMessage(['to' => [$to], 'content' => $message]);
		    
		    // $total = 0;
		    // foreach ($result['messages'] as $message) {
		        /*
		        [
		            'apiMsgId'  => null|string,
		            'accepted'  => boolean,
		            'to'        => string,
		            'error'     => null|string
		        ]
		        */

		        // If ($message['accepted']) {
		        // 	$total++;
		        // }
		    // }

		    return $result;

		} catch (ClickatellException $e) {
			
		    return $e->getMessage();
		}
	}

    public function deliveryStatus($response_id)
    {
        // Outgoing traffic callbacks (MT callbacks)
		Rest::parseStatusCallback(function ($result) {
		    var_dump($result);
		    // This will execute if the request to the web page contains all the values
		    // specified by Clickatell. Requests that omit these values will be ignored.
		});

		// Incoming traffic callbacks (MO/Two Way callbacks)
		Rest::parseReplyCallback(function ($result) {
		    var_dump($result);
		    // This will execute if the request to the web page contains all the values
		    // specified by Clickatell. Requests that omit these values will be ignored.
		});

    }

    public function getBalance()
    {
        return 0;
    }
}

// Reference
// https://github.com/clickatell/clickatell-php