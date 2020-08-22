<?php

namespace App\Models;

class Paging
{
    private $page_size = 2;
    private $link_padding = 10;
    private $page_link_separator = ' ';
    private $next_page_text = '<img src="/images/rightarrow.png" >';
    private $first_text = '<img src="/images/2leftarrow.png" >';
    private $last_text = '<img src="/images/2rightarrow.png" >';
    private $prev_page_text = '<img src="/images/leftarrow.png" >';
    private $result_text_pattern = 'Стр.  %s по %s из  %s';
    private $page_var = 'p';
    private $db;
    private $q;
    private $total_rows;
    private $total_pages;
    private $cur_page;
    private $log = '';
    private $pas = '';
    private $srv = 'localhost';
    private $dBase = '';

    public function __construct($db, $q = '', $page_var = 'p')
    {
        $this->setConfig();

        $_DB = new \mysqli($this->srv, $this->log, $this->pas, $this->dBase);
        $_DB->set_charset('utf8');

        $this->db = $_DB;

        if ($q)
            $this->set_query($q);

        $this->page_var = $page_var;

        $this->cur_page = isset($_GET[$this->page_var]) && (int)$_GET[$this->page_var] > 0 ? (int)$_GET[$this->page_var] : 1;
    }

    public function setConfig()
    {
        //require('./application/config.php');
        $this->srv = HOST;
        $this->log = LOGIN;
        $this->pas = PASSWD;
        $this->dBase = MYSQL_DB;
    }

    public function set_query($q)
    {

        $this->q = $q;
    }

    public function set_page_size($page_size)
    {

        $this->page_size = abs((int)$page_size);
    }

    public function set_link_padding($padding)
    {

        $this->link_padding = abs((int)$padding);
    }

    public function get_page($q = '')
    {

        if ($q)
            $this->set_query($q);


        $r = $this->db->query($this->query_paging($this->q));

        $this->total_rows = array_pop($this->db->query('SELECT FOUND_ROWS()')->fetch_row());


        if ($this->page_size !== 0)
            $this->total_pages = ceil($this->total_rows / $this->page_size);


        if ($this->cur_page > $this->total_pages) {

            $this->cur_page = $this->total_pages;

            if ($this->total_pages > 0)
                $r = $this->db->query($this->query_paging($this->q));
        }


        return $r;
    }

    public function getStartPage()
    {
        return (($this->cur_page - 1) * $this->page_size) + 1;
    }

    public function get_result_text()
    {

        $start = (($this->cur_page - 1) * $this->page_size) + 1;

        $end = (($start - 1 + $this->page_size) >= $this->total_rows) ? $this->total_rows : ($start - 1 + $this->page_size);


        return sprintf($this->result_text_pattern, $start, $end, $this->total_rows);
    }

    public function get_page_links()
    {

        if (!isset($this->total_pages))
            return '';


        $page_link_list = array();


        $start = $this->cur_page - $this->link_padding;

        if ($start < 1)
            $start = 1;

        $end = $this->cur_page + $this->link_padding - 1;

        if ($end > $this->total_pages)
            $end = $this->total_pages;


        if ($start > 1)
            $page_link_list[] = $this->get_page_link($start - 1, $start - 2 > 0 ? '...' : '');

        for ($i = $start; $i <= $end; $i++)
            $page_link_list[] = $this->get_page_link($i);

        if ($end + 1 < $this->total_pages)
            $page_link_list[] = $this->get_page_link($end + 1, $end + 2 == $this->total_pages ? '' : '...');

        if ($end + 1 <= $this->total_pages)
            $page_link_list[] = $this->get_page_link($this->total_pages);


        return implode($this->page_link_separator, $page_link_list);
    }

    public function get_next_page_link()
    {

        return isset($this->total_pages) && $this->cur_page < $this->total_pages ? $this->get_page_link($this->cur_page + 1, $this->next_page_text) : '';
    }

    public function get_last_page_link()
    {

        return isset($this->total_pages) && $this->cur_page < $this->total_pages ? $this->get_page_link($this->total_pages, $this->last_text) : '';
    }

    public function get_prev_page_link()
    {

        return isset($this->total_pages) && $this->cur_page > 1 ? $this->get_page_link($this->cur_page - 1, $this->prev_page_text) : '';
    }

    public function get_first_page_link()
    {

        return isset($this->total_pages) && $this->cur_page > 1 ? $this->get_page_link(1, $this->first_text) : '';
    }

    private function get_page_link($page, $text = '')
    {

        if (!$text)
            $text = $page;


        if ($page != $this->cur_page) {

            $reg = '/((&|^)' . $this->page_var . '=)[^&#]*/';

            $url = '?' . (preg_match($reg, $_SERVER['QUERY_STRING']) ? preg_replace($reg, '${1}' . $page, $_SERVER['QUERY_STRING']) : ($_SERVER['QUERY_STRING'] ? $_SERVER['QUERY_STRING'] . '&' : '') . $this->page_var . '=' . $page);

            return '<a href="' . $url . '">' . $text . '</a>';
        }

        return '<span>' . $text . '</span>';
    }

    private function query_paging()
    {

        $q = $this->q;


        if ($this->page_size != 0) {

            $start = ($this->cur_page - 1) * $this->page_size;

            $q = preg_replace('/^SELECT\s+/i', 'SELECT SQL_CALC_FOUND_ROWS ', $this->q) . " LIMIT {$start},{$this->page_size}" . ';';
        }

        return $q;
    }

    public function get_pageSize()
    {

        return $this->page_size;
    }

    public function get_TotalRecords()
    {

        return $this->total_rows;
    }

    public function updateRow($q, $params)
    {
        $stmt = $this->db->prepare($q);

        $tmp = array();
        $types = '';
        foreach ($params as $key => $value) {
            $types .= 's';
            $tmp[$key] = &$params[$key];
        }
        array_unshift($tmp, $types);
        call_user_func_array(array($stmt, 'bind_param'), $tmp);
        $stmt->execute();
    }

    public function insertRow($q, $params)
    {
        $stmt = $this->db->prepare($q);

        $tmp = array();
        $types = '';
        foreach ($params as $key => $value) {
            $types .= 's';
            $tmp[$key] = &$params[$key];
        }
        array_unshift($tmp, $types);
        call_user_func_array(array($stmt, 'bind_param'), $tmp);
        $stmt->execute();

        return $stmt->insert_id;
    }


    public function selectRecords($q, $params = NULL)
    {
        $stmt = $this->db->prepare($q);
        //print_r($params); exit;
        if ($params) {
            $tmp = array();
            $types = '';
            foreach ($params as $key => $value) {
                $types .= 's';
                $tmp[$key] = &$params[$key];
            }
            array_unshift($tmp, $types);
            call_user_func_array(array($stmt, 'bind_param'), $tmp);
        }

        $stmt->execute();

        $parameters = array();
        $results = array();
        $meta = $stmt->result_metadata();

        while ($field = $meta->fetch_field()) {
            $parameters[] = &$row[$field->name];
        }

        call_user_func_array(array($stmt, 'bind_result'), $parameters);

        while ($stmt->fetch()) {
            $x = array();
            foreach ($row as $key => $val) {
                $x[$key] = $val;
            }
            $results[] = $x;
        }

        return $results;
    }
}