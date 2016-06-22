<?php
namespace Library\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Library
{
   public function exchangeArray($data)
   {
        foreach($data as $field=>$value):
            $this->$field = (isset($value))  ? $value  : null;
        endforeach;
    }
    
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();  
            $factory     = new InputFactory();
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'book_id',
                'required' => false,                
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'branch_id',
                'required' => false,                
            )));
            
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

    // Add the following method:
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}