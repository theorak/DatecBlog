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
class Category extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
	
	/**
	 * @var string
	 * @validate notEmpty
	 */
	protected $name;
	
	/**
	 * @var \Datec\DatecBlog\Domain\Model\Category
	 */
	protected $parent;
	
	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup>
	 */
	protected $usergroups;
	
	/**
	 * Contructs this object
	 */
	public function __construct() {
		$this->usergroups = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}
	
	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * @param $name string
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * @return \Datec\DatecBlog\Domain\Model\Category
	 */
	public function getParent() {
		return $this->parent;
	}
	
	/**
	 * @return boolean
	 */
	public function hasParent() {
		return (isset($this->parent));
	}
	
	/**
	 * @param $parent \Datec\DatecBlog\Domain\Model\Category
	 * @return void
	 */
	public function setParent($parent) {
		$this->parent = $parent;
	}
	
	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $usergroups
	 * @return void
	 */
	public function setUsergroups(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $usergroups) {
		$this->usergroups = $usergroups;
	}
	
	/**
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $usergroup
	 * @return void
	 */
	public function addUsergroup(\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $usergroup) {
		$this->usergroups->attach($usergroup);
	}
	
	/**
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $usergroup
	 * @return void
	 */
	public function removeUsergroup(\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $usergroup) {
		$this->usergroups->detach($usergroup);
	}
	
	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage An object storage containing the usergroups
	 */
	public function getUsergroups() {
		return $this->usergroups;
	}
	
}
?>