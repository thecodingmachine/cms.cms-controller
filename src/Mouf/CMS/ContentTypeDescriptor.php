<?php
namespace Mouf\CMS;

use Mouf\Html\Widgets\EvoluGrid\EvoluGrid;

use Mouf\MVC\BCE\BCEFormInstance;

class ContentTypeDescriptor {
	
	/**
	 * @var BCEFormInstance
	 */
	public $bceForm;
	
	/**
	 * 
	 * @var string
	 */
	public $name;
	
	/**
	 * @var string
	 */
	public $description;


	/**
	 * @var EvoluGridHandlerInterface
	 */
	public $gridHandler;
}