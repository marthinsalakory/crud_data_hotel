<?php

if (!session_id()) session_start();

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'hotel';

$db_conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

$SCRIPT_FILENAME = explode('/', $_SERVER['SCRIPT_FILENAME']);
$FILENAME = explode('.', end($SCRIPT_FILENAME))[0];

function navon($nav)
{
    $SCRIPT_FILENAME = explode('/', $_SERVER['SCRIPT_FILENAME']);
    $FILENAME = explode('.', end($SCRIPT_FILENAME));
    if ($FILENAME[0] == $nav) {
        return 'active';
    }
}

function db_query($query)
{
    global $db_conn;
    return mysqli_query($db_conn, $query);
}

function db_insert($table, $array = [])
{
    $key = '`' . implode('`, `', array_keys($array)) . '`';
    $value = '\'' . implode('\', \'', $array) . '\'';
    $query = "INSERT INTO `$table` ($key) VALUES ($value)";
    return db_query($query);
}

function db_update($table, $array1 = [], $array2 = [])
{
    $i = 0;
    foreach ($array1 as $k => $v) {
        $i++;
        $key[$i] = '`' . $k . '`=\'' . $v . '\'';
    }
    $key = implode(' && ', $key);

    $j = 0;
    foreach ($array2 as $k => $v) {
        $j++;
        $val[$j] = '`' . $k . '`=\'' . $v . '\'';
    }
    $val = implode(', ', $val);

    return db_query("UPDATE `$table` SET $val WHERE $key");
}

function db_delete($table, $array)
{
    foreach ($array as $key => $val) {
        if (isset($result)) {
            $result = $result . ' && ' . $key .  ' = \'' . $val . '\'';
        } else {
            $result = $key .  ' = \'' . $val . '\'';
        }
    }
    return db_query("DELETE FROM `$table` WHERE $result");
}

function old_value($key)
{
    if (isset($_POST[$key])) {
        return $_POST[$key];
    }
}


if (isset($_GET['database'])) {
    echo "db_host = " . $db_host . '<br>';
    echo "db_user = " . $db_user . '<br>';
    echo "db_pass = " . $db_pass . '<br>';
    echo "db_name = " . $db_name . '<br>';
    die;
}

function setFlash($pesan)
{
    $_SESSION['flash'][] = ucfirst($pesan);
}

function flash()
{
    if (isset($_SESSION['flash'])) {
        $pesan = '';
        foreach ($_SESSION['flash'] as $flash) {
            $pesan = $pesan . '<li>' . $flash . '</li>';
        }
        echo '
        <div class="alert alert-primary alert-dismissible fade show mt-3" role="alert">
            <strong>Perhatian!</strong>
            <ul>
                ' . $pesan . '
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        unset($_SESSION['flash']);
    }
}

function isFlash()
{
    if (isset($_SESSION['flash'])) {
        return true;
    }
    return false;
}

function db_findAll($table, $array = [])
{
    if (empty($array)) {
        return db_query("SELECT * FROM $table");
    } else {
        foreach ($array as $key => $val) {
            if (isset($result)) {
                $result = $result . ' && ' . $key .  ' LIKE \'%' . $val . '%\'';
            } else {
                $result = $key .  ' LIKE \'%' . $val . '%\'';
            }
            return db_query("SELECT * FROM $table WHERE $result");
        }
    }
}

function db_find($table, $where)
{
    foreach ($where as $key => $val) {
        if (isset($result)) {
            $result = $result . ' && ' . $key .  ' = \'' . $val . '\'';
        } else {
            $result = $key .  ' = \'' . $val . '\'';
        }
    }
    return db_query("SELECT * FROM `$table` WHERE $result")->fetch_object();
}

function db_findOr($table, $where)
{
    foreach ($where as $key => $val) {
        if (isset($result)) {
            $result = $result . ' || ' . $key .  ' = \'' . $val . '\'';
        } else {
            $result = $key .  ' = \'' . $val . '\'';
        }
    }
    return db_query("SELECT * FROM `$table` WHERE $result")->fetch_object();
}

function db_count($table, $array)
{
    foreach ($array as $key => $val) {
        if (isset($result)) {
            $result = $result . ' && ' . $key .  ' = \'' . $val . '\'';
        } else {
            $result = $key .  ' = \'' . $val . '\'';
        }
    }
    return db_query("SELECT * FROM `$table` WHERE $result")->num_rows;
}
