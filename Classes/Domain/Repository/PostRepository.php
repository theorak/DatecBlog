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
class PostRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
	
	/**
	 * Order by [crdate] by default
	 * 
	 * PR: This is not ideal yet, because the query funtions do not support conditional sorting,
	 * that means either build cutom querries or have this slightly off output:
	 * The current sorting can place posts published [starttime], before created [crdate] posts on the same day, even if the created one is newer by time
	 */
	protected $defaultOrderings = array(
		'crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
	 );
	
	/**
	 * overrides default settings initialisation
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
	 * @param boolean $ingoreEnableFields ignores enable fields [hidden, starttime, endttime] (optional)
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array The query result object or an array if $returnRawQueryResult is TRUE
	 */
	public function findAll($newOrder = array(), $ingoreEnableFields = FALSE) {
		if (!empty($newOrder)) {
			$this->defaultOrderings = $newOrder;
		}
		
		$query = $this->createQuery();
		
		$defaultQuerySettings = $query->getQuerySettings();
		$defaultQuerySettings->setIgnoreEnableFields($ingoreEnableFields);
		if ($ingoreEnableFields) {
			$defaultQuerySettings->setEnableFieldsToBeIgnored(array('disabled', 'starttime', 'endtime'));
		}		
		$this->setDefaultQuerySettings($defaultQuerySettings);
		
		return $query->execute();
	}
	
	/**
	 * Finds an object matching the given identifier
	 *
	 * @param int $uid The identifier of the object to find
	 * @param boolean $ingoreEnableFields ignores enable fields [hidden, starttime, endttime] (optional)
	 * @return object The matching object if found, otherwise NULL
	 */
	public function findByUid($uid, $ingoreEnableFields = FALSE) {
		$query = $this->createQuery();
		
		$defaultQuerySettings = $query->getQuerySettings();
		$defaultQuerySettings->setIgnoreEnableFields($ingoreEnableFields);
		if ($ingoreEnableFields) {
			$defaultQuerySettings->setEnableFieldsToBeIgnored(array('disabled'));
		}
		$this->setDefaultQuerySettings($defaultQuerySettings);
		$object = $query->matching($query->equals('uid', $uid))->execute()->getFirst();
	
		return $object;
	}
	
	/**
	 * Finds all objects by BloglistCriteria properties as filter criteria
	 * and ignores [starttime, endtime] by default to set its own date restrictions
	 *
	 * @param \Datec\DatecBlog\Domain\Model\BloglistCriteria $bloglistCriteria criteria object
	 * @param array $newOrder array of 'COLUMNNAME' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_* (optional)
	 * @param boolean $ingoreEnableFields adds [hidden] to ignored enable fields (optional)
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array The query result object or an array if $returnRawQueryResult is TRUE
	 */
	public function filterPosts($bloglistCriteria, $newOrder, $ingoreAllEnableFields = FALSE) {
		$constraints = array();
		$enableFieldsToIgnore = array('starttime','endtime');
		if ($ingoreAllEnableFields) {
			$enableFieldsToIgnore[] = 'disabled';
		}
		
		if (!empty($newOrder)) {
			$this->defaultOrderings = $newOrder;
		}
		
		$query = $this->createQuery();
		
		$querySettings = $query->getQuerySettings();		
		$querySettings->setIgnoreEnableFields(TRUE);
		$querySettings->setEnableFieldsToBeIgnored($enableFieldsToIgnore);
		$querySettings->setIncludeDeleted(FALSE);
		$query->setQuerySettings($querySettings);
		
		// selected category filters are combined by OR
		$categories = $bloglistCriteria->getCategories();
		if (count($categories)) {
			$categoryConstraints = array();
			foreach ($categories as $category) {
				$categoryConstraints[] = $query->equals('category', $category);
			}
			
			$constraints[] = $query->logicalOr($categoryConstraints);
		}
		
		// only already published posts, default for enable fields [starttime], [endtime]
		$today = time();
		$todayStartConstraint = $query->logicalAnd($query->lessThanOrEqual('starttime', $today), $query->greaterThan('starttime', 0));
		$todayCrdateStartConstraint = $query->logicalAnd($query->lessThanOrEqual('crdate', $today), $query->equals('starttime', 0));		
		$todayCrdateEndConstraint = $query->logicalAnd($query->lessThanOrEqual('crdate', $today), $query->equals('endtime', 0));		
		$datetimeConstraints[] = $query->logicalOr($todayStartConstraint, $todayCrdateStartConstraint);
		$datetimeConstraints[] = $query->logicalOr($query->greaterThanOrEqual('endtime', $today), $todayCrdateEndConstraint);

		// further constrict time by period
		$from = $bloglistCriteria->getArchivePeriod()->getFrom();		
		$to = $bloglistCriteria->getArchivePeriod()->getTo();
		if (!empty($from) && !empty($to)) {
			$from = $from->getTimestamp();
			$to = $to->getTimestamp();
			
			$fromCrdateStartConstraint = $query->logicalAnd($query->greaterThanOrEqual('crdate', $from), $query->equals('starttime', 0));
			$fromStartEndConstraint = $query->logicalAnd($query->equals('endtime', 0), $query->greaterThanOrEqual('starttime', $from));				
			$fromCrdateEndConstraint = $query->logicalAnd($query->greaterThanOrEqual('crdate', $from), $query->equals('endtime', 0));
			$datetimeConstraints[] = $query->logicalOr($query->greaterThanOrEqual('starttime', $from), $fromCrdateStartConstraint);
			$datetimeConstraints[] = $query->logicalOr($query->greaterThanOrEqual('endtime', $from), $fromCrdateEndConstraint, $fromStartEndConstraint);
			
			$toStartConstraint = $query->logicalAnd($query->lessThanOrEqual('starttime', $to), $query->greaterThan('starttime', 0));
			$toCrdateStartConstraint = $query->logicalAnd($query->lessThanOrEqual('crdate', $to), $query->equals('starttime', 0));
			$toEndConstraint = $query->logicalAnd($query->lessThanOrEqual('endtime', $to), $query->greaterThan('endtime', 0));
			$toCrdateEndConstraint = $query->logicalAnd($query->lessThanOrEqual('crdate', $to), $query->equals('endtime', 0));			
			$datetimeConstraints[] = $query->logicalOr($toStartConstraint, $toCrdateStartConstraint);
			$datetimeConstraints[] = $query->logicalOr($toEndConstraint, $toCrdateEndConstraint);
		}
		
		$constraints[] = $query->logicalAnd($datetimeConstraints);
		
		// selected keyword filters are combined by OR
		$keywords = $bloglistCriteria->getKeywords();
		if (count($keywords)) {
			$keywordConstraints = array();
			
			foreach ($keywords as $keyword) {
				$keywordConstraints[] = $query->contains('keywords', $keyword);
			}
			
			$constraints[] = $query->logicalOr($keywordConstraints);
		}
		
		return $query->matching($query->logicalAnd($query->logicalAnd($constraints)))->execute();
	}	
	
}
?>