<?php
use Mouf\CMS\CMSController;
/* @var $this CMSController */
$grid = $this->contentType->gridHandler->getTranslateGrid();
$grid->toHtml();
?>
<div id="cms-list"></div>