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


require_once($CFG->dirroot . '/mod/quiz/backup/moodle2/restore_mod_quiz_access_subplugin.class.php');

defined('MOODLE_INTERNAL') || die();


/**
 * Provides the information to restore the supervisedcheck quiz access plugin.
 */
class restore_quizaccess_studentident_subplugin extends restore_mod_quiz_access_subplugin {

    protected function define_quiz_subplugin_structure() {

        $paths = array();

        $elename = $this->get_namefor('');
        $elepath = $this->get_pathfor('/quizaccess_studentident');
        $paths[] = new restore_path_element($elename, $elepath);
        $elepath = $this->get_pathfor('/quizaccess_studentident_ids');
        $paths[] = new restore_path_element($elename, $elepath);

        return $paths;
    }

    /**
     * Processes the quizaccess_studentident element, if it is in the file.
     * @param array $data the data read from the XML file.
     */
    public function process_quizaccess_studentident($data) {
        global $DB;

        $data = (object)$data;
        $data->quizid = $this->get_new_parentid('quiz');
        $DB->insert_record('quizaccess_studentident', $data);
    }

    /**
     * Processes the quizaccess_studentident_idents element, if it is in the file.
     * @param array $data the data read from the XML file.
     */
    public function process_quizaccess_studentident_ids($data) {
        global $DB;

        $data = (object)$data;
        $data->quizid = $this->get_new_parentid('quiz');
        $DB->insert_record('quizaccess_studentident_ids', $data);
    }
}
