<?php
header('Access-Control-Allow-Credentials: true');
session_start();

$routes["user"] = array(
                        "class"=>"Users",
                        "method"=>"selectUser");
$routes["user/mail"] = array(
                        "class"=>"resetPassword",
                        "method"=>"sendMail");
$routes["user/resetPass"] = array(
                        "class"=>"resetPassword",
                        "method"=>"resetPassword");
$routes["students"] = array(
                        "class"=>"Users",
                        "method"=>"selectAllStudents");
$routes["user/signup"] = array(
                        "class"=>"Users",
                        "method"=>"createUser");
$routes["user/login"] = array(
                        "class"=>"Users",
                        "method"=>"loginUser");
$routes["user/edit"] = array(
                        "class"=>"Users",
                        "method"=>"editUser");

$routes["user/editEmail"] = array(
                        "class"=>"Users",
                        "method"=>"editUserEmail");
$routes["user/editImage"] = array(
                        "class"=>"Users",
                        "method"=>"editUserImage");
$routes["user/logout"] = array(
                        "class"=>"Users",
                        "method"=>"logout");
$routes["user/stud_session"] = array(
                        "class"=>"Users",
                        "method"=>"checkStudentSession");
$routes["user/prof_session"] = array(
                        "class"=>"Users",
                        "method"=>"checkProfesorSession");


$routes["courses"] = array(
                            "class"=>"Courses",
                            "method"=>"getCourses");
$routes["courses/info"] = array(
                            "class"=>"Courses",
                            "method"=>"getCourseInfo");
$routes["courses/page"] = array(
                            "class"=>"Courses",
                            "method"=>"getAllCourses");
$routes["courses/add"] = array(
                            "class"=>"Courses",
                            "method"=>"getAllCourses");

$routes["courses/edit"] = array(
                            "class"=>"Courses",
                            "method"=>"editCourse");

$routes["courses/item"] = array(
                            "class"=>"Courses",
                            "method"=>"getCourse");

$routes["courses/student"] = array(
                            "class"=>"Courses",
                            "method"=>"getStudentCourses");

$routes["courses/remove"] = array(
                            "class"=>"Courses",
                            "method"=>"removeCourseStudent");
$routes["courses/studAdd"] = array(
                            "class"=>"Courses",
                            "method"=>"addCourseStudent");


    define ("API_DIR", "/Elearn/api/");
    $redirectUrl = $_SERVER["REDIRECT_URL"];
    $page = str_replace(API_DIR, "", $redirectUrl);

    $page = rtrim($page, "/"); 
    $page = ltrim($page, "/"); 

    if (array_key_exists($page, $routes)){
        $method = $_SERVER["REQUEST_METHOD"];
        switch ($method) {
            case "POST":
                $content = file_get_contents("php://input");
                $data = json_decode($content, true);
                if ($data) {
                    $_POST = $data;
                }
                break; 
            case "PUT":
                $content = file_get_contents("php://input");
                $PUT = json_decode($content, true);
                break;
            case "DELETE":
                $content = file_get_contents("php://input");
                $DELETE = json_decode($content, true);
                break;
        }
        require "controller/".$routes[$page]["class"]. ".php";

        $controller = new $routes[$page]["class"];
        $method=$routes[$page]["method"];
        $result = $controller->$method();
    }
    else {
        $result = ["error" => "page not found"];
        http_response_code(404);
    }

    header("Content-Type: application/json");
    echo json_encode($result);