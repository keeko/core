<?php
namespace keeko\core\service;

use Symfony\Component\Translation\Translator;

class KeekoTranslator extends Translator {

	private $domain;
	
	public function trans($id, array $parameters = [], $domain = null, $locale = null) {
		$params = $this->prepareParams($parameters);
		if ($this->domain !== null && $domain === null) {
			return parent::trans($id, $params, $this->domain, $locale);
		} else {
			return parent::trans($id, $params, $domain, $locale);
		}
	}

	public function transChoice($id, $number, array $parameters = [], $domain = null, $locale = null) {
		$params = $this->prepareParams($parameters);
		if ($this->domain !== null && $domain === null) {
			return parent::transChoice($id, $number, $params, $this->domain, $locale);
		} else {
			return parent::transChoice($id, $number, $params, $domain, $locale);
		}
	}
	
	private function prepareParams(array $parameters) {
		$params = [];
		foreach ($parameters as $key => $value) {
			$params['{' . $key . '}'] = $value;
		}
		
		return $params;
	}
	
	public function setDomain($domain) {
		$this->domain = $domain;
	}
	
	public function getDomain() {
		return $this->domain;
	}
}
