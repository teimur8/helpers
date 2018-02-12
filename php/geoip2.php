<?php

require(BASEPATH . 'geoip2.phar');


$config = array(
    'driver'    => 'mysql', // Db driver
    'host'      => '',
    'database'  => '',
    'username'  => '',
    'password'  => '',
    'charset'   => 'utf8', // Optional
    'collation' => 'utf8_unicode_ci', // Optional
    'prefix'    => '', // Table prefix, optional
    'options'   => array( // PDO constructor options, optional
        PDO::ATTR_TIMEOUT => 5,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ),
);
$connection = new \Pixie\Connection('mysql', $config, 'QB2');
QB2::registerEvent('before-select', ':any', function($qb)
{
    $qb->setFetchMode(\PDO::FETCH_ASSOC);
});
// чистка кэша при обновлении значений таблицы конфигов
QB2::registerEvent('after-update', 'configs', function($qb){ config_db::cacheClear(); });


$temp1 = FileCache::init()->getOrSet('log_user_visits', function () {

    $query = "
        SELECT INET_NTOA(u.ip) as 'ip', MONTH(u.date) as 'month', count(*) as 'count'
        FROM log_user_visits u
        group by  INET_NTOA(u.ip), MONTH(u.date)
    ";

    $reader = new \GeoIp2\Database\Reader(BASEPATH . 'GeoLite2-Country.mmdb');
    $temp1 = QB2::query($query)->get();

    foreach ($temp1 as &$item) {
        $record = $reader->country($item['ip']);
        $item['country'] = $record->country->name;
    }

    return $temp1;
});


$query1 = "
    CREATE TEMPORARY TABLE temp_table_1 (
      `ip` varchar(100),
      `month` int(2),
      `count` int(11),
      `country` varchar(100)
    )
";

QB2::query($query1);


$query2 = "
   INSERT INTO temp_table_1
        (ip,month,count,country)
    VALUES 
";

$last_key = end(array_keys($temp1));
foreach ($temp1 as $key => $item) {
    $ip = $item['ip'];
    $month = $item['month'];
    $count = $item['count'];
    $country = $item['country'];
    $query2 .= "('$ip', $month, $count, '$country')";
    if ($key == $last_key) {
        // last element
    } else {
        $query2 .= ',';
    }
}


QB2::query($query2);


$temp2 = QB2::query("
    select country, month, count(*) as 'count' from temp_table_1
    where country <> ''
    group by country, month
    order by count DESC"
)->get();
