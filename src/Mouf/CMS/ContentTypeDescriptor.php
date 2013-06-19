<?php
namespace Mouf\CMS;

use Mouf\MVC\BCE\BCEForm;

use Mouf\Html\Template\TemplateInterface;

use Mouf\Html\HtmlElement\HtmlBlock;

use Mouf\Html\HtmlElement\Scopable;

use Mouf\Html\HtmlElement\HtmlFromFile;

use Mouf\Html\Widgets\EvoluGrid\EvoluGrid;

use Mouf\MVC\BCE\BCEFormInstance;

abstract class ContentTypeDescriptor implements Scopable {
	
	/**
	 * @var BCEForm
	 */
	public $bceForm;
	
	/**
	 * @var EvoluGrid
	 */
	public $contentGrid;
	
	/**
	 * @var EvoluGrid
	 */
	public $translateGrid;
	
	/**
	 * @var string
	 */
	public $name;
	
	/**
	 * @var string
	 */
	public $description;

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
	
	public abstract function getNextTid();
	public abstract function getRowCount($languge);
	public abstract function getRows($limit, $offset, $languge);
	public abstract function getTranslateRowCount($languge, $defaultLanguage);
	public abstract function getTranslateRows($limit, $offset, $languge, $defaultLanguage);
	public abstract function getContent($id);
	
}