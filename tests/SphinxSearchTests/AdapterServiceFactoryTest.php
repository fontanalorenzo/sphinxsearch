<?php
/**
 * User: leodido
 * Date: 28/01/14
 * Time: 13.20
 */

namespace SphinxSearchTests;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;

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
    public function setUp()
    {
        $this->serviceManager = new ServiceManager(new Config(array(
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
     */
    public function testInvalidService($service)
    {
        $actual = $this->serviceManager->get($service);
        $this->assertInstanceOf('Zend\Db\Adapter\Adapter', $actual);
    }

}
