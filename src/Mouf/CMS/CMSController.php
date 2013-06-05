<?php
namespace Mouf\CMS;

use Mouf\MVC\BCE\Classes\Renderers\HiddenRenderer;

use Mouf\MoufInstanceDescriptor;

use Mouf\MoufPropertyDescriptor;

use Mouf\Reflection\MoufReflectionClass;

use Mouf\Html\HtmlElement\HtmlBlock;
use Mouf\Html\Template\TemplateInterface;
use Mouf\Mvc\Splash\Controllers\Controller;
use Mouf\MVC\BCE\BCEFormInstance;
use Mouf\MoufManager;

class CMSController extends Controller {
	
	/**
	 * @var array<ContentTypeDescriptor>
	 */
	public $contentTypes;
	
	/**
	 * @var BCEFormInstance
	 */
	public $form;
	
	/**
	 * @var ContentTypeDescriptor
	 */
	public $contentType;
	
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
	
	/**
	 * @URL cms/content/{contentTypeInstanceName}/edit/{id}
	 * @URL cms/content/{contentTypeInstanceName}/add
	 */
	public function addContent($contentTypeInstanceName, $id = null){
		/* @var $contentType ContentTypeDescriptor */
		$contentType = MoufManager::getMoufManager()->getInstance($contentTypeInstanceName);
		$this->form = $contentType->bceForm;
		$this->form->load($id);
		
		$this->content->addFile(dirname(__FILE__)."/../../views/cms-form.php", $this);
		$this->template->toHtml();
	}
	
	/**
	 * @URL cms/content/{contentTypeInstanceName}/translate/{tId}/{lId}
	 */
	public function translateContent($contentTypeInstanceName, $tId = null, $lId = null){
		/* @var $contentType ContentTypeDescriptor */
		$contentType = MoufManager::getMoufManager()->getInstance($contentTypeInstanceName);
		$this->form = $contentType->bceForm;
		$this->form->load();
		
		$this->form->getDescriptorInstance('translate_id')->setFieldValue($tId);
		if ($lId != null){
			$this->form->getDescriptorInstance('language_id')->setFieldValue($lId);
		}
	
		$this->content->addFile(dirname(__FILE__)."/../../views/cms-form.php", $this);
		$this->template->toHtml();
	}
	
	/**
	 * @URL cms/content/{contentTypeInstanceName}/save
	 */
	public function saveContent($contentTypeInstanceName){
		$contentType = MoufManager::getMoufManager()->getInstance($contentTypeInstanceName);
		$this->form = $contentType->bceForm;
		
		$id = $this->form->save();
	}
	
	/**
	 * @URL cms/content/{contentTypeInstanceName}/list
	 */
	public function listContent($contentTypeInstanceName){
		$this->contentType = MoufManager::getMoufManager()->getInstance($contentTypeInstanceName);
		
		$this->content->addFile(dirname(__FILE__)."/../../views/cms-list.php", $this);
		$this->template->toHtml();
	}
	
	/**
	 * @URL cms/content/{contentTypeInstanceName}/list-translate
	 */
	public function listTranslateContent($contentTypeInstanceName){
		$this->contentType = MoufManager::getMoufManager()->getInstance($contentTypeInstanceName);
	
		$this->content->addFile(dirname(__FILE__)."/../../views/cms-list-translate.php", $this);
		$this->template->toHtml();
	}
	
	/**
	 * @URL cms/content/{contentTypeInstanceName}/listdata
	 */
	public function getContentData($contentTypeInstanceName, $offset, $limit = 2){
		$this->contentType = MoufManager::getMoufManager()->getInstance($contentTypeInstanceName);
		$handler = $this->contentType->gridHandler;
		
		$grid = $handler->getGrid();
		
		$rows = $handler->getRows($limit, $offset);
		$count = $handler->getRowCount();
		
		$grid->setTotalRowsCount($count);
		$grid->setRows($rows);
		
		$grid->output();
	}

	/**
	 * @URL cms/content/{contentTypeInstanceName}/translate-listdata
	 */
	public function getTranslateContentData($contentTypeInstanceName, $offset, $limit = 2){
		$this->contentType = MoufManager::getMoufManager()->getInstance($contentTypeInstanceName);
		$handler = $this->contentType->gridHandler;
		
		$grid = $handler->getTranslateGrid();
		
		$rows = $handler->getTranslateRows($limit, $offset);
		$count = $handler->getTranslateRowCount();
		
		$grid->setTotalRowsCount($count);
		$grid->setRows($rows);
		
		$grid->output();
	}
	
}