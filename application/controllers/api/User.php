<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class User extends REST_Controller
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function index_get()
    {
        // Users from a data store e.g. database
        $id = $this->get('id');

        // If the id parameter doesn't exist return all the users

        if ($id === NULL) {
            $user = $this->db->get("user")->result_array();
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($user) {
                // Set the response and exit
                $this->response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'Tidak Ditemukan dokter'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        // Find and return a single record for a particular user.
        else {
            $id = (int) $id;

            // Validate the id.
            if ($id <= 0) {
                // Invalid id, set the response and exit.
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

            $this->db->where(array("id_user" => $id));
            $dokter = $this->db->get("user")->row_array();

            $this->response($dokter, REST_Controller::HTTP_OK);
        }
    }

    public function index_post()
    {
        // $this->some_model->update_user( ... );
        $data = [
            'nama' => $this->post('nama'),
            'username' => $this->post('username'),
            'email' => $this->post('email'),
            'password' => $this->post('password'),
            'level' => $this->post('level'),
            'status' => $this->post('status')
        ];

        $this->db->insert("user", $data);

        $this->set_response($data, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function index_delete()
    {
        // $this->some_model->delete_something($id);

        $id = $this->delete('id_user');
        $this->db->where('id_user', $id);
        $this->db->delete('user');
        $messages = array('user' => "Data berhasil dihapus");
        $this->set_response($messages, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

    public function index_put()
    {

        $data = array(
            'id_user' => $this->put('id_user'),
            'nama' => $this->put('nama'),
            'username' => $this->put('username'),
            'email' => $this->put('email'),
            'password' => $this->put('password'),
            'level' => $this->put('level'),
            'status' => $this->put('status')
        );

        $this->db->where('id_user', $this->put('id_user'));
        $this->db->update('user', $data);

        $this->set_response($data, REST_Controller::HTTP_CREATED);
    }
}
