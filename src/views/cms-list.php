<?php
use Mouf\CMS\CMSController;
/* @var $this CMSController */
$grid = $this->contentType->gridHandler->getGrid();
$grid->toHtml();
?>
<div id="cms-list"></div>