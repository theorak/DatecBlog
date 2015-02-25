<?php
return array(
	'ctrl' => array(
		'title' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_category',		
		'label' => 'name',
		'sortby' => 'sorting',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => 1,
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'searchFields' => 'name,parent',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('datec_blog') . 'Resources/Public/Icons/tx_datecblog_domain_model_category.gif',
	),
	'interface' => array(
		'showRecordFieldList' => 'name, parent, sorting, usergroups',
	),
	'types' => array(
		'1' => array('showitem' => '
			--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.general;general,
				name,
				parent,
			--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,				
            	hidden,
				usergroups
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
		'name' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_category.name',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'parent' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_category.parent',
			'config' => array(
				'type' => 'select',
				'items' => array (
					array('',0),
				),
				'foreign_table' => 'tx_datecblog_domain_model_category',
				'foreign_table_where' => ' ORDER BY tx_datecblog_domain_model_category.uid',
				'foreign_label' => 'name',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			),
		),			
		'usergroups' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_category.usergroups',
			'config' => array(
				'type' => 'select',
				'size' => 6,
				'maxitems' => 20,
				'items' => array(
					array(
						'LLL:EXT:lang/locallang_general.xlf:LGL.hide_at_login',
						-1
					),
					array(
						'LLL:EXT:lang/locallang_general.xlf:LGL.any_login',
						-2
					),
					array(
						'LLL:EXT:lang/locallang_general.xlf:LGL.usergroups',
						'--div--'
					)
				),
				'exclusiveKeys' => '-1,-2',
				'foreign_table' => 'fe_groups',
				'foreign_table_where' => 'ORDER BY fe_groups.title'
			),
		),
	),
);

?>