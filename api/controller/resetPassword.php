<?php
require_once "models/Users.php";
require_once "PHPMailer/Mailer.php";
date_default_timezone_set("Europe/Bucharest");

class resetPassword {
    private $user;
    private $mailer;
    
    function __construct() {
        $this->user = new Users();
        $this->mailer = new Mailer();
    }
    
    function sendMail() {
        $errors = array();
        $filter= $_POST;
        $email = $filter['email'];
        
        if (isset($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                http_response_code(403);
                return $errors['email'] = "Email invalid";
            } else {
                $checkEmail = $this->user->selectEmail(['email'=>$email]);
                if($checkEmail == FALSE){
                    http_response_code(403);
                    return $errors['email'] = "Email not found in database";
                }
            }
        if (empty($errors)) {
            $rand = rand();
            $salt = '$1$crypt';
            $token = rtrim(crypt($rand, $salt), ".");
            $tstamp = $_SERVER['REQUEST_TIME'];
            $entry = $this->user->insertResetEntry(['email'=>$email, 'token'=>$token, 'tstamp'=>$tstamp]);
            if ($entry == TRUE) {
                $origin = $_SERVER['SERVER_NAME'] ;
//                var_dump($origin, $token);
                
                $url = 'http://radgor.app/assets/templates/resetPassword.html?token='. $token;
                $subject = "Password Reset";
                $to = $checkEmail['email'];
                $name = $checkEmail['name'];
                $from = "schooled@gmail.com";

                $body = "<html>
                                <head>
                                <title>Email to reset password:</title>
                                </head>
                            <body>
                                <h3>Hello " . $name .",</h3>
                                <h3> A password reset was requested from your account email: ".$email." .</h3>
                                <hr>
                                <h3>To confirm this request, and set a new password for your account please click the link below </h3>
                                <p>Confirmation link :</p><a href =".$url.">".$url."</a>
                                <hr>
                                <p>This link is valid 30 minutes.</p>
                                <h3>If this password reset was not requested by you, no action is needed.</h3>
                                <h3>If you need help, please contact the site administrator,</h3>
                                <br>
                                <h3>Best regards,</h3>
                                <h3>SchoolEd Team</h3>
                            </body>
                            </html>";
                return $this->mailer->sendMail($from, $to, $subject, $body);
            } else {
                http_response_code(403);
                $errors['entry'] = "Smth went wrong";
                return $errors;
                }
            }
        }
            
    }
    
    function resetPassword() {
        $errors = array();
        $filter = $_POST;
        $token = $filter['token'];
        $password = $filter['password'];
        $repassword = $filter['repassword'];
        $delta = 1800;
    
        if(isset($token)) {
            
            $isToken = $this->user->selectToken(['token'=>$token]);
    
            if ($_SERVER['REQUEST_TIME'] - $isToken['tstamp'] > $delta || $isToken == false) {
                 $this->user->deleteToken(['token'=>$token]);
                $errors['token'] = 'token not valid';
            }   
            else if (!empty($password) && ($password == $repassword)) {
                if (!preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?!.*\s).*$#", $password)) {
	                     $errors["password"]= "The password must include: 8-20 Characters, at least one capital letter, at least one number, no spaces!";
                    }
            } else {
	                 $errors["password"]= "Please Check You've Entered Or Confirmed Your Password!";
				}
            if (!empty($errors)){
                http_response_code(400);
                return $errors;
            } else {
                $salt = '$1$cryptpass$';
                $passwd = crypt($password, $salt);
//                var_dump(['passwd'=>$passwd]);
                $password = $passwd;
//                 var_dump(['password'=>$password]);
                $edit = $this->user->editPassword(['token'=>$token, 'password'=>$password]);
                
                if ($edit == true){
                    return $this->user->deleteToken(['token'=>$token]);
                }
            }
        } else {
            http_response_code(400);
            $errors['token'] = "No token no reset";
            return $errors;
        }
    }
    
    
    
    
    
    
}