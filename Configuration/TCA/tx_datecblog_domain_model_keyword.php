<?php
return array(
	'ctrl' => array(
		'title' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_keyword',
		'label' => 'word',
		'sortby' => 'sorting',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => 1,
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'searchFields' => 'word,posts',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('datec_blog') . 'Resources/Public/Icons/tx_datecblog_domain_model_keyword.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'word, click_count, posts, sorting',
	),
	'types' => array(
		'1' => array('showitem' => '
			--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.general;general,
				hidden,
            	word,
				posts,
				click_count
		'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'word' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_keyword.word',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'nospace,unique,required'
			),
		),
		'posts' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_keyword.posts',
			'config' => array(
				'type'          => 'group',
				'internal_type' => 'db',			
				'foreign_table' => 'tx_datecblog_domain_model_post',
				'allowed'       => 'tx_datecblog_domain_model_post',
				'MM'            => 'tx_datecblog_domain_model_post_keyword_mm',
				'MM_opposite_field'	=> 'keywords',
				'size' => 7,
				'minitems' => 0,
				'maxitems' => 999,			
			),
		),
		'click_count' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_keyword.clickCount',
			'config' => array(
				'type' => 'input',
				'size' => 15,
				'eval' => 'num',
			),
			'readonly' => 1,
		),
			
	),
);

?>