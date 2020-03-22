<?php
require_once "models/UserModel.php";
require_once "PHPMailer/Mailer.php";

class Users
{
    private $user;
    private $mailer;

    function __construct()
    {
        $this->user = new UserModel();
        $this->mailer = new Mailer();
    }

    function selectUser()
    {
        $errors = [];

        if (isset($_SESSION['id'])) {
            return $this->user->selectUser(['id' => $_SESSION['id']]);
        } else {
            http_response_code(403);
            $errors['user'] = "You must log in.";
            return $errors;
        }
    }

    function selectAllStudents()
    {
        return $this->user->getStudents();
    }

    function createUser()
    {
        $filteredPost = $_POST;
        $errors = array();
        $name = $filteredPost['name'];
        $surname = $filteredPost['surname'];
        $email = $filteredPost['email'];
        $phone = $filteredPost['phone'];
        $passwd = $filteredPost['password'];
        $repass = $filteredPost['repassword'];
        $value = $filteredPost['value'];

        if (!empty($_FILES)) {
            if ($_FILES['file']['size'] > 2097152) {
                $errors['img'] = "File too large. File must be less than 2 megabytes";
            } else if (!preg_match('/\.(jpe?g|png|gif|bmp)$/', $_FILES['file']['name'])) {
                $errors['img'] = "Invalid file type. Only PDF, JPG, GIF, BMP and PNG types are accepted";
            } else {
                $currentDir = dirname(__FILE__);

                $tmpPath = $_FILES['file']['tmp_name'];
                $filePath = $currentDir . "/../uploads/" . $_FILES["file"]["name"];
                move_uploaded_file($tmpPath, $filePath);
                $filteredPost['file'] = $_FILES["file"]["name"];
            }
        }
        if (!empty($name) && strlen($name) > 2) {
            if (preg_match('/[@#$%^&*()+=\[\]\';,.\/{}|":<>?~\\\\0-9]/', $name)) {
                $errors['name'] = "Your first name can't contain special characters or numbers";
            }
        } else {
            $errors["name"] = "First Name required, it must contain at least 3 characters";
        }
        if (!empty($surname) && strlen($surname) > 2) {
            if (preg_match('/[@#$%^&*()+=\[\]\';,.\/{}|":<>?~\\\\\-0-9]/', $surname)) {
                $errors["surname"] = "Your last name can't contain special characters or numbers";
            }
        } else {
            $errors['surname'] = "Last Name required, it must contain at least 3 characters";
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email invalid";
        } else {
            $checkEmail = $this->user->selectEmail($_POST);
            if ($checkEmail) {
                $errors['email'] = "Email already taken!";
            }
        }
        if (!empty($phone)) {
            if (!preg_match('/[0-9]{10}$/', $phone)) {
                $errors['phone'] = "Phone number not valid";
            }
        } else {
            $errors['phone'] = "Phone number is required";
        }
        if (!empty($passwd) && ($passwd == $repass)) {
            if (!preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?!.*\s).*$#", $passwd)) {
                $errors['password'] = "The password must include: 8-20 Characters, at least one capital letter, at least one number, no spaces.";
            }
        } else {
            $errors['password'] = "Please Check You've Entered Or Confirmed Your Password!";
        }

        if (empty($errors)) {
            $salt = '$1$cryptpass$';
            $password = crypt($passwd, $salt);
            $filteredPost['password'] = $password;
            $rand = rand();
            $crypt = '$1$crypt';
            $token = rtrim(crypt($rand, $crypt), ".");
            $entry = $this->user->insertNewUserToken(['email' => $email, 'token' => $token]);

            if ($entry == true) {
                $url = 'http://localhost/Elearn/assets/templates/login.html?token=' . $token;
                $subject = "Account activation email";
                $to = $email;

                $from = "fictitious@company.com";
                $body = "<html>
                                    <head>
                                    <title>Email to confirm the registration:</title>
                                    </head>
                                <body>
                                    <h3>Hello Mr./ Mrs.,</h3>
                                    <p> " . $name . "</p>
                                    <h3> Welcome to SchoolEd!</h3>
                                    <hr>
                                    <h3>Please click below to confirm your SchoolEd account.</h3>
                                    <p>Confirmation link :</p><a href =" . $url . ">" . $url . "</a>
                                    <hr>
                                    <h3>In case your email address is not validated within 48h, your account will be automatically deleted.</h3>
                                    <h3>Best regards,</h3>
                                    <h3>SchoolEd Team</h3>
                                </body>
                                </html>";

                $this->mailer->sendMail($from, $to, $subject, $body);
                return $this->user->addUser($filteredPost);
            } else {
                http_response_code(403);
                $errors['entry'] = "Smth went wrong";
                return $errors;
            }
        } else {
            http_response_code(400);
            return $errors;
        }
    }

    function loginUser()
    {
        $filter = $_POST;
        $email = $filter['email'];
        $passwd = $filter['password'];

        $success = array();
        $errors = array();

        if (isset($filter['token'])) {
            $token = $filter['token'];
            $isToken = $this->user->selectActivationToken(['token' => $token]);
            if ($isToken) {
                $active = $this->user->activateAccount(['token' => $token]);
                if ($active == true) {
                    return $this->user->deleteActivationToken(['token' => $token]);
                }
            } else {
                $errors['token'] = 'Token not valid';
            }
        }

        if (isset($email) && isset($passwd)) {
            $salt = '$1$cryptpass$';
            $password = crypt($passwd, $salt);
            $_POST['password'] = $password;
            $user = $this->user->loginUser($_POST);
            if ($user !== false) {
                if ($user['active'] == 1) {
                    $_SESSION['isStudent'] = TRUE;
                    $_SESSION['email'] = $email;
                    $_SESSION['id'] = $user['id'];

                    if ($user['value'] > 0) {
                        $_SESSION['isProfesor'] = TRUE;
                        $_SESSION['email'] = $email;
                        $_SESSION['id'] = $user['id'];
                        $success['profesor'] = "You logged in as profesor!";
                        return $success;
                    } else {
                        $success['student'] = "You logged in as student!";
                        return $success;
                    }
                } else {
                    http_response_code(403);
                    $errors['active'] = "Account not active";
                    return $errors;
                }
            } else {
                http_response_code(403);
                $errors['login'] = "Invalid Credentials!";
                return $errors;
            }
        }
    }

    function logout()
    {
        unset($_SESSION['isStudent']);
        unset($_SESSION['isProfesor']);
        unset($_SESSION['email']);
        unset($_SESSION['id']);
        session_destroy();

        return ["success" => TRUE];
    }

    function checkStudentSession()
    {

        if (isset($_SESSION["isStudent"])) {
            return ["id" => $_SESSION['id'], "isStudent" => $_SESSION['isStudent'], "email" => $_SESSION["email"], "session_id" => session_id() . " " . $_COOKIE["PHPSESSID"]];
        } else {
            http_response_code(401);
            return ["error" => "UNAUTHORIZED"];
        }
    }

    function checkProfesorSession()
    {
        if (!isset($_SESSION['isProfesor'])) {
            http_response_code(401);
            return ["error" => "UNAUTHORIZED"];
        } else {
            return ["isProfessor" => $_SESSION['isProfesor'], "email" => $_SESSION['email']];
        }
    }

    function editUser()
    {
        $errors = array();
        $filter = $_POST;
        $name = $filter['name'];
        $surname = $filter['surname'];
        $phone = $filter['phone'];
        $email = $filter['email'];

        if (!empty($_FILES)) {
            if ($_FILES['file']['size'] > 2097152) {
                $errors['img'] = "File too large. File must be less than 2 megabytes";
            } else if (!preg_match('/\.(jpe?g|png|gif|bmp)$/', $_FILES['file']['name'])) {
                $errors['img'] = "Invalid file type. Only PDF, JPG, GIF, BMP and PNG types are accepted";
            } else {
                $currentDir = dirname(__FILE__);
                $tmpPath = $_FILES['file']['tmp_name'];
                $filePath = $currentDir . "/../uploads/" . $_FILES["file"]["name"];
                move_uploaded_file($tmpPath, $filePath);
                $filter['file'] = $_FILES["file"]["name"];
            }
        }


        if (!empty($name)) {
            if (preg_match('/[@#$%^&*()+=\[\]\';,.\/{}|":<>?~\\\\0-9]/', $name)) {
                $errors['name'] = "Your first name can't contain special characters or numbers";
            }
        }
        if (!empty($surname)) {
            if (preg_match('/[@#$%^&*()+=\[\]\';,.\/{}|":<>?~\\\\\-0-9]/', $surname)) {
                $errors["surname"] = "Your last name can't contain special characters or numbers";
            }
        }

        if (!empty($phone)) {
            if (!preg_match('/[0-9]{10}$/', $phone)) {
                $errors['phone'] = "Phone number not valid";
            }
        }

        if (isset($email)) {
//            if (filter_var($email,FILTER_VALIDATE_EMAIL)) {
            $checkEmail = $this->user->selectEmail(['email' => $email]);
            if ($checkEmail) {
                $errors['email'] = "Email already taken!";
            }
        }

        if (empty($errors)) {
            return $this->user->editUser($filter);
        } else {
            http_response_code(403);
            return $errors;
        }
    }
}

