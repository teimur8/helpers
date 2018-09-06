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
WHERE p.prod_code1c IS NOT NULL
