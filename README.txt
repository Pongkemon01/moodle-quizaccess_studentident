The student-identity check quiz access rule

This quiz access rule was created by Akrapong Patchararungruang of Kasetsart University.
It can be used with versions 2.2 of Moodle, or later.

If you install this plugin, there are new options on the quiz settings form. The first
is the option allow teacher to turn indentity check on and off. The second is the
text that the teacher wants to display as the instruction for student to answer.
The last is the minimum word count of a student answer.

If the teacher turns identity-check on, then when a student tries to start a quiz attempt,
they will see the instruction and an empty text input, and they will have to
type something with the total words at least the previously set minimum before they are
allowed to start the quiz attempt.

To install using git, type this command in the root of your Moodle install
    git clone git://github.com/pongkemon01/moodle-quizaccess_studentident.git mod/quiz/accessrule/studentident
    echo '/mod/quiz/accessrule/studentident/' >> .git/info/exclude

Alternatively, download the zip from
    https://github.com/pongkemon01/moodle-quizaccess_studentident/zipball/master
unzip it into the mod/quiz/accessrule folder, and then rename the new
folder to 'studentident'.

Once installed you need to go to the Site administration -> Notifications page
to let the plugin install itself.
