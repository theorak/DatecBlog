<?php
if(!defined('TYPO3_MODE')){
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Datec Blog');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Datec.' . $_EXTKEY,
	'Blog',
	'Datec Blog'
);

$pluginSignature = 'datecblog_blog';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:'.$_EXTKEY.'/Configuration/FlexForms/ControllerSelection.xml');

if (TYPO3_MODE == 'BE') {
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
			'Datec.' . $_EXTKEY,
			'web',
			'DatecBlogM1',
			'', # Position
			array(
					'BlogmanagementModule' => 'overview, listComments, showBlog, blockCommentary, deleteCommentary, showSinglePost, listPosts, listBlogComments'), # Controller array
			array(
					'access' => 'user,group',
					'icon'   => 'EXT:' . $_EXTKEY . '/Resources/Public/Images/Backend/Icons/DatecBlogM1.png',
					'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xlf',
			)
			);
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_datecblog_domain_model_post');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_datecblog_domain_model_category');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_datecblog_domain_model_keyword');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_datecblog_domain_model_comment');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_datecblog_domain_model_commentcreator');

?>