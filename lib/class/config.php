<?php
$conf = array(
    'host' => 'localhost',
    'user' => 'admin',
    'pass' => 'admin',
    'db' => 'uts4',
);
$role = array(
    1 => array(
        'categories' => array('create', 'index', 'view', 'update', 'delete'),
        'barang' => array('create', 'index', 'view', 'update', 'delete'),
        'users' => array('create', 'index', 'view', 'update', 'delete'),
    ),
    2 => array(
        'categories' => array('create', 'index', 'view'),
        'barang' => array('create', 'index', 'view'),
    ),
);

// menggunakan autoload
// memudahkan tidak perlu pakai include
// syaratnya, nama class sama dengan nama file 
spl_autoload_register(function ($class_name) {
    // tentukan filename nya, diakhiri dengan php
    // $split = explode('Controller', $class_name);
    // $files = 'Controller' . ucwords($split[1]) . '.php';
    $files = $class_name . '.php';
    //folder yang berisi class 
    $folder = array('Model', 'Controller', 'class');
    // loop
    foreach ($folder as $d) {
        $filename = './lib/' . $d . '/' . $files;
        if (is_file($filename)) {
            require_once($filename);
            return;
        }
    }
    die('file ' . $files . ' tidak ditemukan');
    return;
});
// $files = 'lib/%s/%s.php';
// require_once(sprintf($files, 'class', 'Controller'));
// require_once(sprintf($files, 'class', 'Model'));

// require_once(sprintf($files, 'Controller', 'ControllerUser'));
// require_once(sprintf($files, 'Model', 'User'));

// require_once(sprintf($files, 'Controller', 'ControllerCategories'));
// require_once(sprintf($files, 'Model', 'Categories'));
