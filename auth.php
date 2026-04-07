<?php
session_start();
include "includes/db_connect.php";

if(isset($_POST['login'])){

    $input = trim($_POST['login_input']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    if($role == "Patient"){

        $stmt = $conn->prepare("SELECT * FROM users 
                                WHERE role='Patient' 
                                AND (email=? OR phone=?)");
        $stmt->bind_param("ss", $input, $input);

    } else {

        $stmt = $conn->prepare("SELECT * FROM users 
                                WHERE role=? 
                                AND (email=? OR phone=?)");
        $stmt->bind_param("sss", $role, $input, $input);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){

        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if($user['role'] == "Patient"){
                header("Location: patient_dashboard.php");
            } elseif($user['role'] == "Doctor"){
                header("Location: doctor_dashboard.php");
            } else {
                header("Location: admin_dashboard.php");
            }

            exit();

        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Incorrect password provided.'];
            header("Location: login.php");
            exit();
        }

    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'No account found with those credentials.'];
        header("Location: login.php");
        exit();
    }
}
?>