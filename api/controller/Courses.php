<?php
require_once "models/CourseModel.php";
require_once "models/StudentCoursesLinkRepo.php";

class Courses {
    private $course;
    private $studCourse;
    
    function __construct() {
        $this->course = new CourseModel();
        $this->studCourse = new StudentCoursesLinkRepo();
    }
    
    function getCourses() {
        return $this->course->getCourses();
    }

    function getAllCourses() {
        $page = (!empty($_GET["page"])) ? $_GET["page"] : 1;
        $ipp = 2;                       
        $start = $page*$ipp-$ipp;
        $count = $this->course->countCourses();
        $nrPages = ceil($count/$ipp);
        $courses = ['courses' =>$this->course->getAllCourses($start, $ipp), 'nrPages'=>$nrPages];
        return $courses;
    }

    function getCourse() {
        $filter = $_GET;
        $id = $filter['id'];
        return $this->course->getCourse(['id'=>$id]);
    }
    
    function editCourse() {
        $errors = array();
        $filter = $_POST;
        $title = $filter['title'];
        $description = $filter['description'];
        if (!empty($_FILES)){
            if ($_FILES['file']['size'] > 2097152) {
                $errors['img'] = "File too large. File must be less than 2 megabytes";
            } else if (!preg_match('/\.(jpe?g|png|gif|bmp)$/', $_FILES['file']['name'])) {
                    $errors['img'] = "Invalid file type. Only PDF, JPG, GIF, BMP and PNG types are accepted";
            } else {
                $currentDir=dirname(__FILE__);
                $tmpPath = $_FILES['file']['tmp_name'];
                $filePath= $currentDir."/../uploads/" . $_FILES["file"]["name"]; 
                move_uploaded_file($tmpPath, $filePath);
                $filter['file'] = $_FILES["file"]["name"];
            }
        }
      
        
        if (!empty($title)){
            if(strlen($title) < 3 && strlen($title) > 100 ){
             $errors['title'] = "At least 3 cahracters long and max 100 character"; 
            }
        }
        if (!empty($description)){
            if (strlen($description) < 100 && strlen($description) > 1000) {
                $errors['description'] = "At least 100 characters short and max 1000 characters long";
            }
        }
        if (empty($errors)){
            return $this->course->editCourse($filter);
        }
        else {
            return $errors;
        }
    }
    
    function getStudentCourses() {
        $errors=[];
        $studentId = $_SESSION['id'];
        if(!empty($studentId)) {
            return $this->studCourse->selectStudentCourses(['id'=>$studentId]);
        }
        else {
            http_response_code(403);
            $errors['user'] = "You must log in.";
            return $errors;
        }
    }
    
    function removeCourseStudent() {
        $errors=[];
        $courseId = $_POST['course_id'];
        $studentId = $_SESSION['id'];
        if(!empty($studentId) && !empty($courseId)) {
            return $this->studCourse->removeStudentCourse(['course_id'=>$courseId]) ;
        }
        else {
            http_response_code(403);
            $errors['user'] = "You must log in.";
            return $errors;
        }
    }
    
    function addCourseStudent() {
        $errors=[];
        $filter = $_POST;  
        $courseId = $filter['course_id'];
        $studentId = $_SESSION['id'];
        if(!empty($studentId)) {
            $count = $this->studCourse->countStudentCourse(['student_id'=>$studentId]);
            $check = $this->studCourse->checkStudentCourse(['course_id'=>$courseId]);
    
            if ($check < 1 ) {
                if(intval($count['student_count'] < 5))  {
            
                    return $this->studCourse->addStudentCourse(['course_id'=>$courseId]);
                } 
                else {
                    http_response_code(403);
                    $errors['courses'] = "Max 5 courses can be enrolled.";
                    return $errors;
                }
            }
            else {
                http_response_code(403);
                $errors['course'] = "Can't enrolle more than once.Sorry!";
                return $errors;
            }
        }
        else {
            http_response_code(403);
            $errors['user'] = "You must log in.";
            return $errors;
        }
    }
    
    function getCourseInfo() {
        $errors=[];
        $filter = $_GET;
        $courseId = $filter['id'];

        if(!empty($courseId)) {
            return $this->studCourse->selectStudentsInCourse(['id'=>$courseId]);
        }
        else {
            http_response_code(403);
            $errors['course'] = "You must log in.";
            return $errors;
        }
    }
}
