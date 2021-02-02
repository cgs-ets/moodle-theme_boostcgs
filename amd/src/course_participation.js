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
 * Create an announcement draft audience from the course participation page.
 *
 * @package    theme_boostcgs
 * @copyright  Michael Vangelovski
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/log', 'core/ajax'], function ($, Log, Ajax) {

        function init() {
            var control = new CourseParticipation();
        }

        var Selectors = {
            bulkActionSelect: "#formactionid",
            bulkUserSelectedCheckBoxes: "input.usercheckbox[data-togglegroup^='participants-table']:checked",
            participantsForm: '#participantsform',
            messageSelect: '#messageselect',
        };

        function CourseParticipation() {

            $(Selectors.bulkActionSelect).off();

            $(Selectors.bulkActionSelect).on('change', function(e) {
                e.preventDefault();

                const action = e.target.value;
                var checkboxes = $(Selectors.participantsForm).find(Selectors.bulkUserSelectedCheckBoxes);
   
                if (action === Selectors.messageSelect) {
                    // Stop modal and backdrop from showing.
                    $('body').append('<style type="text/css">.modal,.modal-backdrop{display: none !important;}</style>');

                    var ids = [];
                    checkboxes.each(function( index ) {
                        ids.push(parseInt($(this).attr('name').replace('user', '')));
                    });
                    var json = JSON.stringify(ids);

                    // Create the draftaudience.
                    Ajax.call([{
                        methodname: 'local_announcements_set_draftaudience',
                        args: { userids: json },
                        done: function(response) {
                            // Redirect to post announcement.
                            window.location.replace('/local/announcements/post.php?draftaudience=' + response);
                        },
                        fail: function(reason) {
                            Log.debug(reason);
                        }
                    }]);
                }

            });



        };

        return {
            init: init
        };
    });