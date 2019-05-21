/\_cat/indices

sudo systemctl stop elasticsearch.service; sudo systemctl start elasticsearch.service

# teory

[Elasticsearch Введение — 1.1 Основные понятия](https://codedzen.ru/elasticsearch-vvedeniye-1-1-osnovnyye-ponyatiya/)

Elasticsearch — высоко масштабируемая поисковая система с открытым исходным кодом. Elascticsearch построен для обработки неструктурированных данных и может автоматически определять типы данных полей документа. Вы можете индексировать новые документы или добавлять новые поля без изменения схемы. Этот процесс также известен как динамическое отображение.

| Elasticsearch | SQL         |
| ------------- | ----------- |
| Индекс        | База данных |
| Тип           | Таблица     |
| Документа     | Ряд         |
| Поле          | Колонка     |

- данные храняться в JSON
- поддерживает хранение вложенных объектов
- возможно построение отношений между родителями и дочерними элементами

Кластер и узел
В традиционных базах данных обычно у нас есть только один сервер, обслуживающий все запросы. Elasticsearch — это распределенная система, что означает она состоит из одного или нескольких узлов, которые действуют как одно целое, что позволяет масштабировать и обрабатывать нагрузку. Каждый узел (сервер) имеет часть данных.

![img](https://yadi.sk/i/o0fimw1-bc6Gow)
На рисунке кластер имеет три узла с именами elasticsearch1, elasticsearch2, elasticsearch3. Эти три узла работают вместе, чтобы обрабатывать все запросы индексирования и извлечения данных. В зависимости от потребностей вашего приложения вы можете добавлять и удалять узлы (серверы) «на лету».

Shard (осколок или шард)
Индекс представляет собой набор из одного или нескольких шардов. За счет чего Elasticsearch может хранить информацию объем которой превышает возможности одного сервера. Elasticsearch использует Apach Lucene для индексирования и обработки запросов. Шард — это не что иное, как экземпляр Apache Lucene.

[Elasticsearch Введение — 1.2 Взаимодействие](https://codedzen.ru/elasticsearch-vvedeniye-1-2-vzaimodeystviye/)

Основной способ взаимодействия с Elasticsearch — REST API. По умолчанию API — интерфейс Elasticsearch работает на порту 9200. Api можно классифицировать на следующие виды:

- API документов: CRUD (Create Retrieve Update Delete) операции с документами
- API поиска: поиск чего бы то ни было
- API Индексов: управление индексами (создание, удаление …)
- API Cat: вместо JSON данных возвращаются в табличном виде
- API кластера: для управления кластером

Создание документа

```json
// PUT http://localhost:9200/example1/product/1 нужно указывать id
// POST http://localhost:9200/example1/product/ автоматом присвоит id
// Автоматически создаст индекс example1 и тип product если они еще не существуют
// Повторный put запрос обновоит документ. _version указывает на это.
{
  "title": "Мой розовый дневник",
  "author": "Роман Одуванчиков",
  "category": "books"
}
```

```json
// Получение существующего документа
// GET http://localhost:9200/example1/product/1

// Частичное обновление документа
// POST http://localhost:9200/example1/product/1/_update
{
  "doc": {
    "category": "trash"
  }
}

// Удаление существующего документа
// DELETE http://localhost:9200/example1/product/1
```

[Elasticsearch Введение — 1.3 Как работает поиск?](https://codedzen.ru/elasticsearch-vvedeniye-1-3-kak-rabotayet-poisk/)

Все данные в Elasticsearch хранятся в Apache Lucene как инвертированный индекс (для каждого слова коллекции документов где оно встречается).

```json
// select * from user where name like '%Иван%'
// GET http://127.0.0.1:9200/example1/user/_search?q=name:иван
// или
// POST http://127.0.0.1:9200/example1/user/_search
{
  "query": {
    "term": {
      "name": "иван"
    }
  }
}
```

Текст обрабатывается морфологически, сокрощая до корня, используя синонимы, а так же указание положение слов в документе для поиска по фразе, чем ближе слова к друг другу тем релевантнее документ.

[Elasticsearch Введение — 1.4 Масштабируемость и доступность](https://codedzen.ru/elasticsearch-vvedeniye-1-4-masshtabiruyemost-i-dost/)

Части на которые можно разбить данные называются осколками (shards) или шардами. В Elasticsearch есть два типа осколков — мастер и реплика. Мастер — тут происходит как запись так и чтение он главный. Реплика — это точная копия мастера с нее можно только читать. В случае если мастер выходит из строя реплика становиться мастером.

Поскольку индекс распределяется по нескольким осколкам, запрос к индексу выполняется параллельно по всем осколкам. Затем результаты каждого осколка собираются и отправляются обратно клиенту.

[Elasticsearch Настройка — 2.4 Проверка здоровья кластера](https://codedzen.ru/elasticsearch-nastroyka-2-4-proverka-zdorovya-kla/)

```json
// http://127.0.0.1:9200/_cluster/health?pretty
{
  "cluster_name": "es-dev",
  "status": "green", // зеленый, желтый или красный
  "timed_out": false,
  "number_of_nodes": 1,
  "number_of_data_nodes": 1,
  "active_primary_shards": 0,
  "active_shards": 0,
  "relocating_shards": 0,
  "initializing_shards": 0,
  "unassigned_shards": 0,
  "delayed_unassigned_shards": 0,
  "number_of_pending_tasks": 0,
  "number_of_in_flight_fetch": 0,
  "task_max_waiting_in_queue_millis": 0,
  "active_shards_percent_as_number": 100
}
```

- зеленый все хорошо
- желтый все осколки доступны, реплики не все, могут потеряться данные или не верные настройки конфигурации.
- красный не все осколки доступны для поиска

[Elasticsearch — Урок 3.1 Mapping: схема документов](https://codedzen.ru/elasticsearch-urok-3-1-mapping-skhema-dokumentov/)

Маппинг (сопоставление) — это процесс определения схемы или структуры документов. Подобно тому, как вы определяете схему таблицы в SQL.
Информация о типе хранится в метаданных и обрабатывается Elasticsearch.

```json
// нужно сказать для посика
{
  "id": 2,
  "name": "User2",
  "age": "40", // это число целое
  "gender": "F",
  "email": "user2@gmail.com",
  "last_modified_date": "2016-12-02" // указать что это дата и какой формат даты
}
```

Elasticsearch автоматически определяет типы данных если не указать маппинг. Это может быть не очень безопасно, поэтому можно отключить совсем или только для нектороых полей. true, false, strict

```json
// PUT example3/person/1
{
  "name": "john",
  "age": 100,
  "date_of_birth": "1970/01/01"
}
// GET example3/person/_mapping
{
  "example3": {
    "mappings": {
      "person": {
        "properties": {
          "age": {
            "type": "integer"
          },
          "date_of_birth": {
            "type": "date",
            "format": "yyyy/MM/dd HH:mm:ss||yyyy/MM/dd||epoch_millis"
          },
          "name": {
            "type": "keyword"
          }
        }
      }
    }
  }
}
```

Так же можно задать маппинг

```json
// PUT example3
{
  "mappings": {
    "user": {
      "properties": {
        "age": {
          "type": "integer"
        },
        "email": {
          "type": "keyword"
        },
        "gender": {
          "type": "keyword"
        },
        "id": {
          "type": "integer"
        },
        "last_modified_date": {
          "type": "date",
          "format": "yyyy-MM-dd"
        },
        "name": {
          "type": "keyword"
        }
      }
    }
  }
}
```

или добавить

```json
// PUT example3/_mapping/history
{
  "properties": {
    "username": {
      "type": "keyword"
    },
    "login_date": {
      "type": "date",
      "format": "yyyy-MM-dd"
    }
  }
}
```

"analyzer":"english",'russian'
tokenizer
filter

```json
//
/* PUT /custom_analyzer_index
1.Create new analyzer: custom_analyzer, add two filter one of them custom:custom_edge_ngram
2.Create custom type product uses custom anylyzer
*/
{
  "settings": {
    "index": {
      "analysis": {
        "analyzer": {
          "custom_analyzer": {
            "type": "custom",
            "tokenizer": "standard",
            "stopwords": "_russian_",
            "filter": ["lowercase", "custom_edge_ngram"]
          }
        },
        "filter": {
          "custom_edge_ngram": {
            "type": "edge_ngram",
            "min_gram": 2,
            "max_gram": 10
          }
        }
      }
    }
  },
  "mappings": {
    "my_type": {
      "properties": {
        "product": {
          "type": "text",
          "analyzer": "custom_analyzer",
          "search_analyzer": "standard"
        }
      }
    }
  }
}
```

types:

- keyword - fields are only searchable by their exact value.
- text - full-text queries on these fields
- scaled_float - new type from 6.0 13.99 -> 1399/100. This is space efficient
-

### QUERY

term (термин) -поиск точного значения, очень простой и может использоваться для запроса чисел, бинарных значений, дат и текста. Данный запрос используется для поиска одного термина в инвертированном индексе.

```json
({
  "query": {
    // select all,
    "match_all": {},
    // term
    "term": {
      "product_name": "куртк"
    },
    "terms": {
      "product_name": ["куртк", "кожан"]
    },
    // ranger
    // eq("="), gte(">="), gt(">"), lt("<"), lte("<=");
    // greater-than, greater-than-or-equal, equal, less-than-or-equal, less-than
    "range": {
      "price": {
        "gte": 10,
        "lte": 20
      }
    }
  }
},
// SORTING
/*
При сортировке с использованием нескольких полей результаты сначала сортируются по первому полю, а затем по другим полям. Если значение первого поля уникальны, следующие поля не будет иметь эффекта. Только если два или более документов имеют одно и то же значение первого поля, второе поле будет использоваться для сортировки.
*/
{
  "query": {
    "match": {
      "product_name": "куртка"
    }
  },
  "sort": {
    "unit_price": {
      "order": "desc"
    }
  },
  // or
  "sort": [
    {
      "unit_price": {
        "order": "desc"
      }
    },
    {
      "reviews": {
        "order": "desc"
      }
    }
  ]
},
// SELECT
{
  "_source": ["product_name"],
  // or
  "_source": ["pr*"],
  "query": {}
})
```

#### DATE

now+1M: Добавляет один месяц. Вы также можете использовать другую единицу даты, такую ​​как час, день, месяц, год и т. д.
2017-02-01||-1M: Вычитает один месяц
now/d: Используется для округления до ближайшего дня
now+1M-1d/d: Добавляет один месяц, вычитает один день и округляется до ближайшего дня
2017-02-01||+1H/d: Добавляет один час к 2017-02-01 и округляет до ближайшего дня

| Единица времени | Описание |
| --------------- | -------- |
| y               | год      |
| M               | месяц    |
| w               | неделя   |
| d               | день     |
| H               | час      |
| m               | минута   |
| s               | секунда  |

```json
/*
DATE
*/
{
  "query": {
    "range": {
      "release_date": {
        "gt": "2017-01-01",
        "lte": "now"
      },
      //or
      "release_date": {
        "gt": "now-1h"
      },
      // or
      "release_date": {
        "gt": "2017-01-11T20:00:00||+1M"
      }
    }
  }
}
```

```json
/*
Язык сценариев - Painless. Мы можем получить доступ к _source документу для извлечения unit_price и умножения на 1.1 чтобы рассчитать цену, включая налог
*/
{
  "_source": [],
  "query": {
    "match_all": {}
  },
  "script_fields": {
    "price_including_tax": {
      "script": {
        "source": "params['_source']['unit_price'] * 1.1"
      }
    }
  }
}
```

```json
// Поиск одного запроса по нескольким полям
// {
//     "product_name": "Мужская качественная кожаная куртка",
//     "description": "Лучший выбор. Всесезонная кожаная куртка",
//     "unit_price": 79.99,
//     "reviews": 250,
//     "release_date": "2016-08-16"
// }
/*
best_fields - Используется оценка лучше всего совпадающего поля
tie_breaker - чтобы суммировать оценки из других полей
*/
{
  "query": {
    "multi_match": {
      "query": "всесезонная куртка",
      "fields": ["product_name", "description"],
      "type": "best_fields"
    }
    // ---
    "multi_match": {
      "query": "всесезонная куртка",
      "fields": ["product_name", "description"],
      "tie_breaker": 0.2
    }
  }
}
```

### RESPONSE

```json
{
   "size"    : // Количество документов в ответе. По умолчанию 10.
   "from"    : // Смещение результатов
   "timeout" : // Тайм-аут запроса. По умолчанию его нет.
   "_source" : // Поля которые попадут в ответ.
   "query"   : { // Запрос }
   "aggs"    : { // Агрегация }
   "sort"    : { // Сортировка результата }
}
{
  "took"     : // Время потраченное на получение данных
  "timed_out": // Был ли превышен тайм-аут, если он был задан для запроса.
  "_shards": {
    "total": // Количество осколков задействованных при запросе
    "successful": // Количество осколков вернувших успешный результат
    "failed": // Количество осколков с ошибкой
  },
  "hits": {
   "total": // Общее количество документов
   "max_score": // Максимальный балл всех документов
   "hits": [
      // Список документов
    ]
   }
 }
```

```json
{
  "settings": {
    "index": {
      "analysis": {
        "filter": {},
        "analyzer": {
          "keyword_analyzer": {
            "filter": ["lowercase", "asciifolding", "trim"],
            "char_filter": [],
            "type": "custom",
            "tokenizer": "keyword"
          },
          "edge_ngram_analyzer": {
            "filter": ["lowercase"],
            "tokenizer": "edge_ngram_tokenizer"
          },
          "edge_ngram_search_analyzer": {
            "tokenizer": "lowercase"
          }
        },
        "tokenizer": {
          "edge_ngram_tokenizer": {
            "type": "edge_ngram",
            "min_gram": 2,
            "max_gram": 5,
            "token_chars": ["letter"]
          }
        }
      }
    }
  },
  "mappings": {
    "marvels": {
      "properties": {
        "name": {
          "type": "text",
          "fields": {
            "keywordstring": {
              "type": "text",
              "analyzer": "keyword_analyzer"
            },
            "edgengram": {
              "type": "text",
              "analyzer": "edge_ngram_analyzer",
              "search_analyzer": "edge_ngram_search_analyzer"
            },
            "completion": {
              "type": "completion"
            }
          },
          "analyzer": "standard"
        }
      }
    }
  }
}
```

must - clause specifies all the queries that must be true for a document to be considered a match
should - clause specifies a list of queries some must be true for a document to be considered a match.

```json
{
  "query": {
    "match": { "address": "mill" }, // all containing the term "mill"
    "match": { "address": "mill lane" }, // all containing the term "mill" or "lane"
    "match_phrase": { "address": "mill lane" }, // all containing the phrase "mill lane"
    // containing "mill" and "lane"
    "bool": {
      "must": [
        { "match": { "address": "mill" } },
        { "match": { "address": "lane" } }
      ]
    },
    // containing "mill" or "lane"
    "bool": {
      "should": [
        { "match": { "address": "mill" } },
        { "match": { "address": "lane" } }
      ]
    },
    // not contain "mill" nor "lane"
    "bool": {
      "must_not": [
        { "match": { "address": "mill" } },
        { "match": { "address": "lane" } }
      ]
    },
    // combine must, should, and must_not inside a bool query.
    "bool": {
      "must": [{ "match": { "age": "40" } }],
      "must_not": [{ "match": { "state": "ID" } }]
    }
  }
}
```

### requests

```js
GET /_cat/indices // show current info about tables
GET /_cat/health?v
POST /_analyze // example analyse
{
  "tokenizer": "standard",
  "filter": ["lowercase", "asciifolding"],
  "analyzer": "english",
  "text": "AUDI SUPER 90 (1966 - 1971)"
}
GET /td_articles/_settings?pretty //show settings
GET /td_articles/_mapping?pretty // show mapping
PUT /td_models2?pretty // set settings and mapping
{
  "mappings": {
    "models": {
      "properties": {
        "manufacturer": {
          "properties": {
            "id": { "type": "integer" },
            "description": { "type": "text" },
            "matchcode": { "type": "keyword" },
            "ispopular": { "type": "boolean" }
          }
        },

        "id": { "type": "integer" },
        "year_from": { "type": "date" },
        "year_to": { "type": "date" },
        "description": { "type": "text" },
        "fulldescription": { "type": "text" },
        "slug": { "type": "keyword" }
      }
    }
  },
  "settings": {
    "number_of_shards": 1,
    "number_of_replicas": 0
  }
}
GET // example
{
    "aggs": {
        "suppliers": {
            "terms": {
                "field": "article.supplier.description.raw",
                "size": 100
            }
        }
    },
    "size": 50,
    "from": 0,
    "sort": {
        "price": {
            "order": "asc"
        }
    },
    "query": {
        "bool": {
            "must": [
                {
                    "terms": {
                        "article.supplier.description": [
                            "audi"
                        ]
                    }
                },
                {
                    "bool": {
                        "should": [
                            {
                                "terms": {
                                    "article.id": [
                                        5071446
                                    ],
                                    "boost": 3
                                }
                            },
                            {
                                "terms": {
                                    "article.id": [
                                        7767637
                                    ],
                                    "boost": 2
                                }
                            }
                        ]
                    }
                }
            ]
        }
    }
}
```

```json

```
