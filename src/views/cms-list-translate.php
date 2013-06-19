<?php
use Mouf\CMS\CMSController;
/* @var $this CMSController */
$grid = $this->contentType->translateGrid;
$grid->toHtml();
?>
<div id="cms-list"></div>
