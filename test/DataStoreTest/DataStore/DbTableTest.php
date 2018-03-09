<?php

/**
 * Zaboy lib (http://zaboy.org/lib/)
 *
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace rollun\test\datastore\DataStore;

use PDOException;
use rollun\datastore\DataStore\DataStoreException;
use Xiag\Rql\Parser\Node\Query\ScalarOperator\EqNode;
use Xiag\Rql\Parser\Node\SelectNode;
use Xiag\Rql\Parser\Node\SortNode;
use Xiag\Rql\Parser\Query;
use rollun\datastore\DataStore\DbTable;
use rollun\datastore\Rql\RqlQuery;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-11 at 16:19:25.
 */
class DbTableTest extends AbstractTest
{

    const TABLE_EXPLOIT_1_NAME = "test_exploit1_tablle";
    const TABLE_EXPLOIT_2_NAME = "test_exploit2_tablle";

    /**
     * @var DbTable
     */
    protected $object;

    /**
     * @var Adapter
     */
    protected $adapter;
    protected $dbTableName;
    protected $configTableDefault = array(
        'id' => 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY',
        'anotherId' => 'INT NOT NULL',
        'fString' => 'CHAR(20)',
        'fInt' => 'INT'
    );

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * @param string $dataStoreName
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function setUp($dataStoreName = "testDbTable")
    {
        parent::setUp();
        $this->dbTableName = $this->config[$dataStoreName]['tableName'];
        $this->adapter = $this->container->get('db');
        $this->object = $this->container->get($dataStoreName);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $quoteTableName = $this->adapter->platform->quoteIdentifier($this->dbTableName);
        $deleteStatementStr = "DROP TABLE IF EXISTS " . $quoteTableName;
        $deleteStatement = $this->adapter->query($deleteStatementStr);
        //$deleteStatement->execute();
    }

    /**
     *
     * @param array $data
     * @return string
     */
    protected function _getDbTableFields($data)
    {
        $record = array_shift($data);
        reset($record);
        $firstKey = key($record);
        $firstValue = array_shift($record);
        $dbTableFields = '';
        if (is_string($firstValue)) {
            $dbTableFields = '`' . $firstKey . '` CHAR(80) PRIMARY KEY';
        } elseif (is_integer($firstValue)) {
            $dbTableFields = '`' . $firstKey . '` INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
        } else {
            trigger_error("Type of primary key must be int or string", E_USER_ERROR);
        }
        foreach ($record as $key => $value) {
            if (is_string($value)) {
                $fieldType = ', `' . $key . '` CHAR(80)';
            } elseif (is_integer($value)) {
                $fieldType = ', `' . $key . '` INT';
            } elseif (is_float($value)) {
                $fieldType = ', `' . $key . '` DOUBLE PRECISION';
            } elseif (is_null($value)) {
                $fieldType = ', `' . $key . '` INT';
            } else {
                trigger_error("Type of field of array isn't supported.", E_USER_ERROR);
            }
            $dbTableFields = $dbTableFields . $fieldType;
        }
        return $dbTableFields;
    }

    /**
     * This method init $this->object
     */
    protected function _prepareTable($data)
    {
        $quoteTableName = $this->adapter->platform->quoteIdentifier($this->dbTableName);

        $deleteStatementStr = "DROP TABLE IF EXISTS " . $quoteTableName;
        $deleteStatement = $this->adapter->query($deleteStatementStr);
        $deleteStatement->execute();
        $createStr = "CREATE TABLE  " . $quoteTableName;
        $fields = $this->_getDbTableFields($data);
        $createStatementStr = $createStr . '(' . $fields . ') ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;';
        $createStatement = $this->adapter->query($createStatementStr);
        $createStatement->execute();
    }

    /**
     * This method init $this->object
     */
    protected function _initObject($data = null)
    {
        if (is_null($data)) {
            $data = $this->_itemsArrayDelault;
        }
        $this->_prepareTable($data);
        $dbTable = new TableGateway($this->dbTableName, $this->adapter);
        foreach ($data as $record) {
            $dbTable->insert($record);
        }
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function test_exploit()
    {
        $expected = [
            ['id' => 1, 'fString' => 'val1',],
            ['id' => 2, 'fString' => 'val2',],
            ['id' => 3, 'fString' => 'val3',],
            ['id' => 4, 'fString' => 'val4',],
        ];
        $this->setUp("exploited1DbTable");
        $this->_initObject([
            ['id' => 5, 'fString' => 'val5',],
            ['id' => 6, 'fString' => 'val6',],
            ['id' => 7, 'fString' => 'val7',],
            ['id' => 8, 'fString' => 'val8',],
        ]);
        $this->setUp("exploited2DbTable");
        $this->_initObject($expected);

        $query = new Query();
        $query->setSelect(new SelectNode(["id", "fString"]));
        //$query->setQuery(new EqNode("id` IN (SELECT id FROM test_res_http) OR `id", "val"));
        $query->setQuery(new EqNode("id` = 1) UNION SELECT id, fString FROM ".static::TABLE_EXPLOIT_1_NAME.";#`id", "val"));
        try {
            $result = $this->object->query($query);
            $this->assertEquals($expected, $result);//Assert false.
        }catch (PDOException $exception) {
            $this->assertTrue(true);
        }
    }

    /* * ************************** Identifier *********************** */
}
