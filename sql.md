
# EXIST
```php
// laravel
 $query->whereHas('article', function ($query) {
    $q = pregClear($this->input);
    $query->from(AllArticles::as('all_articles'))
        ->leftJoin(Suppliers::as('suppliers'), 'suppliers.id', '=', 'all_articles.supplierid')
        ->where('suppliers.desc_clear','like', "MOB%");
}); 
```
Сгенеит вот такой sql
```sql  
SELECT *
FROM product_oil
WHERE EXISTS (
	SELECT *
	FROM td_test.all_articles AS a
	LEFT JOIN td_test.suppliers AS s ON s.id = a.supplierid
	WHERE product_oil.article_id = a.id AND s.desc_clear LIKE 'MOB%'
)
```

Главный запрос внутри `EXISTS`, основная связь `product_oil.article_id = a.id`. По сути он вытащит то, что плучится в этом запросе. Sql сам определит какие сущности.

# Разное 

```sql
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

DELETE
FROM orders_archive
WHERE orders_archive.CREATE_DATE = '0000-00-00 00:00:00'

ALTER TABLE de_descriptions AUTO_INCREMENT = 1;

UPDATE de_descriptions SET de_descriptions.images = NULL;

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

-- insert or update
INSERT INTO `parts` (`shop_id`, `article_id`, `is_active`, `is_new`, `quantity`, `price`, `description`) VALUES
('20', '1', '1', '1', '10', '310', ''),
('20', '2', '1', '1', '10', '363', '') ON DUPLICATE KEY
UPDATE
	shop_id = VALUES(shop_id),
	article_id = VALUES(article_id)


CREATE TABLE `img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `horse_id` int(11) DEFAULT NULL,
  `big` varchar(255) DEFAULT NULL,
  `small` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



create TEMPORARY table if not EXISTS temp21(
	a varchar(255) null,
	b varchar(255) null,
	c varchar(255) null
);

insert into temp21(a,b,c)
values (152560,	"MOBIL-1   0w-20	1л",	40),
(152559,	"MOBIL-1   0w-20	4л",	120),
(150690,	"MOBIL-1   0w-20	208л",	6240),
(153790,	"M-1 ESP X2 0W20	1л",	30);


INSERT INTO client_bonus_rules (
name,targetId,maxDiscount,abcd,bonusPrice,bonusPriceRate,
dateStart,dateEnd,isActive,toAllClient,bonusCount,product_code1c,
rule_type,created_at,updated_at,deleted_at
)
SELECT
  t.b,
  p.prod_dev_code1c,
  100, NULL, NULL,
  t.c,
  '2018-09-01 00:00:00',
  '2018-12-31 00:00:00',
  1,
  1,
  1,
  p.prod_code1c,
  'pvl', NOW(), NOW(), NULL
FROM temp21 t
LEFT JOIN 1c_products p ON p.prod_article = t.a
WHERE p.prod_code1c IS NOT NULL;

-- load data from csv
LOAD DATA INFILE 'c:/tmp/discounts.csv' 
INTO TABLE discounts 
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

-- grant https://www.cyberciti.biz/faq/how-to-show-list-users-in-a-mysql-mariadb-database/

SELECT host, user FROM mysql.user;
show grants for 'temp'@'%' \G;q


-- union select
SELECT 
*
FROM (
SELECT 1 AS `id`,'MOBIL-1 0W-20' AS `name`,'1' AS `volume_info`, 0 AS `price` UNION ALL
SELECT 2,'MOBIL-1 0W-20','4', 1 UNION ALL
SELECT 1229,'ONZOIL SG CF 20W-50','4.5', 1
) AS temp
LEFT JOIN product_oil ON product_oil.id = temp.id

```

### Crate from select
```sql
CREATE TABLE all_article_doubles AS (
SELECT *, COUNT(*) AS c1
FROM all_articles a
GROUP BY a.supplierid, a.article_clear
HAVING c1 > 1);
```
