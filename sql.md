
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
