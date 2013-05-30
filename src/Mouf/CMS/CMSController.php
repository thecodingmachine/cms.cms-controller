<?php
namespace Mouf\CMS;

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
	 * @URL cms/content/{contentTypeInstanceName}/listdata
	 */
	public function getContentData($contentTypeInstanceName){
		$this->contentType = MoufManager::getMoufManager()->getInstance($contentTypeInstanceName);
		//TODO a améliorer...
		$handler = $this->contentType->gridHandler;
		list($data, $count) = call_user_func(array($dao, $this->contentType->dataMethod), $_GET);
		
		foreach ($data as $bean){
			foreach ($bean->db_row as $key => $value){
				
			}
		}
		
		$this->contentType->grid->setTotalRowsCount($count);
		$this->contentType->grid->setRows($rows);
		
		$this->contentType->grid->output();
	}
	
}