<?php
require_once('../../config.php');
require_login();

global $DB, $USER;

$courseid = required_param('courseid', PARAM_INT);
$context = context_course::instance($courseid);
$baseurl = new moodle_url('/mod/learningstylesurvey/createsteproute.php', array('courseid' => $courseid));
$returnurl = new moodle_url('/mod/learningstylesurvey/crear_ruta_actividad.php', array('courseid' => $courseid));

$PAGE->set_url($baseurl);
$PAGE->set_context($context);
$PAGE->set_title("Crear Ruta de Aprendizaje");
$PAGE->set_heading("Crear Ruta de Aprendizaje");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = required_param('nombre', PARAM_TEXT);
    $archivo = required_param('archivo', PARAM_TEXT);

    $data = new stdClass();
    $data->courseid = $courseid;
    $data->userid = $USER->id;
    $data->name = $nombre;
    $data->filename = $archivo;
    $data->timecreated = time();

    $DB->insert_record('learningstylesurvey_paths', $data);
    redirect($returnurl, "Ruta guardada correctamente.", 2);
}

echo $OUTPUT->header();
echo $OUTPUT->heading("Crear Ruta de Aprendizaje");

// Obtener archivos previamente subidos desde la carpeta uploads
$uploadspath = __DIR__ . '/uploads';
$archivos = [];
if (is_dir($uploadspath)) {
    $archivos = array_diff(scandir($uploadspath), array('.', '..'));
}
?>
<form method="post">
    <div>
        <label>Nombre de la ruta:</label><br>
        <input type="text" name="nombre" required>
    </div>
    <div style="margin-top: 10px;">
        <label>Cargar archivo:</label><br>
        <select name="archivo" required>
            <option value="">Seleccione un archivo</option>
            <?php foreach ($archivos as $file): ?>
                <option value="<?php echo $file; ?>"><?php echo $file; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div style="margin-top: 15px;">
        <button type="submit">Guardar ruta</button>
    </div>
</form>

<div style="margin-top: 20px;">
    <a href="<?php echo $returnurl; ?>">Regresar al menú anterior</a>
</div>

<?php
echo $OUTPUT->footer();
