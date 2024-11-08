<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
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
		$this->db->order_by('id_user', 'desc');
		$this->data['user'] = $this->M_Admin->get_table('tbl_user');

		$this->data['title_web'] = 'Data User | ACS Monit';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('user/user_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function tambah()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['user'] = $this->M_Admin->get_table('tbl_user');

		do {
				$input1 = $this->session->userdata('com_id');
				$input2 = $this->gudangcrypt->rdchr(6);
				$dr = '';
				$length = strlen($input1);
				for ($i = 0; $i < $length; $i++) {
						$dr .= $input1[$i];
						$dr .= $input2[$i];
				}
				$kset_com=$this->session->userdata('com_id');
				$dd = $this->db->query("SELECT * FROM tbl_user WHERE clidentitas = '$dr'  AND set_com='$kset_com'");

		} while ($dd->num_rows() > 0);
			$this->data['rdnid'] = $dr;


		$this->data['wilayah'] =  $this->db->query("SELECT * FROM tbl_wilayah  WHERE set_com='".$this->session->userdata('com_id')."' ORDER BY id_wil ASC")->result_array();

		$this->data['title_web'] = 'Tambah User | ACS Monit';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('user/tambah_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function add()
	{
		$nama = htmlentities($this->input->post('nama', TRUE));
		$user = htmlentities($this->input->post('user', TRUE));
		$clidentitas = htmlentities($this->input->post('clidentitas', TRUE));
		$pass = md5(htmlentities($this->input->post('pass', TRUE)));
		$level = htmlentities($this->input->post('level', TRUE));
		$asal_wilayah = htmlentities($this->input->post('asal_wilayah', TRUE));
		$jenkel = htmlentities($this->input->post('jenkel', TRUE));
		$telepon = htmlentities($this->input->post('telepon', TRUE));
		$alamat = htmlentities($this->input->post('alamat', TRUE));
		$email = $_POST['email'];

		$kset_com=$this->session->userdata('com_id');

		$dd = $this->db->query("SELECT * FROM tbl_user WHERE user = '$user' OR email = '$email'  AND set_com='$kset_com'");
		if ($dd->num_rows() > 0) {
			$this->session->set_flashdata('pesan', '<div class="alert alert-warning" role="alert">Gagal Tambah User : ' . $nama . ' !, Username / Email Anda Sudah Terpakai<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
			redirect(base_url('user'));
		} else {
			// setting konfigurasi upload
			$nmfile = "user_" . time();
			$config['upload_path'] = './assets_style/image/';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['file_name'] = $nmfile;
			// load library upload
			$this->load->library('upload', $config);
			// upload gambar 1
			$this->upload->do_upload('gambar');
			$result1 = $this->upload->data();
			$result = array('gambar' => $result1);
			$data1 = array('upload_data' => $this->upload->data());
			$data = array(
				'nama' => $nama,
				'user' => $user,
				'clidentitas' => $clidentitas,
				'pass' => $pass,
				'level' => $level,
				'asal_wilayah' => $asal_wilayah,
				'tempat_lahir' => $_POST['lahir'],
				'tgl_lahir' => $_POST['tgl_lahir'],
				'level' => $level,
				'email' => $_POST['email'],
				'telepon' => $telepon,
				'foto' => $data1['upload_data']['file_name'],
				'jenkel' => $jenkel,
				'alamat' => $alamat,
				'tgl_bergabung' => date('Y-m-d'),
				'set_com' => $this->session->userdata('com_id')
			);
			$this->db->query("SET FOREIGN_KEY_CHECKS = 0");
			$this->db->insert('tbl_user', $data);
			$this->db->query("SET FOREIGN_KEY_CHECKS = 1");
			$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Tambah user berhasil !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
			redirect(base_url('user'));
		}
	}

	public function edit()
	{
		if ($this->session->userdata('level') == 'administrator' || $this->session->userdata('level') == 'master') {
			if ($this->uri->segment('3') == '') {
				echo '<script>alert("halaman tidak ditemukan");window.location="' . base_url('user') . '";</script>';
			}
				$this->data['idbo'] = $this->session->userdata('ses_id');
			$count = $this->M_Admin->CountTableId('tbl_user', 'id_user', $this->uri->segment('3'));
			if ($count > 0) {
				$this->data['user'] = $this->M_Admin->get_tableid_edit('tbl_user', 'id_user', $this->uri->segment('3'));
				$this->data['wilayah'] =  $this->db->query("SELECT * FROM tbl_wilayah  WHERE set_com='".$this->session->userdata('com_id')."' ORDER BY id_wil ASC")->result_array();
				$this->data['title_web'] = 'Edit User | ACS Monit';
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
				$this->data['wilayah'] =  $this->db->query("SELECT * FROM tbl_wilayah  WHERE set_com='".$this->session->userdata('com_id')."' ORDER BY id_wil ASC")->result_array();
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
				if ($this->session->userdata('level') == 'administrator' || $this->session->userdata('level') == 'master') {
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

				if ($this->session->userdata('level') == 'administrator' || $this->session->userdata('level') == 'master') {
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

				if ($this->session->userdata('level') == 'administrator' || $this->session->userdata('level') == 'master') {
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

				if ($this->session->userdata('level') == 'administrator' || $this->session->userdata('level') == 'master') {
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
			echo '<script>alert("halaman tidak ditemukan");window.location="' . base_url('user') . '";</script>';
		}

		$user = $this->M_Admin->get_tableid_edit('tbl_user', 'id_user', $this->uri->segment('3'));
//		unlink('./assets_style/image/' . $user->foto);
		$this->M_Admin->delete_table('tbl_user', 'id_user', $this->uri->segment('3'));
		$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Berhasil Hapus User !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
		redirect(base_url('user'));
	}
}
