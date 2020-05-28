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

namespace theme_boostcgs\output;

use moodle_url;

defined('MOODLE_INTERNAL') || die;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_boostcgs
 * @copyright  2012 Bas Brands, www.basbrands.nl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class core_renderer extends \core_renderer {

    /**
     * Add the users capmus and role as a body class.
     * This can be useful for displaying minor interface variances based on the users role.
     * WARNING! NEVER depend on this for authorisation. E.g. using CSS to hide an action that a
     * user does not have permission to do. This type of checking must be done in conjunction
     * with server side checking.
     *
     * @return array. Extra classes
     */
    public function campusrole_classes() {
        global $USER;

        $extraclasses = array();

        // First check that the CampusRoles profile field exists. This is very institution specific.
        if(isset($USER->profile['CampusRoles'])) {

            $campusroles = strtolower($USER->profile['CampusRoles']);

            $roles = array(
                'staff' => 'staff', 
                'student' => 'student', 
                'parent' => 'parent',
            );
            foreach ($roles as $role => $class) {
                if (strpos($campusroles, $role) !== false) {
                    $extraclasses[] = 'role-' . $class;
                }
            }

            $campuses = array(
                'early learning centre' => 'elc', 
                'southside' => 'southside', 
                'northside' => 'northside', 
                'junior school' => 'junior',
                'senior school' => 'senior',
                'primary school' => 'primary',
                'whole school' => 'whole',
            );
            foreach ($campuses as $campus => $class) {
                if (strpos($campusroles, $campus) !== false) {
                    $extraclasses[] = 'campus-' . $class;
                }
            }

            $extraclasses = array_unique($extraclasses);
        }

        return $extraclasses;
    }

    /**
     * Returns HTML to display a "Turn editing on/off" button in a form.
     *
     * @param moodle_url $url The URL + params to send through when clicking the button
     * @return string HTML the button
     */
    public function edit_button(moodle_url $url) {
        $url->param('sesskey', sesskey());
        $class = 'btn-turnediting btn-turneditingoff';
        if ($this->page->user_is_editing()) {
            $url->param('edit', 'off');
            $editstring = get_string('turneditingoff');
        } else {
            $class = 'btn-turnediting btn-turneditingon';
            $url->param('edit', 'on');
            $editstring = get_string('turneditingon');
        }
        return $this->single_button($url, $editstring, 'post', ['class' => $class]);
    }

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function full_header() {
        global $PAGE, $DB, $USER;

        $header = new \stdClass();
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->contextheader = $this->context_header();
        $header->hasnavbar = empty($PAGE->layout_options['nonavbar']);
        $header->navbar = $this->navbar();
        $header->pageheadingbutton = $this->page_heading_button();
        $header->courseheader = $this->course_header();

        $profileuser = '';
        if ($PAGE->pagetype === "course-view-tiles") {
           $profileuser = $DB->get_record('user', ['id' => $PAGE->url->get_param('id')]);
           profile_load_custom_fields($profileuser);
        }
        if (isset($profileuser->username)) {
            $header->studentdahsboard = get_string('studentdashboard', 'theme_boostcgs');
            $theme = \theme_config::load('boostcgs');
            $url = str_replace('[username]', $profileuser->username, $theme->settings->studentdashboardurl);
            $header->studentdahsboardurl = $url;
        }
        if ($PAGE->pagetype == "course-view-tiles" && (strpos(strtolower($USER->profile['CampusRoles']), 'staff'))
                && strpos(strtolower($profileuser->profile['CampusRoles']), 'students')) {
            $header->showstudentdashboard = 1;
        }

        return $this->render_from_template('theme_boost/full_header', $header);
    }

}