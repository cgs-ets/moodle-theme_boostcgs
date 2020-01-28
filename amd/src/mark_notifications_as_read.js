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
 * Contain the logic for clean up notifications.
 *
 * @package    theme_boostcgs
 * @copyright  2020 Veronica Bermegui
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/log', 'core/ajax'],
        function ($, Log, Ajax) {

            // Private functions.
            var init = function (user_id) {
                $(".popover-region-toggle.nav-link").click(function () {
                    Log.debug('theme_boostcgs: reading all notifications');
                    Ajax.call([{
                            methodname: 'core_message_mark_all_notifications_as_read',
                            args: {useridto: user_id},
                            done: function () { //response
                                Log.debug('theme_boostcgs: mark_all_notifications_as_read successful');
                            },
                            fail: function (reason) {
                                Log.error('theme_boostcgs: unable to mark messages as read.');
                                Log.debug(reason);
                            }
                        }]);
                });
            };

            // Public functions.
            return {
                init: function (user_id) {
                    init(user_id);
                }
            };
        });