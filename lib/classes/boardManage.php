<?php
// 게시판 관리 클래스
class BoardManage {
  // 멤버 변수, 프로퍼티
  private $conn;

  // 생성자
  public function __construct($db)
  {
    $this->conn = $db;
  }

  // 게시판 목록
  public function list() {
    $sql = "SELECT idx, name, bcode, btype, cnt, DATE_FORMAT(create_at, '%Y-%m-%d %H:%i') AS create_at 
            FROM board_manage 
            ORDER BY idx ASC";

    $stmt = $this -> conn -> prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC); // fetch 할때 key들이 숫자로 나오지 않고 필드명으로 나옴
    $stmt -> execute();
    return $stmt -> fetchAll();
  } 

  // 게시판 생성
  public function create($arr) {
    $sql = "INSERT INTO board_manage(name, bcode, btype, create_at) values
            (:name, :bcode, :btype, NOW())";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':name', $arr['name']);
    $stmt->bindParam(':bcode', $arr['bcode']);
    $stmt->bindParam(':btype', $arr['btype']);
    $stmt->execute();
  }

  public function bcode_create() {
    // 게시판 코드 생성
    // a ~ z
    // 6자리
    $letter = range('a','z');
    $bcode = '';
    for($i = 0; $i < 6; $i++) {
      $r = rand(0, 25);
      $bcode .= $letter[$r];
    }
    return $bcode;
  }

  // 게시판 idx로 정보 가져오기
  public function getBcode($idx) {
    $sql = "SELECT bcode FROM board_manage WHERE idx = :idx";

    $stmt = $this -> conn -> prepare($sql);
    $stmt->bindParam(':idx', $idx);
    $stmt->setFetchMode(PDO::FETCH_COLUMN, 0); // fetch 할때 key들이 숫자로 나오지 않고 필드명으로 나옴
    $stmt -> execute();
    return $stmt -> fetch();
  }

  // 게시판 삭제
  public function delete($idx) {
    $bcode = $this->getBcode($idx);
    $sql = "DELETE FROM board_manage WHERE idx = :idx";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':idx', $idx);
    $stmt->execute();
    

    $sql = "DELETE FROM board WHERE bcode = :bcode";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':bcode', $bcode);
    $stmt->execute();
  }
  
  // 게시판 정보 불러오기
  public function getInfo($idx) {
    $sql = "SELECT * FROM board_manage WHERE idx = :idx";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':idx', $idx);
    $stmt->setFetchMode(PDO::FETCH_ASSOC); // fetch 할때 key들이 숫자로 나오지 않고 필드명으로 나옴
    $stmt -> execute();
    return $stmt -> fetch();
  }

  // 게시판 정보 수정
  public function update($arr) {
    $sql= "UPDATE board_manage SET name = :name, btype = :btype WHERE idx = :idx";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':name', $arr['name']);
    $stmt->bindParam(':idx', $arr['idx']);
    $stmt->bindParam(':btype', $arr['btype']);
    $stmt->execute();
  }

  // 게시판 코드로 게시판 명 가져오기
  public function getBoardName($bcode) {
    $sql = "SELECT name FROM board_manage WHERE bcode = :bcode";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':bcode', $bcode);
    $stmt->setFetchMode(PDO::FETCH_COLUMN, 0);
    $stmt->execute();
    return $stmt -> fetch();
  }
}