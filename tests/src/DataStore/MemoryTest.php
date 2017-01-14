<?php
/**
 * Zaboy lib (http://zaboy.org/lib/)
 *
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace rollun\test\datastore\DataStore;

use rollun\datastore\Rql\RqlQuery;
class MemoryTest extends AbstractTest
{

    /**
     * @var NameOfClass
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function presetUp()
    {
        $this->setUp();
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->object = $this->container->get('testMemory');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * This method init $this->object
     */
    protected function _initObject($data = null)
    {
        if (is_null($data)) {
            $data = $this->_itemsArrayDelault;
        }
        foreach ($data as $record) {
            $this->object->create($record);
        }
    }
}
