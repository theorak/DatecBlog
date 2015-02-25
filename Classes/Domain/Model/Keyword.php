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
class Keyword extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
		
	/**
	 * @var string
	 * @validate notEmpty
	 */
	protected $word;
	
	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Datec\DatecBlog\Domain\Model\Post>
	 */
	protected $posts;
	
	/**
	 * @var int
	 */
	protected $clickCount = 0;
	
	/**
	 * Contructs this object
	 */
	public function __construct() {
		$this->posts = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}
	
	/**
	 * @return string
	 */
	public function getWord() {
		return $this->word;
	}
	
	/**
	 * @param $word string
	 * @return void
	 */
	public function setWord($word) {
		$this->word = $word;
	}
	
	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Datec\DatecBlog\Domain\Model\Post>
	 */
	public function getPosts() {
		return $this->posts;
	}
	
	/**
	 * @param $posts \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Datec\DatecBlog\Domain\Model\Post>
	 * @return void
	 */
	public function setPosts($posts) {
		$this->posts = $posts;
	}
	
	/**
	 * @param \Datec\DatecBlog\Domain\Model\Post $post
	 * @return void
	 */
	public function addPost(\Datec\DatecBlog\Domain\Model\Post $post) {
		$this->posts->attach($post);
	}

	/**
	 * @param \Datec\DatecBlog\Domain\Model\Post $post
	 * @return void
	 */
	public function removePost(\Datec\DatecBlog\Domain\Model\Post $post) {
		$this->posts->detach($post);
	}
	
	/**
	 * @return int
	 */
	public function getClickCount() {
		return $this->clickCount;
	}
	
	/**
	 * @param $clickCount boolean
	 * @return void
	 */
	public function setClickCount($clickCount) {
		$this->clickCount = $clickCount;
	}
	
	/**
	 * @return void
	 */
	public function incrementClickCount() {
		$this->clickCount++;
	}
	
}
?>