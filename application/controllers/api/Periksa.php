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
class Periksa extends REST_Controller
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

            $this->db->select('*');
            $this->db->from('periksa p');
            $this->db->join('user u', 'u.id_user = p.id_user');
            $this->db->join('dokter d', 'd.id_dok = p.id_dok');

            $periksa = $this->db->get()->result_array();

            // Check if the users data store contains users (in case the database result returns NULL)
            if ($periksa) {
                // Set the response and exit
                $this->response($periksa, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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

            $this->db->query("select * from periksa p join user u on u.id_user = p.id_user join dokter d on d.id_dok = p.id_dok order by p.id_periksa DESC");
            $periksa = $this->db->get("periksa")->row_array();

            $this->response($periksa, REST_Controller::HTTP_OK);
        }
    }

    public function index_post()
    {
        // $this->some_model->update_user( ... );
        $data = [
            'id_periksa' => $this->post('id_periksa'),
            'id_user' => $this->post('id_user'),
            'id_dok' => $this->post('id_dok'),
            'keluhan' => $this->post('keluhan'),
            'tglperiksa' => $this->post('tglperiksa')
        ];

        $this->db->insert("periksa", $data);

        $this->set_response($data, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function index_delete()
    {
        // $this->some_model->delete_something($id);

        $id = $this->delete('id_periksa');
        $this->db->where('id_periksa', $id);
        $this->db->delete('periksa');
        $messages = array('status' => "Data berhasil dihapus");
        $this->set_response($messages, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

    public function index_put()
    {

        $data = array(
            'id_periksa' => $this->put('id_periksa'),
            'id_user' => $this->put('id_user'),
            'id_dok' => $this->put('id_dok'),
            'keluhan' => $this->put('keluhan'),
            'tglperiksa' => $this->put('tglperiksa')
        );

        $this->db->where('id_periksa', $this->put('id_periksa'));
        $this->db->update('periksa', $data);

        $this->set_response($data, REST_Controller::HTTP_CREATED);
    }
}
