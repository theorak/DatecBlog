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
class BlogController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
	
	/**
	 * $extKey
	 */
	protected $extKey;
	
	/**
	 * $feUser
	 */
	protected $feUser;
	
	/**
	 * $beUser
	 */
	protected $beUser;
	
	/**
	 * $bloglistCriteria
	 */
	protected $bloglistCriteria;
	
	/**
	 * $comment
	 */
	protected $comment;
	
	/**
	 * $postRepository
	 *
	 * @var \Datec\DatecBlog\Domain\Repository\PostRepository
	 * @inject
	 */
	protected $postRepository;
	
	/**
	 * $categoryRepository
	 *
	 * @var \Datec\DatecBlog\Domain\Repository\CategoryRepository
	 * @inject
	 */
	protected $categoryRepository;
	
	/**
	 * $keywordRepository
	 *
	 * @var \Datec\DatecBlog\Domain\Repository\KeywordRepository
	 * @inject
	 */
	protected $keywordRepository;
	
	/**
	 * $commentRepository
	 *
	 * @var \Datec\DatecBlog\Domain\Repository\CommentRepository
	 * @inject
	 */
	protected $commentRepository;
	
	/**
	 * $commentCreatorRepository
	 *
	 * @var \Datec\DatecBlog\Domain\Repository\CommentCreatorRepository
	 * @inject
	 */
	protected $commentCreatorRepository;
	
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
	 * $persistenceManager
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;
	
	/**
	 * initialize current action
	 * @return void
	 */
	public function initializeAction() {
		$this->extKey = $this->request->getControllerExtensionKey();
		$this->feUser = $this->getFeUser();	
		$this->beUser = $this->getBeUser();
		
		
	}
	
	/**
	 * action showBlog
	 *
	 * @return void
	 */
	public function showBlogAction() {
		$this->initializeBloglistCriteria();		
		$args = $this->request->getArguments();
				
		if (!empty($args['postId'])) { // pass the post Id along for single view
			$this->view->assign('postId', $args['postId']);
		}
		if (!empty($_GET['blogpost'])) { // short param for the above
			$this->view->assign('postId', $_GET['blogpost']);
		}
		if (!empty($args['commentText'])) { // prefilled comment form
			$this->view->assign('commentText', $args['commentText']);
		}
		
		$this->view->assign('bloglistCriteria', $this->bloglistCriteria);
		$this->view->assign('pageId', $GLOBALS['TSFE']->id);
		$this->view->assign('settings', $this->settings);
	}
	
	/**
	 * action listPosts
	 * 
	 * @return void
	 */
	public function listPostsAction() {		
		$filterParams = array();		
		$feUserGroups = array();
		$this->initializeBloglistCriteria();
		$newOrder = (!empty($this->settings['display']['posts']['sorting']) && !empty($this->settings['display']['posts']['sortingDirection'])) ? array($this->settings['display']['posts']['sorting'] => $this->settings['display']['posts']['sortingDirection']) : array();
		
		if ($this->bloglistCriteria->hasFilterPropertiesSet()) {	
			$postsResult = $this->postRepository->filterPosts($this->bloglistCriteria, $newOrder);
		} else {			
			$postsResult = $this->postRepository->findAll($newOrder);
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
	 * action showSinglePost
	 *
	 * @return void
	 */
	public function showSinglePostAction() {
		$filterParams = array();		
		$feUserGroups = array();
		$commentAccess = FALSE;
		$this->initializeComment();
	
		if ($this->request->hasArgument('postId')) {
			$postId = $this->request->getArgument('postId');
			if ($this->feUser) {
				$feUserGroups = explode(',', $GLOBALS['TSFE']->gr_list); // use the hierarchical access list
			}
			
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
	 * action listComments
	 *
	 * @return void
	 */
	public function listCommentsAction() {			
		if ($this->request->hasArgument('postId')) {
			$postId = $this->request->getArgument('postId');
			$newOrder = (!empty($this->settings['display']['comments']['sorting']) && !empty($this->settings['display']['comments']['sortingDirection'])) ? array($this->settings['display']['comments']['sorting'] => $this->settings['display']['comments']['sortingDirection']) : array();			
			
			$commentsResult = $this->commentRepository->findWithChildrenByPost(0, $postId, $newOrder);
			
			if ($commentsResult !== FALSE) {
				if (count($commentsResult)) {
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
	 * action createComment
	 * 
	 * @return void
	 */
	public function createCommentAction() {
		$arg = array();		
		$success = TRUE;
		$this->initializeComment();
		$postId = $this->comment->getPost()->getUid();
		$args['postId'] = $postId;
		
		if ($this->request->hasArgument('duplicant1') && $this->request->hasArgument('duplicant2')) { // spam protection values, they can only match when form submitted via 'DatecBlog.createComment()'
			$duplicant1 = $this->request->getArgument('duplicant1');
			$duplicant2 = $this->request->getArgument('duplicant2');
		
			if ($duplicant1 == $duplicant2) {
				$commentCreator = $this->comment->getCommentCreator();
				
				if ($commentCreator != 0) { // is not backend user?
					if (!$commentCreator->getFeUser()) { // has no frontend user assigned? is public creator
						$commentCreatorResult = $this->commentCreatorRepository->findByCredentials($commentCreator->getEmail(), $commentCreator->getUsername());
												
						if (is_object($commentCreatorResult)) {
							// we know this public user, use the result
							$commentCreator = $commentCreatorResult;
						} else if (empty($commentCreatorResult)) {
						    // we don't know this public user, dont use the result, save a new
							$this->commentCreatorRepository->add($commentCreator);
							$this->persistenceManager->persistAll();
						} else {
							$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.errors.dbError',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
							$success = FALSE;
						}
					}					
					
					if ($commentCreator->isBlocked()) {
						// this badboy is not allowed here
						$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.messages.blogController.commentCreator.blocked_true',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING);
						$success = FALSE;
					}
				}				
				
				if ($this->request->hasArgument('files')) {
					$files = array();
					$tempFiles = $this->request->getArgument('files');
					if (count($tempFiles)) {
						foreach($tempFiles as $tempFile) {
							$tempFileName = $tempFile['name'];
							$tempFilePath = $tempFile['tmp_name'];
							if($tempFilePath) {
								$fileinfo = pathinfo($tempFileName);
						
								if(in_array(strtolower($fileinfo['extension']), explode(',', strtolower($this->settings['allowedFileTypes'])))) { // file type check
									if (filesize($tempFilePath) <= $this->settings['maxFileSize']) { // file size check
										$fileFunctions = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Utility\\File\\BasicFileUtility');
										$newFilePath = $fileFunctions->getUniqueName($tempFileName, \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('uploads/tx_datecblog/'));
										$move = \TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move($tempFilePath, $newFilePath); // move to our upload folder
										$newFileinfo = pathinfo($newFilePath);
										
										$fileName = $newFileinfo['basename'];
										$files[] = $fileName;
									} else {
										$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.messages.blogController.createComment_fileSize_false',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
										$success = FALSE;
										break;
									}
								} else {
									$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.messages.blogController.createComment_fileType_false',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
									$success = FALSE;
									break;
								}
							}
						}
					}
				}
				
				if ($success) { // no problems with the comment creator?				
					$this->comment->setCommentCreator($commentCreator);
					$this->comment->setFiles($files);
					
					$this->commentRepository->add($this->comment);

					$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.messages.blogController.createComment_success',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::OK);
				} else {
					$args['commentText'] = $this->comment->getText();
				}				
			} else {
				$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.messages.blogController.spamProtection_failed',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
			}
		} else {
			$this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_datecblog.messages.blogController.spamProtection_failed',$this->extKey), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		}
	
		$uriBuilder = $this->controllerContext->getUriBuilder();
		$uri = $uriBuilder // back to start, pass arguments
			->reset()
			->setArguments(array ('tx_datecblog_blog' => $args))			
			->setArgumentsToBeExcludedFromQueryString(array('' => 'cHash'))
			->build();
		$this->redirectToURI($uri);
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
	
	/**
	 * look for current backend user
	 *
	 * @return array $beUser or boolean FALSE if no user found
	 */
	private function getBeUser() {
		if(isset($GLOBALS['BE_USER'])) {
			return $GLOBALS['BE_USER']->user;
		}
	
		return FALSE;
	}
	
	/**
	 * initialize a new criteria object and fill it with form data when provided
	 *
	 * @return void
	 */
	private function initializeBloglistCriteria () {
		$this->bloglistCriteria = new \Datec\DatecBlog\Domain\Model\BloglistCriteria();	
		
		if ($this->request->hasArgument('bloglistCriteria')) {
			$formData = $this->request->getArgument('bloglistCriteria');
			if (count($formData['categories'])) {
				foreach($formData['categories'] as $categoryId) {
					$category = $this->categoryRepository->findByUid($categoryId);
					$this->bloglistCriteria->addCategory($category);
				}	
			}
			if (!empty($formData['archivePeriod']['from']) && !empty($formData['archivePeriod']['to'])) {
				$archivePeriod = new \Datec\DatecBlog\Domain\Model\ArchivePeriod();
				$from = new \DateTime();
				$to = new \DateTime();
				
				$from->setTimestamp($formData['archivePeriod']['from']);
				$to->setTimestamp($formData['archivePeriod']['to']);
				$archivePeriod->setFrom($from);
				$archivePeriod->setTo($to);
				$archivePeriod->setType($formData['archivePeriod']['type']);
								
				$this->bloglistCriteria->setArchivePeriod($archivePeriod);
			}
			if (count($formData['keywords'])) {
				foreach($formData['keywords'] as $keywordId) {
					$keyword = $this->keywordRepository->findByUid($keywordId);
					$this->bloglistCriteria->addKeyword($keyword);
				}
			}
		}
	}
	
	/**
	 * initialize a new comment object and fill it with form data when provided
	 *
	 * @return void
	 */
	private function initializeComment () {
		$this->comment = new \Datec\DatecBlog\Domain\Model\Comment();
		$commentCreator = new \Datec\DatecBlog\Domain\Model\CommentCreator();
		
		$this->comment->setPid($this->settings['commentsStoragePid']);
		$commentCreator->setPid($this->settings['commentsStoragePid']);
		
		if ($this->feUser) {
			$commentCreatorResult = $this->commentCreatorRepository->findByFeUser($this->feUser['uid']);
			
			if ($commentCreatorResult) {
				$commentCreator = $commentCreatorResult;
			} else if ($commentCreatorResult !== FALSE) {
				// none found use this fe user as new creator
				$feUserResult = $this->feUserRepository->findByUid($this->feUser['uid']);
				if ($feUserResult) {
					$commentCreator->setFeUser($feUserResult->getUid());
					$commentCreator->setEmail($feUserResult->getEmail());
					$commentCreator->setUsername($feUserResult->getUsername());
				}				
			} else {
				// db error, shouldn't happen
			}
		} else if ($this->beUser) {
			$commentCreator = 0;
			$this->comment->setCruserId($this->beUser['uid']);
		} else {
			// new user, all is well
		}		
		
		if ($this->request->hasArgument('comment')) {
			$formDataComment = $this->request->getArgument('comment');
			if (!empty($formDataComment['text'])) {
				$this->comment->setText($formDataComment['text']);
			}
			if (!empty($formDataComment['post'])) {
				$postResult = $this->postRepository->findByUid($formDataComment['post']);
				
				if ($postResult) {
					$this->comment->setPost($postResult);
				}				
			}
			if (!empty($formDataComment['parent'])) {
				$parentResult = $this->commentRepository->findByUid($formDataComment['parent']);
			
				if ($parentResult) {
					$this->comment->setParent($parentResult);
				}
			}
		}
		
		if ($this->request->hasArgument('commentText')) {
			$this->comment->setText($this->request->getArgument('commentText'));
		}
		
		if ($commentCreator != 0) {
			if ($this->request->hasArgument('commentCreator') && $commentCreator != 0) { // actually new comment creator data is coming in
				$formDataCommentCreator = $this->request->getArgument('commentCreator');
				if (!empty($formDataCommentCreator['email'])) {
					$commentCreator->setEmail($formDataCommentCreator['email']);
				}
				if (!empty($formDataCommentCreator['username'])) {
					$commentCreator->setUsername($formDataCommentCreator['username']);
				}
			}	
		}
		
		$this->comment->setCommentCreator($commentCreator);
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
	 * @param array $resultArr 
	 * @return \stdClass|boolean the pagination information Object or false if not supplied with valid array
	 */
	private function getPagination($resultArr) {
		if (is_array($resultArr)) {
			$pagination = new \stdClass();
			$pagination->itemsPerPage = (bool) $this->settings['display']['posts']['pagination']['itemsPerPage'] ? intval($this->settings['display']['posts']['pagination']['itemsPerPage'], 10) : 10;
			$pagination->maxPages = (bool) $this->settings['display']['posts']['pagination']['maxPages'] ? intval($this->settings['display']['posts']['pagination']['maxPages'], 10) : NULL;
			$pagination->top = (bool) $this->settings['display']['posts']['pagination']['top'];
			$pagination->bottom = (bool) $this->settings['display']['posts']['pagination']['bottom'];
			
			$pagination->resultsCount = count($resultArr);
				
			if ($this->request->hasArgument('paginationCurrent')) {
				$pagination->current = ($this->request->getArgument('paginationCurrent') != 0) ? intval($this->request->getArgument('paginationCurrent'), 10) : 1;
			} else {
				$pagination->current = 1;
			}		
			
			$pagination->pages = array_slice(array_chunk($resultArr, $pagination->itemsPerPage), 0, $pagination->maxPages, TRUE);
			$pagination->pagesCount = count($pagination->pages);
						
			$pagination->index = ($pagination->current - 1);
			if (array_key_exists($pagination->index, $pagination->pages)) {
				$pagination->countOnPage = count($pagination->pages[$pagination->index]);
			} else {
				return FALSE;
			}
					
			$pagination->previous = (($pagination->current - 1) > 0) ? ($pagination->current - 1) : FALSE;
			$pagination->first = $pagination->previous ? 1 : FALSE;
			$pagination->next = (($pagination->current + 1) <= $pagination->pagesCount) ? ($pagination->current + 1) : FALSE;
			$pagination->last = $pagination->next ? $pagination->pagesCount : FALSE;
			
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
?>