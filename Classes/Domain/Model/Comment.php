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
class Comment extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
	
	/**
	 * @var \Datec\DatecBlog\Domain\Model\Post
	 * @validate notEmpty
	 */
	protected $post;
	
	/**
	 * @var \Datec\DatecBlog\Domain\Model\CommentCreator
	 */
	protected $commentCreator = 0;
	
	/**
	 * @var string
	 * @validate notEmpty
	 */
	protected $text;
	
	/**
	 * @var string
	 */
	protected $files;
	
	/**
	 * @var \Datec\DatecBlog\Domain\Model\Comment
	 */
	protected $parent;
	
	/**
	 * @var int
	 */
	protected $cruserId;	
	
	/**
	 * @var int
	 */
	protected $crdate;
	
	/**
	 * @return \Datec\DatecBlog\Domain\Model\Post
	 */
	public function getPost() {
		return $this->post;
	}
	
	/**
	 * @param $post \Datec\DatecBlog\Domain\Model\Post
	 * @return void
	 */
	public function setPost($post) {
		$this->post = $post;
	}
	
	/**
	 * @return \Datec\DatecBlog\Domain\Model\CommentCreator
	 */
	public function getCommentCreator() {
		return $this->commentCreator;
	}
	
	/**
	 * @param $commentCreator \Datec\DatecBlog\Domain\Model\CommentCreator
	 * @return boolean
	 */
	public function isCommentCreator($commentCreator) {
		return ($this->commentCreator == $commentCreator);
	}
	
	/**
	 * @param $commentCreator \Datec\DatecBlog\Domain\Model\CommentCreator
	 * @return void
	 */
	public function setCommentCreator($commentCreator) {
		$this->commentCreator = $commentCreator;
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
	 * @param array $files
	 * @return void
	 */
	public function setFiles($files) {
		$this->files = implode(',', $files);
	}
	
	/**
	 * @return array files
	 */
	public function getFiles() {
		return array_filter(explode(',', $this->files));
	}
	
	/**
	 * @return \Datec\DatecBlog\Domain\Model\Comment
	 */
	public function getParent() {
		return $this->parent;
	}
	
	/**
	 * @return boolean
	 */
	public function hasParent() {
		return (isset($this->parent));
	}
	
	/**
	 * @param $parent \Datec\DatecBlog\Domain\Model\Comment
	 * @return void
	 */
	public function setParent($parent) {
		$this->parent = $parent;
	}
	
	/**
	 * @return int
	 */
	public function getCruserId() {
		return $this->cruserId;
	}
	
	/**
	 * @param $cruserId int
	 * @return void
	 */
	public function setCruserId($cruserId) {
		$this->cruserId = $cruserId;
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
	
}
?>