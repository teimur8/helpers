DELETE
FROM orders_archive
WHERE orders_archive.CREATE_DATE = '0000-00-00 00:00:00'

ALTER TABLE de_descriptions AUTO_INCREMENT = 1;

UPDATE de_descriptions SET de_descriptions.images = NULL;




-- cli
show databases;
use gitlab_production;
show tables;
describe users;
select * from users \G;
select encrypted_password from users where email like '%it06%';

-- cli change password
use mysql;
select Host,User,Password from user;
update mysql.user set Password = PASSWORD('123qwe123qwe') where User = 'root';
flush privileges;


-- table info;
describe all_articles;
show create table all_articles \G;
