<?php
include "DB.php";
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])){
        header("Access-Control-Allow-Methods: GET, POST, OPTIONSM PUT, DELETE");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])){
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
    }
    exit(0);
}

$received =json_decode(file_get_contents('php://input'));
$data = [];
$action ="";
if (isset($_GET['action'])){
    $action = $_GET['action'];
}
if ($action == 'fetchData'){
    $res = $con->query("SELECT * FROM carts");
    if (mysqli_num_rows($res) > 0){
        $data = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
        echo json_encode($data);
    }
}elseif ($action == 'AddCart'){
    $fileName = time() . $_FILES['image']['name'];
    if (move_uploaded_file($_FILES['image']['tmp_name'], "../assets/uploads/" . $fileName)) {
        echo "AddCart" . " / " . $_FILES['image']['name'] . " / " . $_POST['title'] . " / " . $_POST['details'];
        $con->query("INSERT INTO carts (title, details,image) VALUES ('{$_POST['title']}', '{$_POST['details']}', '{$fileName}')");
    }
}elseif ($action == 'editCart'){
    $res = $con->query("SELECT * FROM carts WHERE id={$_POST['id']}");
    if (mysqli_num_rows($res) > 0){
        $data = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
        echo json_encode($data);
    }
}elseif ($action == 'updateCart'){
    $res = $con->query("SELECT image FROM carts WHERE id = {$_POST['id']}");
    while ($row = mysqli_fetch_assoc($res)) {
        $title = $_POST['title'];
        $details = $_POST['details'];
        if ($row['image'] != 'default.png'){
            $fileName = time() . $_FILES['image']['name'];
            if (! empty($_FILES['image']['name'])){
                unlink('../assets/uploads/' . $row['image']);
                move_uploaded_file($_FILES['image']['tmp_name'], "../assets/uploads/" . $fileName);
                $con->query("UPDATE carts SET title = '{$title}', details = '{$details}', image = '{$fileName}' WHERE id = {$_POST['id']}");
            }else{
                $con->query("UPDATE carts SET title = '{$title}', details = '{$details}' WHERE id = {$_POST['id']}");
            }
        }
    }
}elseif ($action == 'deleteCart'){
    $res = $con->query("SELECT image FROM carts WHERE id={$_POST['id']}");
    while ($row = mysqli_fetch_assoc($res)) {
        echo $row['image'];
        if ($row['image'] != 'default.png'){
            unlink('../assets/uploads/' . $row['image']);
        }
        $con->query("DELETE FROM carts WHERE id={$_POST['id']}");
    }
}