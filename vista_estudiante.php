
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vista del Estudiante</title>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 15px;
        }
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            padding: 20px;
        }
        .btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        require_once("../../config.php");
        global $DB, $USER;

        $courseid = required_param('courseid', PARAM_INT);
        require_login($courseid);

        echo "<h2>Ruta de Aprendizaje</h2>";
        echo "<button class='btn' onclick='toggleEditMode()'>Editar orden</button>";
        echo "<button class='btn' onclick='guardarOrden()'>Guardar nuevo orden</button>";

        echo "<div id='tarjetas'>";

        $resources = $DB->get_records_sql("
            SELECT id, filename, steporder as orden, 'recurso' as tipo FROM {learningstylesurvey_inforoute}
            WHERE courseid = ? ORDER BY steporder ASC", [$courseid]);

        $quizzes = $DB->get_records_sql("
            SELECT id, name as filename, orden, 'examen' as tipo FROM {learningstylesurvey_quizzes}
            WHERE courseid = ? ORDER BY orden ASC", [$courseid]);

        $elementos = array_merge($resources, $quizzes);
        usort($elementos, function($a, $b) {
            return $a->orden <=> $b->orden;
        });

        foreach ($elementos as $el) {
            echo "<div class='card' data-id='{$el->id}' data-tipo='{$el->tipo}'>";
            echo "<h3>" . format_string($el->filename) . "</h3>";

            if ($el->tipo === 'recurso') {
                echo "<a class='btn' href='download.php?id={$el->id}&courseid={$courseid}'>Descargar recurso</a>";
            } else {
                echo "<a class='btn' href='responder_quiz.php?id={$el->id}&courseid={$courseid}'>Responder examen</a>";
            }

            echo "</div>";
        }

        echo "</div>";
        ?>
    </div>

<script>
let editMode = false;
let sortable = null;

function toggleEditMode() {
    if (!editMode) {
        sortable = Sortable.create(document.getElementById('tarjetas'), {
            animation: 150
        });
        editMode = true;
    } else {
        if (sortable) sortable.destroy();
        editMode = false;
    }
}

function guardarOrden() {
    const tarjetas = document.querySelectorAll('#tarjetas .card');
    const orden = Array.from(tarjetas).map((el, index) => ({
        id: el.getAttribute('data-id'),
        tipo: el.getAttribute('data-tipo'),
        orden: index
    }));

    fetch('guardar_orden.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(orden)
    })
    .then(response => response.text())
    .then(data => alert('Orden guardado correctamente.'))
    .catch(error => alert('Error al guardar el orden.'));
}
</script>
</body>
</html>
