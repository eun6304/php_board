 /* member 테이블 생성 */
create table member (idx int unsigned not null auto_increment, id varchar(100) default '', name varchar(100) default '', email varchar(100) default '', password varchar(100) default '', zipcode char(5) default '', addr1 varchar(255) default '',  addr2 varchar(255) default '', photo varchar(100) default '', create_at datetime, ip varchar(20) default '', primary key(idx), unique index `id` (`id`) using btree);

/* 샘플 인서트 */
insert into member(id, name, email) values ('esbaek','백은서','esbaek@cash-cow.co.kr');

/*비밀번호 바꾸기 */
ALTER USER 'localhost' IDENTIFIED BY 'dmstj112';
ALTER USER 'root '@'localhost' IDENTIFIED BY 'dmstj112';

/*버전 인증 종류 바꾸기 */
alter user root@localhost identified with mysql_native_password by 'dmstj112';

/* 필드 추가 */
alter table member add column level tinyint unsigned default 1;
alter table member add column login_dt DATETIME default NOW();

/* 어드민 샘플 */
update member set id='admin', level=10, name='관리자' where id='bes1';

/* board_manage table 생성 */
CREATE TABLE board_manage(
idx INT UNSIGNED NOT NULL auto_increment,
`name` VARCHAR(255) DEFAULT '' COMMENT '게시판 이름',
`board_manage` VARCHAR(40) DEFAULT '', 
`btype` ENUM('board','gallery') DEFAULT 'board' COMMENT '게시판 타입',
`cnt` INTEGER DEFAULT 0 COMMENT '게시물 수',
`create_at` DATETIME,
primary key (IDX)
);

/* board_manage 테이블에 값 추가 */
INSERT INTO board_manage
VALUES (NULL, '자유게시판', 'free', 'board', 0, NOW());

/* board 테이블 생성 */
CREATE TABLE board(
`idx` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
`bcode` CHAR(6) DEFAULT '' COMMENT '게시판 코드',
`id` VARCHAR(50) DEFAULT '' COMMENT '아이디',
`name` VARCHAR(50) DEFAULT '' COMMENT '이름',
`subject` VARCHAR(255) DEFAULT '' COMMENT '제목',
`content` MEDIUMTEXT COMMENT '내용',
`hit` INTEGER UNSIGNED DEFAULT 0 COMMENT '조회 수',
`ip` VARCHAR(30) DEFAULT '' COMMENT '글쓴이 ip',
`create_at` DATETIME NOT NULL COMMENT '글 등록일시',
PRIMARY KEY(idx)
);

/* board 테이블 에 인덱스 추가 */
ALTER TABLE board ADD INDEX `bcode`(`bcode`);
ALTER TABLE board ADD INDEX `bcode_id` (`bcode`,`id`);
ALTER TABLE board DROP INDEX `bcode_id`;

/* board 테이블 에 files 컬럼 추가 */
ALTER TABLE board ADD COLUMN files VARCHAR(255) DEFAULT '' AFTER content;

/* board 테이블 에 files 컬럼 타입을 TEXT로 변경 */
ALTER TABLE board CHANGE COLUMN files files TEXT;

/* board 테이블 에 downhit 컬럼 추가 */
ALTER TABLE board ADD COLUMN downhit VARCHAR(20) DEFAULT '' AFTER hit;

/* 조회 수 수동으로 입력 */
UPDATE board SET downhit = '4?0' WHERE idx=40;

/* 마지막 게시물 조회자*/
ALTER TABLE board ADD COLUMN `last_reader` VARCHAR(30) DEFAULT '' AFTER downhit;

/* 댓글 테이블 */
CREATE TABLE `comment` (
  idx INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  pidx INTEGER NOT NULL,
  id VARCHAR(50) DEFAULT '' COMMENT '글작성자',
  content TEXT COMMENT '댓글내용',
  create_at DATETIME NOT NULL,
  ip VARCHAR(30),
  PRIMARY KEY (idx)
);

/* 댓글 수 필드 추가 */
ALTER TABLE board ADD COLUMN comment_cnt INTEGER UNSIGNED DEFAULT 0 COMMENT '댓글수' AFTER hit;