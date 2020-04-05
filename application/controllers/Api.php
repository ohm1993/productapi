<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
class Api extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('user');
    }

    public function index_post() {
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        if(!empty($first_name) && !empty($last_name) && !empty($email) && !empty($password)){
            // Check if the given email already exists
            $con['returnType'] = 'count';
            $con['conditions'] = array(
                'email' => $email,
            );
            $userCount = $this->user->getRows($con);
            if($userCount > 0){
                // Set the response and exit
                $response = ['status' => "false", 'msg' => 'The given email already exists.'];
                $this->response($response);
            }else{
                $userData = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'password' => md5($password),
                );
                $insert = $this->user->insert($userData);
                if($insert){
                    $this->response([
                        'status' => TRUE,
                        'message' => 'The user has been added successfully.',
                        'data' => $insert
                    ], REST_Controller::HTTP_OK);
                }else{
                    $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
                }
            }    
        }else{
            $response = ['status' => "false", 'msg' => 'Provide complete user info to add'];
            $this->response($response);
        }    
    } 

    public function login_post() {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        if(!empty($email) && !empty($password)){
            $con['returnType'] = 'single';
            $con['conditions'] = array(
                'email' => $email,
                'password' => md5($password)
            );
            $user = $this->user->getRows($con);
            if($user){
                // Set the response and exit
                $this->response([
                    'status' => TRUE,
                    'message' => 'User login successful.',
                    'data' => $user
                ], REST_Controller::HTTP_OK);
            }else{
                $response = ['status' => FALSE, 'msg' => 'Wrong email or password.'];
                $this->response($response);
            }
        }else{
            $response = ['status' => FALSE, 'msg' => 'Please Provide email and password.'];
            $this->response($response);
        }    
    }   
}
