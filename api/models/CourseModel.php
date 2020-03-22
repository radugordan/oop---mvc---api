<?php
require_once "DB.php";

class CourseModel extends DB {

    function getCourses() {
        $sql = 'select * from courses';
        return $this->selectAll($sql);
    }
    
    function getAllCourses($start = [], $limit = []) {
    $sql = 'select * from courses limit ' . $start . ',' . $limit;
    return $this->selectAll($sql);
    }
    
    function addCourse($item) {
        $data = [
                $item['title'],
                $item['description'],
                $item['creator_id'],
                $item['file']
                ];
        $sql = 'insert into courses (title, description, creator_id, file) values (?, ?, ?, ?)';
        return $this->addItem($sql, $data);
    }
    
    function countCourses() {
        $sql = 'select * from courses';
        $this->selectAll($sql);
        return $this->countAll();
    }

    function deleteCourse($item) {
        $data = [
                $item['id']
                ];
               
        $sql = 'delete from courses where id = ?';
        return $this->removeItem($sql, $data);
    }
    
    function editCourse($item) {
        $data = [];
        $sqlParams = [];
        if (!empty($item['title'])) {
            $data[] = $item['title'];
            $sqlParams[] = 'title = ?';
        }
        if (!empty($item['description'])) {
            $data[] = $item['description'];
            $sqlParams[] = 'description = ?';
        }
        if (!empty($item['file']) && $item['file'] !== 'undefined') {
            $data[] = $item['file'];
            $sqlParams[] = 'file = ?';
        }
        if (count($sqlParams) && isset($_SESSION['isProfesor'])) {
            $sql = 'UPDATE courses SET '. implode(', ', $sqlParams) . ' WHERE id = ?';
            $data[] = $_POST['id'];
            return $this->updateItem($sql, $data);
        } else {
            http_response_code(403);
            return false;
        }
	}

    function getCourse($item) {
        $data = [
                $item['id'] 
                ];
        $sql = 'select * from courses where id = ?';
        return $this->selectItem($sql, $data);
    }
}