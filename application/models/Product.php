<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product extends CI_Model {

	public function __construct() {
        parent::__construct();

        $this->load->database();
        
        $this->productTbl = 'products';
    }

    function fetch_all($id = 0)
    {
      $this->db->select('*');
      $this->db->from($this->productTbl);    
      if(!empty($id)){
         $this->db->where('categoryId',$id);
         $query = $this->db->where('categoryId',$id)->get();
         $result = $query->row_array();
      }else{
         $query = $this->db->get();
         $result = ($query->num_rows() > 0)?$query->result_array():false;
      }  
      return $result;
    }

    /*
    * Get rows from the products table
    */
    function getRows($params = array()){
        $this->db->select('*');
        $this->db->from($this->productTbl);
        
        //fetch data by conditions
        if(array_key_exists("conditions",$params)){
            foreach($params['conditions'] as $key => $value){
                $this->db->where($key,$value);
            }
        }
       
        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
            $result = $this->db->count_all_results();    
        }elseif(array_key_exists("returnType",$params) && $params['returnType'] == 'single'){
            $query = $this->db->get();
            $result = ($query->num_rows() > 0)?$query->row_array():false;
        }else{
            $query = $this->db->get();
            $result = ($query->num_rows() > 0)?$query->result_array():false;
        }

        //return fetched data
        return $result;
    }

    /*
     * Insert product data
     */
    public function insert($data){
        //add created and modified date if not exists
        if(!array_key_exists("created", $data)){
            $data['date_created'] = date("Y-m-d H:i:s");
        }
        //insert products data to productss table
        $insert = $this->db->insert($this->productTbl, $data);
        
        //return the status
        return $insert?$this->db->insert_id():false;
    }
    
    /*
     * Delete product data
     */
    public function delete($id){
        //update user from users table
         $delete = $this->db->delete($this->productTbl,array('productId'=>$id));
        // //return the status
         return $delete?true:false;
    }

    public function getSearchData($search=""){
        $this->db->select('*');
        $this->db->from($this->productTbl);
        if($search != ''){
          $this->db->like('productName', $search);
          $this->db->or_like('categoryId', $search);
        }
        $query = $this->db->get();
        $result = ($query->num_rows() > 0)?$query->result_array():false;
        return $result;
    }
    
}	