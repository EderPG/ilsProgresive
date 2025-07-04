
<?php
require_once("../../config.php");

$courseid = required_param("courseid", PARAM_INT);
require_login($courseid);

$context = context_course::instance($courseid);
$PAGE->set_context($context);
$PAGE->set_url("/mod/learningstylesurvey/viewresources.php", ["courseid" => $courseid]);
$PAGE->set_title("Material adaptativo");
$PAGE->set_heading("Material adaptativo para tu estilo de aprendizaje");

echo $OUTPUT->header();
echo $OUTPUT->heading("Material adaptativo para tu estilo de aprendizaje");

// Mostrar todos los recursos sin filtro
$resources = $DB->get_records("learningstylesurvey_resources", ["courseid" => $courseid]);

if (!$resources) {
    echo $OUTPUT->notification("No tienes material adaptativo disponible.", "notifymessage");
    echo $OUTPUT->footer();
    exit;
}

echo html_writer::start_tag("ul");

echo html_writer::start_tag("ul", ['style' => 'list-style:none; padding:0;']);
foreach ($resources as $resource) {
    $filepath = "/mod/learningstylesurvey/uploads/" . $resource->filename;
    $ext = pathinfo($resource->filename, PATHINFO_EXTENSION);
    $html = "";

    if (in_array(strtolower($ext), ['pdf', 'jpg', 'jpeg', 'png'])) {
        $html .= "<div style='margin-bottom:20px;'>";
        $html .= "<h4>" . format_string($resource->name) . "</h4>";
        $html .= "<iframe src='" . $filepath . "' width='100%' height='500px' style='border:1px solid #ccc;'></iframe>";
        $html .= "</div>";
    } else {
        $link = new moodle_url($filepath);
        $html .= html_writer::tag("li", html_writer::link($link, $resource->name . " (descargar)"));
    }

    echo $html;
}
echo html_writer::end_tag("ul");

echo html_writer::div(
    html_writer::link(new moodle_url('/mod/learningstylesurvey/view.php', array('id' => 187)), 'Regresar al curso', array('class' => 'btn btn-dark', 'style' => 'margin-top: 30px;')),
    'regresar-curso'
);

echo $OUTPUT->footer();
?>
