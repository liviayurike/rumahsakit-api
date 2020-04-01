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
class Dokter extends REST_Controller
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
            $dokter = $this->db->get("dokter")->result_array();
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($dokter) {
                // Set the response and exit
                $this->response($dokter, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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

            $this->db->where(array("id_dok" => $id));
            $dokter = $this->db->get("dokter")->row_array();

            $this->response($dokter, REST_Controller::HTTP_OK);
        }
    }

    public function index_post()
    {
        // $this->some_model->update_user( ... );
        $data = [
            'nama_dok' => $this->post('nama_dok'),
            'alamat_dok' => $this->post('alamat_dok'),
            'no_telp_dok' => $this->post('no_telp_dok'),
            'spesialis_dok' => $this->post('spesialis_dok')
        ];

        $this->db->insert("dokter", $data);

        $this->set_response($data, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function index_delete()
    {
        // $this->some_model->delete_something($id);

        $id = $this->delete('id_dok');
        $this->db->where('id_dok', $id);
        $this->db->delete('dokter');
        $messages = array('status' => "Data berhasil dihapus");
        $this->set_response($messages, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

    public function index_put()
    {

        $data = array(
            'id_dok' => $this->put('id_dok'),
            'nama_dok' => $this->put('nama_dok'),
            'alamat_dok' => $this->put('alamat_dok'),
            'no_telp_dok' => $this->put('no_telp_dok'),
            'spesialis_dok' => $this->put('spesialis_dok')
        );

        $this->db->where('id_dok', $this->put('id_dok'));
        $this->db->update('dokter', $data);

        $this->set_response($data, REST_Controller::HTTP_CREATED);
    }
}
