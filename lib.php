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
 * @copyright 2020 Michael Vangelovski, Canberra Grammar School
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

function theme_boostcgs_get_main_scss_content($theme) {                                                                                
    global $CFG;                                                                                                                    
 
    $scss = '';                                                                                                                     
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;                                                 
    $fs = get_file_storage();                                                                                                       
 
    $context = context_system::instance();                                                                                          
    if ($filename == 'default.scss') {                                                                                              
        // We still load the default preset files directly from the boost theme. No sense in duplicating them.                      
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');                                        
    } else if ($filename == 'plain.scss') {                                                                                         
        // We still load the default preset files directly from the boost theme. No sense in duplicating them.                      
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');                                          
 
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_boostcgs', 'preset', 0, '/', $filename))) {              
        // This preset file was fetched from the file area for theme_boostcgs and not theme_boost (see the line above).                
        $scss .= $presetfile->get_content();                                                                                        
    } else {                                                                                                                        
        // Safety fallback - maybe new installs etc.                                                                                
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');                                        
    }                                                                                                                                       
 
    // Pre CSS - this is loaded AFTER any prescss from the setting but before the main scss.
    $variables = file_get_contents($CFG->dirroot . '/theme/boostcgs/scss/variables.scss');
    $pre = file_get_contents($CFG->dirroot . '/theme/boostcgs/scss/pre.scss');                                                         
    // Post CSS - this is loaded AFTER the main scss but before the extra scss from the setting.
    $post = file_get_contents($CFG->dirroot . '/theme/boostcgs/scss/post.scss');                                                       
 
    // Combine them together.                                                                                                       
    return $variables . "\n" . $pre . "\n" . $scss . "\n" . $post;                                                                                                                   
}

function theme_boostcgs_update_settings_images($settingname) {                                                                         
    global $CFG;                                                                                                                    
 
    // The setting name that was updated comes as a string like 's_theme_boostcgs_loginbackgroundimage'.                               
    // We split it on '_' characters.                                                                                               
    $parts = explode('_', $settingname);                                                                                            
    // And get the last one to get the setting name..                                                                               
    $settingname = end($parts);                                                                                                     
 
    // Admin settings are stored in system context.                                                                                 
    $syscontext = context_system::instance();                                                                                       
    // This is the component name the setting is stored in.                                                                         
    $component = 'theme_boostcgs';                                                                                                     
 
    // This is the value of the admin setting which is the filename of the uploaded file.                                           
    $filename = get_config($component, $settingname);                                                                               
    // We extract the file extension because we want to preserve it.                                                                
    $extension = substr($filename, strrpos($filename, '.') + 1);                                                                    
 
    // This is the path in the moodle internal file system.                                                                         
    $fullpath = "/{$syscontext->id}/{$component}/{$settingname}/0{$filename}";                                                      
    // Get an instance of the moodle file storage.                                                                                  
    $fs = get_file_storage();                                                                                                       
    // This is an efficient way to get a file if we know the exact path.                                                            
    if ($file = $fs->get_file_by_hash(sha1($fullpath))) {                                                                           
        // We got the stored file - copy it to dataroot.                                                                            
        // This location matches the searched for location in theme_config::resolve_image_location.                                 
        $pathname = $CFG->dataroot . '/pix_plugins/theme/boostcgs/' . $settingname . '.' . $extension;                                 
 
        // This pattern matches any previous files with maybe different file extensions.                                            
        $pathpattern = $CFG->dataroot . '/pix_plugins/theme/boostcgs/' . $settingname . '.*';                                          
 
        // Make sure this dir exists.                                                                                               
        @mkdir($CFG->dataroot . '/pix_plugins/theme/boostcgs/', $CFG->directorypermissions, true);                                      
 
        // Delete any existing files for this setting.                                                                              
        foreach (glob($pathpattern) as $filename) {                                                                                 
            @unlink($filename);                                                                                                     
        }                                                                                                                           
 
        // Copy the current file to this location.                                                                                  
        $file->copy_content_to($pathname);                                                                                          
    }                                                                                                                               
 
    // Reset theme caches.                                                                                                          
    theme_reset_all_caches();                                                                                                       
}


/**
 * Initialize page
 * @param moodle_page $page
 */
function theme_boostcgs_page_init(moodle_page $page) {
    // Hooks.
    // A convenient time in the page loading process to run global hooks.
    run_global_hook_actions($page);

    // A good place to add external fonts, js and css for the theme.
    $page->requires->css( new moodle_url('/theme/boostcgs/vendor/google-fonts/open-sans/open_sans-300.400.500.600.700.css') );
    $page->requires->css( new moodle_url('/theme/boostcgs/vendor/ionicons-2.0.1/css/ionicons.min.css') );
}

/**
 * Register global hook actions.
 * @param moodle_page $page
 */
function run_global_hook_actions(moodle_page $page) {
    global $USER, $DB;

    // Redirect course profile pages to global profile pages.
    redirect_course_profile($page);
    
    // Check whether user is allowed to view this profile.
    check_profile_access($page);
}

/**
 * Hook action: Redirect course profile pages to global profile pages.
 * @param moodle_page $page
 */
function redirect_course_profile(moodle_page $page) {
    if ($page->url->get_path() == '/user/view.php') {
        $userid = $page->url->get_param('id');
        $globalprofile = new moodle_url('/user/profile.php', array(
            'id' => $userid,
        ));
        redirect($globalprofile->out(false));
        exit;
    }
}

/**
 * Hook action: Check whether user is allowed to view this profile.
 * @param moodle_page $page
 */
function check_profile_access(moodle_page $page) {
    global $USER, $DB;

    if ($page->url->get_path() == '/user/profile.php') {

        // User is accessing their own profile.
        if ($page->url->get_param('id') == $USER->id) {
            return true;
        }

        // User is a staff member at CGS.
        profile_load_custom_fields($USER);
        $campusroles = strtolower($USER->profile['CampusRoles']);
        if (strpos($campusroles, 'staff') !== false) {
            return true;
        }

        // User is a mentor of the profile user.
        $mentees = array();
        $menteessql = "SELECT u.id
                         FROM {role_assignments} ra, {context} c, {user} u
                        WHERE ra.userid = :mentorid
                          AND ra.contextid = c.id
                          AND c.instanceid = u.id
                          AND c.contextlevel = :contextlevel";     
        $menteesparams = array(
            'mentorid' => $USER->id,
            'contextlevel' => CONTEXT_USER
        );
        if ($mentees = $DB->get_records_sql($menteessql, $menteesparams)) {
            $menteeids = array_column($mentees, 'userids');
        }
        if (in_array($page->url->get_param('id'), $menteeids)) {
            return true;
        }

        // Else, redirect.
        $profileurl = new moodle_url('/user/profile.php', array(
            'id' => $USER->id,
        ));
        redirect($profileurl->out(false));
        exit;
    }
}

public static function get_users_mentees($userid) {
        global $DB;

        
        return $mentees;
    }