<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		//validasi jika user belum login
		$this->data['CI'] = &get_instance();
		$this->load->helper(array('form', 'url'));
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
		$this->db->order_by('com_nama', 'asc');
		$this->data['usaha'] = $this->M_Admin->get_table('tbl_perusahaan');

		$this->data['title_web'] = 'Data Master | ACS Monit';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('master/company_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}


	public function tambah()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['usaha'] = $this->M_Admin->get_table('tbl_perusahaan');

		$this->data['title_web'] = 'Tambah Company | ACS Monit';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('master/tambah_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function add()
	{
		$nama = htmlentities($this->input->post('nama', TRUE));
		$kode = htmlentities($this->input->post('kode', TRUE));

		$usr_nama = htmlentities($this->input->post('usr_nama', TRUE));
		$usr_user = htmlentities($this->input->post('usr_user', TRUE));
		$usr_clidentitas = htmlentities($this->input->post('usr_clidentitas', TRUE));
		$usr_pass = md5(htmlentities($this->input->post('usr_pass', TRUE)));
		$usr_level = htmlentities($this->input->post('usr_level', TRUE));
		$usr_jenkel = htmlentities($this->input->post('usr_jenkel', TRUE));
		$usr_telepon = htmlentities($this->input->post('usr_telepon', TRUE));
		$usr_alamat = htmlentities($this->input->post('usr_alamat', TRUE));
		$usr_email = $_POST['usr_email'];


		$dd = $this->db->query("SELECT * FROM tbl_user WHERE user = '$usr_user' OR email = '$usr_email'");
		$de = $this->db->query("SELECT * FROM tbl_perusahaan WHERE com_kode = '$kode' OR com_nama = '$nama'");
		if ($dd->num_rows() > 0 && $de->num_rows() > 0) {
			$this->session->set_flashdata('pesan', '<div class="alert alert-warning" role="alert">Gagal Tambah Company : ' . $nama . ' !, Company / Pengelola Sudah Terpakai<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
			redirect(base_url('master'));
		} else {
			// Insert Company
			$nmfile = "company_" . time();
			$config['upload_path'] = './assets_style/image/';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['file_name'] = $nmfile;
			$this->load->library('upload', $config);
			$this->upload->do_upload('gambar');
			$result1 = $this->upload->data();
			$result = array('gambar' => $result1);
			$data1 = array('upload_data' => $this->upload->data());
			$convfoto = base64_encode(file_get_contents($data1['upload_data']['full_path']));
			$data = array(
				'com_nama' => $nama,
				'com_kode' => $kode,
				'com_logo' => $convfoto
			);
			$this->db->query("SET FOREIGN_KEY_CHECKS = 0");
			$this->db->insert('tbl_perusahaan', $data);
			$this->db->query("SET FOREIGN_KEY_CHECKS = 1");
			unlink($data1['upload_data']['full_path']);

			// Insert User
			$nmfile = "user_" . time();
			$config['upload_path'] = './assets_style/image/';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['file_name'] = $nmfile;
			$this->load->library('upload', $config);
			$this->upload->do_upload('foto');
			$result1 = $this->upload->data();
			$result = array('foto' => $result1);
			$data1 = array('upload_data' => $this->upload->data());
			$convfoto = base64_encode(file_get_contents($data1['upload_data']['full_path']));
			$data = array(
				'nama' => $usr_nama,
				'user' => $usr_user,
				'clidentitas' => $usr_clidentitas,
				'pass' => $usr_pass,
				'level' => $usr_level,
				'tempat_lahir' => $_POST['usr_lahir'],
				'tgl_lahir' => $_POST['usr_tgl_lahir'],
				'level' => $usr_level,
				'email' => $_POST['usr_email'],
				'telepon' => $usr_telepon,
				'foto' => $convfoto,
				'jenkel' => $usr_jenkel,
				'alamat' => $usr_alamat,
				'tgl_bergabung' => date('Y-m-d'),
				'set_com' => $kode
			);
			$this->db->query("SET FOREIGN_KEY_CHECKS = 0");
			$this->db->insert('tbl_user', $data);
			$this->db->query("SET FOREIGN_KEY_CHECKS = 1");

			$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Tambah company berhasil !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
			redirect(base_url('master'));
		}
	}

	public function edit()
	{
		if ($this->session->userdata('level') == 'administrator') {
			if ($this->uri->segment('3') == '') {
				echo '<script>alert("halaman tidak ditemukan");window.location="' . base_url('user') . '";</script>';
			}
			$this->data['idbo'] = $this->session->userdata('ses_id');
			$count = $this->M_Admin->CountTableId('tbl_user', 'id_user', $this->uri->segment('3'));
			if ($count > 0) {
				$this->data['user'] = $this->M_Admin->get_tableid_edit('tbl_user', 'id_user', $this->uri->segment('3'));
				$this->data['wilayah'] =  $this->db->query("SELECT * FROM tbl_wilayah ORDER BY id_wil ASC")->result_array();
				$this->data['title_web'] = 'Edit User ';
				$this->load->view('header_view', $this->data);
				$this->load->view('sidebar_view', $this->data);
				$this->load->view('user/edit_view', $this->data);
				$this->load->view('footer_view', $this->data);
			} else {
				echo '<script>alert("USER TIDAK DITEMUKAN");window.location="' . base_url('user') . '"</script>';
			}
		} elseif ($this->session->userdata('level') == 'technician' or 'operator') {
			$this->data['idbo'] = $this->session->userdata('ses_id');
			$count = $this->M_Admin->CountTableId('tbl_user', 'id_user', $this->uri->segment('3'));
			if ($count > 0) {
				$this->data['user'] = $this->M_Admin->get_tableid_edit('tbl_user', 'id_user', $this->session->userdata('ses_id'));
				$this->data['wilayah'] =  $this->db->query("SELECT * FROM tbl_wilayah ORDER BY id_wil ASC")->result_array();
				$this->data['title_web'] = 'Edit User | ACS Monit';
				$this->load->view('header_view', $this->data);
				$this->load->view('sidebar_view', $this->data);
				$this->load->view('user/edit_view', $this->data);
				$this->load->view('footer_view', $this->data);
			} else {
				echo '<script>alert("USER TIDAK DITEMUKAN");window.location="' . base_url('dashboard') . '"</script>';
			}
		}
	}

	public function upd()
	{
		$nama = htmlentities($this->input->post('nama', TRUE));
		$user = htmlentities($this->input->post('user', TRUE));
		$pass = htmlentities($this->input->post('pass'));
		$level = htmlentities($this->input->post('level', TRUE));
		$jenkel = htmlentities($this->input->post('jenkel', TRUE));
		$telepon = htmlentities($this->input->post('telepon', TRUE));
		$alamat = htmlentities($this->input->post('alamat', TRUE));
		$id_user = htmlentities($this->input->post('id_user', TRUE));

		if ($this->input->post('level') == 'technician') {
			$asal_wilayah = htmlentities($this->input->post('asal_wilayah', TRUE));
		}

		// setting konfigurasi upload
		$nmfile = "user_" . time();
		$config['upload_path'] = './assets_style/image/';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['file_name'] = $nmfile;
		// load library upload
		$this->load->library('upload', $config);
		// upload gambar 1


		if (!$this->upload->do_upload('gambar')) {
			if ($this->input->post('pass') !== '') {
				$data = array(
					'nama' => $nama,
					'user' => $user,
					'pass' => md5($pass),
					'tempat_lahir' => $_POST['lahir'],
					'tgl_lahir' => $_POST['tgl_lahir'],
					'level' => $level,
					'asal_wilayah' => $asal_wilayah,
					'email' => $_POST['email'],
					'telepon' => $telepon,
					'jenkel' => $jenkel,
					'alamat' => $alamat,
				);
				$this->M_Admin->update_table('tbl_user', 'id_user', $id_user, $data);
				if ($this->session->userdata('level') == 'administrator') {
					$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Berhasil Update User : ' . $nama . ' !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
					redirect(base_url('user'));
				} elseif ($this->session->userdata('level') == 'technician' or 'operator') {
					$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Berhasil Update User : ' . $nama . ' !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
					redirect(base_url('user/edit/' . $id_user));
				}
			} else {
				$data = array(
					'nama' => $nama,
					'user' => $user,
					'tempat_lahir' => $_POST['lahir'],
					'tgl_lahir' => $_POST['tgl_lahir'],
					'level' => $level,
					'asal_wilayah' => $asal_wilayah,
					'email' => $_POST['email'],
					'telepon' => $telepon,
					'jenkel' => $jenkel,
					'alamat' => $alamat,
				);
				$this->M_Admin->update_table('tbl_user', 'id_user', $id_user, $data);

				if ($this->session->userdata('level') == 'administrator') {
					$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Berhasil Update User : ' . $nama . ' !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
					redirect(base_url('user'));
				} elseif ($this->session->userdata('level') == 'technician' or 'operator') {
					$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Berhasil Update User : ' . $nama . ' !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
					redirect(base_url('user/edit/' . $id_user));
				}
			}
		} else {
			$result1 = $this->upload->data();
			$result = array('gambar' => $result1);
			$data1 = array('upload_data' => $this->upload->data());
			$convfoto = base64_encode(file_get_contents($data1['upload_data']['full_path']));
//			$this->M_Admin->debug_to_console(var_dump($convfoto));
//			unlink('/assets_style/image/' . $this->input->post('foto'));
			if ($this->input->post('pass') !== '') {
				$data = array(
					'nama' => $nama,
					'user' => $user,
					'tempat_lahir' => $_POST['lahir'],
					'tgl_lahir' => $_POST['tgl_lahir'],
					'pass' => md5($pass),
					'level' => $level,
					'asal_wilayah' => $asal_wilayah,
					'email' => $_POST['email'],
					'telepon' => $telepon,
					'foto' => $convfoto,
					'jenkel' => $jenkel,
					'alamat' => $alamat
				);
				$this->M_Admin->update_table('tbl_user', 'id_user', $id_user, $data);
				unlink($data1['upload_data']['full_path']);

				if ($this->session->userdata('level') == 'administrator') {
					$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Berhasil Update User : ' . $nama . ' !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
					redirect(base_url('user'));
				} elseif ($this->session->userdata('level') == 'technician' or 'operator') {
					$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Berhasil Update User : ' . $nama . ' !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
					redirect(base_url('user/edit/' . $id_user));
				}
			} else {
				$data = array(
					'nama' => $nama,
					'user' => $user,
					'tempat_lahir' => $_POST['lahir'],
					'tgl_lahir' => $_POST['tgl_lahir'],
					'level' => $level,
					'asal_wilayah' => $asal_wilayah,
					'email' => $_POST['email'],
					'telepon' => $telepon,
					'foto' => $convfoto,
					'jenkel' => $jenkel,
					'alamat' => $alamat
				);
				$this->M_Admin->update_table('tbl_user', 'id_user', $id_user, $data);
				unlink($data1['upload_data']['full_path']);

				if ($this->session->userdata('level') == 'administrator') {
					$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Berhasil Update User : ' . $nama . ' !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
					redirect(base_url('user'));
				} elseif ($this->session->userdata('level') == 'technician' or 'operator') {
					$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Berhasil Update User : ' . $nama . ' !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
					redirect(base_url('user/edit/' . $id_user));
				}
			}
		}
	}
	public function del()
	{
		if ($this->uri->segment('3') == '') {
			echo '<script>alert("halaman tidak ditemukan");window.location="' . base_url('master') . '";</script>';
		}

		$user = $this->M_Admin->get_tableid_edit('tbl_perusahaan', 'com_kode', $this->uri->segment('3'));
//		unlink('./assets_style/image/' . $user->foto);
		$this->M_Admin->delete_table('tbl_perusahaan', 'com_kode', $this->uri->segment('3'));
		$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Berhasil Hapus Company !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
		redirect(base_url('master'));
	}
}
