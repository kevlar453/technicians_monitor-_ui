<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //validasi jika user belum login
        $this->data['CI'] = &get_instance();
        $this->load->helper(array('form', 'url'));
        $this->load->model('M_login');
    }

    public function index()
    {
        $this->data['title_web'] = 'Login | ACS Monit';
        $this->load->view('v_login', $this->data);
    }

    public function auth()
    {
        $user = htmlspecialchars($this->input->post('user', TRUE), ENT_QUOTES);
        $pass = htmlspecialchars($this->input->post('pass', TRUE), ENT_QUOTES);
        // auth
        $proses_login = $this->db->query("SELECT * FROM tbl_user WHERE user='$user' AND pass = md5('$pass')");
        $row = $proses_login->num_rows();
        if ($row > 0) {
            $hasil_login = $proses_login->row_array();

            // create session
            $kode_com = '';

            // Loop through each character, selecting only odd-indexed elements
            for ($i = 0; $i < strlen($hasil_login['clidentitas']); $i++) {
                // Check if the index is odd (considering 1-based positioning as "odd")
                if (($i % 2) == 0) {
                    $kode_com .= $hasil_login['clidentitas'][$i];
                }
            }
            $cek_usaha = $this->db->query("SELECT * FROM tbl_perusahaan WHERE com_kode='$kode_com'");
            $this->session->set_userdata('com_jns', $cek_usaha['com_jenis']);
            $this->session->set_userdata('com_id', $kode_com);
            $this->session->set_userdata('masuk_sistem_rekam', TRUE);
            $this->session->set_userdata('level', $hasil_login['level']);
            $this->session->set_userdata('asal_wilayah', $hasil_login['asal_wilayah']);
            $this->session->set_userdata('ses_id', $hasil_login['id_user']);
            $this->session->set_flashdata('success_login', 'success_login');
            if ($this->session->userdata('level') == 'master') {
              echo '<script>window.location="' . base_url() . 'master";</script>';
            } else {
              echo '<script>window.location="' . base_url() . 'dashboard";</script>';
            }
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Login Gagal, Periksa Kembali Username dan Password Anda!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            redirect('login');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('masuk_sistem_rekam');
        $this->session->unset_userdata('level');
        $this->session->unset_userdata('asal_wilayah');
        $this->session->unset_userdata('ses_id');
        $this->session->set_flashdata('success_logout', 'success_logout');
        redirect('login');
    }
}
