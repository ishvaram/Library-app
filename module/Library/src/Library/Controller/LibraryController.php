<?php

namespace Library\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Library\Model\LibraryTable;
use Library\Model\Library; 

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;         

use Library\Form\CheckinForm;  
use Library\Form\CheckoutForm;  

class LibraryController extends AbstractActionController
{
    const ROUTE_LOGIN        = 'zfcuser/login';
    protected $LibraryTable;
    protected $libraryService;
    public function indexAction()
    {		
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        //Auth User Identity
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        if($auth->hasIdentity())
        {            
            $logged_user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
        }
        $view = new ViewModel(array(
                    'message' => 'Library',		
                    'library' => $this->getLibraryTable()->searchBy($_REQUEST),
                ));		
       return $view;
    }

    public function getlibraryService()
    {
        if (!$this->libraryService) {
            $this->libraryService = $this->getServiceLocator()->get('zfcuser_user_mapper');
        }
        return $this->libraryService;
    }

    public function getLibraryTable()
    {
        if (!$this->LibraryTable) {
            $sm = $this->getServiceLocator();
            $this->LibraryTable = $sm->get('Library\Model\LibraryTable');
        }
        return $this->LibraryTable;
    }
   
    public function CheckoutAction()
    {       
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new CheckoutForm($dbAdapter);        
        $request = $this->getRequest();        
        if ($request->isPost()) {     
            $template = new Library();
            $form->setInputFilter($template->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                $template = $_POST;
                $card = $template['card_no'];
                $card_no = $this->getLibraryTable()->ValidateCardNo();
                $iscard = 0;
                if (!in_array($card, $card_no)) 
                {
                    $iscard  = 1;
                }

                if($iscard == 1)
                {                    
                $this->flashMessenger()->addErrorMessage('Card number is not valid .. Please Enter a valid card number');
                    return $this->redirect()->toRoute('library');
                 }
                $this->getLibraryTable()->Checkout($template);                
                $this->flashMessenger()->addSuccessMessage('Checked out successfully');
                return $this->redirect()->toRoute('library');
            } else {
                print_r($form->getMessages());
            }
        }
        return array('form' => $form);
    }    
    public function CheckinAction()
    {       
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new CheckinForm($dbAdapter);        
        $request = $this->getRequest();        
        if ($request->isPost()) {     
            $template = new Library();
            $form->setInputFilter($template->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                $template = $_POST;
                $card = $template['card_no'];
                $card_no = $this->getLibraryTable()->ValidateCardNo();
                $iscard = 0;
                if (!in_array($card, $card_no)) 
                {
                    $iscard  = 1;
                }

                if($iscard == 1)
                {                    
                $this->flashMessenger()->addErrorMessage('Card number is not valid .. Please Enter a valid card number');
                    return $this->redirect()->toRoute('library');
                 }
                $this->getLibraryTable()->Checkin($template);                
                $this->flashMessenger()->addSuccessMessage('Book has been Checkin successfully');
                return $this->redirect()->toRoute('library');
            } else {
                print_r($form->getMessages());
            }
        }
        return array('form' => $form);
    }
   
}
