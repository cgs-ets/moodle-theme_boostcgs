<?php
 
// Every file should have GPL and copyright in the header - we skip it in tutorials but you should not skip it for real.
 
// This line protects the file from being accessed by a URL directly.                                                               
defined('MOODLE_INTERNAL') || die();
 
// We will add callbacks here as we add features to our theme.



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

    // A good place to add external fonts, js and css for the theme
    $page->requires->css( new moodle_url('/theme/boostcgs/vendor/google-fonts/open-sans/open_sans-300.400.500.600.700.css') );
    $page->requires->css( new moodle_url('/theme/boostcgs/vendor/ionicons-2.0.1/css/ionicons.min.css') );
    
    //CDN
    //$page->requires->css( new moodle_url('https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700') );
    //$page->requires->css( new moodle_url('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') );
    //$page->requires->js( new moodle_url('https://unpkg.com/ionicons@4.4.4/dist/ionicons.js') );
}