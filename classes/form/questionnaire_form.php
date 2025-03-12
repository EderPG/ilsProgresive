<?php
require_once("$CFG->libdir/formslib.php");

class questionnaire_form extends moodleform {
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        // Número total de preguntas (ajusta según tus necesidades)
        $total_questions = 44;

        // Ciclo para agregar las preguntas dinámicamente
        for ($i = 1; $i <= $total_questions; $i++) {
            $question_key = 'ilsq' . $i;
            $option1_key = 'ilsq' . $i . 'a0';
            $option2_key = 'ilsq' . $i . 'a1';

            $mform->addElement('select', $question_key, get_string($question_key, 'block_ILSprogresive'), [
                get_string($option1_key, 'block_ILSprogresive'),
                get_string($option2_key, 'block_ILSprogresive')
            ]);
        }

        $mform->addElement('submit', 'submitbutton', get_string('submit', 'block_ILSprogresive'));
    }
}