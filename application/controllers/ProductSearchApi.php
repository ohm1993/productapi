<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductSearchApi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('product');
    }

	public function product_search()
	{
		$search_text = "";
	    $search_text = $this->input->post('search');
	    $products = $this->product->getSearchData($search_text);
	    $response = ['status' => TRUE, 'products' => $products];
	    echo json_encode($response);
	}
}
