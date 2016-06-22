<?php
namespace Library\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Driver\DriverInterface;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Predicate\Expression as Expr;
use Zend\Crypt\Password\Bcrypt;

class LibraryTable
{
    protected $tableGateway;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;		
    }
    public function fetchAll()
    {	
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function searchBy($post)
    {		
        $adapter = $this->tableGateway->getAdapter();       
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('c' => 'books')); 
        $select->join(array('se'=>'book_authors'), 'se.book_id = c.book_id',array('author_name' => 'author_name'));
        $select->join(array('s'=>'book_copies'), 's.book_id = c.book_id',array('no_of_copies' => 'no_of_copies'));
        $select->join(array('l'=>'library_branch'), 'l.branch_id = s.branch_id',array('branch_id' => 'branch_id'));
        // $select->group('c.book_id');                
        $select->order(array('c.book_id DESC')); 
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet;
    }

    public function ValidateCardNo(){
        $adapter = $this->tableGateway->getAdapter();       
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->columns(array('card_no'));
        $select->from(array('c' => 'borrower'));  
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $row = array();
        foreach ($resultSet as $card) {
            $row[] = $card->card_no;
        }
        return $row;
    }

    public function Checkout($data){
        $book_id = $data['book_id'];
        $branch_id = $data['branch_id'];
        $card_no = $data['card_no'];
        $date_out = $data['date_out'];
        $due_date = $data['due_date']; 


        $adapter = $this->tableGateway->getAdapter();        
        $sql = "update book_copies set no_of_copies = 0 where book_id = ".$book_id." and branch_id= ".$branch_id.""; 
        $statement = $adapter->query($sql);
        $result = $statement->execute();
        
        $sql1 = "insert into book_loans (id,book_id,branch_id,card_no,date_out,due_date,date_in) values (' ',"."'".$book_id."'".",".$branch_id.",".$card_no.","."'".$date_out."'".","."'".$due_date."'".",' ')";
        $statement1 = $adapter->query($sql1);
        $result1 = $statement1->execute();
    }


    public function Checkin($data){

        $book_id = $data['book_id'];
        $branch_id = $data['branch_id'];
        $card_no = $data['card_no'];
        $date_in = $data['date_in']; 

        $adapter = $this->tableGateway->getAdapter();        
        $sql = "update book_copies set no_of_copies = 1 where book_id = ".$book_id." and branch_id= ".$branch_id.""; 
        $statement = $adapter->query($sql);
        $result = $statement->execute();
        
        $sql1 = "update book_loans set date_in ="."'".$date_in."'"." where book_id = ".$book_id." and branch_id= ".$branch_id." and card_no = ".$card_no."";
        $statement1 = $adapter->query($sql1);
        $result1 = $statement1->execute();
    }



}
