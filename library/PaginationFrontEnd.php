<?php
/**
 * Created by PhpStorm.
 * User: huuqu
 * Date: 1/15/2018
 * Time: 4:45 AM
 */

namespace App\Library;


class PaginationFrontEnd
{
    public static function showPagination($total_pages , $current , $before , $next , $last )
    {
        if ($total_pages > 1) {
            $start = '';
            $prev = '';
            if ($current > 1) {
                $prev = "<li class=\"arrow\"><a href='?page=" . $before . "'>« Trước</a></li>";
            }
            $nexts = '';
            $end = '';
            if ($current < $total_pages) {
                $nexts = "<li class=\"arrow\"><a href='?page=" . $next . "'>Kế tiếp &raquo;</a></li>";
            }
            if (3 < $total_pages) {
                if ($current == 1) {
                    $startPage = 1;
                    $endPage = 2;
                } else if ($current == $total_pages) {
                    $startPage = $total_pages - 1 + 1;
                    $endPage = $total_pages;
                } else {
                    $startPage = $current - (1 - 1) / 2;
                    $endPage = $current + (1 - 1) / 2;
                    if ($startPage < 1) {
                        $endPage = $endPage + 1;
                        $startPage = 1;
                    }
                    if ($endPage > $total_pages) {
                        $endPage = $total_pages;
                        $startPage = $endPage - 1 + 1;
                    }
                }

            } else {
                $startPage = 1;
                $endPage = 2;
            }
            $listPages = '';
            for ($i = $startPage; $i <= $endPage; $i++) {
                if ($i == $current) {

                    $listPages .= '<li class="current"><a href="?page=' . $i . '">' . $i . '</a></li>';
                } else {
                    $listPages .= '<li><a href="?page=' . $i . '">' . $i . '</a></li>';
                }
            }
            return self::prevHtml() . $prev . $listPages . $nexts . self::lastHtml();
        }
    }

    public static function prevHtml(){
        return '<div class="toolbar"><div class="pagination-centered"><ul class="pagination">';
    }
    public static function lastHtml(){
        return '</ul></div></div>';
    }

}