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
 * Table listing all deleted courses
 *
 * @package    lifecyclestep_deletecourse
 * @copyright  2021 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace lifecyclestep_deletecourse;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/tablelib.php');

/**
 * Table listing all deleted courses
 *
 * @package    lifecyclestep_deletecourse
 * @copyright  2021 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class deleted_courses_table extends \table_sql {

    /**
     * Constructor for delayed_courses_table.
     *
     * @param object|null $filterdata filterdata from moodle form.
     * @throws \coding_exception
     */
    public function __construct($filterdata) {
        parent::__construct('lifecyclestep_deletecourse');
        global $DB;

        $fields = 'dc.id, dc.shortname, dc.fullname as coursefullname, dc.idnumber';

        $from = '{lifecyclestep_deletecourse_c} dc';

        $where = ['TRUE'];
        $params = [];

        if ($filterdata) {
            if ($filterdata && $filterdata->id) {
                $where[] = "dc.id = :id";
                $params['id'] = $filterdata->id;
            }

            if ($filterdata && $filterdata->shortname) {
                $where[] = $DB->sql_like('dc.shortname', ':shortname', false, false);
                $params['shortname'] = '%' . $DB->sql_like_escape($filterdata->shortname) . '%';
            }

            if ($filterdata && $filterdata->fullname) {

                $where[] = $DB->sql_like('dc.fullname', ':fullname', false, false);
                $params['fullname'] = '%' . $DB->sql_like_escape($filterdata->fullname) . '%';
            }

            if ($filterdata && $filterdata->idnumber) {
                $where[] = 'dc.idnumber = :idnumber';
                $params['idnumber'] = $filterdata->idnumber;
            }
        }

        $where = join(" AND ", $where);

        $this->set_sql($fields, $from, $where, $params);
        $this->define_columns(['id', 'shortname', 'coursefullname', 'idnumber']);
        $this->define_headers([
            get_string('id', 'lifecyclestep_deletecourse'),
            get_string('shortname'),
            get_string('fullname'),
            get_string('idnumber')
        ]);
    }
}