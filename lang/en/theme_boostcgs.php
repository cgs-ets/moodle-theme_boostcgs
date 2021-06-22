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
// The name of the second tab in the theme settings.                                                                                
$string['advancedsettings'] = 'Advanced settings';                                                                                  
// The brand colour setting.                                                                                                        
$string['brandcolor'] = 'Brand colour';                                                                                             
// The brand colour setting description.                                                                                            
$string['brandcolor_desc'] = 'The accent colour.';     
// A description shown in the admin theme selector.                                                                                 
$string['choosereadme'] = 'Theme Boost CGS is a child theme of Boost. It adds the ability to upload background photos.';                
// Name of the settings pages.                                                                                                      
$string['configtitle'] = 'Boost CGS settings';                                                                                          
// Name of the first settings tab.                                                                                                  
$string['generalsettings'] = 'General settings';                                                                                    
// The name of our plugin.                                                                                                          
$string['pluginname'] = 'Boost CGS';                                                                                                    
// Preset files setting.                                                                                                            
$string['presetfiles'] = 'Additional theme preset files';                                                                           
// Preset files help text.                                                                                                          
$string['presetfiles_desc'] = 'Preset files can be used to dramatically alter the appearance of the theme. See <a href=https://docs.moodle.org/dev/Boost_Presets>Boost presets</a> for information on creating and sharing your own preset files, and see the <a href=http://moodle.net/boost>Presets repository</a> for presets that others have shared.';
// Preset setting.                                                                                                                  
$string['preset'] = 'Theme preset';                                                                                                 
// Preset help text.                                                                                                                
$string['preset_desc'] = 'Pick a preset to broadly change the look of the theme.';                                                  
// Raw SCSS setting.                                                                                                                
$string['rawscss'] = 'Raw SCSS';                                                                                                    
// Raw SCSS setting help text.                                                                                                      
$string['rawscss_desc'] = 'Use this field to provide SCSS or CSS code which will be injected at the end of the style sheet.';       
// Raw initial SCSS setting.                                                                                                        
$string['rawscsspre'] = 'Raw initial SCSS';                                                                                         
// Raw initial SCSS setting help text.                                                                                              
$string['rawscsspre_desc'] = 'In this field you can provide initialising SCSS code, it will be injected before everything else. Most of the time you will use this setting to define variables.';
// Footer HTML setting.                                                                                                                
$string['footerhtml'] = 'Footer HTML';                                                                                                    
// Footer HTML setting help text.                                                                                                      
$string['footerhtml_desc'] = 'Use this field to provide some HTML code which will be injected into the footer after the course footer and page doc link.';       
// We need to include a lang string for each block region.                                                                          
$string['region-side-pre'] = 'Right';
$string['region-fp-main-top'] = 'Main Top';
$string['region-fp-main-bottom'] = 'Main Bottom';
// Background image for login page.                                                                                                 
$string['loginbackgroundimage'] = 'Login page background image';                                                                    
// Background image for login page.                                                                                                 
$string['loginbackgroundimage_desc'] = 'An image that will be stretched to fill the background of the login page.';
$string['studentdashboard'] = 'Dashboard';
$string['studentdashboardsenior'] = 'Dashboard (Senior)';
$string['studentdashboardprimary'] = 'Dashboard (Primary)';
$string['studentdashboard_desc'] = 'Set student dashboard URL';
$string['environment'] = 'Environment';
$string['environment_desc'] = 'This will be added as a class to the body element.';
$string['showenvbar'] = 'Show environment bar?';
$string['environmentcolor'] = 'Environment colour';
$string['environmentcolor_desc'] = 'A css color that will be added as a semi-transparent overlay over the header.';

