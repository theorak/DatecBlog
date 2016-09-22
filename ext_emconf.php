<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "datec_blog"
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Datec Blog',
	'description' => 'Adds a lightweight Blog to your Typo3 Site.',
	'category' => 'plugin',
	'author' => 'Philipp Roensch',
	'author_email' => 'p.roensch@datec-schmidt.de',
	'author_company' => 'Datentechnik Schmidt Software GmbH',
	'shy' => 0,
	'priority' => '',
	'module' => 'DatecBlogM1',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 'uploads/tx_datecblog/',
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '1.0.5',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.2.0-7.99.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
);

?>