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
class CategoriesController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
	
	/**
	 * $extKey
	 */
	protected $extKey;
	
	/**
	 * $feUser
	 */
	protected $feUser;
	
	/**
	 * $categoryRepository
	 *
	 * @var \Datec\DatecBlog\Domain\Repository\CategoryRepository
	 * @inject
	 */
	protected $categoryRepository;
	
	/**
	 * initialize current action
	 * @return void
	 */
	public function initializeAction() {
		$this->extKey = $this->request->getControllerExtensionKey();		
		$this->feUser = $this->getFeUser();
		
	}
	
	/**
	 * action listCategories
	 * 
	 * @return void
	 */
	public function listCategoriesAction() {
		$feUserGroups = array();
		if ($this->feUser) {
			$feUserGroups = explode(',', $GLOBALS['TSFE']->gr_list); // use the hierarchical access list
		}
		$categoryTreeResult = $this->categoryRepository->findWithChildren(0, $feUserGroups);
		
		if (is_array($categoryTreeResult)) {
			if (count($categoryTreeResult)) {				
				$this->view->assign('categoryTree', $categoryTreeResult);
			} else {
				$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.messages.categoriesController.noCategories',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING);
			}
		} else {
			$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.errors.dbError',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		}		
		
		$this->view->assign('feUser', $this->feUser);
		$this->view->assign('settings', $this->settings);
	}
	
	/**
	 * look for current frontend user
	 *
	 * @return array $feUser or boolean FALSE if no user found
	 */
	private function getFeUser() {
		if(isset($GLOBALS['TSFE']->fe_user->user)) {
			return $GLOBALS['TSFE']->fe_user->user;
		}
	
		return FALSE;
	}
	
}
?>