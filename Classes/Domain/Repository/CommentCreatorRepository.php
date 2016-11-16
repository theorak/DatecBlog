<?php
namespace Datec\DatecBlog\Domain\Repository;

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
class CommentCreatorRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
	
	/**
	 * Order by [crdate] by default
	 */
	protected $defaultOrderings = array(
		'crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
	 );
	
	/**
	 * Overrides default settings initialisation
	 * - we do not want to restrict the backend storage pages
	 */
	 public function initializeObject() {
	 	$query = $this->createQuery();	 	
        $defaultQuerySettings = $query->getQuerySettings();
        $defaultQuerySettings->setRespectStoragePage(FALSE);
        $this->setDefaultQuerySettings($defaultQuerySettings);
        unset($query);
	}
	
	/**
	 * Finds all objects
	 *
	 * @param boolean $ignoreEnableFields ignores enable fields [hidden] (optional)
	 * @return object The matching object if found, otherwise NULL
	 */
	public function findAll($ignoreEnableFields = FALSE) {
		$query = $this->createQuery();
		
		$defaultQuerySettings = $query->getQuerySettings();
		$defaultQuerySettings->setIgnoreEnableFields($ignoreEnableFields);
		if ($ignoreEnableFields) {
			$defaultQuerySettings->setEnableFieldsToBeIgnored(array('disabled'));
		}
		$this->setDefaultQuerySettings($defaultQuerySettings);
		
		return $query->execute();
	}
	
	/**
	 * Finds an object matching the given identifier
	 *
	 * @param int $uid The identifier of the object to find
	 * @param boolean $ignoreEnableFields ignores enable fields [hidden] (optional)
	 * @return object The matching object if found, otherwise NULL
	 */
	public function findByUid($uid, $ignoreEnableFields = FALSE) {
		$query = $this->createQuery();
		
		$defaultQuerySettings = $query->getQuerySettings();
		$defaultQuerySettings->setIgnoreEnableFields($ignoreEnableFields);
		if ($ignoreEnableFields) {
			$defaultQuerySettings->setEnableFieldsToBeIgnored(array('disabled'));
		}
		$this->setDefaultQuerySettings($defaultQuerySettings);
	
		return $query->matching($query->equals('uid', $uid))->execute()->getFirst();;
	}
	
	/**
	 * Finds first object by frontend user identifier
	 * we use this to determine the current user as known comment creator
	 *
	 * @param int $feUserId assigned frontend user identificator for this object
	 * @param boolean $ignoreEnableFields ignores enable fields [hidden] (optional)
	 * @return object The matching object if found, otherwise NULL
	 */
	public function findByFeUser($feUserId, $ignoreEnableFields = FALSE) {
		$query = $this->createQuery();
		
		$defaultQuerySettings = $query->getQuerySettings();
		$defaultQuerySettings->setIgnoreEnableFields($ignoreEnableFields);
		if ($ignoreEnableFields) {
			$defaultQuerySettings->setEnableFieldsToBeIgnored(array('disabled'));
		}
		$this->setDefaultQuerySettings($defaultQuerySettings);
	
		return $query->matching($query->equals('fe_user', $feUserId))->execute()->getFirst();
	}
	
	/**
	 * Finds first object by person information
	 * we use this to determine a public user as known comment creator
	 *
	 * @param string $email an email address
	 * @param string $username a username
	 * @param boolean $ignoreEnableFields ignores enable fields [hidden] (optional)
	 * @return object The matching object if found, otherwise NULL
	 */
	public function findByCredentials($email, $username, $ignoreEnableFields = FALSE) {
		$constraints = array();
	
		$query = $this->createQuery();	
		
		$defaultQuerySettings = $query->getQuerySettings();
		$defaultQuerySettings->setIgnoreEnableFields($ignoreEnableFields);
		if ($ignoreEnableFields) {
			$defaultQuerySettings->setEnableFieldsToBeIgnored(array('disabled'));
		}
		$this->setDefaultQuerySettings($defaultQuerySettings);
			
		$constraints[] = $query->equals('email', $email);
		$constraints[] = $query->equals('username', $username);
	
		return $query->matching($query->logicalAnd($query->logicalAnd($constraints)))->execute()->getFirst();
	}
	
	/**
	 * find newest commentary
	 * @param int $date
	 * @param int | array $storagePids
	 * @param string $ignoreEnableFileds
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface | array The query result object or an array if $returnRawQueryResult is TRUE
	 */
	public function findByCreateDate(\DateTime $date, $storagePids = null, $ignoreEnableFileds = false) {
	
		$query = $this->createQuery();
		$defaultQuerySettings = $query->getQuerySettings();
		$defaultQuerySettings->setIgnoreEnableFields($ignoreEnableFileds);
		if($storagePids != null) {
			$defaultQuerySettings->setRespectStoragePage(true);
			$defaultQuerySettings->setStoragePageIds(array($storagePids));
		}
	
		$this->setDefaultQuerySettings($defaultQuerySettings);
	
		return $query->matching($query->greaterThanOrEqual('crdate', $date->getTimestamp()))->execute()->toArray();
	}
	
	/**
	 * remove the childs recursive
	 * @param array $childs
	 * @param int $recursiveLevel
	 * @throws \RangeException
	 *
	 * @return void
	 */
	public function removeChildsRecursive($childs, $recursiveLevel = 0){
		if($recursiveLevel > 100) {
			throw new \RangeException('Recursion too deep.', time());
		}
		foreach($childs as $child) {
			$childResult = $this->findChildByParentId($child->getUid());
			$this->removeChildsRecursive($childResult, $recursiveLevel +1);
			$this->remove($child);
		}
	}
	
	/**
	 * find Comment Children by parent id
	 * @param integer $parentId
	 * @param boolean $ignoreEnableFileds ignores enable fileds [hidden] (optional)
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array The query result object or an arry if $returnRawQueryResult is TRUE
	 */
	public function findChildByParentId($parentId, $ignoreEnableFileds = false) {
		$query = $this->createQuery();
		$defaultQuerySettings = $query->getQuerySettings();
		$defaultQuerySettings->setIgnoreEnableFields($ignoreEnableFields);
	
		$this->setDefaultQuerySettings($defaultQuerySettings);
	
		return $query->matching($query->equals("parent", $parentId))->execute()->toArray();
	
	}
	
}
?>