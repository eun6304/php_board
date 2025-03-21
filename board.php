<?php
// 게시판 클래스
class Board {
  // 멤버 변수, 프로퍼티
  private $conn;

  // 생성자
  public function __construct($db)
  {
    $this->conn = $db;
  }

  // 글 등록
  public function input($arr) {
    $sql = "INSERT INTO board(bcode, id, name, subject, content, files, downhit, ip, create_at) VALUES(
            :bcode, :id, :name, :subject, :content, :files, :downhit, :ip, NOW())";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':bcode', $arr['bcode']);
    $stmt->bindParam(':id', $arr['id']);
    $stmt->bindParam(':name', $arr['name']);
    $stmt->bindParam(':subject', $arr['subject']);
    $stmt->bindParam(':content', $arr['content']);
    $stmt->bindParam(':files', $arr['files']);
    $stmt->bindParam(':downhit', $arr['downhit']);
    $stmt->bindParam(':ip', $arr['ip']);
    $stmt->execute();
  }

  // 글 수정
  public function edit($arr) {
    $sql = "UPDATE board SET subject=:subject, content=:content WHERE idx=:idx";
    $stmt = $this->conn->prepare($sql);
    $params = [':subject' => $arr['subject'], ':content' => $arr['content'], ':idx' => $arr['idx'] ];
    $stmt -> execute($params);
  }

  // 글 목록
  public function list($bcode, $page, $limit, $paramArr) {
    $start = ($page -1) * $limit;
    $where = 'WHERE bcode = :bcode ';

    $params = [':bcode' => $bcode];
    if(isset($paramArr['sn']) && $paramArr['sn'] != '' && isset($paramArr['sf']) && $paramArr['sf'] != '') {
      switch($paramArr['sn']) {
        case 1 : 
          $where .= "AND (subject LIKE CONCAT('%', :sf, '%') OR (content LIKE CONCAT('%', :sf2, '%'))) "; 
          $params = [ ':bcode' => $bcode, ':sf' => $paramArr['sf'], ':sf2' => $paramArr['sf'] ];
        break; // 제목 내용
        case 2 :
          $where .= "AND (subject LIKE CONCAT('%', :sf, '%')) "; 
          $params = [ ':bcode' => $bcode, ':sf' => $paramArr['sf']];
        break; // 제목 
        case 3 :
          $where .= "AND (content LIKE CONCAT('%', :sf, '%')) "; 
          $params = [ ':bcode' => $bcode, ':sf' => $paramArr['sf']];
        break; // 내용
        case 4 :
          $where .= "AND (name = :sf) "; 
          $params = [ ':bcode' => $bcode, ':sf' => $paramArr['sf']];
        break; // 글쓴이
      }
    }
    $sql = "SELECT idx, id, subject, name, hit, DATE_FORMAT(create_at, '%Y-%m-%d %H:%i') AS create_at 
            FROM board ". $where ."ORDER BY idx DESC LIMIT ".$start.",".$limit;

    $stmt = $this -> conn -> prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC); // fetch 할때 key들이 숫자로 나오지 않고 필드명으로 나옴
    $stmt -> execute($params);
    return $stmt -> fetchAll();
  }

  // 전체 글 수 구하기
  public function total($bcode, $paramArr) {
    $where = 'WHERE bcode = :bcode ';

    $params = [':bcode' => $bcode];
    if(isset($paramArr['sn']) && $paramArr['sn'] != '' && isset($paramArr['sf']) && $paramArr['sf'] != '') {
      switch($paramArr['sn']) {
        case 1 : 
          $where .= "AND (subject LIKE CONCAT('%', :sf, '%') OR (content LIKE CONCAT('%', :sf2, '%'))) "; 
          $params = [ ':bcode' => $bcode, ':sf' => $paramArr['sf'], ':sf2' => $paramArr['sf'] ];
        break; // 제목 내용
        case 2 :
          $where .= "AND (subject LIKE CONCAT('%', :sf, '%')) "; 
          $params = [ ':bcode' => $bcode, ':sf' => $paramArr['sf']];
        break; // 제목 
        case 3 :
          $where .= "AND (content LIKE CONCAT('%', :sf, '%')) "; 
          $params = [ ':bcode' => $bcode, ':sf' => $paramArr['sf']];
        break; // 내용
        case 4 :
          $where .= "AND (name = :sf) "; 
          $params = [ ':bcode' => $bcode, ':sf' => $paramArr['sf']];
        break; // 글쓴이
      }
    }
    $sql = "SELECT COUNT(*) AS cnt 
            FROM board ". $where ;
            
    $stmt = $this -> conn -> prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC); // fetch 할때 key들이 숫자로 나오지 않고 필드명으로 나옴
    $stmt -> execute($params);
    $row = $stmt -> fetch();
    return $row['cnt'];
  }

  // 글 보기
  public function view($idx) {
    $sql = "SELECT * FROM board WHERE idx = :idx";
    $stmt = $this -> conn -> prepare($sql);
    $params = [':idx' => $idx];
    $stmt->setFetchMode(PDO::FETCH_ASSOC); // fetch 할때 key들이 숫자로 나오지 않고 필드명으로 나옴
    $stmt -> execute($params);
    return $stmt -> fetch();
  }

  // 글 조회수
  public function hitInc($idx) {
    $sql = "UPDATE board SET hit=hit+1 WHERE idx = :idx";
    $stmt = $this -> conn -> prepare($sql);
    $params = [':idx' => $idx];
    $stmt -> execute($params);
  }

  // 첨부파일 구하기
  public function getAttachFile($idx, $th) {
    $sql = "SELECT files FROM board WHERE idx = :idx";
    $stmt = $this -> conn -> prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $params = [':idx' => $idx];
    $stmt -> execute($params);
    $row = $stmt -> fetch();
    $filelist = explode('?', $row['files']);
    return $filelist[$th] .'|'. count($filelist);
  }

  // 다운로드 횟수 구하기
  public function getDownhit($idx) {
    $sql = "SELECT downhit FROM board WHERE idx = :idx";
    $stmt = $this -> conn -> prepare($sql);
    $params = [':idx' => $idx];
    $stmt -> execute($params);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $row = $stmt -> fetch();
    return $row['downhit'];
  }  

  // 다운로드 횟수 증가시키기
  public function increaseDownhit($idx, $downhit) {
    $sql = "UPDATE board SET downhit = :downhit WHERE idx = :idx";
    $stmt = $this -> conn -> prepare($sql);
    $params = [':downhit' => $downhit, ':idx' => $idx];
    $stmt -> execute($params);
  }

  // last_reader 값 변경
  public function updateLastReader($idx, $str) {
    $sql = "UPDATE board SET last_reader = :last_reader WHERE idx = :idx";
    $stmt = $this -> conn -> prepare($sql);
    $params = [':last_reader' => $str, ':idx' => $idx];
    $stmt -> execute($params);
  }

  // 파일 목록 업데이트
  public function updateFileList($idx, $files, $downs) {
    $sql = "UPDATE board SET files = :files, downhit = :downhit WHERE idx = :idx";
    $stmt = $this -> conn -> prepare($sql);
    $params = [':files' => $files, ':downhit' => $downs, ':idx' => $idx];
    $stmt -> execute($params);
  }

  // 파일 첨부
  public function fileAttach($files, $file_cnt) {
    if(sizeof($files['name']) > $file_cnt) {
      $arr = [ "result" => "file_upload_count_exceed" ];
      die(json_encode($arr));
    }

    $tmp_arr = [];
    foreach($files['name'] AS $key => $val) {
      // $_FILES['files']['name']['key']; -> 0, 1, 2
      $tmparr = explode('.', $files['name'][$key]);
      $ext = end($tmparr);

      $not_allowed_file_ext = ['txt','exe','xls'];

      if(in_array($ext, $not_allowed_file_ext)) {
        $arr = ["result" => "not_allowed_file"];
        die(json_encode($arr));
      }
      
      $flag = rand(1000, 9999);
      $filename = 'a' . date('YmdHis') . $flag .'.'. $ext;

      // 실제 보여주기 위한 진짜 이름
      $file_ori = $files['name'][$key];
      copy($files['tmp_name'][$key], BOARD_DIR . "/" . $filename);

      // 저장용 파일명과 실제 파일명을 "|" 구분자로 넣어서 하나의 스트링으로 관리함
      // 두 개를 분리하기 쉽도록 파일명에서 쓰이지 않는 문자를 구분자로 씀
      // a20230205112.jpg|새파일.jpg
      $full_str = $filename ."|". $file_ori;
      $tmp_arr[] = $full_str;
    }
    return implode('?', $tmp_arr);
  }

  public function extract_image($content) {
    preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $content, $matches);
    $img_array = [];
    foreach($matches[1] AS $key => $row) {
      $img_array[] = $row;
    } 
    return $img_array;
  }

  public function delete($idx) {
    $sql = "DELETE FROM board WHERE idx = :idx";
    $stmt = $this -> conn -> prepare($sql);
    $params = [':idx' => $idx];
    $stmt -> execute($params);
    die(json_encode([ "result" => "success" ]));
  }
}