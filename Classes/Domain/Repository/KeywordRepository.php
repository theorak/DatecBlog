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
class KeywordRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
	
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
	 *
	 * @param array $newOrder array of 'COLUMNNAME' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_* (optional)
	 * @param int $limit limit how many objects to fetch, disabled by default (optional)
     * @param int $storagePid respects storage PID
	 * @param boolean $ignoreEnableFields ignores enable fields [hidden] (optional)
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array The query result object or an array if $returnRawQueryResult is TRUE
	 */
	public function findAll($newOrder = array(), $limit = 0, $storagePid = NULL, $ignoreEnableFields = FALSE) {
		if (!empty($newOrder)) {
			$this->defaultOrderings = $newOrder;
		}
		
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
		
		if ($limit > 0) {
			$query->setLimit($limit);
		}
		
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
		if ($ignoreEnableFields) {
			$defaultQuerySettings->setEnableFieldsToBeIgnored(array('disabled'));
		}
		$this->setDefaultQuerySettings($defaultQuerySettings);
		
		return $query->matching($query->equals('uid', $uid))->execute()->getFirst();
	}
	
}
?>