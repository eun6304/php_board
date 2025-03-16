<?php
// 댓글관리 클래스 
class Comment {
  // 멤버 변수, 프로퍼티
  private $conn;

  // 생성자
  public function __construct($db)
  {
    $this->conn = $db;
  }

  // 댓글 등록
  public function input($arr) {
    $sql = "INSERT INTO comment (pidx, id, content, create_at, ip) VALUES (
            :pidx, :id, :content, NOW(), :ip)";
    $stmt = $this->conn->prepare($sql);
    $params = [ ":pidx" => $arr['pidx'], 
                ":id" => $arr['id'], 
                ":content" => $arr['content'], 
                ":ip" => $_SERVER['REMOTE_ADDR']
              ];
    $stmt->execute($params);

    // 댓글수 1 증가
    $sql = "UPDATE board SET comment_cnt = comment_cnt + 1 WHERE idx = :idx";
    $stmt = $this->conn->prepare($sql);
    $params = [ ":idx" => $arr['pidx'] ];
    $stmt->execute($params);
  }

  // 댓글 목록
  public function list($pidx) {
    $sql = "SELECT * FROM comment WHERE pidx = :pidx";
    $stmt = $this->conn->prepare($sql);
    $params = [ ":pidx" => $pidx ];
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute($params);
    return $stmt -> fetchAll();
  }

  public function delete($pidx, $idx) {
    // 댓글 갯수 감소
    $sql = "UPDATE board SET comment_cnt = comment_cnt - 1 WHERE idx = :idx";
    $stmt = $this->conn->prepare($sql);
    $params = [ ":idx" => $pidx ];
    $stmt->execute($params);

    // 댓글 삭제
    $sql = "DELETE FROM comment WHERE idx = :idx";
    $stmt = $this->conn->prepare($sql);
    $params = [ ":idx" => $idx ];
    $stmt->execute($params);
  }
}