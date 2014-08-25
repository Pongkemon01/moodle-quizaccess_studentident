<?php
// This file is a part of Kasetsart Moodle Kit - https://github.com/Pongkemon01/moodle-quizaccess_studentident
//
// Kasetsart Moodle Kit is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Kasetsart Moodle Kit is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Restore code for the quizaccess_studentident plugin.
 *
 * @package     quizaccess_studentident
 * @author      Akrapong Patchararungruang
 * @copyright   2014 Kasetsart University
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * quizaccess_studentident_ids module upgrade function.
 * @param string $oldversion the version we are upgrading from.
 */
function xmldb_quizaccess_safeexambrowser_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    // Valid for version 2014081701 or less (new DB is valid from 2014082501)
    if ($oldversion < 2014082501) {

        // Add "answer" field to table quizaccess_studentident_ids to save the normalized answer.
        $table = new xmldb_table('quizaccess_studentident_ids');
        $field = new xmldb_field('answer', XMLDB_TYPE_TEXT);

        // Launch add field.
        $dbman->add_field($table, $field);

        // studentident savepoint reached.
        upgrade_plugin_savepoint(true, 2014082501, 'quizaccess', 'studentident');
    }

    return true;
}

