<?php
namespace Mouf\CMS;

interface CMSBeanInterface {
	
	public function getId();
	
	public function getTitle();
	public function setTitle($title);
	
	public function getLanguageId();
	public function setLanguageId($language);
	
	public function getTranslateId();
	public function setTranslateId($translateId);
	
	public function getCreated();
	public function setCreated($created);
	
	public function getUpdated();
	public function setUpdated($updated);
	
	public function getUrl();
	public function setUrl($url);
	
	public function getPublished();
}