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
class KeywordsController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
	
	/**
	 * $extKey
	 */
	protected $extKey;
	
	/**
	 * $keywordRepository
	 *
	 * @var \Datec\DatecBlog\Domain\Repository\KeywordRepository
	 * @inject
	 */
	protected $keywordRepository;
	
	/**
	 * initialize current action
	 * @return void
	 */
	public function initializeAction() {
		$this->extKey = $this->request->getControllerExtensionKey();
		
		
	}
	
	/**
	 * action listKeywords
	 * 
	 * @return void
	 */
	public function listKeywordsAction() {
		$order = array();
		switch ($this->settings['display']['keywords']['order']) { // define ordering by config options
			case 'date' : 
				$order['starttime'] = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING;
				$order['crdate'] = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING;
				break;
			case 'usage' :
				$order['click_count'] = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING;
				break;
			case 'sorting' :
				$order['sorting'] = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING;
				break;
			default : 
				$order['sorting'] = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING;
		}
		
		$keywordsResult = $this->keywordRepository->findAll($order, $this->settings['display']['keywords']['limit'], $this->settings['keywords']['storagePid']);
			
		if ($keywordsResult) {
			$keywordsResult->toArray();
			if (count($keywordsResult)) {
				$this->view->assign('keywords', $keywordsResult);
			} else {
				$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.messages.keywordsController.noKeywords',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING);
			}
		} else {
			$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.errors.dbError',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		}
		
		$this->view->assign('settings', $this->settings);
	}
	
	/**
	 * action updateClickCount
	 *
	 * @return void
	 */
	public function updateClickCountAction() {
		if ($this->request->hasArgument('keywordId')) {
			$keywordId = $this->request->getArgument('keywordId');
			
			$keywordResult = $this->keywordRepository->findByUid($keywordId);
			
			if ($keywordResult) {
				$currentClickCount = $keywordResult->getClickCount();
				$currentClickCount++;
				$keywordResult->setClickCount($currentClickCount);
			
				$this->keywordRepository->update($keywordResult);
			} else {
				$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.errors.dbError',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
			}
		} else {
			$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.messages.keywordsController.noKeywordId',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		}	
	}
	
}
?>