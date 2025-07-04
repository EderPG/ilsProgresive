
<?php
require_once('../../config.php');
global $DB;
require_login();

$data = json_decode(file_get_contents('php://input'), true);

foreach ($data as $item) {
    if ($item['tipo'] === 'recurso') {
        $DB->set_field('learningstylesurvey_inforoute', 'steporder', $item['orden'], ['id' => $item['id']]);
    } elseif ($item['tipo'] === 'examen') {
        $DB->set_field('learningstylesurvey_quizzes', 'orden', $item['orden'], ['id' => $item['id']]);
    }
}

echo "ok";
?>
