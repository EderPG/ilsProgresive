<?php
require_once(__DIR__.'/../../config.php');
require_once($CFG->dirroot.'/blocks/ILSprogresive/locallib.php');

defined('MOODLE_INTERNAL') || die();

$id = required_param('id', PARAM_INT);
$course = get_course($id);
require_login($course);

$url = new moodle_url('/blocks/ILSprogresive/index.php', ['id' => $course->id]);

$PAGE->set_url($url);
$PAGE->set_title(get_string('title', 'block_ILSprogresive'));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();

// Botón para realizar la encuesta
echo html_writer::link(new moodle_url('/blocks/ILSprogresive/questionnaire.php', ['id' => $course->id]), 
    get_string('launchsurvey', 'block_ILSprogresive'), 
    ['class' => 'btn btn-primary']);

// Botón para visualizar la gráfica
echo html_writer::link(new moodle_url('/blocks/ILSprogresive/graph.php', ['id' => $course->id]), 
    get_string('viewgraph', 'block_ILSprogresive'), 
    ['class' => 'btn btn-secondary']);

echo $OUTPUT->footer();