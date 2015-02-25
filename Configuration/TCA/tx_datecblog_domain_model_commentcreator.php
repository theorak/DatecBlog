<?php
return array(
	'ctrl' => array(
		'title' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_commentcreator',
		'label' => 'username',
		'label_alt' => 'fe_user,email,cruser_id',	
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'searchFields' => 'fe_user,username,email',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('datec_blog') . 'Resources/Public/Icons/tx_datecblog_domain_model_commentcreator.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'fe_user, username, email, blocked',
	),
	'types' => array(
		'1' => array('showitem' => '
			--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.general;general,
            	hidden,
				fe_user,
				username,
				email,
				blocked
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
		'fe_user' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_commentcreator.feUser',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('',0),
				),
				'foreign_table' => 'fe_users',
				'foreign_table_where' => ' ORDER BY fe_users.uid',
				'foreign_field' => 'uid',
				'foreign_label' => 'username,last_name,first_name,email',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'username' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_commentcreator.username',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'email' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_commentcreator.email',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'blocked' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_commentcreator.blocked',
			'config' => array(
				'type' => 'check',
			),
		),
	),
);

?>