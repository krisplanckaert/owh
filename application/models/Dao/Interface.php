<?php
interface Application_Model_Dao_Interface
{
    public function getOne($id);
    public function insert($data);
    public function update($data,$where);
    public function delete($id);
    public function getAll();
    //public function save($data,$id);
    
}