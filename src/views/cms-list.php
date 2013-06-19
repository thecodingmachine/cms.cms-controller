<?php
use Mouf\CMS\CMSController;
/* @var $this CMSController */
$grid = $this->contentType->contentGrid;
$grid->toHtml();
?>
<div id="cms-list"></div>