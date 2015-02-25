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
 * @author Philipp Roensch
 * @package datec_blog
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class BloglistCriteria extends \TYPO3\CMS\Extbase\DomainObject\AbstractValueObject {
		
	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Datec\DatecBlog\Domain\Model\Category> $categories
	 */
	protected $categories;
	
	/**
	 * @var \Datec\DatecBlog\Domain\Model\ArchivePeriod
	 */
	protected $archivePeriod;
	
	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Datec\DatecBlog\Domain\Model\Keyword> $keywords
	 */
	protected $keywords;
	
	/**
	 * Contructs this object
	 */
	public function __construct() {
		$this->categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->archivePeriod = new \Datec\DatecBlog\Domain\Model\ArchivePeriod();
		$this->keywords = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}
	
	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function getCategories() {
		return $this->categories;
	}
	
	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories
	 * @return void
	 */
	public function setCategories($categories) {
		$this->categories = $categories;
	}
	
	/**
	 * @param \Datec\DatecBlog\Domain\Model\Category> $category
	 * @return void
	 */
	public function addCategory($category) {
		$this->categories->attach($category);
	}
	
	/**
	 * @param \Datec\DatecBlog\Domain\Model\Category> $category
	 * @return void
	 */
	public function removeCategory($category) {
		$this->categories->detach($category);
	}
	
	/**
	 * @return \Datec\DatecBlog\Domain\Model\ArchivePeriod
	 */
	public function getArchivePeriod() {
		return $this->archivePeriod;
	}
	
	/**
	 * @param $archivePeriod \Datec\DatecBlog\Domain\Model\ArchivePeriod
	 * @return void
	 */
	public function setArchivePeriod($archivePeriod) {
		$this->archivePeriod = $archivePeriod;
	}
	
	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function getKeywords() {
		return $this->keywords;
	}
	
	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $keywords
	 * @return void
	 */
	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}
	
	/**
	 * @param \Datec\DatecBlog\Domain\Model\Keyword> $keyword
	 * @return void
	 */
	public function addKeyword($keyword) {
		$this->keywords->attach($keyword);
	}
	
	/**
	 * @param \Datec\DatecBlog\Domain\Model\Keyword> $keyword
	 * @return void
	 */
	public function removeKeyword($keyword) {
		$this->keywords->detach($keyword);
	}
	
	/**
	 * @return boolean
	 */
	public function hasFilterPropertiesSet() {
		$state = FALSE;
		
		if ($this->categories->count() > 0) {
			$state = TRUE;
		} else if ($this->archivePeriod->getFrom() != NULL) {
			$state = TRUE;
		} else if ($this->keywords->count() > 0) {
			$state = TRUE;
		}
		
		return $state;
	}
	
}
?>