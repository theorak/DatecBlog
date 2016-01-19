<?php
namespace Datec\DatecBlog\Controller;

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
class ArchiveController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
	
	/**
	 * $extKey
	 */
	protected $extKey;
	
	/**
	 * $postRepository
	 *
	 * @var \Datec\DatecBlog\Domain\Repository\PostRepository
	 * @inject
	 */
	protected $postRepository;
	
	/**
	 * initialize current action
	 * @return void
	 */
	public function initializeAction() {
		$this->extKey = $this->request->getControllerExtensionKey();
		
		
	}
	
	/**
	 * action showArchive
	 * 
	 * @return void
	 */
	public function showArchiveAction() {
		$postsResult = $this->postRepository->findAll(array(
				'crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
				));
			
		if ($postsResult) {
			$postsResult->toArray();
			if (count($postsResult)) {
				$archive = $this->generateArchive($postsResult);
				if (count($archive)) {
					$this->view->assign('archive', $archive);
				} else {							
					$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.errors.archiveGeneration',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
				}
			} else {
				$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.messages.blogController.noPosts',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING);
			}
		} else {
			$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.errors.dbError',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		}
		
		$this->view->assign('settings', $this->settings);
	}
	
	/**
	 * generates an array for the year, month and day of post's dates
	 * 
	 * @param array $posts
	 * return array $archive
	 */
	private function generateArchive($posts) {
		$archive = array();
		
		foreach ($posts as $post) {
			$postDate = ($post->getStarttime() != 0) ? $post->getStarttime() : $post->getCrdate();
			
			$year = date('Y', $postDate);
			$month = date('m', $postDate);
			$day = date($this->settings['display']['dateFormat'], $postDate);
			
			if (!isset($archive[$year]['archivePeriod'])) {
				$archivePeriodYear = new \Datec\DatecBlog\Domain\Model\ArchivePeriod();
				
				$archivePeriodYear->setFrom(new \DateTime(date('Y-01-01 00:00:00', $postDate))); // start and end of this posts year
				$archivePeriodYear->setTo(new \DateTime(date('Y-12-t 23:59:59', $postDate)));
				$archivePeriodYear->setType(\Datec\DatecBlog\Domain\Model\ArchivePeriodType::YEAR);
				
				$archive[$year]['archivePeriod'] = $archivePeriodYear;
			}
			
			if (!isset($archive[$year]['months'][$month]['archivePeriod'])) {
				$archivePeriodMonth = new \Datec\DatecBlog\Domain\Model\ArchivePeriod();
				
				$archivePeriodMonth->setFrom(new \DateTime(date('Y-m-01 00:00:00', $postDate))); // start and end of this posts month
				$archivePeriodMonth->setTo(new \DateTime(date('Y-m-t 23:59:59', $postDate)));
				$archivePeriodMonth->setType(\Datec\DatecBlog\Domain\Model\ArchivePeriodType::MONTH);
				
				$archive[$year]['months'][$month]['archivePeriod'] = $archivePeriodMonth;
			}
			
			if (!isset($archive[$year]['months'][$month]['days'][$day]['archivePeriod'])) {
				$archivePeriodDay = new \Datec\DatecBlog\Domain\Model\ArchivePeriod();
				
				$archivePeriodDay->setFrom(new \DateTime(date('Y-m-d 00:00:00', $postDate))); // start and end of this posts day
				$archivePeriodDay->setTo(new \DateTime(date('Y-m-d 23:59:59', $postDate)));
				$archivePeriodDay->setType(\Datec\DatecBlog\Domain\Model\ArchivePeriodType::DAY);
				
				$archive[$year]['months'][$month]['days'][$day]['archivePeriod'] = $archivePeriodDay;
			}			
		}
		
		return $archive;
	}
	
}
?>