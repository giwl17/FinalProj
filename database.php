<?php

class Database
{
    private const DBHOST = "localhost";
    private const DBUSER = "root";
    private const DBPASS = "";
    private const DBNAME = "project";
    private $dsn  = "mysql:host=" . self::DBHOST . ";dbname=" . self::DBNAME . "";
    protected $conn = null;

    public function __construct()
    {
        try {
            $this->conn = new PDO($this->dsn, self::DBUSER, self::DBPASS);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function query($sql)
    {
        return $this->conn->prepare($sql);
    }

    public function selectThesis($id = '')
    {
        if ($id === '') {
            $select = $this->conn->prepare("SELECT * FROM thesis_document");
            $select->execute();
            return $select->fetchAll();
        } else {
            $select = $this->conn->prepare("SELECT * FROM thesis_document WHERE thesis_id = :id");
            $select->bindParam(':id', $id);
            $select->execute();
            return $select->fetchAll();
        }
    }

    public function selectThesisFull()
    {
        $select_thesis = $this->conn->prepare("SELECT * FROM thesis_document");
        $select_thesis->execute();
        $result = $select_thesis->fetchAll(PDO::FETCH_OBJ);
        $thesis = [];
        $i = 1;
        $index = 0;
        // print_r($result);
        foreach ($result as $row) {
            $select_mem =  $this->conn->prepare("SELECT * FROM author_thesis WHERE thesis_id = :thesis_id");
            $select_mem->bindParam(":thesis_id", $row->thesis_id);
            $select_mem->execute();
            $result_mem = $select_mem->fetchAll(PDO::FETCH_OBJ);
            $thesis[$index]['thesis_id'] = $row->thesis_id;
            $thesis[$index]['thai_name'] = $row->thai_name;
            $thesis[$index]['english_name'] = $row->english_name;
            $thesis[$index]['abstract'] = $row->abstract;
            $thesis[$index]['printed_year'] = $row->printed_year;
            $thesis[$index]['semester'] = $row->semester;
            $thesis[$index]['approval_year'] = $row->approval_year;
            $thesis[$index]['thesis_file'] = $row->thesis_file;
            $thesis[$index]['approval_file'] = $row->approval_file;
            $thesis[$index]['poster_file'] = $row->poster_file;
            $thesis[$index]['keyword'] = $row->keyword;
            $thesis[$index]['prefix_chairman'] = $row->prefix_chairman;
            $thesis[$index]['name_chairman'] = $row->name_chairman;
            $thesis[$index]['surname_chairman'] = $row->surname_chairman;
            $thesis[$index]['prefix_director1'] = $row->prefix_director1;
            $thesis[$index]['name_director1'] = $row->name_director1;
            $thesis[$index]['surname_director1'] = $row->surname_director1;
            $thesis[$index]['prefix_director2'] = $row->prefix_director2;
            $thesis[$index]['name_director2'] = $row->name_director2;
            $thesis[$index]['surname_director2'] = $row->surname_director2;
            $thesis[$index]['prefix_advisor'] = $row->prefix_advisor;
            $thesis[$index]['name_advisor'] = $row->name_advisor;
            $thesis[$index]['surname_advisor'] = $row->surname_advisor;
            $thesis[$index]['prefix_coAdvisor'] = $row->prefix_coAdvisor;
            $thesis[$index]['name_coAdvisor'] = $row->name_coAdvisor;
            $thesis[$index]['surname_coAdvisor'] = $row->surname_coAdvisor;
            $thesis[$index]['thesis_status'] = $row->thesis_status;
            $thesis[$index]['approval_status'] = $row->approval_status;
            foreach ($result_mem as $mem) {
                $thesis[$index]['author_member']["member$i"]["student_id"] = $mem->student_id;
                $thesis[$index]['author_member']["member$i"]["prefix"] = $mem->prefix;
                $thesis[$index]['author_member']["member$i"]["name"] = $mem->name;
                $thesis[$index]['author_member']["member$i"]["lastname"] = $mem->lastname;
                $i++;
            }
            $i = 1;
            $index++;
        }

        return $thesis;
    }

    public function selectThesisFullSQL($sql)
    {
        $select_thesis = $this->conn->prepare($sql);
        $select_thesis->execute();
        $result = $select_thesis->fetchAll(PDO::FETCH_OBJ);
        $thesis = [];
        $i = 1;
        $index = 0;
        // print_r($result);
        foreach ($result as $row) {
            $select_mem =  $this->conn->prepare("SELECT * FROM author_thesis WHERE thesis_id = :thesis_id");
            $select_mem->bindParam(":thesis_id", $row->thesis_id);
            $select_mem->execute();
            $result_mem = $select_mem->fetchAll(PDO::FETCH_OBJ);
            $thesis[$index]['thesis_id'] = $row->thesis_id;
            $thesis[$index]['thai_name'] = $row->thai_name;
            $thesis[$index]['english_name'] = $row->english_name;
            $thesis[$index]['abstract'] = $row->abstract;
            $thesis[$index]['printed_year'] = $row->printed_year;
            $thesis[$index]['semester'] = $row->semester;
            $thesis[$index]['approval_year'] = $row->approval_year;
            $thesis[$index]['thesis_file'] = $row->thesis_file;
            $thesis[$index]['approval_file'] = $row->approval_file;
            $thesis[$index]['poster_file'] = $row->poster_file;
            $thesis[$index]['keyword'] = $row->keyword;
            $thesis[$index]['prefix_chairman'] = $row->prefix_chairman;
            $thesis[$index]['name_chairman'] = $row->name_chairman;
            $thesis[$index]['surname_chairman'] = $row->surname_chairman;
            $thesis[$index]['prefix_director1'] = $row->prefix_director1;
            $thesis[$index]['name_director1'] = $row->name_director1;
            $thesis[$index]['surname_director1'] = $row->surname_director1;
            $thesis[$index]['prefix_director2'] = $row->prefix_director2;
            $thesis[$index]['name_director2'] = $row->name_director2;
            $thesis[$index]['surname_director2'] = $row->surname_director2;
            $thesis[$index]['prefix_advisor'] = $row->prefix_advisor;
            $thesis[$index]['name_advisor'] = $row->name_advisor;
            $thesis[$index]['surname_advisor'] = $row->surname_advisor;
            $thesis[$index]['prefix_coAdvisor'] = $row->prefix_coAdvisor;
            $thesis[$index]['name_coAdvisor'] = $row->name_coAdvisor;
            $thesis[$index]['surname_coAdvisor'] = $row->surname_coAdvisor;
            $thesis[$index]['thesis_status'] = $row->thesis_status;
            $thesis[$index]['approval_status'] = $row->approval_status;
            foreach ($result_mem as $mem) {
                $thesis[$index]['author_member']["member$i"]["student_id"] = $mem->student_id;
                $thesis[$index]['author_member']["member$i"]["prefix"] = $mem->prefix;
                $thesis[$index]['author_member']["member$i"]["name"] = $mem->name;
                $thesis[$index]['author_member']["member$i"]["lastname"] = $mem->lastname;
                $i++;
            }
            $i = 1;
            $index++;
        }

        return $thesis;
    }
}
