<?php
namespace Mouf\CMS;

use Mouf\Html\Template\TemplateInterface;

use Mouf\Html\HtmlElement\HtmlBlock;

use Mouf\Html\HtmlElement\Scopable;

use Mouf\Html\HtmlElement\HtmlFromFile;

use Mouf\Html\Widgets\EvoluGrid\EvoluGrid;

use Mouf\MVC\BCE\BCEFormInstance;

class ContentTypeDescriptor implements Scopable {
	
	/**
	 * @var BCEFormInstance
	 */
	public $bceForm;
	
	/**
	 * @var string
	 */
	public $name;
	
	/**
	 * @var string
	 */
	public $description;

	/**
	 * @var ContentTypeHandlerInterface
	 */
	public $handler;
	
	/**
	 * @var HtmlFromFile
	 */
	public $templateFile;
	
	/**
	 * The template used by the controller.
	 *
	 * @var TemplateInterface
	 */
	public $template;
	
	/**
	 * This object represents the block of main content of the web page.
	 *
	 * @var HtmlBlock
	 */
	public $content;
	
	public function render($id){
		$this->page = $this->handler->getContent($id);
		$this->content->addHtmlElement($this->templateFile);
		$this->template->toHtml();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Mouf\Html\HtmlElement\Scopable::loadFile()
	 */
	public function loadFile($file){
		include $file;
	}
	
	public function getUrlsList(){
		$contents = $this->handler->getUrlData();
	}
	
}