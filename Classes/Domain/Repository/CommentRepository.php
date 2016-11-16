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
class CommentRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
	
	/**
	 * Order by [crdate] by default
	 */
	protected $defaultOrderings = array(
		'crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
	 );
	
	/**
	 * Overrides default settings initialisation.
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
	 * @param array $newOrder array of 'COLUMNNAME' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_* (optional)
	 * @param boolean $ignoreEnableFields ignores enable fields [hidden] (optional)
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array The query result object or an array if $returnRawQueryResult is TRUE
	 */
	public function findAll($newOrder = array(), $ignoreEnableFields = FALSE) {
		if (!empty($newOrder)) {
			$this->defaultOrderings = $newOrder;
		}
		
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
	
		return $query->matching($query->equals('uid', $uid))->execute()->getFirst();
	}
	
	/**
	 * Finds all objects by post identifier
	 *
	 * @param int $postId assigned post identificator for this objects
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array The query result object or an array if $returnRawQueryResult is TRUE
	 */
	public function findByPost($postId) {
		$constraints = array();
		
		$query = $this->createQuery();
				
		return $query->matching($query->equals('post', $postId))->execute();
	}

	/**
	 * Finds all objects by post identifier and parent object identifier
	 *
	 * @param int $parentId The parent object to search by
	 * @param int $postId assigned post identificator for this objects
	 * @param array array of 'COLUMNNAME' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_* (optional)
	 * @param boolean $ignoreEnableFields ignores enable fields [hidden] (optional)
	 * @return array List of all matching objects
	 */
	public function findByParentAndPost($parentId, $postId, $newOrder = array(), $ignoreEnableFields = FALSE) {
		$constraints = array();
		
		if (!empty($newOrder)) {
			$this->defaultOrderings = $newOrder;
		}
	
		$query = $this->createQuery();
	
		$defaultQuerySettings = $query->getQuerySettings();
		$defaultQuerySettings->setIgnoreEnableFields($ignoreEnableFields);
		if ($ignoreEnableFields) {
			$defaultQuerySettings->setEnableFieldsToBeIgnored(array('disabled'));
		}
		$this->setDefaultQuerySettings($defaultQuerySettings);
		
		$constraints[] = $query->equals('parent', $parentId);
		$constraints[] = $query->equals('post', $postId);
		
		return $query->matching($query->logicalAnd($query->logicalAnd($constraints)))->execute()->toArray();
	}
	
	/**
	 * Finds all object with their respective children
	 *
	 * @param int $parentId The parent object to search by
	 * @param int $postId assigned post identificator for this objects
	 * @param array $newOrder array of 'COLUMNNAME' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_* (optional)
	 * @param boolean $ignoreEnableFields ignores enable fields [hidden] (optional)
	 * @return array The cascading array of children, by parent objects
	 */
	function findWithChildrenByPost($parentId, $postId, $newOrder = array(), $ignoreEnableFields = FALSE) {
		$children = $this->findByParentAndPost($parentId, $postId, $newOrder, $ignoreEnableFields);
		if (count($children) > 0) {
			$i = 0;
			foreach($children as $child) {
				$childUid = $child->getUid();
				$childrenArray[$i]['comment'] = $child;
				$childrenArray[$i]['children'] = $this->findWithChildrenByPost($childUid, $postId, $newOrder, $ignoreEnableFields);
				$i++;
			}
		} else {
			$childrenArray = Array();
		}
		
		return $childrenArray;
	}
	
	/**
	 * finds latest objects by post id
	 *
	 * @param integer $postId assigned post identificator for this objects
	 * @param boolean $ignoreEnableFields ignores enable fields [hidden] (optional)
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array The query result object or an array if $returnRawQueryResult is TRUE
	 */
	public function findLatestsByPost($postId, $ignoreEnableFields = FALSE) {
		// this is the default ordering, but make sure we get the latest first
		$this->defaultOrderings = array(
			'crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
		);
		
		$query = $this->createQuery();	
		
		$defaultQuerySettings = $query->getQuerySettings();
		$defaultQuerySettings->setIgnoreEnableFields($ignoreEnableFields);
		if ($ignoreEnableFields) {
			$defaultQuerySettings->setEnableFieldsToBeIgnored(array('disabled'));
		}
		$this->setDefaultQuerySettings($defaultQuerySettings);
	
		return $query->matching($query->equals('post', $postId))->execute();
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