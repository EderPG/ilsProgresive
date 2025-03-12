<?php
require_once(__DIR__.'/../../config.php');
require_once($CFG->dirroot.'/blocks/ILSprogresive/locallib.php');

defined('MOODLE_INTERNAL') || die();

$id = required_param('id', PARAM_INT);
$course = get_course($id);
require_login($course);

$url = new moodle_url('/blocks/ILSprogresive/graph.php', ['id' => $course->id]);

$PAGE->set_url($url);
$PAGE->set_title(get_string('title', 'block_ILSprogresive'));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();

// Recuperar las respuestas del estudiante
$responses = $DB->get_records('block_ILSprogresive_responses', ['studentid' => $USER->id, 'courseid' => $course->id]);

if ($responses) {
    // Procesar las respuestas para generar la gráfica
    $data = [];
    foreach ($responses as $response) {
        $data[] = $response->response;
    }

    // Generar la gráfica (esto es un ejemplo básico)
    echo "<div id='chart'></div>";
    echo "<script src='https://cdn.plot.ly/plotly-latest.min.js'></script>";
    echo "<script>
        var data = [{
            values: " . json_encode($data) . ",
            labels: ['Activo/Reflexivo', 'Sensorial/Intuitivo', 'Visual/Verbal', 'Secuencial/Global'],
            type: 'pie'
        }];
        var layout = {
            height: 400,
            width: 500
        };
        Plotly.newPlot('chart', data, layout);
    </script>";
} else {
    echo html_writer::div(get_string('noresponses', 'block_ILSprogresive'));
}

echo $OUTPUT->footer();