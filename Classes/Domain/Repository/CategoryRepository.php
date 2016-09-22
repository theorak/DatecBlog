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
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

/**
 *
 *
 * @package datec_blog
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class CategoryRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
	
	/**
	 * Order by [sorting] by default
	 */
	protected $defaultOrderings = array(
		'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
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
	 * does not respect access or hierarchy, use findWithChildren() for that
	 *
	 * @param array $usergroups current users groups
	 * @param boolean $ignoreEnableFields ignores enable fields [hidden] (optional)
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array The query result object or an array if $returnRawQueryResult is TRUE
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
		
		return $query->matching($query->equals('uid', $uid))->execute()->getFirst();
	}
	
	/**
	 * Finds all objects matching the given parent object identifier
	 *
	 * @param int $parentId The parent object identification to search by
	 * @param array $usergroups current users groups to restrict access by (optional)
     * @param int $storagePid respects storage PID
	 * @param boolean $ignoreEnableFields ignores enable fields [hidden] (optional)
	 * @return array List of all matching objects
	 */
	public function findByParent($parentId, $usergroups = array(), $storagePid = NULL, $ignoreEnableFields = FALSE) {
		$constraints = array();
		$query = $this->createQuery();
        /** @var $defaultQuerySettings Typo3QuerySettings */
        $defaultQuerySettings = $query->getQuerySettings();

        if ($storagePid !== NULL) {
            $defaultQuerySettings->setRespectStoragePage(TRUE);
            $defaultQuerySettings->setStoragePageIds(array($storagePid));
        }

		$defaultQuerySettings->setIgnoreEnableFields($ignoreEnableFields);
		if ($ignoreEnableFields) {
			$defaultQuerySettings->setEnableFieldsToBeIgnored(array('disabled'));
		}
		$this->setDefaultQuerySettings($defaultQuerySettings);
		
		$constraints[] = $query->equals('parent', $parentId);
		
		// logged-in feUser should have usergroups to compare by OR
		if (count($usergroups)) {
			$usergroupsConstraints = array();
            $usergroupsConstraints[] = $query->equals('usergroups', ''); // can be unrestricted
			
			foreach ($usergroups as $usergroup) {
                $usergroupsConstraints[] = $query->contains('usergroups', $usergroup);
			}
			
			$constraints[] = $query->logicalOr($usergroupsConstraints);
		} else { // otherwise only categories without access restictions are allowed
			$constraints[] = $query->equals('usergroups', '');
		}
		
		return $query->matching($query->logicalAnd($query->logicalAnd($constraints)))->execute()->toArray();
	}
	
	/**
	 * Finds all objects with their respective children
	 *
	 * @param int $parentId The parent object identification to search by
	 * @param array $usergroups current users groups to resctrict access by (optional)
	 * @param boolean $hierarchical return as hierarchy or just as list (optional)
	 * @param boolean $ignoreEnableFields ignores enable fields [hidden] (optional
     * @param int $storagePid respects storage PID
	 * @return array The array of objects with children
	 */
	public function findWithChildren($parentId, $usergroups = array(), $hierarchical = TRUE, $storagePid = NULL, $ignoreEnableFields = FALSE) {
		$children = $this->findByParent($parentId, $usergroups, $storagePid, $ignoreEnableFields);

		if (count($children) > 0) {
			$i = 0;
			foreach($children as $child) {
				$childUid = $child->getUid();
				if ($hierarchical) {		
					$childrenArray[$i]['category'] = $child;
					$childrenArray[$i]['children'] = $this->findWithChildren($childUid, $usergroups, $hierarchical, $storagePid, $ignoreEnableFields);
				} else {
					$childrenArray[] = $child;
					$childrenArray = array_merge($childrenArray, $this->findWithChildren($childUid, $usergroups, $hierarchical, $storagePid, $ignoreEnableFields));
				}
				$i++;
			}
		} else {
			$childrenArray = array();
		}
		
		return $childrenArray;
	}
	
}
?>