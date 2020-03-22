<?php
require_once "DB.php";

class StudentCoursesLinkRepo extends DB {

    function addStudentCourse($item) {
        $data = [
                $item['course_id'],
                $item['student_id']=$_SESSION['id'] 
                ];
        $sql = 'insert into course_counter (course_id, student_id) values (?, ?)';
        return $this->addItem($sql, $data);

    }
    function removeStudentCourse($item) {
        $data = [
                $item['course_id'],
                $item['student_id']=$_SESSION['id']  
                ];
        $sql = 'delete from course_counter where course_id = ? and student_id = ?';
        return $this->removeItem($sql, $data);
    } 

    function countStudentCourse($item) {
        $data = [
            $item['student_id']
                ];
        $sql = 'select  count(*) AS student_count from course_counter where student_id = ?';
        return  $this->selectItem($sql, $data);
    }
    
    function checkStudentCourse($item) {
        $data = [
            $item['student_id']=$_SESSION['id'],
            $item['course_id']
                ];
        $sql = 'select * from course_counter where student_id = ? and course_id = ?';
        return  $this->checkItem($sql, $data);
    }
    
    function selectStudentCourses($item) {
        $data =[
                $item['id'] 
                ];
       $sql = 'select courses.id, courses.title, courses.description from courses join course_counter on courses.id = course_counter.course_id join users on course_counter.student_id = users.id where users.id = ?';
        return $this->selectItems($sql, $data);    
    }    
    
    function selectStudentsInCourse($item) {
        $data =[
                $item['id'] 
                ];
       $sql = 'select users.id, users.name, users.surname, users.email, users.file from users join course_counter on users.id = course_counter.student_id join courses on course_counter.course_id = courses.id where courses.id = ?';
        return $this->selectItems($sql, $data);    
    }
}