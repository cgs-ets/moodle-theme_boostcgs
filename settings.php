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
 * @package   theme_boostcgs
 * @copyright 2019 Michael Vangelovski, Canberra Grammar School <michael.vangelovski@cgs.act.edu.au>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

// This is used for performance, we don't need to know about these settings on every page in Moodle, only when
// we are looking at the admin settings pages.
if ($ADMIN->fulltree) {

    // Boost provides a nice setting page which splits settings onto separate tabs. We want to use it here.
    $settings = new theme_boost_admin_settingspage_tabs('themesettingboostcgs', get_string('configtitle', 'theme_boostcgs'));

    // Each page is a tab - the first is the "General" tab.
    $page = new admin_settingpage('theme_boostcgs_general', get_string('generalsettings', 'theme_boostcgs'));

    // Replicate the preset setting from boost.
    $name = 'theme_boostcgs/preset';
    $title = get_string('preset', 'theme_boostcgs');
    $description = get_string('preset_desc', 'theme_boostcgs');
    $default = 'default.scss';

    // We list files in our own file area to add to the drop down. We will provide our own function to
    // load all the presets from the correct paths.
    $context = context_system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'theme_boostcgs', 'preset', 0, 'itemid, filepath, filename', false);

    $choices = [];
    foreach ($files as $file) {
        $choices[$file->get_filename()] = $file->get_filename();
    }
    // These are the built in presets from Boost.
    $choices['default.scss'] = 'default.scss';
    $choices['plain.scss'] = 'plain.scss';

    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Preset files setting.
    $name = 'theme_boostcgs/presetfiles';
    $title = get_string('presetfiles', 'theme_boostcgs');
    $description = get_string('presetfiles_desc', 'theme_boostcgs');

    $setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0,
        array('maxfiles' => 20, 'accepted_types' => array('.scss')));
    $page->add($setting);

    // Variable $brand-color.
    // We use an empty default value because the default colour should come from the preset.
    $name = 'theme_boostcgs/brandcolor';
    $title = get_string('brandcolor', 'theme_boostcgs');
    $description = get_string('brandcolor_desc', 'theme_boostcgs');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');

    $page->add($setting);



    // Login page background setting.
    // We use variables for readability.
    $name = 'theme_boostcgs/loginbackgroundimage';
    $title = get_string('loginbackgroundimage', 'theme_boostcgs');
    $description = get_string('loginbackgroundimage_desc', 'theme_boostcgs');
    // This creates the new setting.
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbackgroundimage');
    // This means that theme caches will automatically be cleared when this setting is changed.
    $setting->set_updatedcallback('theme_boostcgs_update_settings_images');
    // We always have to add the setting to a page for it to have any effect.
    $page->add($setting);



    // Must add the page after definiting all the settings!
    $settings->add($page);

    // Advanced settings.
    $page = new admin_settingpage('theme_boostcgs_advanced', get_string('advancedsettings', 'theme_boostcgs'));

    // Raw SCSS to include before the content.
    $setting = new admin_setting_configtextarea('theme_boostcgs/scsspre',
        get_string('rawscsspre', 'theme_boostcgs'), get_string('rawscsspre_desc', 'theme_boostcgs'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS to include after the content.
    $setting = new admin_setting_configtextarea('theme_boostcgs/scss', get_string('rawscss', 'theme_boostcgs'),
        get_string('rawscss_desc', 'theme_boostcgs'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Custom footer HTML.
    $setting = new admin_setting_configtextarea('theme_boostcgs/footerhtml', get_string('footerhtml', 'theme_boostcgs'),
        get_string('footerhtml_desc', 'theme_boostcgs'), '', PARAM_RAW);
    $page->add($setting);

    // Student Dashboard URL Setting.
    //Senior
    $setting = new admin_setting_configtext('theme_boostcgs/studentdashboardurlsenior', get_string('studentdashboardsenior', 'theme_boostcgs'),
            get_string('studentdashboard_desc', 'theme_boostcgs'), '', PARAM_RAW);
    $page->add($setting);
    // Primary
    $setting = new admin_setting_configtext('theme_boostcgs/studentdashboardurlprimary', get_string('studentdashboardprimary', 'theme_boostcgs'),
            get_string('studentdashboard_desc', 'theme_boostcgs'), '', PARAM_RAW);
    $page->add($setting);
    

    // Server environment. default production.
    $name = 'theme_boostcgs/environment';
    $title = get_string('environment', 'theme_boostcgs');
    $desc = get_string('environment_desc', 'theme_boostcgs');
    $setting = new admin_setting_configtext($name, $title, '', 'production', PARAM_RAW);
    $page->add($setting);

    // Show environment bar.
    $name = 'theme_boostcgs/showenvbar';
    $title = get_string('showenvbar', 'theme_boostcgs');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $page->add($setting);

    // Server environment color. default is nothing which means no overlay.
    $name = 'theme_boostcgs/environmentcolor';
    $title = get_string('environmentcolor', 'theme_boostcgs');
    $desc = get_string('environmentcolor_desc', 'theme_boostcgs');
    $setting = new admin_setting_configtext($name, $title, $desc, '', PARAM_RAW);
    $page->add($setting);

    $settings->add($page);
}