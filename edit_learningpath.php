<?php
require_once('../../config.php');
require_login();

global $DB, $USER;

$courseid = required_param('courseid', PARAM_INT);
$selectedid = optional_param('id', 0, PARAM_INT);
$context = context_course::instance($courseid);

$PAGE->set_url(new moodle_url('/mod/learningstylesurvey/edit_learningpath.php', ['courseid' => $courseid]));
$PAGE->set_context($context);
$PAGE->set_title("Editar Ruta de Aprendizaje");
$PAGE->set_heading("Editar Ruta de Aprendizaje");

echo $OUTPUT->header();

// Obtener todas las rutas del curso
$rutas = $DB->get_records('learningstylesurvey_paths', ['courseid' => $courseid]);

// Mostrar formulario para elegir la ruta
echo "<form method='get'>";
echo "<input type='hidden' name='courseid' value='{$courseid}'>";
echo "<label>Seleccionar ruta:</label> ";
echo "<select name='id' onchange='this.form.submit()'>";
echo "<option value=''>Seleccione una ruta</option>";
foreach ($rutas as $ruta) {
    $selected = ($ruta->id == $selectedid) ? 'selected' : '';
    echo "<option value='{$ruta->id}' {$selected}>{$ruta->name}</option>";
}
echo "</select>";
echo "</form><hr>";

if ($selectedid) {
    $ruta = $DB->get_record('learningstylesurvey_paths', ['id' => $selectedid]);

    if (optional_param('update', 0, PARAM_BOOL)) {
        $nombre = required_param('name', PARAM_TEXT);
        $archivo = required_param('filename', PARAM_TEXT);

        $ruta->name = $nombre;
        $ruta->filename = $archivo;
        $DB->update_record('learningstylesurvey_paths', $ruta);

        redirect(new moodle_url('/mod/learningstylesurvey/edit_learningpath.php', ['courseid' => $courseid]), "Ruta actualizada.", 1);
    }

    // Cargar archivos de la carpeta uploads
    $uploadspath = __DIR__ . '/uploads';
    $archivos = is_dir($uploadspath) ? array_diff(scandir($uploadspath), ['.', '..']) : [];

    echo "<form method='post'>";
    echo "<input type='hidden' name='update' value='1'>";
    echo "<input type='hidden' name='courseid' value='{$courseid}'>";
    echo "<input type='hidden' name='id' value='{$ruta->id}'>";
    echo "<label>Nombre:</label><br>";
    echo "<input type='text' name='name' value='" . s($ruta->name) . "' required><br><br>";

    echo "<label>Archivo:</label><br>";
    echo "<select name='filename' required>";
    echo "<option value=''>Seleccione un archivo</option>";
    foreach ($archivos as $archivo) {
        $selected = ($archivo === $ruta->filename) ? 'selected' : '';
        echo "<option value='{$archivo}' {$selected}>{$archivo}</option>";
    }
    echo "</select><br><br>";

    echo "<button type='submit'>Guardar cambios</button>";
    echo "</form>";
}

$urlreturn = new moodle_url('/mod/learningstylesurvey/learningpath.php', ['courseid' => $courseid]);
echo "<br><a href='{$urlreturn}' class='btn btn-secondary'>Regresar</a>";
echo $OUTPUT->footer();
