<?php
defined('BASEPATH') or exit('No direct script access allowed');

//Example :
// $config['assets_select2'] = [
//     'css' => array(
//         'asset/plugins/select2/css/select2.min.css',
//         'asset/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css'
//     ),
//     'js' => array(
//         'asset/plugins/select2/js/select2.full.min.js'
//     )
// ];


$config['assets_datatables'] = [
	'css' => array(
		PLUGINS . '/custom/datatables/datatables.bundle.css',
	),
	'js' => array(
		PLUGINS . '/custom/datatables/datatables.bundle.js',
	)
];

$config['assets_repeater'] = [
	'js' => array(
		PLUGINS . '/custom/formrepeater/formrepeater.bundle.js',
	)
];

$config['assets_repeater2'] = [
	'js' => array(
		PLUGINS . '/custom/formrepeater/formrepeater2.bundle.js',
	)
];

$config['assets_xlsx'] = [
	'js' => array(
		PLUGINS . '/custom/xlsx/xlsx.min.js',
	)
];

$config['assets_ckeditor'] = [
	'js' => array(
		PLUGINS . '/custom/ckeditor/ckeditor-classic.bundle.js',
	)
];


// $config['assets_jstree'] = [
// 	'css' => array(
// 		PLUGINS . '/custom/jstree/jstree.bundle.css',
// 	),
// 	'js' => array(
// 		PLUGINS . '/custom/jstree/jstree.bundle.js',
// 	)
// ];

// $config['assets_jkanban'] = [
// 	'css' => array(
// 		PLUGINS . '/custom/jkanban/jkanban.bundle.css',
// 	),
// 	'js' => array(
// 		PLUGINS . '/custom/jkanban/jkanban.bundle.js',
// 	)
// ];

$config['assets_repeater3'] = [
	'js' => array(
		PLUGINS . '/custom/formrepeater/formrepeater3.bundle.js',
	)
];
