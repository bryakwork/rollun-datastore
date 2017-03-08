<?php

return [
    'tableManagerMysql' => [
        'tablesConfigs' => [
            'test_table_config' => [],
        ],
        'autocreateTables' => [
            'test_autocreate_table' => 'test_table_config'
        ]
    ],

    'tableGateway' => [
        'test_res_tablle' => [
            'sql' => rollun\datastore\TableGateway\DbSql\MultiInsertSql::class,
        ],
        'table_with_name_same_as_resource_name' => [],
        'tbl_name_which_exist' => [],
        'test_res_http' => []
    ],

    'dataStore' => [
        /*'testDbResHttp' => [
            'class' => rollun\datastore\DataStore\DbTable::class,
            'tableName' => 'test_res_http'
        ],*/
        'test_DataStoreDbTableWithNameAsResourceName' => [
            'class' => rollun\datastore\DataStore\DbTable::class,
            'tableName' => 'table_for_db_data_store'
        ],
        'test_StoreForMiddleware' => [
            'class' => rollun\datastore\DataStore\Memory::class,
        ],
        'testDbTable' => [
            'class' => rollun\datastore\DataStore\DbTable::class,
            'tableName' => 'test_res_tablle'
        ],

        'testDbTableMultiInsert' => [
            'class' => rollun\datastore\DataStore\DbTable::class,
            'tableGateway' => 'test_res_tablle',
        ],
        'testHttpClient' => [
            'class' => rollun\datastore\DataStore\HttpClient::class,
            'tableName' => 'test_res_http',
            'url' => 'http://' . constant("HOST") . '/api/rest/test_res_http',
            'options' => ['timeout' => 30]
        ],
        'testEavOverHttpClient' => [
            'class' => rollun\datastore\DataStore\HttpClient::class,
            'url' => 'http://' . constant("HOST") . '/api/rest/entity_product',
            'options' => ['timeout' => 30]
        ],
        'testEavOverHttpDbClient' => [
            'class' => rollun\datastore\DataStore\HttpClient::class,
            'url' => 'http://' . constant("HOST") . '/api/rest/db~entity_product',
            'options' => ['timeout' => 30]
        ],
        'testMemory' => [
            'class' => rollun\datastore\DataStore\Memory::class,
        ],
        'testCsvBase' => [
            'class' => rollun\datastore\DataStore\CsvBase::class,
            'filename' => sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'testCsvBase.tmp',
            'delimiter' => ';',
        ],
        'testCsvIntId' => [
            'class' => rollun\datastore\DataStore\CsvIntId::class,
            'filename' => sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'testCsvIntId.tmp',
            'delimiter' => ';',
        ],
        'testAspectAbstract' => [
            'class' => rollun\datastore\DataStore\Aspect\AspectAbstract::class,
            'dataStore' => 'testMemory',
        ],

        'testDataSourceDb' => [
            'class' => rollun\datastore\DataSource\DbTableDataSource::class,
            //'class' => 'rollun\datastore\DataStore\DbTable',
            'tableName' => 'test_res_http'
        ],

        'testCacheable' => [
            'class' => rollun\datastore\DataStore\Cacheable::class,
            'dataSource' => 'testDataSourceDb',
            'cacheable' => 'testDbTable'
        ]
    ],
    'middleware' => [
        'test_MiddlewareWithNameAsResourceName' => [
            'class' => rollun\datastore\Middleware\DataStoreRest::class,
            'dataStore' => 'test_StoreForMiddleware'
        ],
        'MiddlewareMemoryTest' => [
            'class' => rollun\datastore\Examples\Middleware\DataStoreMemory::class,
            'dataStore' => 'testMemory'
        ],
       // "test_StoreForMiddlewareMiddleware" => "test_StoreForMiddleware",
        //"testDbResHttpMiddleware" => "testDbResHttp",
        //"entityProductMiddleware" => "entity_product",
    ],
];
