<?php
/**
 * Plugin Name:       Breedio Foal Coat
 * Plugin URI:        https://www.breedio.xyz/en/tools/foal-color-calculator
 * Description:       Let visitors predict the coat of a foal from your stallions, right on your stud website.
 * Version:           0.1.0
 * Requires at least: 6.5
 * Requires PHP:      7.4
 * Author:            Breedio
 * Author URI:        https://www.breedio.xyz
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       breedio-foal-coat
 */

namespace Breedio\FoalCoat;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const VERSION      = '0.1.0';
const STALLION     = 'breedio_stallion';
const COAT_META    = '_breedio_foal_coat';
const LINK_META    = '_breedio_foal_coat_stallion';
const SHORTCODE    = 'breedio_foal_coat';
const FRAME_SCRIPT = 'breedio-foal-coat-frame';
const SETTINGS     = 'breedio_foal_coat_settings';
const CREDIT_ASKED = 'breedio_foal_coat_credit_asked';

define( __NAMESPACE__ . '\FILE', __FILE__ );

require_once __DIR__ . '/includes/genetics.php';
require_once __DIR__ . '/includes/stallion.php';
require_once __DIR__ . '/includes/page-link.php';
require_once __DIR__ . '/includes/settings.php';
require_once __DIR__ . '/includes/render.php';
require_once __DIR__ . '/includes/credit.php';
require_once __DIR__ . '/includes/block.php';
