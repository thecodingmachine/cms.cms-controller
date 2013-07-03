<?php
namespace Mouf\CMS;

use Mouf\Mvc\Splash\Services\UrlProviderInterface;

use Mouf\Mvc\Splash\Services\SplashRoute;

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

class CMSController extends Controller implements UrlProviderInterface {
	
	/**
	 * @var array<ContentTypeDescriptor>
	 */
	public $contentTypes = array();
	
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
		if ($id == null){
			$tId = $contentType->getNextTid();
			$this->formInstance->getDescriptorInstance('translate_id')->setFieldValue($tId);
		}
		
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
		
		$id = $this->formInstance->save();
	
		$cmsBean =  $contentType->bceForm->mainDAO->getById($id);
		/* @var  $cmsBean CMSBeanInterface */
		if ($isNew){
			$cmsBean->setCreated(time());
		}
		$cmsBean->setUrl(str_replace(" ", "-", $contentType->name) . "-" . $cmsBean->getId());
		$cmsBean->setUpdated(time());
		$cmsBean->save();
		
		if (function_exists('menu_rebuild')){
			menu_rebuild();
		}

		$saved = $id != false;
// 		$urls = \Mouf::getSplash()->cacheService->get("splashUrlNodes");
// 		/* @var $splashRoute SplashRoute */
// 		$splashRoute = $urls->walk($cmsBean->getUrl(), null);
// 		if ($splashRoute != null && ($splashRoute->controllerInstanceName != $contentTypeInstanceName || ($splashRoute->parameters[0] instanceof CMSParamFetcher && $splashRoute->parameters[0]->value != $cmsBean->getId()))){
// 			$this->formInstance->form->addError('url', "URL '". $cmsBean->getUrl() ."' alerady exists please choose a different one.");
// 			$saved = false;
// 		}
		if (!$saved){
			foreach ($this->formInstance->form->errorMessages as $field => $messages){
				foreach ($messages as $message){
					$this->messageService->setMessage($message, UserMessageInterface::ERROR);
				}
			}
			header("Location:".ROOT_URL."cms/content/$contentTypeInstanceName/edit/".$cmsBean->getId());
			exit;			
		}
		
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
	
	/**
	 * Returns the list of URLs that can be accessed, and the function/method that should be called when the URL is called.
	 * 
	 * @return array<SplashRoute>
	 */
	public function getUrlsList() {		
		// Let's analyze the controller and get all the @Action annotations:
		$urlsList = parent::getUrlsList();
		$moufManager = MoufManager::getMoufManager();
		
		$refClass = new MoufReflectionClass(get_class($this));
		
		foreach ($this->contentTypes as $contentType) {
			$urlsList = array_merge($urlsList, $contentType->getUrls());
		}
		
		return $urlsList;
	}
	
	public function getUrlByRerence($contentTypeInstanceName, $tId, $relative = false){
		$this->contentType = MoufManager::getMoufManager()->getInstance($contentTypeInstanceName);
		return ($relative ? "" : ROOT_URL).$this->contentType->getUrlByRerence($tId, $this->languageDetection->getLanguage());
	}

}