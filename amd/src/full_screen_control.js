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
 * Contains the logic  to resize pages.
 *
 * @package    theme_boostcgs
 * @copyright  2016 Damyon Wiese
 * @modified   2020 Veronica Bermegui
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery'], function ($) {

    function init() {
        var control = new FullScreenControl();
        control.main();
    }

    // Constructor.
    function FullScreenControl() {
        // For pages with actions menu
        if ($('body').find('.tiles').length == 1) {
            $("a.screensizecontrol").addClass('tileformat');
        }
        // User preference : nav bar close.
        // Keep it close when resizing.
        if ($("#nav-drawer").hasClass("closed")) {
            $("#nav-drawer").addClass("prefclosed");
        }
    }

    FullScreenControl.prototype.main = function () {
        var self = this;
        self.fullscreenmode();
        self.resize();
    }

    // Normal screen size -> Fullscreen
    FullScreenControl.prototype.fullscreenmode = function () {

        $("div#page-content").on("click", ".screensizecontrol", function () {
            $("section#region-main").addClass("fullsize");
            $("div#region-main-settings-menu").addClass("fullsize");
            $("a.screensizecontrol").addClass("resize");
            $("aside#block-region-side-pre").css("display", "none");
            // Hide Nav bar.
            if (!$("#nav-drawer").hasClass("closed")) {
                $("#nav-drawer").addClass("closed");
                $('body').removeClass('drawer-open-left');
            }
                $("#multi_section_tiles").addClass("fullscreenmode"); //To expand tiles to max width
        });

    };
    //Fullscreen -> Normal
    FullScreenControl.prototype.resize = function () {
        $("div#page-content").on("click", ".resize", function (e) {
            $("section#region-main").removeClass("fullsize");
            $("a.screensizecontrol").removeClass("resize");
            $("div#region-main-settings-menu").removeClass("fullsize");
            $("aside#block-region-side-pre").css("display", "block");

            if (!$("#nav-drawer").hasClass("prefclosed")) {
                $("#nav-drawer").removeClass("closed");
                $("#multi_section_tiles").removeClass("fullscreenmode");
                $('body').addClass('drawer-open-left');
            }
        });
    };

    return {
        init: init
    };
});