<?php 
/**
 * 去买呀 OAuth2.0 认证类
 * @author Yang Jing
 * @version 1.0
 */
class qmyOauthV2{
	public $client_id;
	public $client_secret;
	public $access_token;
	public $refresh_token;
	public $http_code;
	public $url;
	public $host = "http://new.boss.qumaiya.com/daigou/oauth/oauthRestful/";
	public $timeout = 30;
	public $connecttimeout = 30;
	public $ssl_verifypeer = FALSE;
	public $format = 'json';
	public $decode_json = TRUE;
	public $http_info;
	public $useragent = 'Qumaiya OAuth2 v0.1';
	public static $boundary = '';
	public $http_header;
	public $error_info;
	
	function accessTokenURL(){ 
	    return 'http://new.boss.qumaiya.com/daigou/oauth/oauth/token'; 
	}
	function authorizeURL(){ 
	    return 'http://new.boss.qumaiya.com/daigou/oauth/oauth/authorize'; 
	}

	function __construct($client_id, $client_secret, $access_token = NULL, $refresh_token = NULL) {
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->access_token = $access_token;
		$this->refresh_token = $refresh_token;
	}

	/**
	 * authorize接口
	 *
	 * @param string $url 授权后的回调地址,需与回调地址一致
	 * @param string $response_type 支持的值包括 code 和token 默认值为code
	 * @param string $state 用于保持请求和回调的状态
	 * @return array
	 */
	function getAuthorizeURL( $url, $response_type = 'code', $state = NULL ) {
		$params = array();
		$params['response_type'] = $response_type;
		$params['client_id'] = $this->client_id;
		$params['redirect_uri'] = $url;
		$params['state'] = $state;
		return $this->authorizeURL() . "?" . http_build_query($params);
	}

	/**
	 * access_token接口
	 *
	 * @param string $type 请求的类型,可以为:code, password, token
	 * @param array $keys 其他参数：
	 *  - 当$type为code时： array('code'=>..., 'redirect_uri'=>...)
	 *  - 当$type为password时： array('username'=>..., 'password'=>...)
	 *  - 当$type为token时： array('refresh_token'=>...)
	 * @return array
	 */
	function getAccessToken( $type = 'code', $keys ) {
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['client_secret'] = $this->client_secret;
		if ( $type === 'token' ) {
			$params['grant_type'] = 'refresh_token';
			$params['refresh_token'] = $keys['refresh_token'];
		} elseif ( $type === 'code' ) {
			$params['grant_type'] = 'authorization_code';
			$params['code'] = $keys['code'];
			$params['redirect_uri'] = $keys['redirect_uri'];
		} elseif ( $type === 'password' ) {
			$params['grant_type'] = 'password';
			$params['username'] = $keys['username'];
			$params['password'] = $keys['password'];
		} else {
			return $this->responese('false', 'error', "wrong auth type");
		}
		$response = $this->oAuthRequest($this->accessTokenURL(), 'POST', $params);
		$token = json_decode($response, true);
		if ( is_array($token) && !isset($token['error']) ) {
			$this->access_token = $token['access_token'];
		} else {
			return $this->responese('false', $token['error'], "get access token failed.");
		}
		return $token;
	}
	
	/**
	 * 从数组中读取access_token和refresh_token
	 * 常用于从Session或Cookie中读取token，或通过Session/Cookie中是否存有token判断登录状态。
	 *
	 * @param array $arr 存有access_token和secret_token的数组
	 * @return array 成功返回array('access_token'=>'value', 'refresh_token'=>'value'); 失败返回false
	 */
	function getTokenFromArray( $arr ) {
		if (isset($arr['access_token']) && $arr['access_token']) {
			$token = array();
			$this->access_token = $token['access_token'] = $arr['access_token'];
			if (isset($arr['refresh_token']) && $arr['refresh_token']) {
				$this->refresh_token = $token['refresh_token'] = $arr['refresh_token'];
			}

			return $token;
		} else {
			return false;
		}
	}
	
	/**
	 * Format and sign an OAuth / API request
	 *
	 * @return string
	 * @ignore
	 */
	function oAuthRequest($url, $method, $parameters, $multi = false) {
		if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
			$url = "{$this->host}{$url}";
    	}
    	switch ($method) {
    		case 'GET':
    			$url = $url . '?' . http_build_query($parameters);
    			return $this->http($url, 'GET');
    		default:
    			$headers = array();
    			if (!$multi && (is_array($parameters) || is_object($parameters)) ) {
    				$body = http_build_query($parameters);
    			} else {
    				$body = self::build_http_query_multi($parameters);
    				$headers[] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
    			}
    			return $this->http($url, $method, $body, $headers);
    	}
	}
	
	/**
	 * Make an HTTP request
	 *
	 * @return string API results
	 * @ignore
	 */
	function http($url, $method, $postfields = NULL, $headers = array()) {
		$this->http_info = array();
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_ENCODING, "");
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
		curl_setopt($ci, CURLOPT_HEADER, FALSE);
		switch ($method) {
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, TRUE);
				
				if (!empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
					$this->postdata = $postfields;
				}
				break;
			case 'DELETE':
				curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if (!empty($postfields)) {
					$url = "{$url}?{$postfields}";
				}
		}

		if ( isset($this->access_token) && $this->access_token ){
			$headers[] = "Authorization:OAuth ".$this->access_token;
		}
		$headers[] = "Connection: close";

		curl_setopt($ci, CURLOPT_URL, $url );
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );

		$response = curl_exec($ci);
		if(!empty($this->error_info)){
			$response['error'] = $this->error_info['error'];
			$response['error_code'] = $this->error_info['error_code']? $this->error_info['error_code']:NULL;
			$response['error_description'] = $this->error_info['error_description'] ? $this->error_info['error_description']:$this->error_info['OAuth'];
			$response = json_encode($response);
		}
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ci));
		$this->url = $url;
		curl_close ($ci);
		return $response;
	}
	
	/**
	 * GET wrappwer for oAuthRequest.
	 *
	 * @return mixed
	 */
	function get($url, $parameters = array()) {
		// if(!isset($parameters['oauth_token'])){
		// 	$parameters['oauth_token'] = $this->access_token;
		// }
		$response = $this->oAuthRequest($url, 'GET', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}

	/**
	 * POST wreapper for oAuthRequest.
	 *
	 * @return mixed
	 */
	function post($url, $parameters = array(), $multi = false) {
		$response = $this->oAuthRequest($url, 'POST', $parameters, $multi );
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}

	/**
	 * DELTE wrapper for oAuthReqeust.
	 *
	 * @return mixed
	 */
	function delete($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'DELETE', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}
	/**
	 * Get the header info to store.
	 *
	 * @return int
	 * @ignore
	 */
	function getHeader($ch, $header) {
		$i = strpos($header, ':');
		if (!empty($i)) {
			$key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
			$value = trim(substr($header, $i + 2));
			$this->http_header[$key] = $value;
			
			if($key == 'authenticate'){
				$authenticate = explode(',',$value);
				if(!empty($authenticate)){
					foreach($authenticate as $i=>$oauth){
						$oauth = explode('=',$oauth);
						$oauth[1] = str_replace('\'', '', $oauth[1]);
						$this->error_info[trim($oauth[0])] = trim($oauth[1]);
					}
				}
			}
		}
		return strlen($header);
	}

	/**
	 * @ignore
	 */
	public static function build_http_query_multi($params) {
		if (!$params) return '';

		uksort($params, 'strcmp');

		$pairs = array();

		self::$boundary = $boundary = uniqid('------------------');
		$MPboundary = '--'.$boundary;
		$endMPboundary = $MPboundary. '--';
		$multipartbody = '';

		foreach ($params as $parameter => $value) {

			if( in_array($parameter, array('pic', 'image')) && $value{0} == '@' ) {
				$url = ltrim( $value, '@' );
				$content = file_get_contents( $url );
				$array = explode( '?', basename( $url ) );
				$filename = $array[0];

				$multipartbody .= $MPboundary . "\r\n";
				$multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"'. "\r\n";
				$multipartbody .= "Content-Type: image/unknown\r\n\r\n";
				$multipartbody .= $content. "\r\n";
			} else {
				$multipartbody .= $MPboundary . "\r\n";
				$multipartbody .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
				$multipartbody .= $value."\r\n";
			}

		}

		$multipartbody .= $endMPboundary;
		return $multipartbody;
	}

	function responese($success, $msg, $data ){
		$result = array();
        $result['success'] = $success;
        $result['msg'] = $msg;
        $result['data'] = $data;
        return  $result;
	}

}

?>