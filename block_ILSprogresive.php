<?php
class block_myplugin extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_myplugin');
    }

    public function get_content() {
        global $OUTPUT, $PAGE;
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $url_questionnaire = new moodle_url('/blocks/myplugin/views/questionnaire.php');
        $url_graph = new moodle_url('/blocks/myplugin/views/graph.php');

        $this->content->text = html_writer::link($url_questionnaire, get_string('take_questionnaire', 'block_myplugin')) . "<br>" .
                               html_writer::link($url_graph, get_string('view_graph', 'block_myplugin'));

        return $this->content;
    }
}
