<?php
/**
 * Plugin Name: Video CPT
 * Plugin URI: https://gabized.com/cv.html/
 * Description: Simple plugin to embed videos from Youtube, Vimeo and Dailymotion
 * Version: 0.4
 * Author: Gabi Zaharia
 * Author URI: https://gabized.com
 * License: GPL2
 * Text Domain: videocpt
 * Domain Path: /languages
 */

/*  Copyright 2017  Gabized  (email : me@gabized.com)
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

define( 'VCPT_PATH', plugin_dir_url( __FILE__ ) );

include('inc/class.video_cpt.php'); //main plugin file, all functions are here except the shortcode button for the editor.

include('inc/class.video_cpt_but.php'); //shortcode button for the TinyMCE editor
