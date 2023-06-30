<?php
/**
 *
 * GeneratePress child theme functions and definitions.
 *
 * @package iwar-theme
 * @author Danny Schmarsel <ds@lichtmetzger.de>
 */

// Include PSR4-compatible autoloader.
require_once dirname( __FILE__ ) . '/vendor/autoload.php';

// Initialize theme.
use IwarTheme\Main;
$main = new Main();
$main->initialize();
