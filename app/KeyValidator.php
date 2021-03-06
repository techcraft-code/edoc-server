<?php
namespace App ;

/**
 * Key validate to validate code 
 * Ex. When need to add school you must get code from Iotech befored make it done.
 */
class KeyValidator {
    
    protected $school_id ; 

    protected $secret_key ;

    /**
     * Receive school_id and code 
     * 
     * @param $school_id Integer
     * @param $secret_key String
     */
    public function __construct($school_id, $secret_key) {
        $this->school_id = $school_id;
        $this->secret_key = $secret_key;
    }

    /**
     * to check match key with school id
     * 
     * @return boolean
     */
    public static function checker($school_id, $secret_key) {
        $url = 'http://key-activate.iotech.co.th/activate';
        $data = [
            'key' => $secret_key,
            'sw' => 'edoc',
            'client' => $school_id
        ];
        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', $url, 
            [
                'http_errors' => false,
                'form_params' => $data
            ]
        );

        if ($res->getStatusCode() == 200 ) {
            return true;
        } else {
            return false;
        }
    }
}