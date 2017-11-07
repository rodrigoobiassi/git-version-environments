<?php 

class Biassi_GitVersion_Block_Adminhtml_Page_Tagversion extends Mage_Adminhtml_Block_Template
{
    const LOCALE_CACHE_LIFETIME = 7200;
    const LOCALE_CACHE_KEY      = 'tagversion_locale';
    const LOCALE_CACHE_TAG      = 'adminhtml';
    const FILEPATH 				= 'appVersion.php';
    const TAG_NOT_FOUND 		= 'Tag não encontrada!';   				

	public function getVersion()
	{
		$apps = Mage::getStoreConfig('biassi_gitversion/app');
		$versionTags = array();

		foreach ($apps as $value) { 
			$label   = $value['label'];
			$ip      = $value['ip'];

			$versionTags[] = array(
					'app' 		 => $label,
					'ip'         => $ip,
					'tagversion' => $this->getFetchHead($ip)					
				);
		}
		
		return $versionTags;		
	} 

	public function getFetchHead($ip)
	{
		try {
			$url = 'http://'.$ip.'/'.self::FILEPATH.'';

			$httpClient = new Varien_Http_Client();
	        $response = $httpClient
	            ->setUri($url)
	            ->setConfig(array('timeout' => 2))
	            ->request('GET')
	            ->getBody();
			
			$response = Mage::helper('core')->escapeHtml($response);
			if ($this->_isResponseError($response)) {
				return 'Error <!-- ' . $response . ' -->';
			}

			return $response;
		} catch (Exception $e) {
			return self::TAG_NOT_FOUND;
		}
	}   		

	protected function _isResponseError($response)
	{
		return strlen($response) > 100;
	}
}