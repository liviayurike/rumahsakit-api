<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Transaksi extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();
    }

    //Menampilkan data transaksi
    public function index_get()
    {
        // Users from a data store e.g. database
        $id = $this->get('id');

        // If the id parameter doesn't exist return all the users

        if ($id === NULL) {
            $this->db->select('*');
            $this->db->from('transaksi t');
            $this->db->join('user u', 'u.id_user = t.id_user');
            $this->db->join('periksa p', 'p.id_periksa = t.id_periksa');
            $this->db->join('dokter d', 'd.id_dok = t.id_dok');
            $this->db->join('obat o', 'o.id_obat = t.id_obat');

            $transaksi = $this->db->get()->result_array();
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($transaksi) {
                // Set the response and exit
                $this->response($transaksi, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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

            $this->db->query("select * from transaksi t join user u on u.id_user = t.id_user 
                                join periksa p on p.id_periksa = t.id_periksa join dokter d on d.id_dok = p.id_dok 
                                join obat o on u.id_obat = t.id_obat order by t.id_transaksi DESC");
            $transaksi = $this->db->get("transaksi")->row_array();


            $this->response($transaksi, REST_Controller::HTTP_OK);
        }
    }

    //Mengirim atau menambah data kontak baru
    function index_post()
    {
        $data = array(
            'id_transaksi'           => $this->post('id_transaksi'),
            'id_user'          => $this->post('id_user'),
            'id_dok'    => $this->post('id_dok'),
            'id_obat'    => $this->post('id_obat'),
            'biaya' => $this->post('biaya'),
            'tgl_transaksi' => $this->post('tgl_transaksi')
        );
        $insert = $this->db->insert('transaksi', $data);
        if ($insert) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    //Memperbarui data kontak yang telah ada
    public function index_put()
    {

        $data = array(
            'id_transaksi'           => $this->put('id_transaksi'),
            'id_user'          => $this->put('id_user'),
            'id_dok'    => $this->put('id_dok'),
            'id_obat'    => $this->put('id_obat'),
            'biaya' => $this->put('biaya'),
            'tgl_transaksi' => $this->put('tgl_transaksi')
        );

        $this->db->where('id_transaksi', $this->put('id_transaksi'));
        $this->db->update('transaksi', $data);

        $this->set_response($data, REST_Controller::HTTP_CREATED);
    }

    //Masukan function selanjutnya disini

    //Menghapus salah satu data kontak
    function index_delete()
    {
        $id = $this->delete('id');

        $where = [
            'id_transaksi' => $id,
        ];

        $this->db->delete("transaksi", $where);
        $message = array('status' => 'success');

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT);
    }
}
