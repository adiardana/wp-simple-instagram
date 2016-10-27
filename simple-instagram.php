<?php
/*
Plugin Name: Simple Instagram
Description: Simple Instagram
Plugin URI: http://adiardana.github.io
Author: Adi Ardana
Author URI: http://adiardana.github.io
Version: 1.0
License: GPL2
Text Domain: simple-instagram
Domain Path: Domain Path
*/

/*

    Copyright (C) Year  Adi Ardana  adhixz@gmail.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

define('SIG_FILE', plugin_dir_path( __FILE__ ));
define('SIG_URL', plugin_dir_url( __FILE__ ));

function activate_simple_instagram()
{
    require_once SIG_FILE.'includes/plugin-activator.php';

    Simple_Instagram_Activator::activate();
}

function deactivate_simple_instagram()
{
    require_once SIG_FILE.'includes/plugin-deactivator.php';

    Simple_Instagram_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_simple_instagram' );
register_deactivation_hook( __FILE__, 'deactivate_simple_instagram' );

require_once SIG_FILE.'vendor/autoload.php';
require_once SIG_FILE.'includes/instagram.php';

$ig = new SimpleInstagram();