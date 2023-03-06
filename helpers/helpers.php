<?php
if (!function_exists('markdown_task_list')) {
    /**
     * Function markdown_task_list
     *
     * @param $content
     *
     * @return array|string|string[]
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 06/03/2023 08:08
     */
    function markdown_task_list($content)
    {
        return str_replace(
            array(
                '<li>[ ] ',
                '<li>[x] '
            ),
            array(
                '<li class="task-list-item"><input type="checkbox" class="task-list-item-checkbox" disabled="disabled"> ',
                '<li class="task-list-item"><input type="checkbox" class="task-list-item-checkbox" checked="checked" disabled="disabled"> '
            ),
            $content
        );
    }
}
