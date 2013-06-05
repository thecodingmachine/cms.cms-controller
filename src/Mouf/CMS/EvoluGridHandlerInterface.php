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

	public function getTranslateRowCount();
	
	public function getTranslateRows();
	
	/**
	 * @return EvoluGrid
	 */
	public function getTranslateGrid();
	
}