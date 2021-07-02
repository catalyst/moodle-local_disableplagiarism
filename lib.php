<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Hooks for disableplagiarism plugin.
 *
 * @package     local_disableplagiarism
 * @copyright   2021 Catalyst IT
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Hook to add a button to header of assignment grading page.
 *
 */
function local_disableplagiarism_before_standard_top_of_body_html() {
    global $PAGE, $OUTPUT, $CFG;
    if (!$PAGE->context instanceof \context_module) {
        return;
    }
    if (!$PAGE->url->compare(new moodle_url('/mod/assign/view.php'), URL_MATCH_BASE)) {
        return;
    }
    if (empty(optional_param('action', '', PARAM_TEXT))) {
        return;
    }
    $url = $PAGE->url;
    $disableaction = optional_param('local_disableplagiarism',  false, PARAM_BOOL);
    if (data_submitted()) { // Only check if a change to user preference needs to happen on page POST actions.
        set_user_preference('local_disableplagiarism', $disableaction);
    }

    $disableplagiarism = get_user_preferences('local_disableplagiarism', 0);

    if ($disableplagiarism) {
        $buttonstring = get_string('showplagiarismlinks', 'local_disableplagiarism');
        $CFG->enableplagiarism = 0;
        $url->param('local_disableplagiarism', 0);
    } else {
        $buttonstring = get_string('hideplagiarismlinks', 'local_disableplagiarism');
        $url->param('local_disableplagiarism', 1);
    }

    $button = html_writer::div($OUTPUT->single_button($url, $buttonstring), 'local_disableplagiarism');
    $PAGE->set_button($PAGE->button . $button);
}