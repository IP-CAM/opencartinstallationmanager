<?php

if (isset($_POST['install'])) {
    $_POST['oc_versions'];
    if (create($_POST['store'], $_POST['oc_versions'])) {
        showAlert('store created successfully');
    } else {
        showAlert('Something went wrong, try again.', 'danger');
    }
}

if (isset($_POST['delete'])) {
    if (delete($_POST['store'])) {
        showAlert('store deleted successfully');
    } else {
        showAlert('Something went wrong, try again.', 'danger');
    }
}

if (isset($_POST['refresh_store'])) {
    $version = getVersionZip($_POST['store']) != '' ? getVersionZip($_POST['store']) : 'oc3_0_3.zip';

    if (delete($_POST['store']) && create($_POST['store'], $version)) {
        showAlert('store refreshed successfully');
    } else {
        showAlert('Something went wrong, try again.', 'danger');
    }
}

function create($store, $oc_version) {
    $store = str_replace(" ", "_", $store);
    // $db = str_replace("/", "", $store);
    $db = 'opencart';
    $prefix = str_replace("/", "", $store);
    $drive = getDrive();

    $target = STORE_DIR . $store . '/';
    @mkdir($target);

    $mysqli = dbObj();

    if (!$mysqli->query("Create database if not exists " . $db)) {
        die("Failed creating new database: " . $db);
    }

    $mysqli->select_db($db);

    $zip = new ZipArchive;

    $res = $zip->open(ASSETS_DIR . $oc_version);

    if ($res === true) {

        $zip->extractTo($target);
        $zip->close();

        $path_parts = pathinfo($oc_version);

        $oldMessage = "{name}";
        $oldDrive = "{drive}";

        $newmessage = $store;

        $main = file_get_contents($target . 'config.php');

        $main = str_replace($oldMessage, $newmessage, $main);
        $main = str_replace($oldDrive, $drive, $main);
        $main = str_replace($newmessage, $prefix, $main);

        file_put_contents($target . 'config.php', $main);

        $admin = file_get_contents($target . 'admin/config.php');
        $admin = str_replace($oldMessage, $newmessage, $admin);
        $admin = str_replace($oldDrive, $drive, $admin);
        $admin = str_replace($newmessage, $prefix, $admin);

        file_put_contents($target . 'admin/config.php', $admin);

        $sql = file_get_contents(ASSETS_DIR . $path_parts["filename"] . '.sql');
        $sql = str_replace('oc_', $prefix . '_', $sql);
        $mysqli->multi_query($sql);

        $mysqli->query("UPDATE setting set value='" . STORE_URL . $store . "/' WHERE key='config_url");
        $mysqli->close();

        return true;
    } else {
        return false;
    }
}

function delete($store) {
    $dir = STORE_DIR . $store;

    if (!emptyDir($dir)) {
        return false;
    }

    $mysqli = dbObj();
    // Old delete
    // if (!$mysqli->query("DROP DATABASE " . $store)) {
    //     return false;
    // }

    // New Delete
    $sql = "SELECT CONCAT('DROP TABLE ', TABLE_SCHEMA, '.', TABLE_NAME, ';') as 'query'
            FROM INFORMATION_SCHEMA.TABLES 
            WHERE TABLE_NAME LIKE '$store%' 
            AND TABLE_SCHEMA = 'opencart'";

    $result = $mysqli->query($sql);

    while($row = $result->fetch_assoc()) {
        $mysqli->query($row['query']);
    }

    return true;
}

function emptyDir($dir) {
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? emptyDir("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

function dbObj() {
    return new mysqli('localhost', 'root', '');
}

function showAlert($msg, $class = 'success') {
    echo "<div class='alert alert-{$class} alert-dismissible fade show custom-alert' role='alert'>
            <strong>Success!</strong> $msg.
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
        </div>";
}

function getDrive() {
    $cwd = explode(':', STORE_DIR);
    return $cwd[0];
}

function getVersion($store) {
    if(file_exists(STORE_DIR . $store .'/index.php')) {
        $main = explode("'", file_get_contents(STORE_DIR . $store .'/index.php'));
        $version = isset($main[3]) ? $main[3] : '';
        return $version;
    }
}

function getVersionZip($store) {
    $version = getVersion($store);
    $version = substr($version, 0, -2);
    $version = str_replace('.', '_', $version);
    $version = 'oc' . $version . '.zip';
    return $version;
}