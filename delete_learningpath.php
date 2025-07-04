<?php
require_once('../../config.php');
require_login();

$courseid = required_param('courseid', PARAM_INT);
$context = context_course::instance($courseid);
$PAGE->set_url(new moodle_url('/mod/learningstylesurvey/delete_learningpath.php', array('courseid' => $courseid)));
$PAGE->set_context($context);
$PAGE->set_title("Eliminar Ruta de Aprendizaje");
$PAGE->set_heading("Eliminar Ruta de Aprendizaje");

echo $OUTPUT->header();

global $DB;

if ($idruta = optional_param('delete', 0, PARAM_INT)) {
    $DB->delete_records('learningstylesurvey_paths', array('id' => $idruta));
    redirect(new moodle_url('/mod/learningstylesurvey/delete_learningpath.php', array('courseid' => $courseid)), "Ruta eliminada.", 1);
    exit;
}

$records = $DB->get_records('learningstylesurvey_paths', array('courseid' => $courseid));

if (!$records) {
    echo "<p>No hay rutas registradas.</p>";
} else {
    echo "<ul>";
    foreach ($records as $ruta) {
        $deleteurl = new moodle_url('/mod/learningstylesurvey/delete_learningpath.php', array('courseid' => $courseid, 'delete' => $ruta->id));
        $confirmmsg = "¿Eliminar esta ruta?";
	echo "<li><strong>{$ruta->name}</strong> - {$ruta->filename} 
      	      <a href=\"{$deleteurl}\" onclick=\"return confirm('" . addslashes($confirmmsg) . "')\">Eliminar</a></li>";

    }
    echo "</ul>";
}

$urlreturn = new moodle_url('/mod/learningstylesurvey/learningpath.php', array('courseid' => $courseid));
echo "<a href='{$urlreturn}' class='btn btn-secondary'>Regresar</a>";
echo $OUTPUT->footer();
