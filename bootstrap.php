<?php

Autoloader::add_namespace('Table', __DIR__.'/classes/');

Autoloader::add_core_namespace('Table');

Autoloader::add_core_namespace('Table', true);

Autoloader::add_classes(array(
    'Table\\Table' => __DIR__.'/classes/table.php',
));