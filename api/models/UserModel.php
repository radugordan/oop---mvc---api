<?php 
require_once "DB.php";

class UserModel extends DB {
    
    function getStudents() {
        $sql = 'select * from users where value = 0';
        return $this->selectAll($sql);
    }

	function addUser($item) {
		$data = [
				$item["name"],
				$item["surname"],
				$item["email"],
				$item["phone"],
				$item["password"],
				$item["value"],
                $item["file"]
				];

		$sql = 'insert into users (name, surname, email, phone, password, value, file) values (?, ?, ?, ?, ?, ?, ?)';
		return $this->addItem($sql, $data);
	}

	function loginUser($item) {
		$data = [
				$item['email'],
				$item['password']
				];

		$sql = 'select * from users where email = ? and password =?';
		return $this->selectItem($sql, $data);
	}

	function editUser($item) {
        $data = [];
        $sqlParams = [];
        if (!empty($item['name'])) {
            $data[] = $item['name'];
            $sqlParams[] = 'name = ?';
        }
        if (!empty($item['surname'])) {
            $data[] = $item['surname'];
            $sqlParams[] = 'surname = ?';
        }
        if (!empty($item['phone'])) {
            $data[] = $item['phone'];
            $sqlParams[] = 'phone = ?';
        }
        if (!empty($item['email'])) {
            $data[] = $item['email'];
            $sqlParams[] = 'email = ?';
        }
        if (!empty($item['file']) && $item['file'] !== 'undefined') {
            $data[] = $item['file'];
            $sqlParams[] = 'file = ?';
        }

        if (count($sqlParams) && isset($_SESSION['id'])) {
            $sql = 'UPDATE users SET '. implode(', ', $sqlParams) . ' WHERE id = ?';
            $data[] = $_SESSION['id'];
            
            return $this->updateItem($sql, $data);
        } else {
            return false;
        }
	}
    
    function editPassword($item) {
        $data=[
                $item['password'],
                $item['token']
                ];
        $sql = 'update users join password_reset on users.email = password_reset.email set users.password = ? where password_reset.token = ?';
        return $this->updateItem($sql, $data);
    }

	function selectEmail($item=array()) {
		$data = [
				$item['email']
				];
		$sql = 'select name, email from users where email = ?';
		return $this->selectItem($sql, $data);
	}
    
    function selectUser($item) {
        $data = [
                $item['id'] 
                ];
        $sql = 'select * from users where id = ?';
        return $this->selectItem($sql, $data);
    }
    
    
    function insertResetEntry($item) {
        $data = [
                $item['email'],
                $item['token'],
                $item['tstamp']
                ];
        $sql = 'insert into password_reset (email, token, tstamp) values (?, ?, ?)';
        return $this->addItem($sql, $data);
    }
    
    function selectToken($item) {
        $data = [$item['token']
                ];
        $sql = 'select * from password_reset where token = ?';
        return $this->selectItem($sql, $data);
    }
    
    function selectActivationToken($item) {
        $data = [
                $item['token']
                ];
        $sql = 'select * from signup_token where token = ?';
        return $this->selectItem($sql, $data);
    }
    
    function activateAccount($item) {
        $data = [
                $item['token']
                ];
        $sql = 'update users join signup_token on users.email = signup_token.email set users.active = 1 where signup_token.token = ?';
        return $this->updateItem($sql, $data);
    }
    
    function deleteToken($item) {
        $data = [
                $item['token']
                ];
        $sql = 'delete from password_reset where token = ?';
        return $this->removeItem($sql, $data);
    }     
    function deleteActivationToken($item) {
        $data = [
                $item['token']
                ];
        $sql = 'delete from signup_token where token = ?';
        return $this->removeItem($sql, $data);
    } 
    
    function    insertNewUserToken($item) {
        $data = [
                $item['email'],
                $item['token']
                ];
        $sql = 'insert into signup_token (email, token) values (?, ?)';
        return $this->addItem($sql, $data);
    }
}

?>