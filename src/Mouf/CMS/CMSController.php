<?php
namespace Mouf\CMS;

use Mouf\Html\Widgets\MessageService\Service\UserMessageInterface;

use Mouf\Html\Widgets\MessageService\Service\SessionMessageService;

use Mouf\Utils\I18n\Fine\Language\LanguageDetectionInterface;

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
	protected $formInstance;
	
	/**
	 * @var ContentTypeDescriptor
	 */
	protected $contentType;
	
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
	 * 
	 * @var LanguageDetectionInterface
	 */
	public $languageDetection;
	
	/**
	 * @var string
	 */
	public $defaultLanguage = 'default';
	
	/**
	 * @var SessionMessageService
	 */
	public $messageService;
	
	/**
	 * @URL cms/content/{contentTypeInstanceName}/edit/{id}
	 * @URL cms/content/{contentTypeInstanceName}/add
	 */
	public function addContent($contentTypeInstanceName, $id = null){
		/* @var $contentType ContentTypeDescriptor */
		$contentType = MoufManager::getMoufManager()->getInstance($contentTypeInstanceName);
		$this->formInstance = new BCEFormInstance();
		$this->formInstance->form = $contentType->bceForm; 
		
		$this->formInstance->load($id);
		
		$this->content->addFile(dirname(__FILE__)."/../../views/cms-form.php", $this);
		$this->template->toHtml();
	}
	
	/**
	 * @URL cms/content/{contentTypeInstanceName}/translate/{tId}
	 */
	public function translateContent($contentTypeInstanceName, $tId = null, $lId = null){
		/* @var $contentType ContentTypeDescriptor */
		$contentType = MoufManager::getMoufManager()->getInstance($contentTypeInstanceName);
		$this->formInstance = new BCEFormInstance();
		$this->formInstance->form = $contentType->bceForm;
		$this->formInstance->load();
		
		$this->formInstance->getDescriptorInstance('translate_id')->setFieldValue($tId);
		if ($lId != null){
			$this->formInstance->getDescriptorInstance('language_id')->setFieldValue($lId);
		}
	
		$this->content->addFile(dirname(__FILE__)."/../../views/cms-form.php", $this);
		$this->template->toHtml();
	}
	
	/**
	 * @URL cms/content/{contentTypeInstanceName}/save
	 */
	public function saveContent($contentTypeInstanceName){
		$contentType = MoufManager::getMoufManager()->getInstance($contentTypeInstanceName);
		$contentType = MoufManager::getMoufManager()->getInstance($contentTypeInstanceName);
		$this->formInstance = new BCEFormInstance();
		$this->formInstance->form = $contentType->bceForm;
	
		$id = get('id');
		$isNew = empty($id);
	
		$this->formInstance->load();
		$id = $this->formInstance->save();
	
		$cmsBean =  $contentType->bceForm->mainDAO->getById($id);
	
		if ($isNew){
			if (!$cmsBean->getTranslateId()){
				$tId =  $contentType->getNextTid();
				$cmsBean->setTranslateId($tId);
			}
			$cmsBean->setCreated(time());
		}
		$cmsBean->setUpdated(time());
		$cmsBean->save();
		
		$this->messageService->setMessage("Content has been saved", UserMessageInterface::SUCCESS);
		
		header("Location:".ROOT_URL."cms/content/$contentTypeInstanceName/list");
		exit;
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
	public function getContentData($contentTypeInstanceName, $offset, $limit = 2){
		$this->contentType = MoufManager::getMoufManager()->getInstance($contentTypeInstanceName);
		$grid = $this->contentType->contentGrid;
		$rows = $this->contentType->getRows($limit, $offset, $this->languageDetection->getLanguage(), $this->defaultLanguage);
		$count = $this->contentType->getRowCount($this->languageDetection->getLanguage(), $this->defaultLanguage);
		
		$grid->setTotalRowsCount($count);
		$grid->setRows($rows);
		
		$grid->output();
	}

}