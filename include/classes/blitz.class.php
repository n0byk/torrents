<?php

class View extends Blitz {
    var $news = array();

    function View($tmpl_name) {
        return parent::Blitz($tmpl_name);
    }

    function set_news($data) {
        $this->news = $data;
    }

    function list_news() {
        $result = '';
        foreach($this->news as $i_news) {
            $result .= $this->include('../templates/default/header.tpl', $i_news);
        }
        return $result;
    }
}

?>