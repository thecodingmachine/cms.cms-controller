<?php
namespace Mouf\CMS;

use Mouf\Html\Widgets\EvoluGrid\EvoluGrid;

interface EvoluGridHandlerInterface {
	
	public function getRowCount();
	
	public function getRows();
	
	/**
	 * @return EvoluGrid
	 */
	public function getGrid();
	
}