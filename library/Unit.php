<?php
/**
 * Created by PhpStorm.
 * User: huuqu
 * Date: 1/15/2018
 * Time: 4:45 AM
 */

namespace App\Library;


class Unit
{
    public static function showPagination($total_pages , $current , $before , $next , $last )
    {
        if ($total_pages > 1) {
            $start = '';
            $prev = '';
            if ($current > 1) {
                $start = "<li><a href='?page=1'>Start</a></li>";
                $prev = "<li><a href='?page=" . $before . "'><<</a></li>";
            }
            $nexts = '';
            $end = '';
            if ($current < $total_pages) {
                $nexts = "<li><a href='?page=" . $next . "'>>></a></li>";
                $end = "<li><a href='?page=" . $last . "'>End</a></li>";
            }
            if (5 < $total_pages) {
                if ($current == 1) {
                    $startPage = 1;
                    $endPage = 4;
                } else if ($current == $total_pages) {
                    $startPage = $total_pages - 3 + 1;
                    $endPage = $total_pages;
                } else {
                    $startPage = $current - (3 - 1) / 2;
                    $endPage = $current + (3 - 1) / 2;
                    if ($startPage < 1) {
                        $endPage = $endPage + 1;
                        $startPage = 1;
                    }
                    if ($endPage > $total_pages) {
                        $endPage = $total_pages;
                        $startPage = $endPage - 3 + 1;
                    }
                }

            } else {
                $startPage = 1;
                $endPage = 3;
            }
            $listPages = '';
            for ($i = $startPage; $i <= $endPage; $i++) {
                if ($i == $current) {

                    $listPages .= '<li id="actions"><a href="?page=' . $i . '">' . $i . '</a></li>';
                } else {
                    $listPages .= '<li><a href="?page=' . $i . '">' . $i . '</a></li>';
                }
            }
            return $start . $prev . $listPages . $nexts . $end;
        }
    }

}