<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2014 Iceberg Commerce
 */
class IcebergCommerce_VideoGallery_Model_Video_Vimeo extends IcebergCommerce_VideoGallery_Model_Video
{
	protected $_xml;
	protected $_xml_label;
	protected $_xml_thumbnail;
	protected $_xml_description;
	
	// Vimeo Pro
	const API_REST_URL        = 'http://vimeo.com/api/rest/v2';
    private $_consumer_key    = false;
    private $_consumer_secret = false;
    private $_token           = false;
    private $_token_secret    = false;
    
    
	protected function getXmlData()
	{
		if (!isset( $this->_xml ) ) 
		{
			$this->getDataFromVimeo();
		}

    	return $this->_xml;
	}
	
	public function getProvider()
	{
		return 'vimeo';
	}
	
	public function setVideoByUrl( $url )
	{
		// Test vimeo url
		if ( !preg_match( '#vimeo.com#i' , $url ) )
		{
			return false;
		}
		
		// Test url format
		if ( !preg_match('#vimeo.com/(\d+)#' , $url , $matches ) )
		{
			Mage::throwException(Mage::helper('videogallery')->__('Invalid Vimeo URL. Video ID could not be found.'));
		}
    	$this->video_value = isset($matches[1]) ? $matches[1] : null;
		
		// Test if valid video
		if (!$this->getXmlData())
		{
			Mage::throwException(Mage::helper('videogallery')->__('Invalid Vimeo Video. Video does not exist.'));
		}
    			
    	
		return $this;
	}
	
	public function getThumbnail()
	{
		if ($this->_xml_thumbnail == '')
		{
			return parent::getThumbnail();
		}
		
		return $this->_xml_thumbnail;
	}
	
	public function getLabel()
	{
		return $this->_xml_label;
	}
	
	
	public function getDescription()
	{
		return $this->_xml_description;
	}
	
	public function getEmbedCode( $width = 640, $height = null, $autoplay=true)
	{
		$height = ($height > 0) ? $height : floor( $width / 1.6)-40;
		$videoId = $this->video_value;

		$autoplayConfig = $autoplay ? '&amp;autoplay=1' : '';
		
		return <<<END
<div class="video-object" style="width: {$width}px; height: {$height}px;">	
<iframe src="//player.vimeo.com/video/{$this->video_value}?&amp;show_title=1show_byline=0&amp;show_portrait=0&amp;fullscreen=1&amp;wmode=transparent$autoplayConfig" width="{$width}" height="{$height}" frameborder="0"></iframe>
</div>
END;
	}
	
	public function getMobileEmbedCode($width, $height, $autoplay=true)
	{
		$autoplayConfig = $autoplay ? '&amp;autoplay=1' : '';

		return <<<END
<div class="video-object" style="width: {$width}px; height: {$height}px;">
<iframe src="http://player.vimeo.com/video/{$this->video_value}?portrait=0{$autoplayConfig}" width="{$width}" height="{$height}" frameborder="0"></iframe>
</div>
END;
	}
	
	
	
	private function getDataFromVimeo()
	{
		$vimeoProAccess = Mage::helper('videogallery')->getVimeoProAPICredentials();

		if ($vimeoProAccess)
		{
			//----------------------------
			// Use Vimeo Advanced API
			//----------------------------
			$this->_consumer_key = $vimeoProAccess['api_key'];
			$this->_consumer_secret = $vimeoProAccess['api_secret'];
			$this->_token = $vimeoProAccess['access_token'];
			$this->_token_secret = $vimeoProAccess['access_token_secret'];
			
			$result = $this->call('vimeo.videos.getInfo', array('video_id' => $this->video_value,'full_response' => '0'));

			$hash = $result->video;
			
			
			if (!isset($hash[0]) && !$hash[0])
			{
	    		Mage::throwException(Mage::helper('videogallery')->__('Invalid Vimeo Video. Video does not exist.'));
	    	}
	    	
	    	$this->_xml = $hash[0];

	    	$this->_xml_label       = $this->_xml->title;
			$this->_xml_description = $this->_xml->description;
			
			$thumbs = $this->_xml->thumbnails->thumbnail;
			if (is_array($thumbs))
			{
				$len = count($thumbs);
				if ($len > 0)
				{
					$this->_xml_thumbnail = $thumbs[$len-1]->_content;
				}
			}
			
		}
		else 
		{
			//----------------------------
			// Use Vimeo Simple API
			//----------------------------
			// Null when invalid video value
			$hash = unserialize(Mage::helper('videogallery')->file_get_contents_curl("http://vimeo.com/api/v2/video/$this->video_value.php") );
			
			if (!isset($hash[0]) && !$hash[0])
			{
	    		Mage::throwException(Mage::helper('videogallery')->__('Invalid Vimeo Video. Video does not exist or is private.'));
	    	}
	    	
	    	$this->_xml = $hash[0];

	    	$this->_xml_thumbnail   = isset($this->_xml['thumbnail_large']) ? $this->_xml['thumbnail_large'] : null;
			$this->_xml_label       = isset($this->_xml['title']) ? $this->_xml['title'] : null;
			$this->_xml_description = isset($this->_xml['description']) ? strip_tags($this->_xml['description']) : null;
		}
	}

	
	
	/* ------------------------------- Vimeo PRO Support  ---------------------------------------------------------- */

	/**
     * Generate the OAuth signature.
     *
     * @param array $args The full list of args to generate the signature for.
     * @param string $request_method The request method, either POST or GET.
     * @param string $url The base URL to use.
     * @return string The OAuth signature.
     */
    private function _generateSignature($params, $request_method = 'GET', $url = self::API_REST_URL)
    {
        uksort($params, 'strcmp');
        $params = self::url_encode_rfc3986($params);

        // Make the base string
        $base_parts = array(
            strtoupper($request_method),
            $url,
            urldecode(http_build_query($params, '', '&'))
        );
        $base_parts = self::url_encode_rfc3986($base_parts);
        $base_string = implode('&', $base_parts);

        // Make the key
        $key_parts = array(
            $this->_consumer_secret,
            ($this->_token_secret) ? $this->_token_secret : ''
        );
        $key_parts = self::url_encode_rfc3986($key_parts);
        $key = implode('&', $key_parts);

        // Generate signature
        return base64_encode(hash_hmac('sha1', $base_string, $key, true));
    }

   

    /**
     * Call an API method.
     *
     * @param string $method The method to call.
     * @param array $call_params The parameters to pass to the method.
     * @param string $request_method The HTTP request method to use.
     * @param string $url The base URL to use.
     * @return string The response from the method call.
     */
    private function call($method, $call_params = array(), $request_method = 'GET', $url = self::API_REST_URL)
    {
    	$use_auth_header = true;
    	
        // Prepare oauth arguments
        $oauth_params = array(
            'oauth_consumer_key' => $this->_consumer_key,
            'oauth_version' => '1.0',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_nonce' => md5(uniqid(microtime())),
        );

        // If we have a token, include it
        if ($this->_token) {
            $oauth_params['oauth_token'] = $this->_token;
        }

        // Regular args
        $api_params = array('format' => 'php');
        if (!empty($method)) {
            $api_params['method'] = $method;
        }

        // Merge args
        foreach ($call_params as $k => $v) {
            if (strpos($k, 'oauth_') === 0) {
                $oauth_params[$k] = $v;
            }
            else if ($call_params[$k] !== null) {
                $api_params[$k] = $v;
            }
        }

        // Generate the signature
        $oauth_params['oauth_signature'] = $this->_generateSignature(array_merge($oauth_params, $api_params), $request_method, $url);

        // Merge all args
        $all_params = array_merge($oauth_params, $api_params);


        // Curl options
        if ($use_auth_header) {
            $params = $api_params;
        }
        else {
            $params = $all_params;
        }

        if (strtoupper($request_method) == 'GET') {
            $curl_url = $url.'?'.http_build_query($params, '', '&');
            $curl_opts = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 6
            );
        }
        else if (strtoupper($request_method) == 'POST') {
            $curl_url = $url;
            $curl_opts = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 6,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($params, '', '&')
            );
        }

        // Authorization header
        if ($use_auth_header) {
        	
        	$auth_header = 'Authorization: OAuth realm=""';

        	foreach ($oauth_params as $k => $v) {
        	    $auth_header .= ','.self::url_encode_rfc3986($k).'="'.self::url_encode_rfc3986($v).'"';
        	}
        
            $curl_opts[CURLOPT_HTTPHEADER] = array($auth_header);
        }

        // Call the API
        $curl = curl_init($curl_url);
        curl_setopt_array($curl, $curl_opts);
        $response = curl_exec($curl);
        $curl_info = curl_getinfo($curl);
        curl_close($curl);


        // Return
        if (!empty($method)) {
            $response = unserialize($response);
            if ($response->stat == 'ok') {
                return $response;
            }
            else if ($response->err) {
                Mage::throwException('Vimeo Pro Error: ' . $response->err->msg, $response->err->code);
            }

            return false;
        }

        return $response;
    }

    /**
     * URL encode a parameter or array of parameters.
     *
     * @param array/string $input A parameter or set of parameters to encode.
     */
    public static function url_encode_rfc3986($input)
    {
        if (is_array($input)) {
            return array_map(array('IcebergCommerce_VideoGallery_Model_Video_Vimeo', 'url_encode_rfc3986'), $input);
        }
        else if (is_scalar($input)) {
            return str_replace(array('+', '%7E'), array(' ', '~'), rawurlencode($input));
        }
        else {
            return '';
        }
    }
	
}
