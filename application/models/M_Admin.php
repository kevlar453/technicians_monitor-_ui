<?php if (!defined('BASEPATH')) exit('No direct script acess allowed');

class M_Admin extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }

  function get_table($table_name)
  {
    if($this->session->userdata('level') != 'master'){
      $this->db->where('set_com',$this->session->userdata('com_id'));
    }
    $get_user = $this->db->get($table_name);
    return $get_user->result_array();
  }

  function get_table_maps($table_name)
  {
    $this->db->cache_on();
    if($this->session->userdata('level') != 'master'){
      $this->db->where('set_com',$this->session->userdata('com_id'));
    }
    $get_user = $this->db->get($table_name);
		$this->db->cache_off();
    return $get_user->result();
  }

  // Start tampil data unit berdasarkan asal unit user
  function get_table_wilayah_user()
  {
    if($this->session->userdata('level') != 'master'){
      $this->db->where('set_com',$this->session->userdata('com_id'));
    }
    $this->db->where('tbl_wilayah.nama_wilayah', $this->session->userdata('asal_wilayah'));
    return $this->db->get('tbl_wilayah')->result();
  }

  function get_table_absen_user()
  {
    $this->db->select('*');
    $this->db->from('tbl_absensi');
    $this->db->join('tbl_user','tbl_absensi.absen_idn=tbl_user.clidentitas');
    $this->db->where('absen_tgl',date('Y-m-d'));
    if($this->session->userdata('level') != 'master'){
      $this->db->where('tbl_absensi.set_com',$this->session->userdata('com_id'));
    }
    return $this->db->get()->result();
  }



  // Start tampil data unit berdasarkan asal unit user
  function get_table_unit_user()
  {
    $this->db->cache_on();
    if($this->session->userdata('level') != 'master'){
      $this->db->where('set_com',$this->session->userdata('com_id'));
    }
    $this->db->where('tbl_client.nama_client', $this->session->userdata('asal_unit'));
    return $this->db->get('tbl_client')->result();
		$this->db->cache_off();
  }

  // Start tampil geografis berdasarkan asal unit user
  function get_table_panen_user()
  {
    $this->db->where('set_com',$this->session->userdata('com_id'));
    $this->db->where('tbl_panen.lokasi', $this->session->userdata('asal_unit'));
    return $this->db->get('tbl_panen')->result_array();
  }
  function get_table_pemeliharaan_user()
  {
    $this->db->where('set_com',$this->session->userdata('com_id'));
    $this->db->where('tbl_pemeliharaan.lokasi', $this->session->userdata('asal_unit'));
    return $this->db->get('tbl_pemeliharaan')->result_array();
  }
  function get_table_jalan_user()
  {
    $this->db->where('set_com',$this->session->userdata('com_id'));
    $this->db->where('tbl_jalan.lokasi', $this->session->userdata('asal_unit'));
    return $this->db->get('tbl_jalan')->result_array();
  }
  function get_table_pencurian_user()
  {
    $this->db->where('set_com',$this->session->userdata('com_id'));
    $this->db->where('tbl_pencurian.lokasi', $this->session->userdata('asal_unit'));
    return $this->db->get('tbl_pencurian')->result_array();
  }
  function get_table_bencana_user()
  {
    $this->db->where('set_com',$this->session->userdata('com_id'));
    $this->db->where('tbl_bencana_alam.lokasi', $this->session->userdata('asal_unit'));
    return $this->db->get('tbl_bencana_alam')->result_array();
  }
  // End tampil data unit geografis berdasarkan user unit login

  function get_tableid($table_name, $where, $id)
  {
    $this->db->where('set_com',$this->session->userdata('com_id'));
    $this->db->where($where, $id);
    $edit = $this->db->get($table_name);
    return $edit->result_array();
  }

  function get_tableid_edit($table_name, $where, $id)
  {
    if($this->session->userdata('level') != 'master'){
      $this->db->where('set_com',$this->session->userdata('com_id'));
    }
    $this->db->where($where, $id);
    $edit = $this->db->get($table_name);
    return $edit->row();
  }

  function get_tableid_detedit($table_name, $where, $id)
  {
    $this->db->where('set_com',$this->session->userdata('com_id'));
    $this->db->where($where, $id);
    $edit = $this->db->get($table_name);
    return $edit->result();
  }

  function get_tableid_detedit_rep($table_name, $where, $id)
  {
    $this->db->where('set_com',$this->session->userdata('com_id'));
    $this->db->where('left('.$where.',16)', $id);
    $edit = $this->db->get($table_name);
    return $edit->result();
  }

  function insertTable($table_name, $data)
  {
    $tambah = $this->db->insert($table_name, $data);
    return $tambah;
  }

  function update_table($table_name, $where, $id, $data)
  {
    if(is_array($id)){
      $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
      $this->db->where($id);
    } else {
      $this->db->where($where, $id);
    }
    if($this->session->userdata('level') != 'master'){
      $this->db->where('set_com',$this->session->userdata('com_id'));
    }
    $update = $this->db->update($table_name, $data);
    $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
    return $update;
  }

  function delete_table($table_name, $where, $id)
  {
    if($this->session->userdata('level') != 'master'){
      $this->db->where('set_com',$this->session->userdata('com_id'));
    }
    $this->db->where($where, $id);
    $hapus = $this->db->delete($table_name);
    return $hapus;
  }

  function edit_table($table_name, $where, $id)
  {
    if($this->session->userdata('level') != 'master'){
      $this->db->where('set_com',$this->session->userdata('com_id'));
    }
    $this->db->where($where, $id);
    $edit = $this->db->get($table_name);
    return $edit->row();
  }

  function CountTable($table_name)
  {
    if($this->session->userdata('level') != 'master'){
      $this->db->where('set_com',$this->session->userdata('com_id'));
    }
    $Count = $this->db->get($table_name);
    return $Count->num_rows();
  }

  function CountTableId($table_name, $where, $id)
  {
    if($this->session->userdata('level') != 'master'){
      $this->db->where('set_com',$this->session->userdata('com_id'));
    }
    $this->db->where($where, $id);
    $Count = $this->db->get($table_name);
    return $Count->num_rows();
  }


    // Start pencarian unit geografis
    public function get_wilayah_keyword($keyword)
    {
      $this->db->select('*');
      $this->db->from('tbl_wilayah');
      $this->db->like('nama_wilayah', $keyword);
      if($this->session->userdata('level') != 'master'){
        $this->db->where('set_com',$this->session->userdata('com_id'));
      }
      return $this->db->get()->result();
    }


  // Start pencarian unit geografis
  public function get_unit_keyword($keyword)
  {
    $this->db->select('*');
    $this->db->from('tbl_client');
    $this->db->where('nama_client', $keyword);
    if($this->session->userdata('level') != 'master'){
      $this->db->where('set_com',$this->session->userdata('com_id'));
    }
    $this->db->limit(1);
    return $this->db->get()->result();
  }

  public function get_panen_keyword($keyword)
  {
    $this->db->select('*');
    $this->db->from('tbl_panen');
    $this->db->like('lokasi', $keyword);
    $this->db->where('set_com',$this->session->userdata('com_id'));
    $this->db->limit(3);
    $this->db->order_by('tanggal DESC');
    return $this->db->get()->result();
  }

  public function get_pemeliharaan_keyword($keyword)
  {
    $this->db->select('*');
    $this->db->from('tbl_pemeliharaan');
    $this->db->like('lokasi', $keyword);
    $this->db->where('set_com',$this->session->userdata('com_id'));
    $this->db->limit(3);
    $this->db->order_by('tanggal DESC');
    return $this->db->get()->result();
  }

  public function get_jalan_keyword($keyword)
  {
    $this->db->select('*');
    $this->db->from('tbl_jalan');
    $this->db->like('lokasi', $keyword);
    $this->db->where('set_com',$this->session->userdata('com_id'));
    $this->db->limit(3);
    $this->db->order_by('id_jalan DESC');
    return $this->db->get()->result();
  }

  public function get_pencurian_keyword($keyword)
  {
    $this->db->select('*');
    $this->db->from('tbl_pencurian');
    $this->db->like('lokasi', $keyword);
    $this->db->where('set_com',$this->session->userdata('com_id'));
    $this->db->limit(3);
    $this->db->order_by('tanggal DESC');
    return $this->db->get()->result();
  }

  public function get_bencana_keyword($keyword)
  {
    $this->db->select('*');
    $this->db->from('tbl_bencana_alam');
    $this->db->like('lokasi', $keyword);
    $this->db->where('set_com',$this->session->userdata('com_id'));
    $this->db->limit(3);
    $this->db->order_by('tanggal DESC');
    return $this->db->get()->result();
  }
  // End pencarian unit geografis

  // Start laporan untuk bagian operator
  public function getPanenSortTgl($tgl_awal_panen, $tgl_akhir_panen)
  {
    $query = "SELECT * FROM tbl_panen
			WHERE tanggal BETWEEN '$tgl_awal_panen' AND '$tgl_akhir_panen'
			ORDER BY tanggal DESC
		";
    return $this->db->query($query);
  }
  public function getPemeliharaanSortTgl($tgl_awal_pemeliharaan, $tgl_akhir_pemeliharaan)
  {
    $query = "SELECT * FROM tbl_pemeliharaan
			WHERE tanggal BETWEEN '$tgl_awal_pemeliharaan' AND '$tgl_akhir_pemeliharaan'
			ORDER BY tanggal DESC
		";
    return $this->db->query($query);
  }
  public function getPencurianSortTgl($tgl_awal_pencurian, $tgl_akhir_pencurian)
  {
    $query = "SELECT * FROM tbl_pencurian
			WHERE tanggal BETWEEN '$tgl_awal_pencurian' AND '$tgl_akhir_pencurian'
			ORDER BY tanggal DESC
		";
    return $this->db->query($query);
  }
  public function getBencanaSortTgl($tgl_awal_bencana, $tgl_akhir_bencana)
  {
    $query = "SELECT * FROM tbl_bencana_alam
			WHERE tanggal BETWEEN '$tgl_awal_bencana' AND '$tgl_akhir_bencana'
			ORDER BY tanggal DESC
		";
    return $this->db->query($query);
  }
  // End laporan untuk bagian operator

  // Start laporan untuk bagian unit
  function getPanenUnitSortTgl($tgl_awal_panen, $tgl_akhir_panen)
  {
    $lokasi = $this->session->userdata('asal_unit');
    $query = "SELECT * FROM tbl_panen
			WHERE lokasi = '$lokasi' and tanggal BETWEEN '$tgl_awal_panen' AND '$tgl_akhir_panen'
			ORDER BY tanggal DESC
		";
    return $this->db->query($query);
  }
  function getPemeliharaanUnitSortTgl($tgl_awal_pemeliharaan, $tgl_akhir_pemeliharaan)
  {
    $lokasi = $this->session->userdata('asal_unit');
    $query = "SELECT * FROM tbl_pemeliharaan
			WHERE lokasi = '$lokasi' and tanggal BETWEEN '$tgl_awal_pemeliharaan' AND '$tgl_akhir_pemeliharaan'
			ORDER BY tanggal DESC
		";
    return $this->db->query($query);
  }
  function getPencurianUnitSortTgl($tgl_awal_pencurian, $tgl_akhir_pencurian)
  {
    $lokasi = $this->session->userdata('asal_unit');
    $query = "SELECT * FROM tbl_pencurian
			WHERE lokasi = '$lokasi' and tanggal BETWEEN '$tgl_awal_pencurian' AND '$tgl_akhir_pencurian'
			ORDER BY tanggal DESC
		";
    return $this->db->query($query);
  }
  function getBencanaUnitSortTgl($tgl_awal_bencana, $tgl_akhir_bencana)
  {
    $lokasi = $this->session->userdata('asal_unit');
    $query = "SELECT * FROM tbl_bencana_alam
			WHERE lokasi = '$lokasi' and tanggal BETWEEN '$tgl_awal_bencana' AND '$tgl_akhir_bencana'
			ORDER BY tanggal DESC
		";
    return $this->db->query($query);
  }

  public function base64_to_jpeg($base64_string = false, $cnama = false)
  {
      $dirname = "./sjsurabaya";
      $tujuan  = $dirname . "/" . date("d-m-Y");
      if (!file_exists($tujuan)) {
          mkdir($tujuan, 0777, true);
          chmod($dirname, 0777);
          chmod($tujuan, 0777);
      }

      $target = $dirname . "/" . date("d-m-Y")."/".$cnama;

      // open the output file for writing
      $ifp = fopen($target, 'wb');

      // split the string on commas
      // $data[ 0 ] == "data:image/png;base64"
      // $data[ 1 ] == <actual base64 string>
//        $data = explode( ',', $base64_string );

      // we could add validation here with ensuring count( $data ) > 1
      fwrite($ifp, base64_decode($base64_string));

      // clean up the file resource
      fclose($ifp);

      return $target;
  }

//  $goqr = base64_encode(file_get_contents($pgambar));

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
  // End laporan untuk bagian unit

  private function extractBase64($htmlContent) {
      // Regular expression to extract base64 content within <img src="data:image/png;base64,...">
      if (preg_match('/<img\s+src="data:image\/\w+;base64,([^"]+)"/', $htmlContent, $matches)) {
          return $matches[1]; // Return base64 content
      }
      return null; // Return null if no match is found
  }

  public function insertReport($rdata = false,$notakode = false) {
    $data = json_decode($rdata, true);
      // Define mapping using direct index positions
      $mapping = [
          'rep01' => [
            'rep1idnota' => array_slice($data,91,1),                       // Element 91
              'rep1v01' => array_slice($data, 0, 3),    // Elements 0 to 2
              'rep1v02' => array_slice($data, 3, 3),    // Elements 3 to 5
              'rep1v03' => array_slice($data, 6, 1),                  // Element 6
              'rep1v04' => array_slice($data, 7, 1),                  // Element 7
          ],
          'rep02' => [
            'rep2idnota' => array_slice($data,91,1),                       // Element 91
              'rep2v01' => array_slice($data, 8, 3),    // Elements 8 to 10
              'rep2v02' => array_slice($data, 11, 3),   // Elements 11 to 13
              'rep2v03' => array_slice($data, 14, 3),   // Elements 14 to 16
              'rep2v04' => array_slice($data, 17, 3),   // Elements 17 to 19
              'rep2v05' => array_slice($data, 20, 3),   // Elements 20 to 22
              'rep2v06' => array_slice($data, 23, 9),   // Elements 23 to 31
          ],
          'rep03' => [
            'rep3idnota' => array_slice($data,91,1),                       // Element 91
              'rep3v01' => array_slice($data, 32, 3),   // Elements 32 to 34
              'rep3v02' => array_slice($data, 35, 3),   // Elements 35 to 37
              'rep3v03' => array_slice($data, 38, 3),   // Elements 38 to 40
              'rep3v04' => array_slice($data, 41, 3),   // Elements 41 to 43
              'rep3v05' => array_slice($data, 44, 3),   // Elements 44 to 46
              'rep3v06' => array_slice($data, 47, 9),   // Elements 47 to 55
          ],
          'rep04' => [
            'rep4idnota' => array_slice($data,91,1),                       // Element 91
              'rep4v01' => array_slice($data, 56, 3),   // Elements 56 to 58
          ],
          'rep05' => [
            'rep5idnota' => array_slice($data,91,1),                       // Element 91
              'rep5v01' => array_slice($data,59,1),                 // Element 59
              'rep5v02' => array_slice($data,60,1),                 // Element 60
              'rep5v03' => array_slice($data, 61, 3),   // Elements 61 to 63
          ],
          'rep06' => [
            'rep6idnota' => array_slice($data,91,1),                       // Element 91
              'rep6v01' => array_slice($data, 64, 3),   // Elements 64 to 66
              'rep6v02' => array_slice($data,67,1),                 // Element 67
              'rep6v03' => array_slice($data,68,1),                 // Element 68
              'rep6v04' => array_slice($data,69,1),                 // Element 69
          ],
          'rep07' => [
            'rep7idnota' => array_slice($data,91,1),                       // Element 91
              'rep7v01' => array_slice($data,70,1),                 // Element 70
              'rep7v02' => array_slice($data,71,1),                 // Element 71
              'rep7v03' => array_slice($data,72,1),                 // Element 72
              'rep7v04' => array_slice($data,73,1),                 // Element 73
              'rep7v05' => array_slice($data,74,1),                 // Element 74
          ],
          'rep08' => [
            'rep8idnota' => array_slice($data,91,1),                       // Element 91
              'rep8v01' => array_slice($data,75,1),                 // Element 75
              'rep8v02' => array_slice($data,76,1),                 // Element 76
              'rep8v03' => array_slice($data,77,1),                 // Element 77
          ],
          'rep09' => [
            'rep9idnota' => array_slice($data,91,1),                       // Element 91
              'rep9v01' => array_slice($data, 78, 2),   // Elements 78 to 79
              'rep9v02' => array_slice($data, 80, 2),   // Elements 80 to 81
          ],
          'tbl_report' => [
              'rep_logo1' => array_slice($data,82,1), // Element 82
              'rep_logo2' => array_slice($data,83,1), // Element 83
              'rep_tgl' => array_slice($data,84,1),                        // Element 84
              'rep_nmclient' => array_slice($data,85,1),                   // Element 85
              'rep_periode' => array_slice($data,86,1),                    // Element 86
              'rep_label' => array_slice($data,87,1),                      // Element 87
              'rep_model' => array_slice($data,88,1),                      // Element 88
              'rep_serial' => array_slice($data,89,1),                     // Element 89
              'rep_kdclient' => array_slice($data,90,1),                   // Element 90
              'rep_kode' => array_slice($data,91,1),                       // Element 91
              'rep_tglback' => array_slice($data,92,1),                    // Element 92
              'rep_desc' => array_slice($data,93,1),                       // Element 93
              'rep_nmpic' => array_slice($data,94,1),                      // Element 94
              'rep_ttclient' => array_slice($data,95,1),                   // Element 95
              'rep_nmtek' => array_slice($data,96,1),                      // Element 96
              'rep_tttek' => array_slice($data,97,1),                      // Element 97
              'rep_job' => array_slice($data, 98, 4),          // Elements 98 to 101
              'rep_status' => array_slice($data, 102, 3),      // Elements 102 to 104
          ],
      ];

      // Insert data for each table
      foreach ($mapping as $table => $fields) {
          $insert_data = [];
          foreach ($fields as $field => $values) {
              // Combine values if needed
              $insert_data[$field] = implode(",", array_filter($values));
          }

          // Insert data if the array is not empty
          if (!empty($insert_data)) {
              $this->db->insert($table, $insert_data);
//              if ($this->db->affected_rows() > 0) {
//                  echo "Data inserted successfully into $table\n";
//              } else {
//                  echo "Error inserting data into $table\n";
//              }
          }
      }
  }

}
