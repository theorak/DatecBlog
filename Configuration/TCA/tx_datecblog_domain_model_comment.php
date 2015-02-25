<?php
return array(
	'ctrl' => array(
		'title' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_comment',
		'label' => 'text',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'searchFields' => 'comment_creator,text,parent',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('datec_blog') . 'Resources/Public/Icons/tx_datecblog_domain_model_comment.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'comment_creator, text, parent',
	),
	'types' => array(
		'1' => array('showitem' => '
			--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.general;general,
            	hidden,
				post,
				comment_creator,
				text,
				files,
				parent
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
		'cruser_id' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_comment.cruserId',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('',0),
				),
				'foreign_table' => 'be_users',
				'foreign_table_where' => ' ORDER BY be_users.uid',
				'foreign_label' => 'username,email',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'crdate' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.crdate',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'post' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_comment.post',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_datecblog_domain_model_post',
				'foreign_table_where' => ' ORDER BY tx_datecblog_domain_model_post.uid',
				'foreign_field' => 'uid',
				'foreign_label' => 'uid',
				'eval' => 'required',
				'size' => 1,
				'minitems' => 1,
				'maxitems' => 1,
			),
		),
		'comment_creator' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_comment.commentCreator',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('',0),
				),
				'foreign_table' => 'tx_datecblog_domain_model_commentcreator',
				'foreign_table_where' => ' ORDER BY tx_datecblog_domain_model_commentcreator.uid',
				'foreign_label' => 'username,fe_user',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'text' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_comment.text',
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 5,
			),
			'defaultExtras' => 'richtext[]:rte_transform[flag=rte_enabled|mode=ts_css]'
		),			
		'files' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_comment.files',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => '',
				'disallowed' => 'php,php3',
				'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],
				'uploadfolder' => 'uploads/tx_datecblog',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 10,
			),
		),
		'parent' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_comment.parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('',0),
				),	
				'foreign_table' => 'tx_datecblog_domain_model_comment',
				'foreign_table_where' => ' ORDER BY tx_datecblog_domain_model_comment.uid',
				'foreign_label' => 'text',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			),
		),					
	),
);

?>