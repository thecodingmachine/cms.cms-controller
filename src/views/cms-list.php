<?php
use Mouf\CMS\CMSController;
/* @var $this CMSController */
$grid = $this->contentType->contentGrid;
?>
<h1>List of '<?php echo $this->contentType->name; ?>' contents</h1>
<?php 
$grid->toHtml();
?>
