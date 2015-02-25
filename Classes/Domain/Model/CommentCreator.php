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
class CommentCreator extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * @var int
	 */
	protected $feUser;
	
	/**
	 * @var string
	 */
	protected $username;
	
	/**
	 * @var string
	 */
	protected $email;
	
	/**
	 * @var boolean
	 */
	protected $blocked = FALSE;

	/**
	 * @return int
	 */
	public function getFeUser() {
		return $this->feUser;
	}
	
	/**
	 * @param $feUser int
	 * @return void
	 */
	public function setFeUser($feUser) {
		$this->feUser = $feUser;
	}
	
	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * @param $username string
	 * @return void
	 */
	public function setUsername($username) {
		$this->username = $username;
	}
	
	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * @param $email string
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = $email;
	}
	
	/**
	 * @return boolean
	 */
	public function isBlocked() {
		return $this->blocked;
	}
	
	/**
	 * @param $blocked boolean
	 * @return void
	 */
	public function setBlocked($blocked) {
		$this->blocked = $blocked;
	}
	
}
?>