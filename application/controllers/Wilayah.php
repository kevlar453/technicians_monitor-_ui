<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wilayah extends CI_Controller
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
		$this->db->order_by('id_wil', 'desc');
		$this->data['wilayah'] = $this->M_Admin->get_table('tbl_wilayah');
		$this->db->order_by('id_wil', 'desc');
		$this->data['wilayah_user'] = $this->M_Admin->get_table_wilayah_user();

		$this->data['title_web'] = 'Data Wilayah | ACS Monit';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('wilayah/wilayah_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function tambah()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['wilayah'] = $this->M_Admin->get_table('tbl_wilayah');

		$this->data['title_web'] = 'Tambah Wilayah | ACS Monit';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('wilayah/tambah_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}
	public function add()
	{
		$nama = htmlentities($this->input->post('nama_wilayah', TRUE));
		$geojson = $this->input->post('unit_geojson', TRUE);
		$latitude = htmlentities($this->input->post('latitude', TRUE));
		$longitude = htmlentities($this->input->post('longitude', TRUE));
		$warna = htmlentities($this->input->post('warna', TRUE));

		$dd = $this->db->query("SELECT * FROM tbl_wilayah WHERE nama_wilayah = '$nama'");
		if ($dd->num_rows() > 0) {
			$this->session->set_flashdata('pesan', '<div class="alert alert-warning" role="alert"> Gagal Tambah Unit : ' . $nama . ' !, Wilayah Sudah Ada<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
			redirect(base_url('wilayah/tambah'));
		} else {
			// setting konfigurasi upload
			$nmfile = $nama . '_' . time();
			$config['upload_path'] = './assets_style/file/';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['file_name'] = $nmfile;
			// load library upload
			$this->load->library('upload', $config);

			// uploud file pertama
			if ($this->upload->do_upload('foto')) {
				$this->upload->data();
				$file1 = array('upload_data' => $this->upload->data());
			} else {
				return false;
			}

			$convfoto = base64_encode(file_get_contents($file1['upload_data']['full_path']));
			$data = array(
				'kode_wilayah' => 'WL'.time().str_pad((intval($this->M_Admin->CountTable('tbl_client'))+1), 4, '0', STR_PAD_LEFT),
				'nama_wilayah' => $nama,
				'unit_geojson' => $geojson,
				'latitude' => $latitude,
				'longitude' => $longitude,
				'warna' => $warna,
				'foto' => $convfoto
			);
			$this->db->insert('tbl_wilayah', $data);
			$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert"> Tambah Wilayah berhasil !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
			redirect(base_url('wilayah'));
		}
	}

	public function edit()
	{
		if ($this->uri->segment('3') == '') {
			echo '<script>alert("halaman tidak ditemukan");window.location="' . base_url('wilayah') . '";</script>';
		}
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_wilayah', 'id_wil', $this->uri->segment('3'));
		if ($count > 0) {
			$this->data['wilayah'] = $this->M_Admin->get_tableid_edit('tbl_wilayah', 'id_wil', $this->uri->segment('3'));
		} else {
			echo '<script>alert("WILAYAH TIDAK DITEMUKAN");window.location="' . base_url('wilayah') . '"</script>';
		}
		$this->data['title_web'] = 'Edit Wilayah | ACS Monit';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('wilayah/edit_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function detail()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_wilayah', 'id_wil', $this->uri->segment('3'));
		if ($count > 0) {
			$this->data['wilayah'] = $this->M_Admin->get_tableid_edit('tbl_wilayah', 'id_wil', $this->uri->segment('3'));
		} else {
			echo '<script>alert("WILAYAH TIDAK DITEMUKAN");window.location="' . base_url('wilayah') . '"</script>';
		}
		$this->data['unit_admin'] = $this->M_Admin->get_table_maps('tbl_client');

		$this->data['title_web'] = 'Detail Wilayah | ACS Monit';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('wilayah/detail', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function upd()
	{
		$nama = htmlentities($this->input->post('nama_wilayah', TRUE));
		$geojson = $this->input->post('unit_geojson', TRUE);
		$latitude = htmlentities($this->input->post('latitude', TRUE));
		$longitude = htmlentities($this->input->post('longitude', TRUE));
		$warna = htmlentities($this->input->post('warna', TRUE));
		$id = htmlentities($this->input->post('id_wil', TRUE));

		// setting konfigurasi upload
		$post = $this->input->post();
		$nmfile = $nama . '_' . time();
		$config['upload_path'] = './assets_style/file/';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['file_name'] = $nmfile;
		// load library upload
		$this->load->library('upload', $config);

		if (!empty($_FILES['foto']['name'])) {
			$this->upload->initialize($config);
			if ($this->upload->do_upload('foto')) {
				$result1 = $this->upload->data();
				$result = array('foto' => $result1);
				$data1 = array('upload_data' => $this->upload->data());
				$convfoto = base64_encode(file_get_contents($data1['upload_data']['full_path']));
			} else {
				return false;
			}

			$foto = './assets_style/file/' . htmlentities($post['foto_old']);
			if (file_exists($foto)) {
				unlink($foto);
			}
			$data = array(
				'nama_wilayah' => $nama,
				'unit_geojson' => $geojson,
				'latitude' => $latitude,
				'longitude' => $longitude,
				'warna' => $warna,
				'foto' => $convfoto
			);
		} else {
			$data = array(
				'nama_wilayah' => $nama,
				'unit_geojson' => $geojson,
				'latitude' => $latitude,
				'longitude' => $longitude,
				'warna' => $warna
			);
		}
		$this->M_Admin->update_table('tbl_wilayah', 'id_wil', $id, $data);
		$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Berhasil Update Wilayah : ' . $nama . ' !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
		redirect(base_url('wilayah'));
	}

	public function del()
	{
		if ($this->uri->segment('3') == '') {
			echo '<script>alert("halaman tidak ditemukan");window.location="' . base_url('wilayah') . '";</script>';
		}

		$unit = $this->M_Admin->get_tableid_edit('tbl_wilayah', 'id_wil', $this->uri->segment('3'));
		unlink('./assets_style/file/' . $unit->foto);
		$this->M_Admin->delete_table('tbl_wilayah', 'id_wil', $this->uri->segment('3'));
		$this->session->set_flashdata('pesan', '<div class="alert alert-warning" role="alert">Wilayah Berhasil di Hapus!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
		redirect(base_url('wilayah'));
	}
}
