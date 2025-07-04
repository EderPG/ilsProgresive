
<?php
require_once('../../config.php');
require_login();

global $PAGE, $OUTPUT, $DB, $USER;

$courseid = required_param('courseid', PARAM_INT);
$context = context_course::instance($courseid);
require_capability('moodle/course:update', $context);

$PAGE->set_url('/mod/learningstylesurvey/uploadresource.php', ['courseid' => $courseid]);
$PAGE->set_context($context);
$PAGE->set_title('Subir recurso');
$PAGE->set_heading('Subir recurso');

echo $OUTPUT->header();
echo $OUTPUT->heading('Formulario para subir recurso');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resourcename = required_param('resourcename', PARAM_TEXT);
    $style = required_param('style', PARAM_TEXT);

    if (!isset($_FILES['resourcefile']) || $_FILES['resourcefile']['error'] != UPLOAD_ERR_OK) {
        echo $OUTPUT->notification("Error al subir el archivo", 'notifyproblem');
        echo html_writer::div(
    html_writer::link(new moodle_url('/mod/learningstylesurvey/view.php', array('id' => 187)), 'Regresar al curso', array('class' => 'btn btn-dark', 'style' => 'margin-top: 30px;')),
    'regresar-curso'
);
echo $OUTPUT->footer();
        exit;
    }

    $upload_dir = __DIR__ . '/uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $filename = basename($_FILES['resourcefile']['name']);
    $target_path = $upload_dir . $filename;

    if (!move_uploaded_file($_FILES['resourcefile']['tmp_name'], $target_path)) {
        echo $OUTPUT->notification("No se pudo guardar el archivo", 'notifyproblem');
        echo html_writer::div(
    html_writer::link(new moodle_url('/course/view.php', array('id' => $courseid)), 'Regresar al curso', array('class' => 'btn btn-dark', 'style' => 'margin-top: 30px;')),
    'regresar-curso'
);
echo $OUTPUT->footer();
        exit;
    }

    $record = new stdClass();
    $record->courseid = $courseid;
    $record->name = $resourcename;
    $record->filename = $filename;
    $record->style = $style;
    $record->timecreated = time();

    $DB->insert_record('learningstylesurvey_resources', $record);

    redirect(new moodle_url('/mod/learningstylesurvey/vista_estudiante.php', ['courseid' => $courseid]), 'Recurso subido correctamente.', 2);
} else {
    echo '<form action="uploadresource.php" method="post" enctype="multipart/form-data">';
    echo '<input type="hidden" name="courseid" value="' . $courseid . '">';

    echo '<div><label>Nombre del recurso:</label><br>';
    echo '<input type="text" name="resourcename" required></div>';

    echo '<div style="margin-top: 10px;"><label>Archivo:</label><br>';
    echo '<input type="file" name="resourcefile" required></div>';

    echo '<div style="margin-top: 10px;"><label>Estilo de aprendizaje:</label><br>';
    echo '
<select name="style" required>
    <option value="sensitivo">Sensitivo</option>
    <option value="intuitivo">Intuitivo</option>
    <option value="visual">Visual</option>
    <option value="verbal">Verbal</option>
    <option value="activo">Activo</option>
    <option value="reflexivo">Reflexivo</option>
    <option value="secuencial">Secuencial</option>
    <option value="global">Global</option>
</select>
</div>';

    echo '<div style="margin-top: 20px;"><button type="submit">Subir recurso</button></div>';
    echo '</form>';
}

echo html_writer::div(
    html_writer::link(new moodle_url('/mod/learningstylesurvey/view.php', array('id' => 187)), 'Regresar al curso', array('class' => 'btn btn-dark', 'style' => 'margin-top: 30px;')),
    'regresar-curso'
);
echo $OUTPUT->footer();
?>
