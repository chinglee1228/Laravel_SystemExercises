<?php

namespace App\Helpers;

class ServerEvent
{
    public static function send($data, $new_line = "\n")
    {
        $chunks = str_split($data, 2048); // 將數據分割成2048位元組的塊

        foreach ($chunks as $chunk) {
            echo "{$chunk}{$new_line}";

            // Check for an output error
            if (ob_get_level() > 0 && ob_get_length() > 0) {
                if (!@ob_flush()) {
                    // Handle the error appropriately
                    error_log('無法刷新輸出緩衝區');
                    break;
                }
            }

            // Check for a flush error
            if (!@flush()) {
                // Handle the error appropriately
                //無法刷新系統寫入緩衝區
                error_log('//無法刷新系統寫入緩衝區');
                break;
            }
        }
    }
}

