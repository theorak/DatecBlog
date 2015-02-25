<?php
namespace Datec\DatecBlog\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package datec_blog
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ArchivePeriod extends \TYPO3\CMS\Extbase\DomainObject\AbstractValueObject {
		
	/**
	 * @var \DateTime
	 */
	protected $from;
	
	/**
	 * @var \DateTime
	 */
	protected $to;
	
	/**
	 * @var \Datec\DatecBlog\Domain\Model\ArchivePeriodType
	 */
	protected $type;
	
	/**
	 * @return \DateTime
	 */
	public function getFrom() {
		return $this->from;
	}
	
	/**
	 * @param $from \DateTime
	 * @return void
	 */
	public function setFrom($from) {
		$this->from = $from;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getTo() {
		return $this->to;
	}
	
	/**
	 * @param $to \DateTime
	 * @return void
	 */
	public function setTo($to) {
		$this->to = $to;
	}
	
	/**
	 * @return \Datec\DatecBlog\Domain\Model\ArchivePeriodType
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * @param $type \Datec\DatecBlog\Domain\Model\ArchivePeriodType
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}
		
}
?>