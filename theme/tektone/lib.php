<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Photo backgrounds callbacks.
 *
 * @package    theme_tektone
 * @copyright  2022 Amit Singh (web.amitsingh@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string All fixed Sass for this theme.
 */
function theme_tektone_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';

    $fs = get_file_storage();

    // Main CSS - Get the CSS from theme Classic.
    $scss .= file_get_contents($CFG->dirroot . '/theme/classic/scss/classic/pre.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/classic/scss/preset/default.scss');
    $responsivescss = file_get_contents($CFG->dirroot . '/theme/tektone/scss/responsive.scss');

    // Pre CSS - this is loaded AFTER any prescss from the setting but before the main scss.
    $pre = file_get_contents($CFG->dirroot . '/theme/tektone/scss/pre.scss');

    // Post CSS - this is loaded AFTER the main scss but before the extra scss from the setting.
    $post = file_get_contents($CFG->dirroot . '/theme/tektone/scss/post.scss');

    // Combine them together.
    return $pre . "\n" . $scss . "\n" . $post . "\n" . $responsivescss;
}

/**
 * Returns variables for Sass.
 *
 * We will inject some Sass variables from the settings that the user has defined
 * for the theme.
 *
 * @param theme_config $theme The theme config object.
 * @return String with Sass variables.
 */
function theme_tektone_get_pre_scss($theme) {
    $scss = '';
    $configurable = [                                                                                                                
        'headerbg' => ['header-bg'],
        'loginbg' => ['login-bg'],
        'blockbg' => ['block-bg'],
        'primarybtnbg' => ['primarybtn-bg'],
    ];                                                                                                                                    
    // Prepend variables first.                                                                                                     
    foreach ($configurable as $configkey => $targets) {                                                                             
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;                                     
        if (empty($value)) {                                                                                                        
            continue;                                                                                                               
        }                                                                                                                           
        array_map(function($target) use (&$scss, $value) {                                                                          
            $scss .= '$' . $target . ': ' . $value . ";\n";                                                                         
        }, (array) $targets);                                                                                                       
    }
    // Prepend pre-scss.
    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }
    return $scss;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_tektone_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM && ($filearea === 'logo' || $filearea === 'loginpageimg')) {
        $theme = theme_config::load('tektone');
        // By default, theme files must be cache-able by both browsers and proxies.
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}

/**
 *
 * Description
 * @param type|string $setting
 * @param type|bool $format
 * @return type|string
 */
function theme_tektone_get_setting($setting, $format = false) {
    global $CFG;
    require_once($CFG->dirroot . '/lib/weblib.php');
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('tektone');
    }
    if (empty($theme->settings->$setting)) {
        return false;
    } else if (!$format) {
        return $theme->settings->$setting;
    } else if ($format === 'format_text') {
        return format_text($theme->settings->$setting, FORMAT_PLAIN);
    } else if ($format === 'format_html') {
        return format_text($theme->settings->$setting, FORMAT_HTML, array('trusted' => true, 'noclean' => true));
    } else {
        return format_string($theme->settings->$setting);
    }
}

// Logo Image URL Fetch from theme settings.
// @ return string.
if (!function_exists('get_logo_url')) {
    /**
     * Description
     * @return type|string
     */
    function get_logo_url() {
        global $OUTPUT;
        static $theme;
        if (empty($theme)) {
            $theme = theme_config::load('tektone');
        }
        $logo = $theme->setting_file_url('logo', 'logo');
        $logo = empty($logo) ? $OUTPUT->image_url('tektone_logo', 'theme') : $logo;
        return $logo;
    }
}

// Loginimage Image URL Fetch from theme settings.
// @ return string.
if (!function_exists('get_loginimage_url')) {
    /**
     * Description
     * @return type|string
     */
    function get_loginimage_url() {
        global $OUTPUT;
        static $theme;
        if (empty($theme)) {
            $theme = theme_config::load('tektone');
        }
        $loginpageimg = $theme->setting_file_url('loginpageimg', 'loginpageimg');
        $loginpageimg = empty($loginpageimg) ? $OUTPUT->image_url('login-bg', 'theme') : $loginpageimg;
        return $loginpageimg;
    }
}