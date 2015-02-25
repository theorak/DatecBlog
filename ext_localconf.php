<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$extPath    = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY);
$extRelPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Datec.' . $_EXTKEY,
	'Blog',
	array(
		'Blog' => 'showBlog,listPosts,showSinglePost,listComments,createComment',
		'Categories' => 'listCategories',
		'Archive' => 'showArchive',
		'Keywords' => 'listKeywords,updateClickCount',	
	),
	// non-cacheable actions
	array(
		'Blog' => 'showBlog,listPosts,showSinglePost,listComments,createComment',
		'Categories' => 'listCategories',
		'Archive' => 'showArchive',
		'Keywords' => 'listKeywords,updateClickCount',
	)
);


?>