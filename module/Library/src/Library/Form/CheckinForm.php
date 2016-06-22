<?php
namespace Library\Form;

use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;

class CheckinForm extends Form
{
    public function __construct(AdapterInterface $dbAdapter)
    {
        // we want to ignore the name passed
        //$this->setDbAdapter($dbAdapter);		
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
                'label' => 'Card No <span style="color:red;"> *</span>',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'date_in',
            'options' => array(
                'label' => 'Date IN <span style="color:red;"> *</span>',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'class' => 'form-control',
                'placeholder' => 'yyyy-mm-dd',
            ),
        ));
	
	          
	$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'CheckIn',
                'id' => 'submitbutton',
                'class' => 'btn btn-large btn-primary',
            ),
        ));
        
    }
   
}
?>