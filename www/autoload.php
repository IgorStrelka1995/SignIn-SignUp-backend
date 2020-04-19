<?php

spl_autoload_register(function ($class) {
    $source = str_replace("\\", "/", $class . ".php");
    $source = str_replace("App", "src", $source);

    if(file_exists(__DIR__ . "/" .$source)){
        include (__DIR__ . "/" . "$source");
    } 
});