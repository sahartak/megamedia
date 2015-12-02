<?php
import('forms');
import('users');
import('roles');

if( !empty($_POST) ){
    if( trim($_POST['USER_LOGIN_ID']) == '' ){$errors[] = 'Please fill in username';}
    if( trim($_POST['CURRENT_PASSWORD']) == '' ){$errors[] = 'Please fill in password';}

    if( empty($errors) ){
        if( user_login_new($_POST['USER_LOGIN_ID'], $_POST['CURRENT_PASSWORD']) ){


            $_SESSION['user']['IS_ADMIN'] = true;
            $redirect_url = '/test/index';
            header(sprintf("location: %s", $redirect_url));
            exit();
        }
        else{
            $errors[] = 'Invalid username and/or password';


        }
    }
}

$link = THEME . 'index.php';
require_once($link);
?>
