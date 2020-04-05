<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
class ProductApi extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('product');
    }

    public function index_get($id = 0){
        if(!empty($id)){
           $products = $this->product->fetch_all($id);
        }else{
           $products = $this->product->fetch_all();
        }
       $response = ['status' => TRUE, 'products' => $products];
       $this->response($response);
    }

    public function index_post() {
        $productName = $this->input->post('productName');
        $categoryId = $this->input->post('categoryId');
        if(!empty($productName) && !empty($categoryId)){
            $con['returnType'] = 'count';
            $con['conditions'] = array(
                'productName' => $productName,
            );
            $productCount = $this->product->getRows($con);
            if($productCount > 0){
                $response = ['status' => "false", 'msg' => 'The given product name already exists.'];
                $this->response($response);
            }else{
                $productData = array(
                    'productName' => $productName,
                    'categoryId' => $categoryId,
                );
                $insert = $this->product->insert($productData);
                if($insert){
                    $this->response([
                        'status' => TRUE,
                        'message' => 'The product has been added successfully.',
                        'data' => $insert
                    ], REST_Controller::HTTP_OK);
                }else{
                    $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
                }
            }
        }else{
            $response = ['status' => "false", 'msg' => 'Please provide both categoryid and product name.'];
            $this->response($response);
        }
    } 
    public function index_delete(){
        $id = $this->uri->segment(3);
        $delete = $this->product->delete($id);
        $response = ['status' => TRUE, 'msg' => 'Product deleted successfully'];
        $this->response($response);
    } 
    function loadRecord(){
        echo "search";
        $search_text = "";
    }     

}
