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

define(['jquery', 'core/log', 'message_popup/notification_repository'],
        function ($, Log, NotificationRepo) {
            var init = function (user_id) {
                
                $("div.popover-region-toggle.nav-link").click(function () {
                    Log.debug('theme_boostcgs: reading all notifications');         
                    return NotificationRepo.markAllAsRead({useridto: user_id}).then(function() {
                         $('div.count-container').addClass('hidden');
                    });
                });
            };
            return {
                init: function (user_id) {
                    init(user_id);
                }
            };
        });