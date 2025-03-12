<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/blocks/myplugin/classes/form/questionnaire_form.php');

global $DB, $OUTPUT, $PAGE, $USER;

$courseid = required_param('courseid', PARAM_INT);
$context = context_course::instance($courseid);
require_login($courseid);
require_capability('block/myplugin:view', $context);

$PAGE->set_url(new moodle_url('/blocks/myplugin/views/questionnaire.php', ['courseid' => $courseid]));
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'block_myplugin'));

$form = new questionnaire_form();

if ($form->is_submitted() && $form->is_validated()) {
    $data = $form->get_data();

    // Guardar cada respuesta individualmente
    $total_questions = 44; // Ajusta según el número de preguntas
    for ($i = 1; $i <= $total_questions; $i++) {
        $question_key = 'ilsq' . $i;
        $response = $data->$question_key;

        $record = new stdClass();
        $record->userid = $USER->id;
        $record->courseid = $courseid;
        $record->questionid = $i;
        $record->response = $response;
        $record->timecreated = time();

        $DB->insert_record('block_myplugin_responses', $record);
    }

    redirect(new moodle_url('/course/view.php', ['id' => $courseid]), get_string('thankyou', 'block_myplugin'));
}

echo $OUTPUT->header();
$form->display();
echo $OUTPUT->footer();