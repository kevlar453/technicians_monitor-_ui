<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitor extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //validasi jika user belum login
        $this->data['CI'] = &get_instance();
        $this->load->helper(array('form', 'url', 'file'));
        $this->load->model('M_Admin');
        if ($this->session->userdata('masuk_sistem_rekam') != TRUE) {
            $url = base_url('login');
            redirect($url);
        }
    }

    public function index()
    {
        $this->data['idbo'] = $this->session->userdata('ses_id');
        $this->data['title_web'] = 'Peta | ACS Monit';
        if($this->session->userdata('level') == 'operator'){
          $this->data['unit'] = $this->M_Admin->get_table_maps('tbl_client');
          $this->data['daftar_unit'] =  $this->db->query("SELECT * FROM tbl_client  WHERE set_com='".$this->session->userdata('com_id')."' ORDER BY id_unit ASC")->result_array();
        } elseif ($this->session->userdata('level') == 'manajemen') {
          $this->data['unit'] = $this->M_Admin->get_table_maps('tbl_client');
          $this->data['daftar_unit'] =  $this->db->query("SELECT * FROM tbl_client  WHERE set_com='".$this->session->userdata('com_id')."' ORDER BY id_unit ASC")->result_array();
          $this->data['wilayah'] = $this->M_Admin->get_table_maps('tbl_wilayah');
          $this->data['daftar_wilayah'] =  $this->db->query("SELECT * FROM tbl_wilayah  WHERE set_com='".$this->session->userdata('com_id')."' ORDER BY id_wil ASC")->result_array();
        }
//        if($this->data['daftar_unit']){
          $this->data['hadir'] = $this->M_Admin->get_table_absen_user();
          $this->load->view('header_view', $this->data);
          $this->load->view('sidebar_view', $this->data);
          $this->load->view('monitor/v_peta', $this->data);
          $this->load->view('footer_view', $this->data);
//        }
    }

    public function reportv1($kdreport = false)
    {
      $this->load->library('pdf');
      $this->pdf->set_option('isRemoteEnabled', TRUE);
      $this->pdf->set_option('isHtml5ParserEnabled', TRUE);

        $this->data['idbo'] = $this->session->userdata('ses_id');
        $this->data['title_web'] = 'Report Version-1 | ACS Monit';
        $this->data['treport'] = $this->M_Admin->get_tableid_detedit('tbl_report','rep_kode',$kdreport);
        $this->data['trep01'] = $this->M_Admin->get_tableid_detedit('rep01','rep1idnota',$kdreport);
        $this->data['trep02'] = $this->M_Admin->get_tableid_detedit('rep02','rep2idnota',$kdreport);
        $this->data['trep03'] = $this->M_Admin->get_tableid_detedit('rep03','rep3idnota',$kdreport);
        $this->data['trep04'] = $this->M_Admin->get_tableid_detedit('rep04','rep4idnota',$kdreport);
        $this->data['trep05'] = $this->M_Admin->get_tableid_detedit('rep05','rep5idnota',$kdreport);
        $this->data['trep06'] = $this->M_Admin->get_tableid_detedit('rep06','rep6idnota',$kdreport);
        $this->data['trep07'] = $this->M_Admin->get_tableid_detedit('rep07','rep7idnota',$kdreport);
        $this->data['trep08'] = $this->M_Admin->get_tableid_detedit('rep08','rep8idnota',$kdreport);
        $this->data['trep09'] = $this->M_Admin->get_tableid_detedit('rep09','rep9idnota',$kdreport);
//        $this->data['hadir'] = $this->M_Admin->get_table_absen_user();
$paper_size = 'legal';
$orientation = 'portrait';
$this->pdf->setPaper($paper_size, $orientation);
$this->pdf->filename = $kdreport.".pdf";
        $this->pdf->load_view('monitor/laporan/report_var1', $this->data);
    }

    public function up_index()
    {
        $thadir = $this->M_Admin->get_table_absen_user();
        echo json_encode($thadir);
    }

    public function pencarian()
    {
        $keyword = $this->input->post('keyword');
        $data = array(
            'title_web' => 'Pencarian Klien | ACS Monit',
            'keyword' =>  $keyword,
            'idbo' => $this->session->userdata('ses_id'),
            'unit' => $this->M_Admin->get_unit_keyword($keyword),
            'daftar_unit' =>  $this->db->query("SELECT * FROM tbl_client  WHERE set_com='".$this->session->userdata('com_id')."' ORDER BY id_unit ASC")->result_array()
        );
        $this->load->view('header_view', $data);
        $this->load->view('sidebar_view', $data);
        $this->load->view('monitor/v_cari', $data);
        $this->load->view('footer_view', $data);
    }
}
