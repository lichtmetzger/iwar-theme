<?php
/**
 * GeneratePress child theme functions and definitions.
 *
 * @package iwar-theme
 * @author Danny Schmarsel <ds@lichtmetzger.de>
 */

require_once dirname( __FILE__ ) . '/vendor/autoload.php';

// Initialize wpcs-compatible autoloader.
use Pablo_Pacheco\WP_Namespace_Autoloader\WP_Namespace_Autoloader;
$autoloader = new WP_Namespace_Autoloader(
	array(
		'directory'         => __DIR__,       // Directory of the project.
		'namespace_prefix'  => 'IwarTheme',   // Main namespace of the project.
		'classes_dir'       => 'src',         // It is where your namespaced classes are located inside your project.
		'prepend_class'     => true,          // Prepends class- before the final class name.
		'prepend_interface' => true,          // Prepends interface- before the final interface name.
		'prepend_trait'     => true,          // Prepends trait- before the final trait name.
	)
);
$autoloader->init();

// Initialize theme.
use IwarTheme\Main;
$main = new Main();
$main->initialize();
