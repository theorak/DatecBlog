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
class BlogmanagementModuleController  extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
	/**
	 * $extKey
	 */
	protected $extKey;
	/**
	 * $categoryRepository
	 * 
	 * @var \Datec\DatecBlog\Domain\Repository\CategoryRepository
	 * @inject
	 */
	protected $categoryRepository;
	/**
	 * $commentCreatorRepository
	 * 
	 * @var \Datec\DatecBlog\Domain\Repository\CommentCreatorRepository
	 * @inject
	 */
	protected $commentCreatorRepository;
	/**
	 * $commentrepository
	 * 
	 * @var \Datec\DatecBlog\Domain\Repository\CommentRepository
	 * @inject
	 */
	protected $commentRepository;
	/**
	 * $postRepository
	 * 
	 * @var \Datec\DatecBlog\Domain\Repository\PostRepository
	 * @inject
	 */
	protected $postRepository;
	/**
	 * $keywordRepository
	 * 
	 * @var \Datec\DatecBlog\Domain\Repository\KeywordRepository
	 * @inject
	 */
	protected $keywordRepository;
	/**
	 * $blogCriteria
	 * 
	 * @var \Datec\DatecBlog\Domain\Model\BloglistCriteria
	 * @inject
	 */
	protected $bloglistCriteria;
	
	/**
	 * $feUserRepository
	 *
	 * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
	 * @inject
	 */
	protected $feUserRepository;
	
	/**
	 * $beUserRepository
	 *
	 * @var \TYPO3\CMS\Extbase\Domain\Repository\BackendUserRepository
	 * @inject
	 */
	protected $beUserRepository;
	
	/**
	 * $settings
	 * 
	 * @var array
	 */
	protected $settings;
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \TYPO3\CMS\Extbase\Mvc\Controller\ActionController::initializeAction()
	 */
	public function initializeAction() {
		$this->extKey = $this->request->getControllerExtensionKey();
		$beConfigurationManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager::class);
		$this->settings = $beConfigurationManager->getConfiguration(
                $this->request->getControllerExtensionName(),
                $this->request->getPluginName()
        );
        $this->settings = $this->settings['settings'];
	}
	
	public function overviewAction() {
	
	}
	/**
	 * display a list of Commentary
	 */
	public function listCommentsAction() {
		$doc = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Template\\DocumentTemplate');
		$date = null;
		$pageRenderer = $doc->getPageRenderer();
		//settings for the date time picker
		$typo3Settings = array(
				'datePickerUSmode' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['USdateFormat'] ? 1 : 0,
				'dateFormat' => array('j-n-Y', 'd.m.Y'), // format to german date
				'dateFormatUS' => array('n-j-Y', 'n-j-Y') // formate to english date 
			);
		$pageRenderer->addInlineSettingArray('', $typo3Settings);
		//load date time picker and date time picker button
		$doc->loadJavascriptLib('js/extjs/ux/Ext.ux.DateTimePicker.js');
		$doc->loadJavascriptLib('sysext/backend/Resources/Public/JavaScript/tceforms.js');
		// load picker icon
		$icon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon('actions-edit-pick-date',array(
				'style'=>'cursor:pointer;',
				'id' => 'picker-tceforms-datetimefield-start'));
		$this->view->assign('icon', $icon);
		
		if($this->request->hasArgument("crdate")){
			$date = new \DateTime($this->request->getArgument("crdate"));
			$this->view->assign("date", $this->request->getArgument("crdate"));
		} else {
			$date = new \DateTime();
			$date->modify("-10 day");
		}
		
		$commentResult = $this->commentRepository->findByCreateDate($date,\TYPO3\CMS\Core\Utility\GeneralUtility::_GP("id"));
		$commentArr = array();
		foreach($commentResult as $comments) {
			$comment['entry'] = $comments;
			$comment['isChild'] =  $this->isParent($comments);
			$commentArr[] = $comment;
		}
		$this->view->assign('commentResult', $commentArr);
		$this->view->assign('lang', $GLOBALS['BE_USER']->user['lang']);
	}
	
	/**
	 * show Blog
	 */
	public function showBlogAction() {
		$this->initializeBloglistCriteria();
		$args = $this->request->getArguments();
		
		if (!empty($args['postId'])) { // pass the post Id along for single view
			$this->view->assign('postId', $args['postId']);
		}
		if (!empty(\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('blogpost'))) { // short param for the above
			$this->view->assign('postId', \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('blogpost'));
		}
		if (!empty($args['commentText'])) { // prefilled comment form
			$this->view->assign('commentText', $args['commentText']);
		}
		
		$this->view->assign('bloglistCriteria', $this->bloglistCriteria);
		$this->view->assign('settings', $this->settings);
		$this->view->assign('lang', $GLOBALS['BE_USER']->user['lang']);
	}
	
	/**
	 * Display all post of the blog
	 */
	public function listPostsAction() {
		$filterParams = array();
		$feUserGroups = array();
		$this->initializeBloglistCriteria();
		$newOrder = (!empty($this->settings['display']['posts']['sorting']) && !empty($this->settings['display']['posts']['sortingDirection'])) ? array($this->settings['display']['posts']['sorting'] => $this->settings['display']['posts']['sortingDirection']) : array();
		
		if ($this->bloglistCriteria->hasFilterPropertiesSet()) {
			$postsResult = $this->postRepository->filterPosts($this->bloglistCriteria, $newOrder,$newOrder,\TYPO3\CMS\Core\Utility\GeneralUtility::_GP("id"));
		} else {
			$postsResult = $this->postRepository->findAll($newOrder, \TYPO3\CMS\Core\Utility\GeneralUtility::_GP("id"));
		}
		
		if ($postsResult) {
			$postsResult = $postsResult->toArray();
			if (count($postsResult)) {
				// reduce results to current page if pagination is enabled
				if ((bool) $this->settings['display']['posts']['pagination']['enable']) {
					$pagination = $this->getPagination($postsResult);
						
					if ($pagination) {
						$postsResult = $pagination->pages[$pagination->index];
		
						if ($pagination->pagesCount > 1) {
							$this->view->assign('pagination', $pagination);
						}
					}
				}
		
				$latestComments = array();
		
				if ($this->feUser) {
					$feUserGroups = explode(',', $GLOBALS['TSFE']->gr_list); // use the hierarchical access list
				}
		
				$categoriesResult = $this->categoryRepository->findWithChildren(0, $feUserGroups, FALSE); // get allowed Categories
				if (!$categoriesResult) {
					$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.errors.dbError',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
				}
		
				foreach ($postsResult as $key => $post) {
					// remove post when not in allowed categories
					if (!in_array($post->getCategory(), $categoriesResult)) {
						unset($postsResult[$key]);
						continue;
					}
						
					$postId = $post->getUid();
						
					$latestCommentsResult = $this->commentRepository->findLatestsByPost($postId);
					if ($latestCommentsResult) {
						$latestCommentsResult->toArray();
						if (count($latestCommentsResult)) {
							$latestComment = $latestCommentsResult[0];
							$commentsCount = count($latestCommentsResult);
							$commentCreator = $latestComment->getCommentCreator();
								
							if ($commentCreator  != 0) {
								$feUserId = $commentCreator->getFeUser();
								if ($feUserId){
									$feUser = $this->feUserRepository->findByUid($feUserId);
									if ($feUser) {
										$latestComment->getCommentCreator()->setFeUser($feUser);
									}
								}
							} else {
								$beUserId = $latestComment->getCruserId();
								if ($beUserId){
									$beUser = $this->beUserRepository->findByUid($beUserId);
									if ($beUser) {
										$latestComment->setCruserId($beUser);
									}
								}
							}
								
							$latestComments[$postId]['postId'] = $postId;
							$latestComments[$postId]['comment'] = $latestComment;
							$latestComments[$postId]['count'] = $commentsCount;
						}
					} else {
						$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.errors.dbError',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
					}
				}
				if (count($postsResult)) {		// check again, category access test might have unset them all
					$this->view->assign('posts', $postsResult);
				} else {
					$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.messages.blogController.noPosts',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING);
				}
				$this->view->assign('latestComments', $latestComments);
			} else {
				$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.messages.blogController.noPosts',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING);
			}
		} else {
			$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.errors.dbError',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		}
		
		$this->view->assign('bloglistCriteria', $this->bloglistCriteria);
		$this->view->assign('settings', $this->settings);
	}
	
	/**
	 * Display all comment of the post 
	 */
	public function listBlogCommentsAction() {
		if($this->request->hasArgument('postId')) {
			$postId = $this->request->getArgument('postId');
			$newOrder = (!empty($this->settings['display']['comments']['sorting']) && !empty($this->settings['display']['comments']['sortingDirection'])) ? array($this->settings['display']['comments']['sorting'] => $this->settings['display']['comments']['sortingDirection']) : array();
			
			$commentsResult = $this->commentRepository->findWithChildrenByPost(0, $postId, $newOrder);
			
			if($commentsResult !== FALSE) {
				if(count($commentsResult)) {
					$comments = $this->setUsersInComments($commentsResult);
					
					$this->view->assign('comments', $comments);
				} else {
					$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.messages.blogController.noComments',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING);
				}
			} else {
				$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.errors.dbError',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
			}
		} else {
			$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.messages.blogController.noPostId',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		}
		
		$this->view->assign('settings', $this->settings);
	}
	
	/**
	 * Each comment creator in the list of comments gets the full user object when containing a valid uid
	 * We do this, because mapping to the feUser/beUser did not work
	 *
	 * @param array $commentResult an array of comments with their children
	 * @return array $resultArr
	 */
	private function setUsersInComments($commentsResult) {
		$resultArr = array();
			
		if (is_array($commentsResult)) {
			foreach ($commentsResult as $commentResult) {
				$commentCreator = $commentResult['comment']->getCommentCreator();
				if ($commentCreator  != 0) {
					$feUserId = $commentResult['comment']->getCommentCreator()->getFeUser();
					if ($feUserId) {
						$feUser = $this->feUserRepository->findByUid($feUserId);
						if ($feUser) {
							$commentResult['comment']->getCommentCreator()->setFeUser($feUser);
						}
					}
				} else {
					$beUserId = $commentResult['comment']->getCruserId();
					if ($beUserId) {
						$beUser = $this->beUserRepository->findByUid($beUserId);
						if ($beUser) {
							$commentResult['comment']->setCruserId($beUser);
						}
					}
				}
				if (count($commentResult['children'])) {
					$commentResult['children'] = $this->setUsersInComments($commentResult['children']);
				}
				$resultArr[] = $commentResult;
			}
		}
	
		return $resultArr;
	}
	
	/**
	 * Display a Single Post
	 */
	public function showSinglePostAction() {
		$filterParams = array();		
		$feUserGroups = array();
		$commentAccess = FALSE;
	
		if ($this->request->hasArgument('postId')) {
			$postId = $this->request->getArgument('postId');
			
			$postResult = $this->postRepository->findByUid($postId);			
			if ($postResult) {
				$allowedUsergroups = $postResult->getCommentsUsergroups()->toArray();
				
				// check user access to comments
				if (empty($allowedUsergroups)) { // not restricted
					$commentAccess = TRUE;
				} else {
					if (count($feUserGroups) && count($allowedUsergroups)) { // match frontend user groups
						foreach ($allowedUsergroups as $allowedUsergroup) {
							if (in_array($allowedUsergroup->getUid(), $feUserGroups)) {
								$commentAccess = TRUE;
							}
						}
					}
				}
				
				if ($postResult->isCommentsFileupload()) {
					// get php settings for possible file size
					$postMaxSize = $this->returnBytes(ini_get('post_max_size'));
					$uploadMaxFilesize = $this->returnBytes(ini_get('upload_max_filesize'));
					// use smallest setting
					$this->settings['maxFileSize'] = min($this->settings['maxFileSize'], $postMaxSize, $uploadMaxFilesize);
					$this->view->assign('allowedFileSizeDisplay', $this->convertBytesStr($this->settings['maxFileSize']));
				}
				
				$this->view->assign('post', $postResult);
			} else {
				$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.errors.dbError',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
			}
		} else {
			$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.messages.blogController.noPostId',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		}
		
		$this->view->assign('duplicant1', md5(\TYPO3\CMS\Core\Utility\GeneralUtility::generateRandomBytes(64)));
		$this->view->assign('comment', $this->comment);
		$this->view->assign('commentAccess', $commentAccess);
		$this->view->assign('feUser', $this->feUser);
		$this->view->assign('settings', $this->settings);		
	}
	
	/**
	 * Blocked comment creator
	 */
	public function blockCommentaryAction() {
		$id = $this->request->getArgument("id");
		$comment = $this->commentRepository->findByUid($id);
		$creator = $comment->getCommentCreator();
		if($creator) {
			if($creator->isBlocked()){
				$block = false;
			} else {
				$block = true;
			}
			
			$creator->setBlocked($block);
			$this->commentCreatorRepository->update($creator);
		}
		
		if($this->request->hasArgument("crdate")) {
			$args['crdate']= $this->request->getArgument("crdate");
		}
		
		$this->redirect('listComments', NULL, NULL,$args);
	}
	
	/**
	 * Delete commentary
	 */
	public function deleteCommentaryAction() {
		$id = $this->request->getArgument("id");
		$commentObj = $this->commentRepository->findByUid($id);
		$childsArr = $this->commentRepository->findChildByParentId($commentObj->getUid());
		if(count($childsArr)) {
			$this->commentRepository->removeChildsRecursive($childsArr);
		}
		$this->commentRepository->remove($commentObj);
		$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_datecblog.be.label.comment.delete", $this->extKey));
		$this->redirect("listComments");
	}
	
	/**
	 * Check is parent
	 * @param \Datec\ $commentEntry
	 * @return number
	 */
	private function isParent($commentEntry) {
		$child = $this->commentRepository->findChildByParentId($commentEntry->getUid());
		if(is_array($child) && count($child)) {
			return 1;
		}
		return 0;
	}
	
	/**
	 * Get a list of Keywords
	 * 
	 * @return array @keywordsResult
	 */
	public function listKeywords() {
		$order = array();
		switch($this->settngs['display']['keywords']['order']) {
			case 'date':
				$order['startime'] = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING;
				$order['endtime'] = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING;
				break;
			case 'usage':
				$order['clickCount'] = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING;
				break;
			case 'sorting':
				$order['sorting'] = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING;
				break;
			default;
				$order['sorting'] = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING;
		}
		
		$keywordsResult = $this->keywordRepository->findAll($order,$this->settings['display']['keywords']['limit']);
		
		if($keywordsResult) {
			$keywordsResult->toArray();
			if(count($keywordsResult)) {
				return $keywordsResult;
			}
		}
		
		return false;
	}

	/**
	 * initialize a new criteria object and fill it with form data when provided
	 *
	 * @return void
	 */
	private function initializeBloglistCriteria() {
		$this->bloglistCriteria = new \Datec\DatecBlog\Domain\Model\BloglistCriteria();
		
		if($this->request->hasArgument('bloglistCriteria')){
			$formData = $this->request->getArgument('bloglistCriteria');
			if(count($formData)) {
				foreach($formData['blogListCriteria'] as $categoryId) {
					$category = $this->categoryRepository->findByUid($categoryId);
					$this->bloglistCriteria->addCategory($category);
				}
			}
		}
		
		if(!empty($formData['archivePeriod']['from']) && !empty($formData['archivePeriod']['to'])){
			$archivePeriod = new \Datec\DatecBlog\Domain\Model\ArchivePeriod();
			$form = new \DateTime();
			$to = new \DateTime();
			
			$from->setTimeStamp($formData['archivePeriod']['from']);
			$to->setTimestamp($formData['archivePeriod']['to']);
			$archivePeriod->setFrom($from);
			$archivePeriod->setTo($to);
			$archivePeriod->setType($formData['archivePeriod']['type']);
			
			$this->bloglistCriteria->setArchivePeriod($archivePeriod);
		}
		
		if(count($formData['keywords'])) {
			foreach($formData['keywords'] as $keywordId) {
				$keyword = $this->keywordRepository->findByUid($keywordId);
				$this->bloglistCriteria->addKeyword($keyword);
			}
		}
	}
	
	/**
	 * @param array $resultArr
	 * @return \stdClass|boolean the pagination information Object or false if not supplied with valid array
	 */
	private function getPagination($resultArr) {
		if(is_array($resultArr)){
			$pagination = new \stdClass();
			$pagination->itemsPerPage = (bool) $this->settings['display']['posts']['pagination']['itemsPerPage'] ? intval($this->settings['display']['posts']['pagination']['itemsPerPage'], 10) : 10;
			$pagination->maxPages = (bool) $this->settings['display']['posts']['pagination']['maxPages'] ? intval($this->settings['display']['posts']['pagination']['maxPages'], 10) : NULL;
			$pagination->top = (bool) $this->settings['display']['posts']['pagination']['top'];
			$pagination->bottom = (bool) $this->settings['display']['posts']['pagination']['bottom'];
			
			$pagination->resultsCount = count($resultArr);
			
			if($this->request->hasArgument('paginationCurrent')) {
				$pagination->current = ($this->request-getArgument('paginationCurrent') != 0) ? intval($this->request->getArgument('paginationCurrent'), 10) :1;
			} else {
				$pagination->current = 1;				
			}
			
			$pagination->pages = array_slice(array_chunk($resultArr, $pagination->itemPerPage),0, $pagination->maxPages,TRUE);
			$pagination->pagesCount = count($pagination->pages);
			
			$pagination->index = ($pagination->current -1);
			if(array_key_exists($pagination->index, $pagination-pages)) {
				$pagination->countOnPage = count($pagination->pages[$pagination->index]);	
			} else {
				return FALSE;
			}
			
			$pagination->previous = (($pagination->current - 1)>0) ? ($pagination->current - 1): FALSE;
			$pagination->first = $pagination->previous ? 1 : FALSE;
			$pagination->next = (($pagination->current + 1)<= $pagination->pagesCount) ? ($pagination->current + 1): FALSE;
			$pagination->lase = $pagination->next ? $pagination->next: FALSE;
			
			return $pagination;
				
		} else {
			return FALSE;
		}
	}
	
	/**
	 * get bytes for size string
	 *
	 * @param string $val the size	 *
	 * @return string $val the size in bytes
	 */
	private function returnBytes($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}
	
		return $val;
	}
	
	/**
	 * get highest possible bytes conversion for size string
	 *
	 * @param string $bytes the size in bytes
	 * @param int $precision of round(), default = 2	 *
	 * @return string $bytesStr the size in bytes by unit
	 */
	private function convertBytesStr($bytes, $precision = 2) {
		$val = trim($bytes);
		$units = array('B', 'KB', 'MB', 'GB', 'TB');
		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
		$bytes /= pow(1024, $pow);
	
		return round($bytes, $precision) . ' ' . $units[$pow];
	}
}