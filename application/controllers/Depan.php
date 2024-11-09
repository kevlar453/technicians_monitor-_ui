<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Depan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'file'));
        $this->load->model('M_Admin');
    }

    public function index()
    {
        $data = array(
            'title' => 'AR Setontong',
            'isi' => 'home/d_home',
        );
        $this->load->view('home/d_wrapper', $data, FALSE);
    }
    public function about()
    {
        $data = array(
            'title' => 'About | AR Setontong',
            'isi' => 'home/d_tentang'
        );
        $this->load->view('home/v_wrapper', $data, FALSE);
    }
    public function peta()
    {
        $data = array(
            'title' => 'Peta | AR Setontong',
            'isi' => 'home/d_peta',
            'unit' => $this->M_Admin->get_table_maps('tbl_client')
        );
        $this->load->view('home/v_wrapper', $data, FALSE);
    }
}
