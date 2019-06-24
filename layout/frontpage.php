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
 * A front page layout for the cgs boost child theme.
 *
 * @package   theme_boostcgs
 * @copyright 2019 Michael Vangelovski, Canberra Grammar School <michael.vangelovski@cgs.act.edu.au>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');

if (isloggedin()) {
    $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
} else {
    $navdraweropen = false;
}
$extraclasses = [];
if ($navdraweropen) {
    $extraclasses[] = 'drawer-open-left';
}
$bodyattributes = $OUTPUT->body_attributes($extraclasses);

//$mainrightblockshtml = $OUTPUT->blocks('fp-main-right');
//$hasmainrightblocks = strpos($mainrightblockshtml, 'data-block=') !== false;
$maintopblockshtml = $OUTPUT->blocks('fp-main-top');
$hasmaintopblocks = strpos($maintopblockshtml, 'data-block=') !== false;
$mainbottomblockshtml = $OUTPUT->blocks('fp-main-bottom');
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;

$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    //'fpmainrightblocks' => $mainrightblockshtml,
    //'hasmainrightblocks' => $hasmainrightblocks,
    'fpmaintopblocks' => $maintopblockshtml,
    'hasmaintopblocks' => $hasmaintopblocks,
    'fpmainbottomblocks' => $mainbottomblockshtml,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'navdraweropen' => $navdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu)
];

$templatecontext['flatnavigation'] = $PAGE->flatnav;
echo $OUTPUT->render_from_template('theme_boost/frontpage', $templatecontext);

