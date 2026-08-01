<?php

class crud_class{
    
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "shifa";
    private $conn;

    public function __construct(){
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        if($this->conn->connect_error){
            die("Connection failed: " . $this->conn->connect_error);
        }
    }



    public function common_select($table, $columns = "*", $where = [],$where_condition = "AND", $order_by = "",$sort_order = "ASC",$limit = "",$offset = ""){
        $result=[
            "status"=>false,
            "data"=>[],
            "message"=>""
        ];
    
        $sql = "SELECT $columns FROM $table";
        //$where=[id=>1, name=>'John'];
        if(!empty($where)){
            $where_clauses = [];
            foreach($where as $column => $value){
                $where_clauses[] = "$column = '$value'";
                //$where_clauses[] = "id='1'"
                //$where_clauses[] = "name='kamal'"
            }

            $sql .= " WHERE " . implode(" $where_condition ", $where_clauses);
            // "SELECT * FROM users WHERE id='1' AND name='kamal'"
        }

        if(!empty($order_by)){
            $sql .= " ORDER BY $order_by $sort_order";
             // "SELECT * FROM users WHERE id='1' AND name='kamal' ORDER BY name ASC"
        }

        if(!empty($limit)){
            $sql .= " LIMIT $limit";
            if(!empty($offset)){
                $sql .= " OFFSET $offset";
            }

            // "SELECT * FROM users WHERE id='1' AND name='kamal' ORDER BY name ASC LIMIT 10 OFFSET 5"
        }

        $rs = $this->conn->query($sql);
        if($rs->num_rows > 0){
            $result["status"] = true;
            $result["message"] = "Records found";
            while($row = $rs->fetch_object()){
                $result["data"][] = $row;
            }
            return $result; 
        } else {
            $result["message"] = "No records found";
            return $result;
        }
    }

    public function common_query($query){
        $result=[
            "status"=>false,
            "data"=>[],
            "message"=>""
        ];

        $rs = $this->conn->query($query);
        if($rs->num_rows > 0){
            $result["status"] = true;
            $result["message"] = "Records found";
            while($row = $rs->fetch_object()){
                $result["data"][] = $row;
            }
            return $result; 
        } else {
            $result["message"] = "No records found";
            return $result;
        }
    }


    public function common_insert($table, $data){
        $result=[
            "status"=>false,
            "data"=>[],
            "message"=>""
        ];

        $columns = implode(", ", array_keys($data));
        $values = implode("', '", array_values($data));
        $sql = "INSERT INTO $table ($columns) VALUES ('$values')";
        if($this->conn->query($sql)){
            $result["status"] = true;
            $result["data"] = $this->conn->insert_id;
            $result["message"] = "Record inserted successfully";
            return $result;
        } else {
            $result["message"] = "Error: " . $this->conn->error;
            return $result;
        }
    }


    public function common_update($table, $data, $where = [], $where_condition = "AND"){
        $result=[
            "status"=>false,
            "data"=>[],
            "message"=>""
        ];
       
        
        $sql = "UPDATE $table SET ";
        $set_clauses = [];
        foreach($data as $column => $value){
            $set_clauses[] = "$column = '$value'";
        }
        $sql .= implode(", ", $set_clauses);

        if(!empty($where)){
            $where_clauses = [];
            foreach($where as $column => $value){
                $where_clauses[] = "$column = '$value'";
            }
            $sql .= " WHERE " . implode(" $where_condition ", $where_clauses);
        }
        if($this->conn->query($sql)){
            $result["status"] = true;
            $result["data"] = $this->conn->affected_rows;
            $result["message"] = "Record updated successfully";
            return $result;
        } else {
            $result["message"] = "Error: " . $this->conn->error;
            return $result;
        }
    }

    public function common_delete($table, $where = [], $where_condition = "AND"){
        $result=[
            "status"=>false,
            "data"=>[],
            "message"=>""
        ];

        $sql = "DELETE FROM $table";
        if(!empty($where)){
            $where_clauses = [];
            foreach($where as $column => $value){
                $where_clauses[] = "$column = '$value'";
            }
            $sql .= " WHERE " . implode(" $where_condition ", $where_clauses);
        }

        if($this->conn->query($sql)){
            $result["status"] = true;
            $result["data"] = $this->conn->affected_rows;
            $result["message"] = "Record deleted successfully";
            return $result;
        } else {
            $result["message"] = "Error: " . $this->conn->error;
            return $result;
        }
    }


    public function __destruct(){
        $this->conn->close();
    }
}

