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
 * Display the list of courses relevant for a specific user in a specific step instance.
 *
 * @package lifecyclestep_deletecourse
 * @copyright  2021 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../../../../config.php');

require_admin();

global $PAGE, $OUTPUT;

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new \moodle_url('/admin/tool/lifecycle/step/deletecourse/view.php'));

$PAGE->set_title(get_string('searchdeletedcourses', 'lifecyclestep_deletecourse'));
$PAGE->set_heading(get_string('searchdeletedcourses', 'lifecyclestep_deletecourse'));

$mform = new \lifecyclestep_deletecourse\deleted_courses_mform();

// Cache handling.
$cache = cache::make('tool_lifecycle', 'mformdata');
if ($mform->is_cancelled()) {
    $cache->delete('deletecourses_filter');
    redirect($PAGE->url);
} else if ($data = $mform->get_data()) {
    $cache->set('deletecourses_filter', $data);
} else {
    $data = $cache->get('deletecourses_filter');
    if ($data) {
        $mform->set_data($data);
    }
}

if ($mform->is_cancelled()) {
    redirect($PAGE->url);
}

$table = new \lifecyclestep_deletecourse\deleted_courses_table($data);
$table->define_baseurl($PAGE->url);

echo $OUTPUT->header();

$mform->display();

echo '<br><br>';

$table->out(48, false);

echo $OUTPUT->footer();
