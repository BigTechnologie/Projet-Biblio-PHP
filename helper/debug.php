<?php

if (!function_exists('dd')) { 
    function dd(...$vars): void 
    {
        echo "<pre style='background:#222;color:#eee;padding:10px;border-radius:5px;'>";

        foreach ($vars as $var) {
          
            var_dump($var);
           
            echo "<br>"; 
        }

        echo "</pre>";

        exit;
    }

}

