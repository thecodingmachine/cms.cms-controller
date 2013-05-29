<?php
use Mouf\Mvc\Splash\Controllers\Controller;

use Mouf\Mvc\Splash\Services\UrlProviderInterface;

class CMSControlller extends Controller implements UrlProviderInterface {
	

	/**
	 * @var array<ContentTypeDescriptor>
	 */
	public $contentTypeDescriptors;
	
	/**
	 * (non-PHPdoc)
	 * @see \Mouf\Mvc\Splash\Services\UrlProviderInterface::getUrlsList()
	 */
	public function getUrlsList();
	
	
}