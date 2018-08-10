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


-- info
select * from  information_schema.PROCESSLIST \G;
select TIME, STATE, INFO, MEMORY_USED  from  information_schema.PROCESSLIST \G;
show processlist \G;


-- длина
SELECT *, LENGTH(all_articles.article) AS 'length'
FROM all_articles
ORDER BY LENGTH(all_articles.article) DESC
LIMIT 10;



limit 10000

select a.*, a2.id as 'article_id' from article_links a
left join all_articles a2 on (a2.supplierid = a.supplierid and a2.article = a.datasupplierarticlenumber)
INTO OUTFILE '/tmp/article_links.csv'
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n';



-- insert distinct
INSERT INTO all_articles ( all_articles.supplierid, all_articles.article )
SELECT DISTINCT
article_attributes.supplierid,
article_attributes.datasupplierarticlenumber
FROM
	article_attributes
WHERE
	NOT EXISTS ( SELECT 1 FROM all_articles WHERE article_attributes.supplierid = all_articles.supplierid AND article_attributes.datasupplierarticlenumber = all_articles.article )