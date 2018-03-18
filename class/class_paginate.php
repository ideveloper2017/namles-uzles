<?php

/**
 * Created by PhpStorm.
 * User: IDeveloper
 * Date: 09.10.2015
 * Time: 11:26
 */
class Paginator
{
    public $items_per_page;
    public $items_total;
    public $num_pages = 1;
    public $limit;
    public $current_page;
    public $default_ipp;
    public $path = 0;
    public $path_after;
    private $mid_range;
    private $low;
    private $high;
    private $retdata;
    private $querystring;
    private static $instance;

    public function __construct()
    {
        $this->current_page = 1;
        $this->mid_range = 7;
        $this->items_per_page = (isset($_REQUEST['ipp']) and !empty($_REQUEST['ipp'])) ? ($_REQUEST['ipp']) : $this->default_ipp;
    }

    public function paginate()
    {
        $this->items_per_page = (isset($_GET['ipp']) and !empty($_GET['ipp'])) ? intval($_GET['ipp']) : $this->default_ipp;
        $this->num_pages = ceil($this->items_total / $this->items_per_page);

        $this->current_page = $_GET['pg'];
        if ($this->current_page < 1 or !is_numeric($this->current_page))
            $this->current_page = 1;
        if ($this->current_page > $this->num_pages)
            $this->current_page = $this->num_pages;
        $prev_page = $this->current_page - 1;
        $next_page = $this->current_page + 1;

        if (isset($_GET)) {
            $args = explode("&amp;", $_SERVER['QUERY_STRING']);
            foreach ($args as $arg) {
                $keyval = explode("=", $arg);
                if ($keyval[0] != "pg" && $keyval[0] != "ipp")
                    $this->querystring .= "&amp;" . $arg;
            }
        }

        if (isset($_POST)) {

            foreach ($_POST as $key => $val) {
                if ($key != "pg" && $key != "ipp")
                    $this->querystring .= "&amp;$key=" . $val;
            }
        }

        if ($this->num_pages > 1) {
            if ($this->current_page != 1 && $this->items_total >= $this->default_ipp) {
                if ($this->path) {
                    $this->retdata = "<li><span><a class=\"prev page-numbers\" href=\"".$this->path."pg=".$prev_page."{$this->path_after}\">«</a></span></li>";
                } else {
                    $this->retdata = "<span><a class=\"item\" href=\"" . $_SERVER['PHP_SELF'] . "?pg=$prev_page&amp;ipp=$this->items_per_page$this->querystring\"><i class=\"icon left arrow\"></i></a></span>  ";
                }
            } else {
                $this->retdata = "<li><span><a class=\"disabled item\"><</a></span></li>";
            }

            $this->start_range = $this->current_page - floor($this->mid_range / 2);
            $this->end_range = $this->current_page + floor($this->mid_range / 2);

            if ($this->start_range <= 0) {
                $this->end_range += abs($this->start_range) + 1;
                $this->start_range = 1;
            }
            if ($this->end_range > $this->num_pages) {
                $this->start_range -= $this->end_range - $this->num_pages;
                $this->end_range = $this->num_pages;
            }
            $this->range = range($this->start_range, $this->end_range);

            for ($i = 1; $i <= $this->num_pages; $i++) {
                if ($this->range[0] > 2 && $i == $this->range[0])
                    $this->retdata .= "<li><span><a class=\"disabled item\"> ... </a></span></li>";

                if ($i == 1 or $i == $this->num_pages or in_array($i, $this->range)) {
                    if ($i == $this->current_page) {
                        $this->retdata .= "<li><span><a title=\"" . Lang::$word->_PAG_GOTO . $i . Lang::$word->_PAG_OF . $this->num_pages . "\" class=\"active item pi-active\">$i</a></span></li>";
                    } else {
                        if ($this->path) {
                            $this->retdata .= "<li><span><a class=\"item\" title=\"Go To $i of $this->num_pages\" href=\"".$this->path."pg=$i{$this->path_after}\">$i</a></span></li>";
                        } else {
                            $this->retdata .= "<li><span><a class=\"item\" title=\"Go To $i of $this->num_pages\" href=\"" . $_SERVER['PHP_SELF'] . "?pg=$i&amp;ipp=$this->items_per_page$this->querystring\">$i</a></span></li>";
                        }
                    }
                }

                if ($this->range[$this->mid_range - 1] < $this->num_pages - 1 && $i == $this->range[$this->mid_range - 1])
                    $this->retdata .= "<li><a class=\"disabled item\"> ... </a></li>";
            }

            if ($this->current_page != $this->num_pages && $this->items_total >= $this->default_ipp) {
                if ($this->path) {
                    $this->retdata .= "<li><span><a class=\"item\" href=\"".$this->path."pg=".$next_page."{$this->path_after}\">></a></span></li>";
                } else {
                    $this->retdata .= "<li><span><a class=\"item\" href=\"" . $_SERVER['PHP_SELF'] . "?pg=$next_page&amp;ipp=$this->items_per_page$this->querystring\">>> </i></a></span></li>";
                }
            } else {
                $this->retdata .= "<li><span><a class=\"disabled item\">»</a></span></li>";
            }

        } else {
            for ($i = 1; $i <= $this->num_pages; $i++) {
                if ($i == $this->current_page) {
                    $this->retdata .= "<li><a class=\"active item pi-active\">$i</a></li>";
                } else {
                    if ($this->path) {
                        $this->retdata .= "<li><a class=\"item\" href=\"".$this->path . "pg=$i{$this->path_after}\">$i</a></li>";
                    } else {
                        $this->retdata .= "<li><a class=\"item\" href=\"" . $_SERVER['PHP_SELF'] . "?pg=$i&amp;ipp=$this->items_per_page$this->querystring\">$i</a></li>";
                    }
                }
            }
        }
        $this->low = ($this->current_page - 1) * $this->items_per_page;
        $this->high = $this->current_page * $this->items_per_page - 1;
        $this->limit = ($this->items_total == 0) ? '' : " LIMIT $this->low,$this->items_per_page";
    }



    public function site_paginate()
    {
        $this->items_per_page = (isset($_GET['ipp']) and !empty($_GET['ipp'])) ? intval($_GET['ipp']) : $this->default_ipp;
        $this->num_pages = ceil($this->items_total / $this->items_per_page);

        $this->current_page = $_GET['pg'];
        if ($this->current_page < 1 or !is_numeric($this->current_page))
            $this->current_page = 1;
        if ($this->current_page > $this->num_pages)
            $this->current_page = $this->num_pages;
        $prev_page = $this->current_page - 1;
        $next_page = $this->current_page + 1;

        if (isset($_GET)) {
            $args = explode("&amp;", $_SERVER['QUERY_STRING']);
            foreach ($args as $arg) {
                $keyval = explode("=", $arg);
                if ($keyval[0] != "pg" && $keyval[0] != "ipp")
                    $this->querystring .= "&amp;" . $arg;
            }
        }

        if (isset($_POST)) {

            foreach ($_POST as $key => $val) {
                if ($key != "pg" && $key != "ipp")
                    $this->querystring .= "&amp;$key=" . $val;
            }
        }

        if ($this->num_pages > 1) {
            if ($this->current_page != 1 && $this->items_total >= $this->default_ipp) {
                if ($this->path) {
                    $this->retdata = "<li><span><a class=\"prev page-numbers\" href=\"".$this->path."pg=".$prev_page."{$this->path_after}\">«</a></span></li>";
                } else {
                    $this->retdata = "<span><a class=\"item\" href=\"" . $_SERVER['PHP_SELF'] . "?pg=$prev_page&amp;ipp=$this->items_per_page$this->querystring\"><i class=\"icon left arrow\"></i></a></span>  ";
                }
            } else {
                $this->retdata = "<li><span><a class=\"disabled item\"><</a></span></li>";
            }

            $this->start_range = $this->current_page - floor($this->mid_range / 2);
            $this->end_range = $this->current_page + floor($this->mid_range / 2);

            if ($this->start_range <= 0) {
                $this->end_range += abs($this->start_range) + 1;
                $this->start_range = 1;
            }
            if ($this->end_range > $this->num_pages) {
                $this->start_range -= $this->end_range - $this->num_pages;
                $this->end_range = $this->num_pages;
            }
            $this->range = range($this->start_range, $this->end_range);

            for ($i = 1; $i <= $this->num_pages; $i++) {
                if ($this->range[0] > 2 && $i == $this->range[0])
                    $this->retdata .= "<li><span><a class=\"disabled item\"> ... </a></span></li>";

                if ($i == 1 or $i == $this->num_pages or in_array($i, $this->range)) {
                    if ($i == $this->current_page) {
                        $this->retdata .= "<li><span><a title=\"" . Lang::$word->_PAG_GOTO . $i . Lang::$word->_PAG_OF . $this->num_pages . "\" class=\"active item pi-active\">$i</a></span></li>";
                    } else {
                        if ($this->path) {
                            $this->retdata .= "<li><span><a class=\"item\" title=\"Go To $i of $this->num_pages\" href=\"".$this->path."pg=$i{$this->path_after}\">$i</a></span></li>";
                        } else {
                            $this->retdata .= "<li><span><a class=\"item\" title=\"Go To $i of $this->num_pages\" href=\"" . $_SERVER['PHP_SELF'] . "?pg=$i&amp;ipp=$this->items_per_page$this->querystring\">$i</a></span></li>";
                        }
                    }
                }

                if ($this->range[$this->mid_range - 1] < $this->num_pages - 1 && $i == $this->range[$this->mid_range - 1])
                    $this->retdata .= "<li><a class=\"disabled item\"> ... </a></li>";
            }

            if ($this->current_page != $this->num_pages && $this->items_total >= $this->default_ipp) {
                if ($this->path) {
                    $this->retdata .= "<li><span><a class=\"item\" href=\"".$this->path."pg=".$next_page."{$this->path_after}\">></a></span></li>";
                } else {
                    $this->retdata .= "<li><span><a class=\"item\" href=\"" . $_SERVER['PHP_SELF'] . "?pg=$next_page&amp;ipp=$this->items_per_page$this->querystring\">>> </i></a></span></li>";
                }
            } else {
                $this->retdata .= "<li><span><a class=\"disabled item\">»</a></span></li>";
            }

        } else {
            for ($i = 1; $i <= $this->num_pages; $i++) {
                if ($i == $this->current_page) {
                    $this->retdata .= "<li><a class=\"active item pi-active\">$i</a></li>";
                } else {
                    if ($this->path) {
                        $this->retdata .= "<li><a class=\"item\" href=\"".$this->path . "pg=$i{$this->path_after}\">$i</a></li>";
                    } else {
                        $this->retdata .= "<li><a class=\"item\" href=\"" . $_SERVER['PHP_SELF'] . "?pg=$i&amp;ipp=$this->items_per_page$this->querystring\">$i</a></li>";
                    }
                }
            }
        }
        $this->low = ($this->current_page - 1) * $this->items_per_page;
        $this->high = $this->current_page * $this->items_per_page - 1;
        $this->limit = ($this->items_total == 0) ? '' : " LIMIT $this->low,$this->items_per_page";
    }

    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new Paginator();
        }

        return self::$instance;
    }

    public function jump_menu()
    {
        $option = '';
        for ($i = 1; $i <= $this->num_pages; $i++) {
            $option .= ($i == $this->current_page) ? "<option value=\"$i\" selected=\"selected\">$i</option>\n" : "<option value=\"$i\">$i</option>\n";
        }
        return "<select class=\"custombox\" onchange=\"window.location='$_SERVER[PHP_SELF]?pg='+this[this.selectedIndex].value+'&amp;ipp=$this->items_per_page$this->querystring';return false\" style=\"min-width:50px\">$option</select>\n";
    }

    public function items_per_page()
    {
        $items = '';
        $ipp_array = array(10, 25, 50, 75, 100);
//        $items .= "<option  value=\"\">Items per page</option>";
        foreach ($ipp_array as $ipp_opt)
            $items .= ($ipp_opt == $this->items_per_page) ? "<option selected=\"selected\" value=\"$ipp_opt\">$ipp_opt</option>\n" : "<option value=\"$ipp_opt\">$ipp_opt</option>\n";
        return ($this->num_pages >= 1) ? "<select class=\"select-liquid select2-offscreen\" onchange=\"window.location='" . $_SERVER['PHP_SELF'] . "?pg=1&amp;ipp='+this[this.selectedIndex].value+'$this->querystring';return false\">$items</select>\n" : '';
    }


    public function display_pages()
    {
        return ($this->items_total > $this->items_per_page) ? '<ul class="pagination page-numbers">'.$this->retdata.'</ul>' : "";
    }

    public function site_display_pages(){
        return ($this->items_total > $this->items_per_page) ? ''.$this->retdata.'' : "";
    }
}

?>