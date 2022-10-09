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
 * Moodle frontpage.
 *
 * @package    core
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('config.php');
global $DB, $CFG, $USER, $COURSE;

$sectionid = 13;
$courseid = 3;
echo count_progress($sectionid, $courseid);

function count_progress($sectionid, $courseid) {
    global $DB, $USER;
    $value = $DB->get_field('course_format_options', 'value', array('sectionid' => $sectionid, 'name' => 'parent'));
    if ($value) {
        return 0; //If its child section return 0 as we are not showing it anywhere
    } else {
        $parent_child_arr = array();
        $arr = get_child($sectionid, $courseid, $parent_child_arr);
        $total_number_activity = 0;
        $user_completed = 0;
        foreach ($arr as &$value) {
            $total_number_activity += $DB->count_records('course_modules', array('section' => $value, 'visible' => 1, 'completion' => 1, 'deletioninprogress' => 0));
            $sql = "SELECT count(cmc.id) as count FROM `mdl_course_modules` as cm left join mdl_course_modules_completion as cmc on cm.id = cmc.coursemoduleid WHERE section = $value and userid = $USER->id";
            $user_completed += $DB->get_record_sql($sql)->count;
        }
        return get_percentage($user_completed, $total_number_activity);
    }
}

function get_child($sectionid, $courseid, $parent_child_arr) {
    global $DB;
    $parent_child_arr[] = $sectionid;
    $sectionnum = $DB->get_field('course_sections', 'section', array('id' => $sectionid));
    $sql = "select id,sectionid from {course_format_options} where courseid = $courseid and value = $sectionnum and " . $DB->sql_compare_text('name') . "= 'parent'";
    $child_sections = $DB->get_records_sql($sql);
    if (!empty($child_sections)) {
        foreach ($child_sections as $key => $value) {
            $parent_child_arr = get_child($value->sectionid, $courseid, $parent_child_arr);
        }
    }
    return $parent_child_arr;
}

function get_percentage($of, $from) {
    if ($from == 0)
        $percent = 0;
    else {
        $percent = $of * 100 / $from;
        $percent = number_format($percent, 2);
    }
    return $percent;
}

?>