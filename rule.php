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

require_once($CFG->dirroot . '/mod/quiz/accessrule/accessrulebase.php');


/**
 * A rule requiring the student to promise not to cheat.
 *
 * @copyright  2011 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_studentident extends quiz_access_rule_base {

    public function add_preflight_check_form_fields(mod_quiz_preflight_check_form $quizform,
            MoodleQuickForm $mform, $attemptid) {

        $mform->addElement('header', 'studentidentheader',
                get_string('studentidentheader', 'quizaccess_studentident'));
        $mform->addElement('static', 'studentidentcaption', '',
                $this->quiz->studentidentcaption);
        $mform->addElement('textarea', 'studentident', '',
                array('rows' => 2, 'cols' => 70));
        $mform->setType('studentident', PARAM_RAW_TRIMMED);
    }

    public function compute_ident_key($data) {
        return sha1($data);
    }

    public function validate_preflight_check($data, $files, $errors, $attemptid) {
        global $DB;
        if (empty($data['studentident'])) {
            $errors['studentident'] = get_string('tooshort', 'quizaccess_studentident');
            return $errors;
        }

        // == Clean up phase ==
        // split the phrase by any number of commas or space characters,
        // which include " ", \r, \t, \n and \f
        $identwords = preg_split("/[\s,]+/", $data['studentident']);
        // Join the split phrase back without adding any commas
        $ident = implode (' ', $identwords);
        // Finally, convert all char to lower case
        $ident = strtolower ($ident);

        // == Checking phase ==
        // Minimum word count checking
        if (count ($identwords) < intval ($this->quiz->studentidentmin)) {
            $errors['studentident'] = get_string('tooshort', 'quizaccess_studentident');
            return $errors;
        }

        $attempt = new stdClass();
        $identkey = $this->compute_ident_key ($ident);
        $attempt = $DB->get_record('quiz_attempts', array('id' => $attemptid)); // We need 'userid'
        // Search for existing ident in DB
        $record = $DB->get_record_select('quizaccess_studentident_ids',
                $DB->sql_compare_text('ident', 255) . "= '" . $identkey . "'");

        if (!$record) {
            // Not found. Student have passed the identity check. Store new identkey to the database.
            $record = new stdClass();
            $record->quizid = $this->quiz->id;
            $record->userid = $attempt->userid;
            $record->ident = $identkey;
            $DB->insert_record('quizaccess_studentident_ids', $record);
        } else {
            $errors['studentident'] = get_string('youmustpass', 'quizaccess_studentident');
        }

        return $errors;
    }

    public function is_preflight_check_required($attemptid) {
        global $SESSION;
        return empty($SESSION->passidentcheckedquizzes[$this->quiz->id]);
    }

    public function notify_preflight_check_passed($attemptid) {
        global $SESSION;
        $SESSION->passidentcheckedquizzes[$this->quiz->id] = true;
    }

    public function current_attempt_finished() {
        global $SESSION;
        // Clear the flag in the session that says that the user has already
        // entered the password for this quiz.
        if (!empty($SESSION->passidentcheckedquizzes[$this->quiz->id])) {
            unset($SESSION->passidentcheckedquizzes[$this->quiz->id]);
        }
    }

    public static function make(quiz $quizobj, $timenow, $canignoretimelimits) {

        if (empty($quizobj->get_quiz()->studentidentrequired)) {
            return null;
        }

        return new self($quizobj, $timenow);
    }

    public static function add_settings_form_fields(
            mod_quiz_mod_form $quizform, MoodleQuickForm $mform) {

        $mform->addElement('checkbox', 'studentidentrequired',
                get_string('studentidentrequired', 'quizaccess_studentident'));
        $mform->addElement('text', 'studentidentcaption',
                get_string('studentidentcaption', 'quizaccess_studentident'), '');
        $mform->addElement('text', 'studentidentmin',
                get_string('studentidentmin', 'quizaccess_studentident'), 'size="3"');

        $mform->setType('studentidentcaption', PARAM_RAW_TRIMMED);
        $mform->setType('studentidentmin', PARAM_INTEGER);

        $mform->addHelpButton('studentidentrequired',
                'studentidentrequired', 'quizaccess_studentident');
    }

    public static function validate_settings_form_fields(array $errors,
            array $data, $files, mod_quiz_mod_form $quizform) {

        // If identity is not required, we skip all checking
        if (empty($data['studentidentrequired'])) {
            return $errors;
        }

        // Caption cannot be empty
        if (empty($data['studentidentcaption'])) {
            $errors['studentidentcaption'] = get_string('errorcaption', 'quizaccess_studentident');
        }

        // Minimum length of identity must be at least 2
        if (empty($data['studentidentmin'])) {
            $errors['studentidentmin'] = get_string('errorminlength', 'quizaccess_studentident');
        } else {
            if (intval ($data['studentidentmin']) < 3) {
                $errors['studentidentmin'] = get_string('errorminlength', 'quizaccess_studentident');
            }
        }

        return $errors;
    }

    public static function save_settings($quiz) {
        global $DB;
        if (empty($quiz->studentidentrequired)) {
            $DB->delete_records('quizaccess_studentident', array('quizid' => $quiz->id));
        } else {
            $record = $DB->get_record('quizaccess_studentident', array('quizid' => $quiz->id));
            if (!$record) {
                $record = new stdClass();
                $record->quizid = $quiz->id;
                $record->studentidentrequired = 1;
                $record->studentidentcaption = $quiz->studentidentcaption;
                $record->studentidentmin = intval ($quiz->studentidentmin);
                $DB->insert_record('quizaccess_studentident', $record);
            } else {
                $record->studentidentrequired = 1;
                $record->studentidentcaption = $quiz->studentidentcaption;
                $record->studentidentmin = intval ($quiz->studentidentmin);
                $DB->update_record('quizaccess_studentident', $record);
            }
        }
    }

    public static function delete_settings($quiz) {
        global $DB;
        $DB->delete_records('quizaccess_studentident', array('quizid' => $quiz->id));
        $DB->delete_records('quizaccess_studentident_ids', array('quizid' => $quiz->id));
    }

    public static function get_settings_sql($quizid) {
        return array(
            'studentidentrequired, studentidentcaption, studentidentmin',
            'LEFT JOIN {quizaccess_studentident} studentident ON studentident.quizid = quiz.id',
            array());
    }
}
