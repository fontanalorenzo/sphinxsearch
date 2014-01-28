<?php
/**
 * ZF2 Sphinx Search
 *
 * @link        https://github.com/ripaclub/zf2-sphinxsearch
 * @copyright   Copyright (c) 2014, Leonardo Di Donato <leodidonato at gmail dot com>, Leonardo Grasso <me at leonardograsso dot com>
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace SphinxSearchTests\Db\Adapter;

use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

class AdapterServiceFactoryTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    private $serviceManager;

    /**
     * Set up service manager and database configuration.
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->serviceManager = new ServiceManager(new ServiceManagerConfig(array(
            'factories' => array(
                'SphinxSearch\Db\Adapter\Adapter' => 'SphinxSearch\Db\Adapter\AdapterServiceFactory'
            ),
            'alias' => array(
                'sphinxql' => 'SphinxSearch\Db\Adapter\Adapter'
            )
        )));
        $this->serviceManager->setService('Config', array(
                'sphinxql' => array(
                    'driver' => 'pdo_mysql',
                ),
        ));
    }

    /**
     * @return array
     */
    public function providerValidService()
    {
        return array(
            array('SphinxSearch\Db\Adapter\Adapter')
        );
    }

    /**
     * @return array
     */
    public function providerInvalidService()
    {
        return array(
            array('SphinxSearch\Db\Adapter\Unknown'),
        );
    }

    /**
     * @param string $service
     * @dataProvider providerValidService
     * @testdox Instantiates an adapter
     */
    public function testValidService($service)
    {
        $actual = $this->serviceManager->get($service);
        $this->assertInstanceOf('Zend\Db\Adapter\Adapter', $actual);
    }

    /**
     * @param string $service
     * @dataProvider providerInvalidService
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @testdox Does not instantiate an invalid/unknow adapter
     */
    public function testInvalidService($service)
    {
        $actual = $this->serviceManager->get($service);
        $this->assertInstanceOf('Zend\Db\Adapter\Adapter', $actual);
    }

}
 