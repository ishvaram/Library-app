<?php

namespace Library;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Library\Model\Library;
use Library\Model\LibraryTable;
use Library\Form\CheckoutForm;
use Library\Form\CheckinForm;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
	// Add this method:
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Library\Model\LibraryTable' =>  function($sm) {
                    $tableGateway = $sm->get('LibraryTableGateway');
                    $table = new LibraryTable($tableGateway);
                    return $table;
                },
				
                'LibraryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Library());
		    return new TableGateway('books', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
