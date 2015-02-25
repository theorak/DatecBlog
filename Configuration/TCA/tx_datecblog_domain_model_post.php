<?php
return array(
	'ctrl' => array(
		'title' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_post',
		'label' => 'header',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => 1,
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'header,text,teaser_text,keywords,category',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('datec_blog') . 'Resources/Public/Icons/tx_datecblog_domain_model_post.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'header, text, image, teasertext, first_image_in_teaser, category, keywords, comments_disable, comments_fileupload',
	),
	'types' => array(
		'1' => array('showitem' => '
			--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.general;general,
            	header,
                text;LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_post.text;;richtext:rte_transform[flag=rte_enabled|mode=ts_css],                
				category,
				keywords,
            --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.images,
				images,
            --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,
            	hidden,
            	starttime;LLL:EXT:cms/locallang_ttc.xlf:starttime_formlabel, endtime;LLL:EXT:cms/locallang_ttc.xlf:endtime_formlabel,				
				comments_disable,
				comments_fileupload,
				comments_usergroups,
            --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.extended,
				teaser_text;LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_post.teaserText;;richtext:rte_transform[flag=rte_enabled|mode=ts_css],
				first_image_in_teaser,
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
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
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
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
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
		'header' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_post.header',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'text' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_post.text',
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 5,
				'eval' => 'required',	
			),
			'defaultExtras' => 'richtext[]:rte_transform[flag=rte_enabled|mode=ts_css]'
		),
		'images' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_post.images',
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
		        'images',
		        array(
		            'maxitems' => 5,
		            'minitems' => 0,
		            'appearance' => array(
		                'enabledControls' => array(
		                    'dragdrop' => FALSE,
		                    'localize' => FALSE,
		                ),
		            	'createNewRelationLinkTitle' => 'Dateien hinzufügen',
		            ),
		        )
			),
		),
		'teaser_text' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_post.teaserText',
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 3,
			),
			'defaultExtras' => 'richtext[]:rte_transform[flag=rte_enabled|mode=ts_css]'
		),
		'first_image_in_teaser' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_post.firstImageInTeaser',
			'config' => array(
				'type' => 'check',
			),
		),
		'category' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_post.category',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_datecblog_domain_model_category',
				'foreign_table_where' => ' ORDER BY tx_datecblog_domain_model_category.uid',
				'foreign_label' => 'name',
				'eval' => 'required',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'keywords' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_post.keywords',
			'config' => array(
				'type'          => 'group',
				'internal_type' => 'db',			
				'foreign_table' => 'tx_datecblog_domain_model_keyword',
				'allowed'       => 'tx_datecblog_domain_model_keyword',
				'MM'            => 'tx_datecblog_domain_model_post_keyword_mm',
				'size' => 10,
				'minitems' => 0,
				'maxitems' => 999,			
			),
		),
		'comments_disable' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_post.commentsDisable',
			'config' => array(
				'type' => 'check',
			),
		),
		'comments_fileupload' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_post.commentsFileupload',
			'config' => array(
				'type' => 'check',
				'default' => true,
			),
		),
		'comments_usergroups' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:datec_blog/Resources/Private/Language/locallang.xlf:tx_datecblog_domain_model_post.commentsUsergroups',
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