<?php
namespace Mouf\CMS;

use Mouf\Mvc\Splash\Services\SplashRequestContext;

use Mouf\Mvc\Splash\Services\SplashParameterFetcherInterface;

class CMSParamFetcher implements SplashParameterFetcherInterface {
	
	public $value;
	public $name;
	
	public function __construct($name, $value){
		$this->name = $name;
		$this->value = $value;
	}
	
	/**
	 * Get the name of the parameter (only for error handling purposes).
	 *
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}
	
	/**
	 * We pass the context of the request, the object returns the value to fill.
	 *
	 * @param SplashRequestContext $context
	 * @return mixed
	*/
	public function fetchValue(SplashRequestContext $context){
		return $this->value;
	}
	
}