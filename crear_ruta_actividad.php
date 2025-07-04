<?php
require_once('../../config.php');
require_login();

$courseid = required_param('courseid', PARAM_INT);
$PAGE->set_url(new moodle_url('/mod/learningstylesurvey/crear_ruta_actividad.php', array('courseid' => $courseid)));
$PAGE->set_context(context_course::instance($courseid));
$PAGE->set_title('Crear Ruta de Aprendizaje');
$PAGE->set_heading('Crear Ruta de Aprendizaje');

echo $OUTPUT->header();
echo $OUTPUT->heading('Selecciona una opción para continuar');

// Mostrar menú con dos opciones
$baseurl = new moodle_url('/mod/learningstylesurvey');
echo html_writer::start_div('ruta-menu', array('style' => 'margin-top: 20px;'));
echo html_writer::link(new moodle_url("$baseurl/createsteproute.php", array('courseid' => $courseid)),
    'Crear Ruta de Aprendizaje', array('class' => 'btn btn-primary', 'style' => 'margin-right: 20px;'));

echo html_writer::link(new moodle_url("$baseurl/crear_examen.php", array('courseid' => $courseid)),
    'Crear Recursos de Evaluación', array('class' => 'btn btn-secondary'));
echo html_writer::end_div();

// Botón de regresar al curso
echo html_writer::div(
    html_writer::link(new moodle_url('/course/view.php', array('id' => $courseid)), 'Regresar al curso', array('class' => 'btn btn-dark', 'style' => 'margin-top: 30px;')),
    'regresar-curso'
);

echo $OUTPUT->footer();
?>
