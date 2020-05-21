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
 * Privacy Subsystem implementation for theme_boost.
 *
 * @package    theme_boost
 * @copyright  2018 Andrew Nicols <andrew@nicols.co.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boostcgs\privacy;

use \core_privacy\local\metadata\collection;

defined('MOODLE_INTERNAL') || die();

/**
 * The boost theme stores a user preference data.
 *
 * @copyright  2018 Andrew Nicols <andrew@nicols.co.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
    // This plugin has data.
    \core_privacy\local\metadata\provider,
    // This plugin has some sitewide user preferences to export.
    \core_privacy\local\request\user_preference_provider {

    /** The user preference for the navigation drawer. */
    const DRAWER_OPEN_NAV = 'drawer-open-nav';
    
    /** The user preference for the navigation drawer on a desktop. */
    const DRAWER_OPEN_DESKTOP_NAV = 'drawer-open-desktop-nav';

    /**
     * Returns meta data about this system.
     *
     * @param  collection $items The initialised item collection to add items to.
     * @return collection A listing of user data stored through this system.
     */
    public static function get_metadata(collection $items) : collection {
        $items->add_user_preference(self::DRAWER_OPEN_NAV, 'privacy:metadata:preference:draweropennav');
        $items->add_user_preference(self::DRAWER_OPEN_DESKTOP_NAV, 'privacy:metadata:preference:draweropendesktopnav');
        return $items;
    }

    /**
     * Store all user preferences for the plugin.
     *
     * @param int $userid The userid of the user whose data is to be exported.
     */
    public static function export_user_preferences(int $userid) {
        $draweropennavpref = get_user_preferences(self::DRAWER_OPEN_NAV, null, $userid);

        if (isset($draweropennavpref)) {
            $preferencestring = get_string('privacy:drawernavclosed', 'theme_boostcgs');
            if ($draweropennavpref == 'true') {
                $preferencestring = get_string('privacy:drawernavopen', 'theme_boostcgs');
            }
            \core_privacy\local\request\writer::export_user_preference(
                'theme_boostcgs',
                self::DRAWER_OPEN_NAV,
                $draweropennavpref,
                $preferencestring
            );
        }
        
        $draweropennavdesktoppref = get_user_preferences(self::DRAWER_OPEN_DESKTOP_NAV, null, $userid);
       
        if (isset($draweropennavdesktoppref)) {
            $preferencestring = get_string('privacy:drawernavdesktopclosed', 'theme_boostcgs');
            if ($draweropennavdesktoppref == 'true') {
                $preferencestring = get_string('privacy:drawernavdesktopopen', 'theme_boostcgs');
            }
            \core_privacy\local\request\writer::export_user_preference(
                'theme_boostcgs',
                self::DRAWER_OPEN_DESKTOP_NAV,
                $draweropennavdesktoppref,
                $preferencestring
            );
        }
    }
}
