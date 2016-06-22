<?php
namespace Library\Form;

use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;

class CheckoutForm extends Form
{
    public function __construct(AdapterInterface $dbAdapter)
    {
        // we want to ignore the name passed
        $this->setDbAdapter($dbAdapter);        
        parent::__construct('Library');     
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'book_id',
            'options' => array(
                'label' => 'Book ID <span style="color:red;"> *</span>',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'branch_id',
            'options' => array(
                'label' => 'Branch ID <span style="color:red;"> *</span>',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'card_no',
            'options' => array(
                'label' => 'card No <span style="color:red;"> *</span> ',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'date_out',
            'options' => array(
                'label' => 'Date Out',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'value' => date('Y-m-d'),
                'class' => 'form-control',
                'readonly' =>true
            ),
        ));
        $this->add(array(
            'name' => 'due_date',
            'options' => array(
                'label' => 'Due Date',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => false,
                'value' => Date('Y-m-d', strtotime("+14 days")),
                'class' => 'form-control',
                'readonly' =>true
            ),
        ));

        $this->add(array(
            'name' => 'date_in',
            'options' => array(
                'label' => 'Date In',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => false,
                'class' => 'form-control',
                'placeholder' => 'yyyy-mm-dd',
            ),
        ));
    
              
    $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Checkout',
                'id' => 'submitbutton',
                'class' => 'btn btn-large btn-primary',
            ),
        ));
        
    }
   public function setDbAdapter(AdapterInterface $dbAdapter)
    {
       $this->dbAdapter = $dbAdapter;
       return $this;
    }

     public function getDbAdapter()
    {
       return $this->dbAdapter;
    }
}
?>