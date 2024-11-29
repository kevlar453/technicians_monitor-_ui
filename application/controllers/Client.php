<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Client extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		//validasi jika user belum login
		$this->data['CI'] = &get_instance();
		$this->load->helper(array('form', 'url', 'file'));
		$this->load->model('M_Admin');
		$this->load->model('gudangcrypt', '', true);
		if ($this->session->userdata('masuk_sistem_rekam') != TRUE) {
			$url = base_url('login');
			redirect($url);
		}
	}

	public function index()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$this->db->order_by('id_unit', 'desc');
		$this->data['unit'] = $this->M_Admin->get_table('tbl_client');
		$this->db->order_by('id_unit', 'desc');
		$this->data['unit_user'] = $this->M_Admin->get_table_unit_user();

		$this->data['title_web'] = 'Data Klien | ACS Monit';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('client/client_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function tambah()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['wilayah'] = $this->M_Admin->get_table('tbl_wilayah');

		$this->data['title_web'] = 'Tambah Klien | ACS Monit';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('client/tambah_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function sim_unit(){
		$c_client = $this->input->post('sn_kdclient');
		$c_merk = $this->input->post('sn_merk');
		$c_serial = $this->input->post('sn_serial');
		$c_model = $this->input->post('sn_model');
		$c_numlabel = $this->input->post('sn_numlabel');
		$c_periode = $this->input->post('sn_periode');
		$c_install = $this->input->post('sn_install');
		do {
				$input1 = $c_client;
				$input2 = $this->gudangcrypt->rdnum(3);
				$dr = $input1.$input2;
				$kset_com=$this->session->userdata('com_id');
				$dd = $this->db->query("SELECT * FROM tbl_unit WHERE unit_kode = '$dr'  AND set_com='$kset_com'");

		} while ($dd->num_rows() > 0);
			$c_kode = $dr;
			$data = array(
				'unit_kode'=>$c_kode,
				'unit_client'=>$c_client,
				'unit_merk'=>$c_merk,
				'unit_serial'=>$c_serial,
				'unit_model'=>$c_model,
				'unit_numlabel'=>$c_numlabel,
				'unit_periode'=>$c_periode,
				'unit_install'=>date('Y-m-d',strtotime($c_install)),
				'set_com'=>$kset_com
			);
			$this->db->query("SET FOREIGN_KEY_CHECKS = 0");
			$this->db->insert('tbl_unit', $data);
			$this->db->query("SET FOREIGN_KEY_CHECKS = 1");
			$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert"> Tambah Unit berhasil !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
	}

	public function add()
	{
		$nama = htmlentities($this->input->post('nama_client', TRUE));
		$nama_pic = htmlentities($this->input->post('nama_pic', TRUE));
		$alamat = htmlentities($this->input->post('alamat', TRUE));
		$asal_wilayah = htmlentities($this->input->post('asal_wilayah', TRUE));
		$serial = htmlentities($this->input->post('serial', TRUE));
		$model = htmlentities($this->input->post('model', TRUE));
		$numlabel = htmlentities($this->input->post('numlabel', TRUE));
		$periode = htmlentities($this->input->post('periode', TRUE));
		$geojson = $this->input->post('unit_geojson', TRUE);
		$latitude = htmlentities($this->input->post('latitude', TRUE));
		$longitude = htmlentities($this->input->post('longitude', TRUE));
		$warna = htmlentities($this->input->post('warna', TRUE));

		$dd = $this->db->query("SELECT * FROM tbl_client WHERE nama_client = '$nama'");
		if ($dd->num_rows() > 0) {
			$this->session->set_flashdata('pesan', '<div class="alert alert-warning" role="alert"> Gagal Tambah Unit : ' . $nama . ' !, Nama Klien Sudah Ada<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
			redirect(base_url('client/tambah'));
		} else {
			// setting konfigurasi upload
			$nmfile = $nama . '_' . time();
			$config['upload_path'] = './assets_style/file/';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['file_name'] = $nmfile;
			// load library upload
			$this->load->library('upload', $config);

			// uploud file pertama
			if ($this->upload->do_upload('gambar')) {
				$result1 = $this->upload->data();
				$result = array('gambar' => $result1);
				$data1 = array('upload_data' => $this->upload->data());
				$convfoto = base64_encode(file_get_contents($data1['upload_data']['full_path']));
			} else {
				return false;
			}

			$data = array(
				'kode_client' => 'CL'.time().str_pad((intval($this->M_Admin->CountTable('tbl_client'))+1), 4, '0', STR_PAD_LEFT),
				'nama_client' => $nama,
				'nama_pic' => $nama_pic,
				'alamat' => $alamat,
				'asal_wilayah' => $asal_wilayah,
				'serial' => $serial,
				'model' => $serial,
				'numlabel' => $numlabel,
				'periode' => $periode,
				'unit_geojson' => $geojson,
				'latitude' => $latitude,
				'longitude' => $longitude,
				'warna' => $warna,
				'foto' => $convfoto,
			);
			$this->db->query("SET FOREIGN_KEY_CHECKS = 0");
			$this->db->insert('tbl_client', $data);
			$this->db->query("SET FOREIGN_KEY_CHECKS = 1");
			unlink($data1['upload_data']['full_path']);
			$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert"> Tambah Unit berhasil !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
			redirect(base_url('unit'));
		}
	}

	public function edit()
	{
		if ($this->uri->segment('3') == '') {
			echo '<script>alert("halaman tidak ditemukan");window.location="' . base_url('unit') . '";</script>';
		}
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_client', 'id_unit', $this->uri->segment('3'));
		if ($count > 0) {
			$this->data['unit'] = $this->M_Admin->get_tableid_edit('tbl_client', 'id_unit', $this->uri->segment('3'));
		} else {
			echo '<script>alert("UNIT TIDAK DITEMUKAN");window.location="' . base_url('unit') . '"</script>';
		}
		$this->data['wilayah'] =  $this->db->query("SELECT * FROM tbl_wilayah ORDER BY id_wil ASC")->result_array();
		$this->data['title_web'] = 'Edit Klien | ACS Monit';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('client/edit_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function detail()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_client', 'id_unit', $this->uri->segment('3'));
		if ($count > 0) {
			$this->data['client'] = $this->M_Admin->get_tableid_edit('tbl_client', 'id_unit', $this->uri->segment('3'));
		} else {
			echo '<script>alert("Klien TIDAK DITEMUKAN");window.location="' . base_url('unit') . '"</script>';
		}
		$cunit = json_decode(json_encode($this->data['client']));
		//		$this->data['keter_unit'] = $cunit->kode_client;
		$this->data['dunit'] = $this->M_Admin->get_tableid_detedit('tbl_unit', 'unit_client', $cunit->kode_client);
		$this->data['rep_main'] = $this->M_Admin->get_tableid_detedit('tbl_report', 'rep_kdclient', $cunit->kode_client);
		$this->data['rep01'] = $this->M_Admin->get_tableid_detedit_rep('rep01', 'rep1idnota', $cunit->kode_client);
		$this->data['rep02'] = $this->M_Admin->get_tableid_detedit_rep('rep02', 'rep2idnota', $cunit->kode_client);
		$this->data['rep03'] = $this->M_Admin->get_tableid_detedit_rep('rep03', 'rep3idnota', $cunit->kode_client);
		$this->data['rep04'] = $this->M_Admin->get_tableid_detedit_rep('rep04', 'rep4idnota', $cunit->kode_client);
		$this->data['rep05'] = $this->M_Admin->get_tableid_detedit_rep('rep05', 'rep5idnota', $cunit->kode_client);
		$this->data['rep06'] = $this->M_Admin->get_tableid_detedit_rep('rep06', 'rep6idnota', $cunit->kode_client);
		$this->data['rep07'] = $this->M_Admin->get_tableid_detedit_rep('rep07', 'rep7idnota', $cunit->kode_client);
		$this->data['rep08'] = $this->M_Admin->get_tableid_detedit_rep('rep08', 'rep8idnota', $cunit->kode_client);
		$this->data['rep09'] = $this->M_Admin->get_tableid_detedit_rep('rep09', 'rep9idnota', $cunit->kode_client);

		$this->data['title_web'] = 'Detail Klien | ACS Monit';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('client/detail', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function upd()
	{
		$nama = htmlentities($this->input->post('nama_client', TRUE));
		$nama_pic = htmlentities($this->input->post('nama_pic', TRUE));
		$alamat = htmlentities($this->input->post('alamat', TRUE));
		$asal_wilayah = htmlentities($this->input->post('asal_wilayah', TRUE));
		$serial = htmlentities($this->input->post('serial', TRUE));
		$model = htmlentities($this->input->post('model', TRUE));
		$numlabel = htmlentities($this->input->post('numlabel', TRUE));
		$periode = htmlentities($this->input->post('periode', TRUE));
		$geojson = $this->input->post('unit_geojson', TRUE);
		$latitude = htmlentities($this->input->post('latitude', TRUE));
		$longitude = htmlentities($this->input->post('longitude', TRUE));
		$warna = htmlentities($this->input->post('warna', TRUE));
		$id = htmlentities($this->input->post('id_unit', TRUE));

		// setting konfigurasi upload
		$post = $this->input->post();
		$nmfile = $nama . '_' . time();
		$config['upload_path'] = './assets_style/file/';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['max_size'] = 5000;
		$config['file_name'] = $nmfile;
		// load library upload
		$this->load->library('upload', $config);

		if (!empty($_FILES['gambar']['name'])) {
			$this->upload->initialize($config);
			if ($this->upload->do_upload('gambar')) {
				$this->upload->data();
				$file1 = array('upload_data' => $this->upload->data());
			} else {
				return false;
			}

			$foto = './assets_style/file/' . htmlentities($post['foto_old']);
			if (file_exists($foto)) {
				unlink($foto);
			}

			$convfoto = base64_encode(file_get_contents($file1['upload_data']['full_path']));
			$data = array(
				'nama_client' => $nama,
				'nama_pic' => $nama_pic,
				'alamat' => $alamat,
				'asal_wilayah' => $asal_wilayah,
				'serial' => $serial,
				'model' => $serial,
				'numlabel' => $numlabel,
				'periode' => $periode,
				'unit_geojson' => $geojson,
				'latitude' => $latitude,
				'longitude' => $longitude,
				'warna' => $warna,
				'foto' => $convfoto
			);
			unlink($file1['upload_data']['full_path']);
		} else {
			$data = array(
				'nama_client' => $nama,
				'nama_pic' => $nama_pic,
				'alamat' => $alamat,
				'asal_wilayah' => $asal_wilayah,
				'serial' => $serial,
				'model' => $serial,
				'numlabel' => $numlabel,
				'periode' => $periode,
				'unit_geojson' => $geojson,
				'latitude' => $latitude,
				'longitude' => $longitude,
				'warna' => $warna
			);
		}
		$this->M_Admin->update_table('tbl_client', 'id_unit', $id, $data);
		$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Berhasil Update Unit : ' . $nama . ' !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
		redirect(base_url('client'));
	}

	public function del()
	{
		if ($this->uri->segment('3') == '') {
			echo '<script>alert("halaman tidak ditemukan");window.location="' . base_url('unit') . '";</script>';
		}

		$unit = $this->M_Admin->get_tableid_edit('tbl_client', 'id_unit', $this->uri->segment('3'));
//		unlink('./assets_style/file/' . $unit->foto);
		$this->M_Admin->delete_table('tbl_client', 'id_unit', $this->uri->segment('3'));
		$this->session->set_flashdata('pesan', '<div class="alert alert-warning" role="alert">Klien Berhasil di Hapus!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
		redirect(base_url('unit'));
	}
}
