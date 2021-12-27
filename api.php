<?php
/**
 *
 */


class PHP_API {
    
    protected $url = '';
    
    private $token = ''; // not used yet.
    
    private $last_response_raw = null;
    private $last_response = null;
    
    public function __construct() {
        $this->url = 'https://swapi.co/api/'; 
    }
    
    public function get($path = '', $params = array()) {
        return $this->request('GET', $path, $params);    
    }
    
    private function request($method, $path, $query = array(), $data = null) {
        $curl = $this->init_curl($method, $path, $query);
        
        $bodyLength = 0;
        
/*
        if ($data !== null) {
            $bodyEncoded = json_encode($data);
            $bodyLength = strlen($bodyEncoded);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $bodyEncoded);
        }
*/

        $headers = $this->get_curl_headers($bodyLength, 'application/json');

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        return $this->execute($curl);       
    }
    
    private function init_curl($method, $path, $query) {
        $curl = curl_init($this->get_url($path, $query));
        
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        
        
        return $curl;              
    }
    
    private function get_url($path, $query) {
        $url = $this->url . ltrim($path, '/');
        
        if ($query) {
            $url .= '?' . http_build_query($query);
        }
        
        return $url;        
    }
    
    private function execute($curl) {
        $this->last_response_raw = null;
        $this->last_response = null;
        
        $responseRaw = curl_exec($curl);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $errorNumber = curl_errno($curl);
        $error = curl_error($curl);
        
        curl_close($curl);
        
        if ($errorNumber) {
            // throw exception.
        }
        
        $response = json_decode($responseRaw, true);
        
        if ($responseCode >= 400 || !empty($response['error'])) {
            // error.
/*
            $error = [];
            if (is_array($response)) {
                $response += ['error' => []];
                $error = $response['error'];
            }
            $error += [
                'type' => 'Unknown type',
                'message' => 'Unknown message',
            ];
            throw new BreezyApiException($error['type'] . ': ' . $error['message'], $responseCode);
*/
        }
        
        $this->last_response = $response;
        $this->last_response_raw = $responseRaw;
        
        return $response;
    }
    
    private function get_curl_headers($contentLength, $contentType) {
        $headers = [
            'Content-Type: ' . $contentType,
            'Content-Length: ' . $contentLength,
        ];
        
        if ($this->token) {
            $headers[] = 'Authorization: ' . $this->token;
        }
        
        return $headers;
    }
    
    public function strip_url($url = '') {
        return str_replace($this->url, '', $url);
    }    
    
}