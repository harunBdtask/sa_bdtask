<?php 
namespace App\Libraries;
use Firebase\JWT\JWT;

class Authorization_Token 
{
    /**
     * Token Key
     */
    protected $token_key;
    protected $token_algorithm;
    protected $token_header;
    protected $token_expire_time; 


    public function __construct()
	{
        $this->token_key        = 'eyJ0eXAiOiJKV1QiLCJhbGciTWvLUzI1NiJ9IiRkYXRhIg';
        $this->token_algorithm  = 'HS256';
        $this->token_header  = 'authorization';
        $this->token_expire_time  = 86400;
    }

    /**
     * Generate Token
     * @param: {array} data
     */
    public function activeToken($old_token)
    {
        try {
            return JWT::decode($old_token, $this->token_key, array($this->token_algorithm));
        }
        catch(\Exception $e) {
            return 'Message: ' .$e->getMessage();
        }

    }
    public function generateToken($data = null)
    {
        if ($data AND is_array($data))
        {
            // add api time key in user array()
            $data['API_TIME'] = time();

            try {
                return JWT::encode($data, $this->token_key, $this->token_algorithm);
            }
            catch(\Exception $e) {
                return 'Message: ' .$e->getMessage();
            }
        } else {
            return "Token Data Undefined!";
        }
    }

    /**
     * Validate Token with Header
     * @return : user informations
     */
    public function validateToken($authorization)
    {
        /**
         * Request All Headers
         */
        // $authorization = $this->request->getServer('HTTP_AUTHORIZATION');
        
        /**
         * Authorization Header Exists
         */
        $token_data = $this->tokenIsExist($authorization);
        if($token_data['status'] === TRUE)
        {
            try
            {
                /**
                 * Token Decode
                 */
                try {
                    $token_decode = JWT::decode($token_data['token'], $this->token_key, array($this->token_algorithm));
                }
                catch(\Exception $e) {
                    return ['status' => FALSE, 'message' => $e->getMessage()];
                }

                if(!empty($token_decode) AND is_object($token_decode))
                {
                    // Check Token API Time [API_TIME]
                    if (empty($token_decode->API_TIME OR !is_numeric($token_decode->API_TIME))) {
                        
                        return ['status' => FALSE, 'message' => 'Token Time Not Define!'];
                    }
                    else
                    {
                        /**
                         * Check Token Time Valid 
                         */
                        $time_difference = strtotime('now') - $token_decode->API_TIME;
                        if( $time_difference >= $this->token_expire_time )
                        {
                            return ['status' => FALSE, 'message' => 'Token Time Expire.'];

                        }else
                        {
                            /**
                             * All Validation False Return Data
                             */
                            return ['status' => TRUE, 'data' => $token_decode];
                        }
                    }
                    
                }else{
                    return ['status' => FALSE, 'message' => 'Forbidden'];
                }
            }
            catch(\Exception $e) {
                return ['status' => FALSE, 'message' => $e->getMessage()];
            }
        }else
        {
            // Authorization Header Not Found!
            return ['status' => FALSE, 'message' => $token_data['message'] ];
        }
    }

    /**
     * Token Header Check
     * @param: request headers
     */
    private function tokenIsExist($headers)
    {
        if(!empty($headers)) {
            return ['status' => TRUE, 'token' => $headers];
        }
        return ['status' => FALSE, 'message' => 'Token is not defined.'];
    }
    // private function tokenIsExist($headers)
    // {
    //     if(!empty($headers) AND is_array($headers)) {
    //         foreach ($headers as $header_name => $header_value) {
    //             if (strtolower(trim($header_name)) == strtolower(trim($this->token_header)))
    //                 return ['status' => TRUE, 'token' => $header_value];
    //         }
    //     }
    //     return ['status' => FALSE, 'message' => 'Token is not defined.'];
    // }
}