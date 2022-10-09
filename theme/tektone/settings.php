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
 * @package   theme_tektone
 * @copyright 2022 Amit Singh (web.amitsingh@gmail.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

// This is used for performance, we don't need to know about these settings on every page in Moodle, only when
// we are looking at the admin settings pages.
if ($ADMIN->fulltree) {

    // Tektone provides a nice setting page which splits settings onto separate tabs. We want to use it here.
    $settings = new theme_boost_admin_settingspage_tabs('themesettingtektone', get_string('configtitle', 'theme_tektone'));

    // Each page is a tab - the first is the "General" tab.
    $page = new admin_settingpage('theme_tektone_general', get_string('generalsettings', 'theme_tektone'));
    
    $name = 'theme_tektone/logo';
    $title = get_string('logo', 'theme_tektone');
    $description = get_string('logodesc', 'theme_tektone');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_tektone_update_settings_images');
    $page->add($setting);

    $name = 'theme_tektone/loginpageimg';
    $title = get_string('loginpageimg', 'theme_tektone');
    $description = get_string('loginpageimg_desc', 'theme_tektone');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginpageimg');
    $setting->set_updatedcallback('theme_tektone_update_settings_images');
    $page->add($setting);

    $setting = new admin_setting_confightmleditor('theme_tektone/companyinfo', get_string('companyinfo', 'theme_tektone'),
    get_string('companyinfo_desc', 'theme_tektone'), get_string('companyinfo_default', 'theme_tektone'), PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_tektone/headerbg';
    $title = get_string('headerbg', 'theme_tektone');
    $description = get_string('headerbg_desc', 'theme_tektone');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#faefef');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_tektone/loginbg';
    $title = get_string('loginbg', 'theme_tektone');
    $description = get_string('loginbg_desc', 'theme_tektone');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#f5f5f5');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_tektone/blockbg';
    $title = get_string('blockbg', 'theme_tektone');
    $description = get_string('blockbg_desc', 'theme_tektone');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#e5e5e5');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_tektone/primarybtnbg';
    $title = get_string('primarybtnbg', 'theme_tektone');
    $description = get_string('primarybtnbg_desc', 'theme_tektone');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#06a1ac');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Must add the page after defining all the settings!
    $settings->add($page);
    
    // Advanced settings.
    $page = new admin_settingpage('theme_tektone_advanced', get_string('advancedsettings', 'theme_boost'));

    // Raw SCSS to include before the content.
    $setting = new admin_setting_scsscode('theme_tektone/scsspre',
        get_string('rawscsspre', 'theme_boost'), get_string('rawscsspre_desc', 'theme_boost'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS to include after the content.
    $setting = new admin_setting_scsscode('theme_tektone/scss', get_string('rawscss', 'theme_boost'),
        get_string('rawscss_desc', 'theme_boost'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $settings->add($page);
}
