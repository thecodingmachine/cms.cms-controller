<?php
use Mouf\CMS\CMSController;
/* @var $this CMSController */
$grid = $this->contentType->gridHandler->getTranslateGrid();
$grid->toHtml();