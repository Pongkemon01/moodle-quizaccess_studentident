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

$string['pluginname'] = 'Checking student identity from type-in text';
$string['studentidentheader'] = 'Please answer the following question';
$string['studentidentrequired'] = 'Students passed identity checking';
$string['studentidentrequired_help'] = 'If you enable this option, students will not be able to start an attempt until they have passed identity check by typing in a text.';
$string['studentidentcaption'] = 'Question that asks students to provide identity';
$string['studentidentmin'] = 'Minimum word count of the identity provided by each student';
$string['youmustpass'] = 'You are not allowed to perform the quiz because you are likely to attempt cheating.';
$string['tooshort'] = 'Your answer is too short.';
$string['errorcaption'] = 'Caption cannot be empty.';
$string['errorminlength'] = 'Identity length cannot be less than 3.';
$string['hint'] = '<small><b>Be careful.</b> If you log out, you will have to answer this question again before you can continue the quiz. The 30-minute time limit of the quiz will start <b>after</b> you complete this question.</small>';
