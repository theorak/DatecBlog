<?php
namespace Datec\DatecBlog\Domain\Model;

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
class Post extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
	
	/**
	 * @var string
	 * @validate notEmpty
	 */
	protected $header;
	
	/**
	 * @var string
	 * @validate notEmpty
	 */
	protected $text;
	
	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
	 */
	protected $images;
	
	/**
	 * @var string
	 */
	protected $teaserText;
	
	/**
	 * @var boolean
	 */
	protected $firstImageInTeaser = FALSE;
	
	/**
	 * @var \Datec\DatecBlog\Domain\Model\Category
	 */
	protected $category;
	
	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Datec\DatecBlog\Domain\Model\Keyword>
	 */
	protected $keywords;
	
	/**
	 * @var boolean
	 */
	protected $commentsDisable = FALSE;
	
	/**
	 * @var boolean
	 */
	protected $commentsFileupload = TRUE;
	
	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup>
	 */
	protected $commentsUsergroups;
	
	/**
	 * @var int
	 */
	protected $crdate;
	
	/**
	 * @var int
	 */
	protected $starttime;
	
	/**
	 * @var int
	 */
	protected $endtime;
	
	/**
	 * Contructs this object
	 */
	public function __construct() {
		$this->keywords = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->commentsUsergroups = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}
	
	/**
	 * @return string
	 */
	public function getHeader() {
		return $this->header;
	}
	
	/**
	 * @param $header string
	 * @return void
	 */
	public function setHeader($header) {
		$this->header = $header;
	}
	
	/**
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}
	
	/**
	 * @param $text string
	 * @return void
	 */
	public function setText($text) {
		$this->text = $text;
	}
	
	/**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $images
     */
    public function getImages() {
		return $this->images;
    }
 
    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $images
     * @return void
     */
    public function setImages($images) {
		$this->images = $images;
    }
	
	/**
	 * @return string
	 */
	public function getTeaserText() {
		return $this->teaserText;
	}
	
	/**
	 * @param $teaserText string
	 */
	public function setTeaserText($teaserText) {
		$this->teaserText = $teaserText;
	}
	
	/**
	 * @return boolean
	 */
	public function isFirstImageInTeaser() {
		return $this->firstImageInTeaser;
	}
	
	/**
	 * @param $firstImageInTeaser boolean
	 * @return void
	 */
	public function setFirstImageInTeaser($firstImageInTeaser) {
		$this->firstImageInTeaser = $firstImageInTeaser;
	}
	
	/**
	 * @return \Datec\DatecBlog\Domain\Model\Category
	 */
	public function getCategory() {
		return $this->category;
	}
	
	/**
	 * @param $category \Datec\DatecBlog\Domain\Model\Category
	 * @return void
	 */
	public function setCategory($category) {
		$this->category = $category;
	}
	
	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Datec\DatecBlog\Domain\Model\Keyword>
	 */
	public function getKeywords() {
		return $this->keywords;
	}
	
	/**
	 * @param $keywords \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Datec\DatecBlog\Domain\Model\Keyword>
	 * @return void
	 */
	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}
	
	/**
	 * @param \Datec\DatecBlog\Domain\Model\Keyword $keyword
	 * @return void
	 */
	public function addKeyword(\Datec\DatecBlog\Domain\Model\Keyword $keyword) {
		$this->keywords->attach($keyword);
	}

	/**
	 * @param \Datec\DatecBlog\Domain\Model\Keyword $keyword
	 * @return void
	 */
	public function removeKeyword(\Datec\DatecBlog\Domain\Model\Keyword $keyword) {
		$this->keywords->detach($keyword);
	}
	
	/**
	 * @return boolean
	 */
	public function isCommentsDisable() {
		return $this->commentsDisable;
	}
	
	/**
	 * @param $commentsDisable boolean
	 * @return void
	 */
	public function setCommentsDisable($commentsDisable) {
		$this->commentsDisable = $commentsDisable;
	}
	
	/**
	 * @return boolean
	 */
	public function isCommentsFileupload() {
		return $this->commentsFileupload;
	}
	
	/**
	 * @param $commentsFileupload boolean
	 * @return void
	 */
	public function setCommentsFileupload($commentsFileupload) {
		$this->commentsFileupload = $commentsFileupload;
	}
	
	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $commentsUsergroups
	 * @return void
	 */
	public function setCommentsCommentsUsergroups(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $commentsUsergroups) {
		$this->commentsUsergroups = $commentsUsergroups;
	}
	
	/**
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $commentsUsergroup
	 * @return void
	 */
	public function addCommentsUsergroup(\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $commentsUsergroup) {
		$this->commentsUsergroups->attach($commentsUsergroup);
	}
	
	/**
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $commentsUsergroup
	 * @return void
	 */
	public function removeCommentsUsergroup(\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $commentsUsergroup) {
		$this->commentsUsergroups->detach($commentsUsergroup);
	}
	
	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage An object storage containing the commentsUsergroups
	 */
	public function getCommentsUsergroups() {
		return $this->commentsUsergroups;
	}
	
	/**
	 * @return int
	 */
	public function getCrdate() {
		return $this->crdate;
	}
	
	/**
	 * @param int $crdate
	 * @return void
	 */
	public function setCrdate($crdate) {
		$this->crdate = $crdate;
	}
	
	/**
	 * @return int
	 */
	public function getStarttime() {
		return $this->starttime;
	}
	
	/**
	 * @param int $starttime
	 * @return void
	 */
	public function setStarttime($starttime) {
		$this->starttime = $starttime;
	}
	
	/**
	 * @return int
	 */
	public function getEndtime() {
		return $this->endtime;
	}

	/**
	 * @param int $endtime
	 * @return void
	 */
	public function setEndtime($endtime) {
		$this->endtime = $endtime;
	}
	
}
?>