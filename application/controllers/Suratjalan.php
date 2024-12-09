<?php
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') or exit('No direct script access allowed');
class Suratjalan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array(
            'cookie',
            'url',
            'file'
        ));
        $this->load->model('gudangcrypt', '', true);
        $this->load->model('M_Admin');
    }
    public function index()
    {
        $lsdaf = $this->gudangcrypt->gd_dafsj();
        if ($lsdaf) {
            $idaf = array(
              'ardaf' => $lsdaf,
          );
            $this->load->view('semlist', $idaf);
        } else {
            $this->load->view('semlist');
        }
    }

    public function cekuser()
    {
        $usid  = $this->input->post('wpgidentitas');
        if(empty($this->session->userdata('com_id'))){
          $this->gudangcrypt->mobsess($usid);
        }

        $cuser = $this->gudangcrypt->cek_user($usid);

        if ($cuser) {
            $this->catet("Cek pengguna dengan kode ".$usid);
            echo json_encode($cuser);
        } else {
//            echo json_encode($cuser.",".$usid.",".$this->session->userdata('com_id'));
            echo '0';
        }
    }

    public function reguser()
    {
        $usky = $this->input->post('wpgidentitas');
        $uslv = $this->input->post('wpglevel');
        $usnm = $this->input->post('wpgnama');
        $usid = $this->input->post('wpguuid');
        $usdv = $this->input->post('wpgdevi');
        $uslt = $this->input->post('wpglat');
        $uslg = $this->input->post('wpglng');
        $uskt = $this->input->post('wpgkota');
        $uspr = $this->input->post('wpgprop');
        $usdt = $this->input->post('wpgdet');
        if(empty($this->session->userdata('com_id'))){
          $this->gudangcrypt->mobsess($usky);
        }

        $this->kirimtel('Client BARU CPSJ2019:'.PHP_EOL.'======================'.PHP_EOL.'Kode: '.$usky.PHP_EOL.'Nama: '.$usnm.PHP_EOL.'UUID: '.$usid.PHP_EOL.'Kota: '.$uskt.PHP_EOL.'Alamat: '.$usdt, '674868958');
        $data = array(
            'clidentitas' => ($usky) ? strtoupper($usky) : '---',
            'level' => ($uslv) ? $uslv : 0,
            'nama' => ($usnm) ? $usnm : '---',
            'cluuid' => ($usid) ? $usid : '---',
//            'cldevice' => ($usdv) ? $usdv : '---',
            'latitude' => ($uslt) ? $uslt : '---',
            'longitude' => ($uslg) ? $uslg : '---',
            'asal_wilayah' => ($uskt) ? $uskt : '---',
//            'clprop' => ($uspr) ? $uspr : '---',
            'alamat' => ($usdt) ? $usdt : '---'
        );
        $this->catet("Mendaftarkan pengguna. Kode: ".$usky.", Nama: ".$usnm);
        $this->gudangcrypt->upd_user($usky, $data);
        echo $usnm;
    }

    public function go_supplier()
    {
        $cjen  = '';
        $gkota = '';
        $cidn  = $this->input->post('idn');
        if(empty($this->session->userdata('com_id'))){
          $this->gudangcrypt->mobsess($cidn);
        }
        $csupp = $this->gudangcrypt->lssupp($cidn);
        //        $this->catet("Ambil data supplier.");
//        if($csupp){
          echo json_encode($csupp);
//        } else {
//          echo '';
//        }
    }

    public function go_logo()
    {
        $cidn  = $this->input->post('idn');
        if(empty($this->session->userdata('com_id'))){
          $this->gudangcrypt->mobsess($cidn);
        }
        $kode_per = $this->session->userdata('com_id');
        $this->db->cache_on();
        $dd = $this->db->query("SELECT com_logo FROM tbl_perusahaan  WHERE com_kode = '$kode_per'")->row_array();
        $image = json_decode(json_encode($dd),true);
    		$this->db->cache_off();
//        if ($dd->num_rows() == 0) {
          echo $image['com_logo'];
//        }
    }

    public function sm_absen()
  	{
      $absidn = htmlentities($this->input->post('abidn', TRUE));
      $abstgl = htmlentities($this->input->post('abtgl', TRUE));
      $absjam = htmlentities($this->input->post('abjam', TRUE));
      $absfto = htmlentities($this->input->post('abfoto', TRUE));
      $abslon = htmlentities($this->input->post('ablon', TRUE));
      $abslat = htmlentities($this->input->post('ablat', TRUE));
      if(empty($this->session->userdata('com_id'))){
        $this->gudangcrypt->mobsess($absidn);
      }

      $data = array(
        'absen_idn' => $absidn,
        'absen_tgl' => $abstgl,
        'absen_jam' => $absjam,
        'absen_fto' => $absfto,
        'absen_lat' => $abslat,
        'set_com' => $this->session->userdata('com_id'),
        'absen_lon' => $abslon
      );
      $kode_per = $this->session->userdata('com_id');
      $this->db->cache_on();
      $dd = $this->db->query("SELECT * FROM tbl_absensi WHERE absen_idn = '$absidn' and absen_tgl = '$abstgl' and set_com='$kode_per'");
  		$this->db->cache_off();
      if ($dd->num_rows() == 0) {
        $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
        $this->db->insert('tbl_absensi', $data);
  			$this->db->query("SET FOREIGN_KEY_CHECKS = 1");
      }
  	}

    public function upload_pdf() {

      $key = $this->input->post('key'); // Retrieve the key

      if ($key !== '') {
        if(empty($this->session->userdata('com_id'))){
          $this->gudangcrypt->mobsess($key);
        }
        $kode_per = $this->session->userdata('com_id');
      }
      $baseUploadPath = './dapur0/companies/'.$kode_per.'/';
      $currentDate = date('Y-m-d');
      $dailyDir = $baseUploadPath . $currentDate;

      if (!is_dir($dailyDir)) {
          mkdir($dailyDir, 0755, true);
      }

      $config['upload_path'] = $dailyDir;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 2048; // 2MB limit

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            $response = array(
                'status' => 'error',
                'error' => $this->upload->display_errors()
            );
            echo json_encode($response);
        } else {

          $uploadData = $this->upload->data();
          $filePath = $dailyDir . '/' . $uploadData['file_name'];

          // Send the file to Telegram
          $telegramResponse = $this->sendToTelegram($filePath,$key);

          $response = array(
              'status' => 'success',
              'file_path' => base_url('dapur0/companies/'.$kode_per.'/' . $currentDate . '/' . $uploadData['file_name']),
              'telegram_response' => $telegramResponse,
              'message' => 'File uploaded and sent to Telegram successfully.'
          );

          echo json_encode($response);
        }
    }

    private function sendToTelegram($filePath,$key) {
      $token = "b0lncG5kWWU2V1ZpU29MR2xWbUFJNmFQSkhjR1lnR0xYaWRWYTkwZUdTZ2xqMHFydVF0d2Q4bmQzaG52dnlNUA==";
        $url = "https://api.telegram.org/bot{$this->gudangcrypt->routekey($token,'d')}/sendDocument";

        $postData = array(
            'chat_id' => "674868958",
            'caption' => $key.' Sukses mengirim laporan',
            'document' => new CURLFile($filePath) // Attach the file
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = 'Error:' . curl_error($ch);
            curl_close($ch);
            return $error;
        }

        curl_close($ch);
        return json_decode($response, true);
    }

    public function send_email() {
        // Load email library
        $this->load->library('email');

        // SMTP configuration
        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'mail.arsetontong.top', // e.g., smtp.gmail.com
            'smtp_port' => 587, // or 465 for SSL
            'smtp_user' => 'arsetontong', // Your email address
            'smtp_pass' => '*Histalumir$$65216*', // Your email password
            'mailtype'  => 'html', // or 'text'
            'charset'   => 'utf-8',
            'newline'   => "\r\n",
            'smtp_crypto' => 'tls' // or 'ssl'
        );

        $this->email->initialize($config);

        // Email content
        $this->email->from('your_email@example.com', 'Your Name');
        $this->email->to('recipient@example.com'); // Recipient's email
        $this->email->subject('Contact Form Submission');
        $this->email->message('This is a test email from the contact page.');

        // Send email
        if ($this->email->send()) {
            echo 'Email sent successfully!';
        } else {
            show_error($this->email->print_debugger());
        }
    }
    public function sm_report()
  	{
      $iniden = htmlentities($this->input->post('dtpgn', TRUE));
      $inreport = $this->input->post('dtreport');
      $inreport = str_replace('LOGO-1','LOGO1',$inreport);
      $inreport = str_replace('LOGO-2','LOGO2',$inreport);
      $inreport = str_replace('head-tanggal','headtanggal',$inreport);
      $inreport = str_replace('head-customer','headcustomer',$inreport);
      $inreport = str_replace('head-periode','headperiode',$inreport);
      $inreport = str_replace('head-label-number','headlabelnumber',$inreport);
      $inreport = str_replace('head-model','headmodel',$inreport);
      $inreport = str_replace('head-serial','headserial',$inreport);
      $inreport = str_replace('nota-ket','notaket',$inreport);
      $inreport = str_replace('id-cust','idcust',$inreport);
      $inreport = str_replace('id-cust-tt','idcusttt',$inreport);
      $inreport = str_replace('id-tech','idcusttt',$inreport);
      $inreport = str_replace('id-tech-tt','idcusttt',$inreport);
      $inreport = str_replace('j-1','j1',$inreport);
      $inreport = str_replace('j-2','j2',$inreport);
      $inreport = str_replace('j-3','j3',$inreport);
      $inreport = str_replace('j-4','j4',$inreport);
      $inreport = str_replace('s-1','s1',$inreport);
      $inreport = str_replace('s-2','s2',$inreport);
      $inreport = str_replace('s-3','s3',$inreport);
//      $inreport = str_replace('\"',"'",$inreport);
      $jsreport = json_decode($inreport);

      $data = array(
        'bulk_idn' => $iniden,
        'bulk_tgl' => date('Y-m-d',strtotime($jsreport->notatgl)),
        'bulk_kdsup' => $jsreport->notasupp,
        'bulk_kdrep' => $jsreport->notakode,
        'bulk_tek' => $inreport
      );
      if(empty($this->session->userdata('com_id'))){
        $this->gudangcrypt->mobsess($iniden);
      }
      $kode_per = $this->session->userdata('com_id');
      $this->db->cache_on();
      $dd = $this->db->query("SELECT * FROM tbl_bulk WHERE bulk_idn = '$iniden' and bulk_kdsup = '$jsreport->notasupp' and bulk_tgl = '$jsreport->notatgl' and set_com='$kode_per'");
  		$this->db->cache_off();
      if ($dd->num_rows() == 0) {
        $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
        $this->db->insert('tbl_bulk', $data);
        $this->M_Admin->insertReport($inreport,$jsreport->notakode);
  			$this->db->query("SET FOREIGN_KEY_CHECKS = 1");


      }
//      echo $jsreport->ac01;
  //    $this->catet("Kirim nota dengan data ".$ddev.", ".$dsup.", ".$dnmr.", ".$durt.", ".$dstgl.", ".$dtgl);
  	}

    public function up_gps()
  	{
      $inidn = htmlentities($this->input->post('bidn', TRUE));
      $intgl = htmlentities($this->input->post('btgl', TRUE));
      $inlat = htmlentities($this->input->post('blat', TRUE));
      $inlon = htmlentities($this->input->post('blon', TRUE));
      if(empty($this->session->userdata('com_id'))){
        $this->gudangcrypt->mobsess($inidn);
      }

      $data = array(
        'absen_up_lat' => $inlat,
        'absen_up_lon' => $inlon
      );
      $arid = array('absen_idn'=>$inidn,'absen_tgl'=>$intgl);
      $this->M_Admin->update_table('tbl_absensi', 'absen_idn', $arid, $data);
      echo json_encode($data);
  //    $this->catet("Kirim nota dengan data ".$ddev.", ".$dsup.", ".$dnmr.", ".$durt.", ".$dstgl.", ".$dtgl);
  	}



    public function sm_dafnota2()
    {
        $ddev  = $this->input->post('ntfdev');
        $dsup  = $this->input->post('ntfsup');
        $dnmr  = $this->input->post('ntfnmr');
        $durt  = $this->input->post('ntfurt');
        $dfot  = $this->input->post('ntfoto');
        $dstgl = $this->input->post('ntfstgl');
        $dtgl  = $this->input->post('ntftgl');
        $cpgn = $this->input->post('vusr');
        if ($ddev !='') {
            $cnamacl = $this->gudangcrypt->cek_user($ddev);
            if ($cnamacl) {
                $this->catet($cnamacl['clnama']." kirim SJ nomor ".$dnmr);
            }
        }
        $cekdtf = $this->gudangcrypt->caridetdaft($dsup, $dnmr);
        if ($cekdtf) {
            $parnot = substr($dnmr, -1);
            $vb =0;
            for ($ci=10; $ci <100 ; $ci++) {
                $dnmr = $dsup.$ci.$parnot;
                $cekdtf2 = $this->gudangcrypt->caridetdaft($dsup, $dnmr);
                if (!$cekdtf2) {
                    break;
                }
                $vb++;
            }
            if ($vb>1) {
                $hci = $ci+1;
            } else {
                $hci = $ci;
            }
            $dnmr = $dsup.$hci.$parnot;
        }
        $this->go_gambar($dfot, $dnmr.".jpg");
        if ($cekdtf) {
            $this->gudangcrypt->del_univ('dfkode', $dnmr, 'osjdaft');
            $this->gudangcrypt->del_univ('ntnota', $dnmr, 'osjnota');
        }
        $data  = array(
              'dfclient' => $ddev,
              'dfsupplier' => $dsup,
              'dfkode' => $dnmr,
              'dfnota' => $durt,
              'dffoto' => 'https://wg.arsetontong.top/sjsurabaya/'.date('d-m-Y').'/'.$dnmr.'.jpg',
              'dftglnota' => $dstgl,
              'dftanggal' => $dtgl
          );
        $this->gudangcrypt->tbh_dnota($data);

        $this->catet("Kirim nota dengan data ".$ddev.", ".$dsup.", ".$dnmr.", ".$durt.", ".$dstgl.", ".$dtgl);
    }

    public function getkodeid(){
      $par_com_id = htmlentities($this->input->post('kode_com', TRUE));
      do {
          $input1 = $par_com_id;
  				$input2 = $this->gudangcrypt->rdchr(6);
  				$dr = '';
  				$length = strlen($input1);
  				for ($i = 0; $i < $length; $i++) {
  						$dr .= $input1[$i];
  						$dr .= $input2[$i];
  				}
  		    $dd = $this->db->query("SELECT * FROM tbl_user WHERE clidentitas = '$dr'");

  		} while ($dd->num_rows() > 0);
        echo $dr;
    }


//+++++++++++++++++++++++ SARING
    public function ntbayar()
    {
        $this->load->view('ntbayar');
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

    public function ceknamaf()
    {
        $cbaru='';
        $files = glob('./sjsurabaya/ranya/*apk');
        foreach ($files as $file) {
            $cbaru =  basename(str_replace('.apk', '', $file));
        }
        $abaru = substr($cbaru, 0, 1).'.'.substr($cbaru, 1, 1).'.'.substr($cbaru, 2);
        echo $abaru;
    }

    public function cekterbaru($cpgn = false)
    {
        if (!$cpgn) {
            $cpgn = $this->input->post('vusr');
            $cnamacl = $this->gudangcrypt->cek_user($cpgn);
            if ($cnamacl) {
                $this->catet($cnamacl['clnama']." cek update. Standby.... ");
            }
        }

        $files = glob('./sjsurabaya/ranya/*apk');
        foreach ($files as $file) {
            $cbaru =  basename(str_replace('.apk', '', $file));
        }
        $abaru = substr($cbaru, 0, 1).'.'.substr($cbaru, 1, 1).'.'.substr($cbaru, 2);
        $sekarang[]=array("version"=>$abaru);
        echo json_encode($sekarang);
    }

    public function daftlog()
    {
        $cbaru='<b>File logs untuk CPSJ</b>'.PHP_EOL;
        $cbaru.='========================='.PHP_EOL;
        $files = glob('./dapur0/qnotes/*txt');
        foreach ($files as $file) {
            $mtlog = str_replace('log_', '', basename(str_replace('.txt', '', $file)));
            $tlog = "Tgl ".substr($mtlog, 6).'/'.substr($mtlog, 4, 2).'/'.substr($mtlog, 0, 4).PHP_EOL;
            $alamat = 'https://wg.arsetontong.top/dapur0/qnotes/'.basename($file);
            $cbaru .=  '<a href="'.$alamat.'">'.$tlog.'</a>'.PHP_EOL;
        }
        $cbaru.='========================='.PHP_EOL;
        echo $cbaru;
//        $this->kirimtel($cbaru, "-243776253");
    }

    public function go_tanda($paket = false)
    {
        $dirname = "./sjsurabaya";
        $tujuan  = $dirname . "/" . date("d-m-Y");
        $sasaran = $tujuan."/".$paket;
        $kdsupp = substr($paket, 0, 16);
        $dtsupp = $this->gudangcrypt->cek_supp($kdsupp);
        if ($dtsupp) {
            $namasupp = $dtsupp[0]->nama;
            $nmsupp = strtoupper(url_title($namasupp, 'dash', true)).'_'.date("d_m_Y_H_i_s");
            $imgConfig = array();
            $imgConfig['image_library']    = 'gd2';
            $imgConfig['source_image']     = $sasaran;
            $imgConfig['create_thumb']     = false;
            $imgConfig['maintain_ratio']   = true;
            $imgConfig['width']            = 720;
            $imgConfig['height']           = 1280;
            $this->load->library('image_lib', $imgConfig);
            $this->image_lib->clear();
            if (!$this->image_lib->resize()) {
                echo $this->image_lib->display_errors();
            } else {
                $imgConfig = array();
                $imgConfig['image_library']   = 'gd2';
                $imgConfig['source_image']    = $sasaran;
                $imgConfig['wm_text']         = $nmsupp;
                $imgConfig['wm_type']         = 'text';
                $imgConfig['wm_font_size']    = '12';
                $imgConfig['wm_font_color']    = '#00ff00';
                $imgConfig['wm_shadow_color']    = '#ffff00';
                $imgConfig['wm_shadow_distance']    = '1';
                $imgConfig['wm_opacity']    = '20';
                $this->load->library('image_lib', $imgConfig);
                $this->image_lib->initialize($imgConfig);
                $this->image_lib->watermark();
                $this->image_lib->clear();
            }
        }
    }

    public function go_gambar($gbrstr = false, $gbrnama = false)
    {
        $dirname = "./sjsurabaya";
        $tujuan  = $dirname . "/" . date("d-m-Y");
        $arane = $gbrnama;

        $pgambar = $tujuan."/".$arane;

//        $pgambar = FCPATH.str_replace('https://wg.arsetontong.top/', '', $tgambar);
        if (file_exists($pgambar)) {
            unlink($pgambar);
        }
        $this->base64_to_jpeg($gbrstr, $arane);


        if (!file_exists($tujuan)) {
            mkdir($tujuan, 0777, true);
            chmod($dirname, 0777);
            chmod($tujuan, 0777);
        }
        $this->go_tanda($arane);
    }
    public function go_gambarx()
    {
        print_r($_FILES);
        $ntgl    = date('dmY_His');
        $nmorig  = $_FILES["suratjalan"]["name"];
        $dirname = "sjsurabaya";
        $tujuan  = $dirname . "/" . date("d-m-Y");
        move_uploaded_file($_FILES["suratjalan"]["tmp_name"], $tujuan . "/" . $nmorig);
        if (!empty($_FILES["suratjalan"]["tmp_name"])) {
            print_r($_FILES);
            if (!file_exists($tujuan)) {
                mkdir($tujuan, 0777, true);
                chmod($dirname, 0777);
                chmod($tujuan, 0777);
            }
            $nmimage1 = $_FILES["suratjalan"]["tmp_name"];
            $nmimage2 = $tujuan . "/" . $nmorig;
            $image    = imagecreatefromjpeg($nmimage1);
            $stamp    = imagecreatetruecolor(200, 70);
            imagefilledrectangle($stamp, 0, 0, 199, 169, 0x0000A2);
            imagefilledrectangle($stamp, 9, 9, 190, 60, 0xFFFFFF);
            imagestring($stamp, 5, 20, 20, $nmorig, 0x0000FF);
            imagestring($stamp, 3, 20, 40, '(c)PT.BMI', 0x0000FF);
            $right  = 10;
            $bottom = 10;
            $sx     = imagesx($stamp);
            $sy     = imagesy($stamp);
            imagecopymerge($image, $stamp, imagesx($image) - $sx - $right, imagesy($image) - $sy - $bottom, 0, 0, imagesx($stamp), imagesy($stamp), 15);
            imagepng($image, $nmimage2);
            imagedestroy($image);
        } else {
            print_r($_FILES);
        }
    }
    public function go_excel($tgbeli = false)
    {
        if (!$tgbeli) {
            $tgbeli = date("Ymd");
        }
        $this->go_rekdaf($tgbeli);
        $filename = "sjrekap_" . date("Ymd");
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=$filename.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $lstable = $this->gudangcrypt->struktable('osjrekap');
        foreach ($lstable as $field) {
            echo $field . "\t";
        }
        print("\n");
        $dttable = $this->gudangcrypt->cekrekap();
        $hit1    = count($dttable);
        for ($i = 0; $i <= $hit1 - 1; $i++) {
            foreach ($dttable[$i] as $cbdt) {
                echo $cbdt . "\t";
            }
            print("\n");
        }
    }
    public function repairsupp()
    {
        $this->gudangcrypt->cleansupp();
        $this->gudangcrypt->cleanharga();
        $cleargsupp = $this->gudangcrypt->cleartb('osjwilcenter');
        $rawgsupp   = $this->gudangcrypt->allgsupraw();
        if ($rawgsupp) {
            $g = 1;
            foreach ($rawgsupp as $dsup) {
                $gsuppkode = strtoupper(substr($dsup['arsupp'], 0, 2));
                $cekgsupp  = $this->gudangcrypt->cekgsup($gsuppkode);
                if ($cekgsupp>0) {
                    $g++;
                    $vkode = $gsuppkode . str_pad($g, 2, "0", STR_PAD_LEFT);
                } else {
                    $g = 1;
                    $vkode = $gsuppkode . '01';
                }

                $data = array(
                    'ctkode' => $vkode,
                    'ctarea' => $dsup['arsupp']
                );
                $this->gudangcrypt->tbh_gsupp($data);
                $dtkum[]=$data;
            }
            echo json_encode($dtkum);
        }
    }
    public function repsuppadd1()
    {
        $clearsupp  = $this->gudangcrypt->cleartb('osjsupplier');
        $cleardsupp = $this->gudangcrypt->cleartb('osjdsupplier');
        $rawsupp    = $this->gudangcrypt->allsupraw();
        $skode = '';
        if ($rawsupp) {
            $s = 1;
            foreach ($rawsupp as $ksup) {
                $cekgsupp = $this->gudangcrypt->cekgsup(strtoupper($ksup['arsupp']), '1');
                if ($cekgsupp) {
                    $psuppkode = $cekgsupp['ctkode'] . str_replace('-', '', $ksup['tglaktif']). strtoupper(substr($ksup['nmsupp'], 0, 1));
                    $ceksupp   = $this->gudangcrypt->ceksup($psuppkode, strtoupper($ksup['nmsupp']), strtoupper($ksup['arsupp']));
                    if ($ceksupp > 0) {
                        $s++;
                        $skode = $psuppkode . str_pad(($ceksupp+1), 3, "0", STR_PAD_LEFT);
                    } else {
                        $s =1;
                        $skode = $psuppkode . '001';
                    }
                    $datas = array(
                      'idsupb' => $skode,
                      'idsupl' => $ksup['kdsupp'],
                      'nama' => $ksup['nmsupp'],
                      'idwil' => $ksup['arsupp'],
                      'idbankakun' => $ksup['NamaRekening'],
                      'idbanknama' => $ksup['Bank'],
                      'idbankrek' => $ksup['NoRekening'],
                      'idnpwp' => $ksup['NPWP'],
                      'idhp' => $ksup['HP'],
                      'idseri'=> $ksup['NoSeri']
                  );
                    $this->gudangcrypt->tbh_dsupp($datas);
                    $dtkum[]=$datas;
//                    $cekdtsupp   = $this->gudangcrypt->ceksup($skode);
//                    if ($cekdtsupp == 0) {
                    $csupp1   = $ksup['nmsupp'];
                    $csupp2   = $ksup['arsupp'];
                    $rawdsupp = $this->gudangcrypt->allsuph($csupp1, $csupp2);
                    if ($rawdsupp) {
                        foreach ($rawdsupp as $kdsup) {
                            $datah = array(
                                    'idsupb' => $skode,
                                    'idsupl' => $ksup['kdsupp'],
                                    'TglAktif' => $kdsup['tglaktif'],
                                    'Harga_RM_A' => $kdsup['Harga_RM_A'] ? $kdsup['Harga_RM_A'] : 0,
                                    'Harga_RM_B' => $kdsup['Harga_RM_B'] ? $kdsup['Harga_RM_B'] : 0,
                                    'Harga_RM_C' => $kdsup['Harga_RM_C'] ? $kdsup['Harga_RM_C'] : 0,
                                    'Harga_RM_D' => $kdsup['Harga_RM_D'] ? $kdsup['Harga_RM_D'] : 0,
                                    'Harga_RC_A' => $kdsup['Harga_RC_A'] ? $kdsup['Harga_RC_A'] : 0,
                                    'Harga_RC_B' => $kdsup['Harga_RC_B'] ? $kdsup['Harga_RC_B'] : 0,
                                    'Harga_RC_C' => $kdsup['Harga_RC_C'] ? $kdsup['Harga_RC_C'] : 0,
                                    'Harga_RC_D' => $kdsup['Harga_RC_D'] ? $kdsup['Harga_RC_D'] : 0
                                );
                            $this->gudangcrypt->tbh_hsupp($datah);
                        }
                    }
//                    }
                }
            }
            echo json_encode($dtkum);
        }
    }
    public function ceksupp($usid = false)
    {
        if (!$usid) {
            $usid = $this->input->post('kdsupp');
            $this->catet("Cek data Supplier dgn kode ".$usid);
        }
        $csupp = $this->gudangcrypt->cek_supp($usid);
        if ($csupp) {
            echo json_encode($csupp);
        } else {
            echo '0';
        }
    }

    public function cekar($idcl)
    {
        $crcl = $this->gudangcrypt->cek_supp($idcl);
        if ($crcl) {
            $usernama = $crcl[0]->nama;
            echo $usernama;
        }
    }
    public function cekus($idcl)
    {
        $crcl = $this->gudangcrypt->lsuser($idcl);
        if ($crcl) {
            $usernama = $crcl[0]['clnama'];
            echo $usernama;
        }
    }

    public function go_status($jsts = false, $vsts = false)
    {
        if (!$jsts) {
            $jsts  = $this->input->post('jen');
            $vsts  = $this->input->post('kod');
            $idcl  = $this->input->post('idcl');
            $idad  = $this->input->post('admin');
            $this->catet("Perintah ganti status dengan data ".$jsts.', '.$vsts.', '.$idcl.', '.$idad);

            $stntnama = substr($vsts, 0, 16);
            $stadnama = $idad;
            $stclnama = $idcl;
            $crad = $this->gudangcrypt->lsuser($idad);
            if ($crad) {
                $stadnama = str_replace('_', '', $crad[0]['clnama']);
            }
            $crcln = $this->gudangcrypt->lsuser($idcl);
            if ($crcln) {
                $stclnama = str_replace('_', '', $crcln[0]['clnama']);
            }
            $crsp = $this->gudangcrypt->cek_supp(substr($vsts, 0, 16));
            if ($crsp) {
                $stntnama = $crsp[0]->nama;
            }
        }
        $istat = '*Ganti STATUS SJ:*'.PHP_EOL;
        $istat .= '=================='.PHP_EOL;
        if ($jsts == 'byr') {
            $istat .= '*TYPE:* PEMBAYARAN'.PHP_EOL;
            $ddaft = array('dfkonfirm' => 'BYR');
            $qsts = $this->gudangcrypt->upd_univ($ddaft, 'osjdaft', 'dfkode', $vsts, $idcl);
        } elseif ($jsts == 'edt') {
            $istat .= '*TYPE:* PERBAIKAN'.PHP_EOL;
            $ddaft = array('dfkonfirm' => 'EDT');
            $qsts = $this->gudangcrypt->upd_univ($ddaft, 'osjdaft', 'dfkode', $vsts, $idcl);
        } elseif ($jsts == 'hps') {
            $istat .= '*TYPE:* PENGHAPUSAN'.PHP_EOL;
            $this->chapus(substr($vsts, 0, 16), $vsts);
            $qsts = $this->gudangcrypt->del_univ('dfkode', $vsts, 'osjdaft', $idcl);
            $qsts2 = $this->gudangcrypt->del_univ('ntnota', $vsts, 'osjnota', $idcl);
        }
        $istat .= '*SUPP:* '.strtoupper($stntnama).PHP_EOL;
        $istat .= '*KOORD:* '.strtoupper($stclnama).PHP_EOL;
        $istat .= '*OLEH:* '.strtoupper($stadnama).PHP_EOL;
        $istat .= '*MARK:* '.date("d-m-Y H:i").PHP_EOL;
        $this->kirimtel($istat, "-397911439");
        if ($qsts) {
            echo 'OK';
        } else {
            echo 'NO';
        }
    }
    public function go_groupsupp()
    {
        $csupp = $this->gudangcrypt->lsgrsupp();
        echo json_encode($csupp);
    }
    public function go_harga($ksuppi = false)
    {
        if ($ksuppi) {
            $ksupp = $ksuppi;
        } else {
            $ksupp = $this->input->post('kdsupp');
        }
        $hsupp = $this->gudangcrypt->lshsupp($ksupp);
        if ($hsupp) {
            $this->catet("Ambil data HARGA");
            echo json_encode($hsupp);
        }
    }
    public function go_item()
    {
        $this->catet("Ambil data item");

        $cjen = $this->input->post('jen');
        if ($cjen == 'ambil') {
            $citem = $this->gudangcrypt->lsitem();
        }
        echo json_encode($citem);
    }
    public function go_csupp($idsup = false)
    {
        $supbio = array();
        $dbio   = $this->gudangcrypt->caridetsup($idsup);
        if ($dbio) {
            $this->catet("Cari supplier dgn kode ".$idsup);

            echo json_encode($dbio);
        }
    }
    public function up_notif($cksupp = false, $ckode = false, $ctglnota = false, $mark = false)
    {
        if (!$cksupp) {
            $cksupp   = $this->input->post('aksupp');
            $ckode    = $this->input->post('akode');
            $ctglnota = $this->input->post('atglnota');
            $cclient = $this->input->post('idcl');
            /*
                        $cnamacl = $this->gudangcrypt->cek_user($cclient);
                        if ($cnamacl) {
                            $this->catet($cnamacl['clnama'].'Cek notifikasi dengan data KdSupp:'.$cksupp.', KdNota'.$ckode.', TglNota'.$ctglnota);
                        }
            */
        }
        if ($cksupp != '') {
            $ddaft = $this->gudangcrypt->caridetdaft($cksupp, $ckode, $ctglnota, $cclient);
        } else {
            $ddaft = $this->gudangcrypt->caridetdaft();
        }
        if ($ddaft) {
            if (!$mark) {
                echo json_encode($ddaft);
            } else {
                return $ddaft;
            }
        } else {
            echo 0;
        }
    }
    public function go_rekdaf($tgls = false)
    {
        $this->catet("Ambil data rekap tgl. ".$tgls);

        $TglBeli      = '';
        $KdNota       = '';
        $KdSupplier   = '';
        $NmSupplier   = '';
        $RM_Besar     = 0;
        $Hg_RM_Besar  = 0;
        $RM_Sedang    = 0;
        $Hg_RM_Sedang = 0;
        $RM_Kecil     = 0;
        $Hg_RM_Kecil  = 0;
        $Tot_RM       = 0;
        $Tot_Hg_RM    = 0;
        $RC_Besar     = 0;
        $Hg_RC_Besar  = 0;
        $RC_Sedang    = 0;
        $Hg_RC_Sedang = 0;
        $RC_Kecil     = 0;
        $Hg_RC_Kecil  = 0;
        $Tot_RC       = 0;
        $Tot_Hg_RC    = 0;
        $Susut        = 0;
        $BOP_1        = 0;
        $Hg_BOP_1     = 0;
        $BOP_2        = 0;
        $Hg_BOP_2     = 0;
        $BOP_3        = 0;
        $Hg_BOP_3     = 0;
        $BOP_4        = 0;
        $Hg_BOP_4     = 0;
        $BOP_5        = 0;
        $Hg_BOP_5     = 0;
        $BOP_6        = 0;
        $Hg_BOP_6     = 0;
        $Tot_Hg_BOP   = 0;
        $Pelapor      = '';
        $Lokasi       = '';
        $lsdaf        = $this->gudangcrypt->gd_dafsj($tgls);
        if ($lsdaf) {
            $this->gudangcrypt->cleartb('osjrekap');
            foreach ($lsdaf as $idaf) {
                $dkdsupp = $idaf['dfsupplier'];
                $dkdnota = $idaf['dfnota'];
                $dtgnota = $idaf['dftglnota'];
                $dkduser = $idaf['dfclient'];
                $kdnota  = str_pad($dkdsupp, 6, "s", STR_PAD_LEFT) . str_pad($dkdnota, 6, "n", STR_PAD_LEFT) . str_replace('-', '', $dtgnota);
                $dtuser  = $this->gudangcrypt->lsuser($dkduser);
                foreach ($dtuser as $cdtuser) {
                    $Pelapor = $cdtuser['clnama'];
                    $Lokasi  = $cdtuser['clkota'];
                }
                $dtsupp = $this->gudangcrypt->lssupp('', '', '', $dkdsupp);
                foreach ($dtsupp as $cdtsupp) {
                    $sNmSupplier   = $cdtsupp->nama;
                    $sHg_RM_Besar  = $cdtsupp->hgrmbesar;
                    $sHg_RM_Sedang = $cdtsupp->hgrmsedang;
                    $sHg_RM_Kecil  = $cdtsupp->hgrmkecil;
                    $sHg_RC_Besar  = $cdtsupp->hgrcbesar;
                    $sHg_RC_Sedang = $cdtsupp->hgrcsedang;
                    $sHg_RC_Kecil  = $cdtsupp->hgrckecil;
                    $sHg_BOP_1     = 0;
                    $sHg_BOP_2     = 0;
                    $sHg_BOP_3     = 0;
                    $sHg_BOP_4     = 0;
                    $sHg_BOP_5     = 0;
                    $sHg_BOP_6     = 0;
                }
                $Hg_RM_Besar  = $sHg_RM_Besar;
                $Hg_RM_Sedang = $sHg_RM_Sedang;
                $Hg_RM_Kecil  = $sHg_RM_Kecil;
                $Hg_RC_Besar  = $sHg_RC_Besar;
                $Hg_RC_Sedang = $sHg_RC_Sedang;
                $Hg_RC_Kecil  = $sHg_RC_Kecil;
                $Hg_BOP_1     = $sHg_BOP_1 > 0 ? $sHg_BOP_1 : 0;
                $Hg_BOP_2     = $sHg_BOP_2 > 0 ? $sHg_BOP_2 : 0;
                $Hg_BOP_3     = $sHg_BOP_3 > 0 ? $sHg_BOP_3 : 0;
                $Hg_BOP_4     = $sHg_BOP_4 > 0 ? $sHg_BOP_4 : 0;
                $Hg_BOP_5     = $sHg_BOP_5 > 0 ? $sHg_BOP_5 : 0;
                $Hg_BOP_6     = $sHg_BOP_6 > 0 ? $sHg_BOP_6 : 0;
                $lsnota       = $this->gudangcrypt->gd_nota($kdnota);
                if ($lsnota) {
                    foreach ($lsnota as $dnota) {
                        $ntket  = $dnota['ntketer'];
                        $pnil   = $dnota['ntnilai'];
                        $pdev   = $dnota['ntclient'];
                        $cekrek = $this->gudangcrypt->cekrekap($kdnota);
                        if ($cekrek) {
                            foreach ($cekrek as $hrekap) {
                                $RM_Besar     = $hrekap['RM_Besar'];
                                $Hg_RM_Besar  = $hrekap['Hg_RM_Besar'];
                                $RM_Sedang    = $hrekap['RM_Sedang'];
                                $Hg_RM_Sedang = $hrekap['Hg_RM_Sedang'];
                                $RM_Kecil     = $hrekap['RM_Kecil'];
                                $Hg_RM_Kecil  = $hrekap['Hg_RM_Kecil'];
                                $Tot_RM       = floatval($hrekap['Tot_RM']);
                                $Tot_Hg_RM    = intval($hrekap['Tot_Hg_RM']);
                                $RC_Besar     = $hrekap['RC_Besar'];
                                $Hg_RC_Besar  = $hrekap['Hg_RC_Besar'];
                                $RC_Sedang    = $hrekap['RC_Sedang'];
                                $Hg_RC_Sedang = $hrekap['Hg_RC_Sedang'];
                                $RC_Kecil     = $hrekap['RC_Kecil'];
                                $Hg_RC_Kecil  = $hrekap['Hg_RC_Kecil'];
                                $Tot_RC       = floatval($hrekap['Tot_RC']);
                                $Tot_Hg_RC    = intval($hrekap['Tot_Hg_RC']);
                                $Susut        = floatval($hrekap['Susut']);
                                $BOP_1        = $hrekap['BOP_1'];
                                $Hg_BOP_1     = intval($hrekap['Hg_BOP_1']);
                                $BOP_2        = $hrekap['BOP_2'];
                                $Hg_BOP_2     = intval($hrekap['Hg_BOP_2']);
                                $BOP_3        = $hrekap['BOP_3'];
                                $Hg_BOP_3     = intval($hrekap['Hg_BOP_3']);
                                $BOP_4        = $hrekap['BOP_4'];
                                $Hg_BOP_4     = intval($hrekap['Hg_BOP_4']);
                                $BOP_5        = $hrekap['BOP_5'];
                                $Hg_BOP_5     = intval($hrekap['Hg_BOP_5']);
                                $BOP_6        = $hrekap['BOP_6'];
                                $Hg_BOP_6     = intval($hrekap['Hg_BOP_6']);
                                $Tot_Hg_BOP   = intval($hrekap['Tot_Hg_BOP']);
                            }
                        } else {
                            $RM_Besar     = 0;
                            $Hg_RM_Besar  = 0;
                            $RM_Sedang    = 0;
                            $Hg_RM_Sedang = 0;
                            $RM_Kecil     = 0;
                            $Hg_RM_Kecil  = 0;
                            $Tot_RM       = 0;
                            $Tot_Hg_RM    = 0;
                            $RC_Besar     = 0;
                            $Hg_RC_Besar  = 0;
                            $RC_Sedang    = 0;
                            $Hg_RC_Sedang = 0;
                            $RC_Kecil     = 0;
                            $Hg_RC_Kecil  = 0;
                            $Tot_RC       = 0;
                            $Tot_Hg_RC    = 0;
                            $Susut        = 0;
                            $BOP_1        = 0;
                            $Hg_BOP_1     = 0;
                            $BOP_2        = 0;
                            $Hg_BOP_2     = 0;
                            $BOP_3        = 0;
                            $Hg_BOP_3     = 0;
                            $BOP_4        = 0;
                            $Hg_BOP_4     = 0;
                            $BOP_5        = 0;
                            $Hg_BOP_5     = 0;
                            $BOP_6        = 0;
                            $Hg_BOP_6     = 0;
                            $Tot_Hg_BOP   = 0;
                        }
                        if (substr($ntket, 0, 2) != 'RC' && substr($ntket, 0, 2) != 'RM') {
                            if ($cekrek) {
                                if ($Hg_BOP_1 == 0) {
                                    $BOP_1    = $ntket . '(' . $pnil . ')';
                                    $Hg_BOP_1 = $pnil;
                                } elseif ($Hg_BOP_2 == 0) {
                                    $BOP_2    = $ntket . '(' . $pnil . ')';
                                    $Hg_BOP_2 = $pnil;
                                } elseif ($Hg_BOP_3 == 0) {
                                    $BOP_3    = $ntket . '(' . $pnil . ')';
                                    $Hg_BOP_3 = $pnil;
                                } elseif ($Hg_BOP_4 == 0) {
                                    $BOP_4    = $ntket . '(' . $pnil . ')';
                                    $Hg_BOP_4 = $pnil;
                                } elseif ($Hg_BOP_5 == 0) {
                                    $BOP_5    = $ntket . '(' . $pnil . ')';
                                    $Hg_BOP_5 = $pnil;
                                } elseif ($Hg_BOP_6 == 0) {
                                    $BOP_6    = $ntket . '(' . $pnil . ')';
                                    $Hg_BOP_6 = $pnil;
                                }
                            } else {
                                $BOP_1    = $ntket . '(' . $pnil . ')';
                                $Hg_BOP_1 = $pnil;
                            }
                            $Tot_Hg_BOP = $Tot_Hg_BOP + $pnil;
                        }
                        if (substr($ntket, 0, 2) == 'RC') {
                            switch ($ntket) {
                                case 'RC Sedang':
                                    $RC_Sedang    = $pnil;
                                    $Hg_RC_Sedang = $pnil * $sHg_RC_Sedang;
                                    $Tot_Hg_RC    = $Tot_Hg_RC + $Hg_RC_Sedang;
                                    break;
                                case 'RC Kecil':
                                    $RC_Kecil    = $pnil;
                                    $Hg_RC_Kecil = $pnil * $sHg_RC_Kecil;
                                    $Tot_Hg_RC   = $Tot_Hg_RC + $Hg_RC_Kecil;
                                    break;
                                default:
                                    $RC_Besar  = $pnil;
                                    $Hg_RC     = $pnil * $sHg_RC_Besar;
                                    $Tot_Hg_RC = $Tot_Hg_RC + $Hg_RC_Besar;
                                    break;
                            }
                            $Tot_RC = $Tot_RC + $pnil;
                        }
                        if (substr($ntket, 0, 2) == 'RM') {
                            switch ($ntket) {
                                case 'RM Sedang':
                                    $RM_Sedang    = $pnil;
                                    $Hg_RM_Sedang = $pnil * $sHg_RM_Sedang;
                                    $Tot_Hg_RM    = $Tot_Hg_RM + $Hg_RM_Sedang;
                                    break;
                                case 'RM Kecil':
                                    $RM_Kecil    = $pnil;
                                    $Hg_RM_Kecil = $pnil * $sHg_RM_Kecil;
                                    $Tot_Hg_RM   = $Tot_Hg_RM + $Hg_RM_Kecil;
                                    break;
                                default:
                                    $RM_Besar    = $pnil;
                                    $Hg_RM_Besar = $pnil * $sHg_RM_Besar;
                                    $Tot_Hg_RM   = $Tot_Hg_RM + $Hg_RM_Besar;
                                    break;
                            }
                            $Tot_RM = $Tot_RM + $pnil;
                        }
                        $Susut     = 100 * abs($Tot_RM - $Tot_RC) / $Tot_RM;
                        $datarekap = array(
                            'TglBeli' => $dtgnota,
                            'KdNota' => $kdnota,
                            'KdSupplier' => $dkdsupp,
                            'NmSupplier' => $sNmSupplier,
                            'RM_Besar' => $RM_Besar,
                            'Hg_RM_Besar' => $Hg_RM_Besar,
                            'RM_Sedang' => $RM_Sedang,
                            'Hg_RM_Sedang' => $Hg_RM_Sedang,
                            'RM_Kecil' => $RM_Kecil,
                            'Hg_RM_Kecil' => $Hg_RM_Kecil,
                            'Tot_RM' => $Tot_RM,
                            'Tot_Hg_RM' => $Tot_Hg_RM,
                            'RC_Besar' => $RC_Besar,
                            'Hg_RC_Besar' => $Hg_RC_Besar,
                            'RC_Sedang' => $RC_Sedang,
                            'Hg_RC_Sedang' => $Hg_RC_Sedang,
                            'RC_Kecil' => $RC_Kecil,
                            'Hg_RC_Kecil' => $Hg_RC_Kecil,
                            'Tot_RC' => $Tot_RC,
                            'Tot_Hg_RC' => $Tot_Hg_RC,
                            'Susut' => round($Susut, 2),
                            'BOP_1' => $BOP_1,
                            'Hg_BOP_1' => $Hg_BOP_1,
                            'BOP_2' => $BOP_2,
                            'Hg_BOP_2' => $Hg_BOP_2,
                            'BOP_3' => $BOP_3,
                            'Hg_BOP_3' => $Hg_BOP_3,
                            'BOP_4' => $BOP_4,
                            'Hg_BOP_4' => $Hg_BOP_4,
                            'BOP_5' => $BOP_5,
                            'Hg_BOP_5' => $Hg_BOP_5,
                            'BOP_6' => $BOP_6,
                            'Hg_BOP_6' => $Hg_BOP_6,
                            'Tot_Hg_BOP' => $Tot_Hg_BOP,
                            'Pelapor' => $Pelapor,
                            'Lokasi' => $Lokasi
                        );
                        if ($cekrek) {
                            $this->gudangcrypt->upd_rekap($datarekap, $kdnota);
                        } else {
                            $this->gudangcrypt->tbh_rekap($datarekap);
                        }
                    }
                }
            }
        }
    }
    public function sm_detnota()
    {
        $pdev   = $this->input->post('ntkdev');
        $psup   = $this->input->post('ntksup');
        $pnmr   = $this->input->post('ntknmr');
        $pket   = $this->input->post('ntkket');
        $pkod   = $this->input->post('ntkkod');
        $pnil   = $this->input->post('ntknil');

        $cekdtf = $this->gudangcrypt->caridetdaft(substr($pnmr, 0, 16), $pnmr);
        if ($cekdtf) {
            $tgnot = $cekdtf['dftglnota'];
            $cekjdaft = $this->gudangcrypt->caridetdaft(substr($pnmr, 0, 16), false, $tgnot, false, 'jum');
            if ($cekjdaft>1) {
                $parnot = substr($pnmr, -1);
                for ($ci=10; $ci <$cekjdaft+9 ; $ci++) {
                    $dnmr = substr($pnmr, 0, 16).$ci.$parnot;
                }
                $pnmr = $dnmr;
            }
        }
        /*
                $dtdaft = $this->gudangcrypt->gd_dafsj('', $pdev, substr($pnmr, 0, 16), $pnmr);
                if ($dtdaft) {
                    foreach ($dtdaft as $cdtdaft) {
                        $tgnot = $cdtdaft['dftglnota'];
                    }
                }
        */
        $data   = array(
            'ntclient' => $pdev,
            'ntkode' => $pkod,
            'ntnota' => $pnmr,
            'ntsupplier' => $psup,
            'ntketer' => $pket,
            'ntnilai' => $pnil
        );
        $this->gudangcrypt->tbh_nota($data);

        $this->catet("Kirim detail nota dengan data ".$pdev.", ".$pkod.", ".$pnmr.", ".$psup.", ".$pket.", ".$pnil);
    }


    public function chapus($ksupp=false, $knota=false)
    {
        $cekdtf = $this->gudangcrypt->caridetdaft($ksupp, $knota);
        $tgambar = $cekdtf[0]['dffoto'];
        $pgambar = FCPATH.str_replace('https://wg.arsetontong.top/', '', $tgambar);
        if (file_exists($pgambar)) {
            unlink($pgambar);
        }
        $this->catet("Hapus nota dengan data ".$ksupp.", ".$knota);
    }
    public function sm_dafnota()
    {
        $ddev  = $this->input->post('ntfdev');
        $dsup  = $this->input->post('ntfsup');
        $dnmr  = $this->input->post('ntfnmr');
        $durt  = $this->input->post('ntfurt');
        $dfot  = $this->input->post('ntfoto');
        $dstgl = $this->input->post('ntfstgl');
        $dtgl  = $this->input->post('ntftgl');
        $cpgn = $this->input->post('vusr');
        if ($ddev !='') {
            $cnamacl = $this->gudangcrypt->cek_user($ddev);
            if ($cnamacl) {
                $this->catet($cnamacl['clnama']." kirim SJ nomor ".$dnmr);
            }
        }
        $cekdtf = $this->gudangcrypt->caridetdaft($dsup, $dnmr);
        if ($cekdtf) {
            $parnot = substr($dnmr, -1);
            $vb =0;
            for ($ci=10; $ci <100 ; $ci++) {
                $dnmr = $dsup.$ci.$parnot;
                $cekdtf2 = $this->gudangcrypt->caridetdaft($dsup, $dnmr);
                if (!$cekdtf2) {
                    break;
                }
                $vb++;
            }
            if ($vb>1) {
                $hci = $ci+1;
            } else {
                $hci = $ci;
            }
            $dnmr = $dsup.$hci.$parnot;
        }
        $this->go_gambar($dfot, $dnmr.".jpg");
        if ($cekdtf) {
            $this->gudangcrypt->del_univ('dfkode', $dnmr, 'osjdaft');
            $this->gudangcrypt->del_univ('ntnota', $dnmr, 'osjnota');
        }
        $data  = array(
              'dfclient' => $ddev,
              'dfsupplier' => $dsup,
              'dfkode' => $dnmr,
              'dfnota' => $durt,
              'dffoto' => 'https://wg.arsetontong.top/sjsurabaya/'.date('d-m-Y').'/'.$dnmr.'.jpg',
              'dftglnota' => $dstgl,
              'dftanggal' => $dtgl
          );
        $this->gudangcrypt->tbh_dnota($data);

        $this->catet("Kirim nota dengan data ".$ddev.", ".$dsup.", ".$dnmr.", ".$durt.", ".$dstgl.", ".$dtgl);
    }

    public function ambilrekap($jenis = false)
    {
        $this->ssn_repsatu();
        $data = array(
            'rekapa' => $this->gudangcrypt->grepa('sys')
            );
        if ($jenis) {
            $this->load->view('semexcel', $data);
        } else {
            $this->load->view('detsemexcel', $data);
        }
    }

    public function promodul()
    {
        $this->catet("Buka PROMODUL!!!");
        $this->load->view('hamur');
    }


    public function reprc($ctanggal = false, $cnama = false, $carea = false)
    {
        $rbakses = get_cookie('kodeku');
        $this->ssn_repdua($ctanggal, $cnama, $carea, $rbakses);
        $hrepdua = $this->gudangcrypt->grepb($ctanggal, $cnama, $carea, $rbakses);
        if ($hrepdua) {
            $data = array(
              'tgawal' => substr($ctanggal, 0, 8),
              'tgakhir' => (strlen($ctanggal)==16)?substr($ctanggal, -8):substr($ctanggal, 0, 8),
              'tgnama' => ($cnama || $cnama!='')?$cnama:'',
              'tgarea' => ($carea || $carea!='')?$carea:'',
              'tgakses' => ($rbakses || $rbakses!='')?$rbakses:''
            );
            $this->load->view('exclrc', $data);
            $rbakses = get_cookie('kodeku');
            $cnamacl = $this->gudangcrypt->cek_user($rbakses);
            if ($cnamacl) {
                $this->catet($cnamacl['clnama']." ambil data type excel. ".$ctanggal);
            }
        } else {
            echo 'Data Tidak Ditemukan';
        }
    }

    public function cekpanitera()
    {
        $jen = $this->input->post('vjen');
        $ket = $this->input->post('vket');
        $cpanit = $this->gudangcrypt->cek_panit($jen, $ket);
        if ($cpanit) {
            echo $cpanit['pnnilai'];
        }
    }

    public function dwebuser()
    {
        $cekakses = $this->gudangcrypt->gdatalog();
        if ($cekakses) {
            echo json_encode($cekakses);
        }
    }


    public function webustatus()
    {
        $jen = $this->input->post('kodeku');
        $simses = array(
      'rxakses'=>$jen,
      'rxstatus'=>'OPEN'
    );
        $this->gudangcrypt->upd_uses($jen, $simses);


//        $ket = $this->input->post('vket');
        $cuser = $this->gudangcrypt->cek_webuser($jen);
        if ($cuser) {
            echo $cuser['rxstatus'];
        }
    }

    public function repvrc($ctanggal = false)
    {
        if ($ctanggal) {
            $this->ssn_repdua($ctanggal);
            $datarc = $this->gudangcrypt->grepb($ctanggal);
            $this->load->view('semexcelrc');
        } else {
            $this->load->view('semexcelrc');
        }
    }

    public function prafill()
    {
        $vtglaw = '';
        $vtglak = '';
        $vnama = '';
        $varea = '';
        $vakses = '';
        $cekakses = false;
        $vtglaw = $this->input->post('vtga');
        $vtglak = $this->input->post('vtgk');
        $vnama = $this->input->post('vcnm');
        $varea = $this->input->post('vcar');
        $vakses = $this->input->post('vkey');
        if ($vakses!='') {
            $cekakses = $this->gudangcrypt->gdatalog($vakses);
        }
        if ($cekakses) {
            $ctanggal = $vtglak==''?$vtglaw.$vtglaw:$vtglaw.$vtglak;
            if ($vtglaw!='') {
                $this->ssn_reptiga($ctanggal, $vnama, $varea, $vakses);
                $hreptiga = $this->gudangcrypt->grepc($ctanggal, $vnama, $varea, $vakses);
                if ($hreptiga) {
                    $datarc = $this->gudangcrypt->grepc($ctanggal, $vnama, $varea, $vakses);
                    echo json_encode($datarc);
                } else {
                    echo 'ZONK';
                }
            }
        } else {
            echo 'BENTROK';
        }
    }

    public function detrepc()
    {
        $vtglaw = '';
        $vtglak = '';
        $vnama = '';
        $varea = '';
        $vakses = '';
        $cekakses = false;
        $vtglaw = $this->input->post('vtga');
        $vtglak = $this->input->post('vtgk');
        $vakses = $this->input->post('vkey');
        if ($vakses!='') {
            $cekakses = $this->gudangcrypt->gdatalog($vakses);
        }
        if ($cekakses) {
            $ctanggal = $vtglak==''?$vtglaw.$vtglaw:$vtglaw.$vtglak;
            if ($vtglaw!='') {
                $hreptiga = $this->gudangcrypt->grepc($ctanggal, false, false, $vakses);
                if ($hreptiga) {
                    $datarc = $this->gudangcrypt->grepc($ctanggal, false, false, $vakses);
                    echo json_encode($datarc);
                } else {
                    echo 'ZONK';
                }
            }
        } else {
            echo 'BENTROK';
        }
    }

    public function addrepc()
    {
        $vtglaw = $this->input->post('vtga');
        $vtglak = $this->input->post('vtgk');
        $vnama = $this->input->post('vnma');
        switch ($vnama) {
          case 'BM':
          $cnama = 'Harga BMI';
            break;

            case 'NR':
            $cnama = 'Harga NR';
              break;

          default:
          $cnama = 'Harga PL SBY';
            break;
        }
        $ctanggal = $vtglak==''?$vtglaw.$vtglaw:$vtglaw.$vtglak;
        $hreptiga = $this->gudangcrypt->getpl($ctanggal, $cnama);
        if ($hreptiga) {
            $datarc = $this->gudangcrypt->getpl($ctanggal, $cnama);
            echo json_encode($datarc);
        }
    }

    public function ssn_reptiga($ctanggal = false, $cnama = false, $carea = false, $vrakses = false)
    {
        $rbakses = $vrakses!=''?$vrakses:get_cookie('kodeku');
        $cnamacl = $this->gudangcrypt->cek_user($rbakses);
        if ($cnamacl) {
            $lgtgl = $ctanggal?(substr($ctanggal, 0, 2).'/'.substr($ctanggal, 2, 2).'/'.substr($ctanggal, 4, 4).'-'.substr($ctanggal, 8, 2).'/'.substr($ctanggal, 10, 2).'/'.substr($ctanggal, 12, 4)):'noRange';
            $lgnama = $cnama?$cnama:'noName';
            $lgarea = $carea?$carea:'noArea';
            $this->catet($cnamacl['clnama']." filter data Analisa dgn range ".$lgtgl." addon nama ".$lgnama." addon area ".$lgarea);
        }
        $this->gudangcrypt->del_univ('rcakses', $rbakses, 'osjrepc');
        $h1 = 1;
        $arsuppl = $this->gudangcrypt->ceksupppro('HasilSortir.CanJbColosal as SCanJbColosal,HasilSortir.CanJumbo as SCanJumbo,HasilSortir.CanJUS1 as SCanJUS1,HasilSortir.CanJUS2 as SCanJUS2,HasilSortir.CanBfdariJumbo as SCanBfdariJumbo,HasilSortir.CanFlakedariJumbo as SCanFlakedariJumbo,HasilSortir.CanLump as SCanLump,HasilSortir.CanBfFlake as SCanBfFlake,HasilSortir.CanBfSmall as SCanBfSmall,HasilSortir.CanBfShrd as SCanBfShrd,HasilSortir.CanSpesialA as SCanSpesialA,HasilSortir.CanSpesialEx as SCanSpesialEx,HasilSortir.CanSpesialPlus as SCanSpesialPlus,HasilSortir.CanClawmeatMerus as SCanClawmeatMerus,HasilSortir.CanClawmeatUtuh as SCanClawmeatUtuh,HasilSortir.CanClawmeatHancur as SCanClawmeatHancur,HasilSortir.CanClawmeatCarpus as SCanClawmeatCarpus,HasilSortir.CanCF as SCanCF,HasilSortir.AddJbColosal as SAddJbColosal,HasilSortir.AddJumbo as SAddJumbo,HasilSortir.AddJUS as SAddJUS,HasilSortir.AddLump as SAddLump,HasilSortir.AddBackfin as SAddBackfin,HasilSortir.AddSpesialA as SAddBackfin,HasilSortir.AddSpesialA as SAddSpesialA,HasilSortir.AddSpesialEx as SAddSpesialEx,HasilSortir.AddSpesialPlus as SAddSpesialPlus,HasilSortir.AddClawmeat as SAddClawmeat,HasilSortir.BJumbo as SBJumbo,HasilSortir.BLump as SBLump,HasilSortir.BBackfin as SBBackfin,HasilSortir.BSpesial as SBSpesial,HasilSortir.BClawmeat as SBClawmeat,HasilSortir.R001Jumbo as SR001Jumbo,HasilSortir.R001Lump as SR001Lump,HasilSortir.R001Backfin as SR001Backfin,HasilSortir.R001Spesial as SR001Spesial,HasilSortir.R001Clawmeat as SR001Clawmeat,HasilSortir.R004Jumbo as SR004Jumbo,HasilSortir.R004Lump as SR004Lump,HasilSortir.R004Backfin as SR004Backfin,HasilSortir.R004Spesial as SR004Spesial,HasilSortir.R004Clawmeat as SR004Clawmeat,HasilSortir.R005Jumbo as SR005Jumbo,HasilSortir.R005Lump as SR005Lump,HasilSortir.R005Backfin as SR005Backfin,HasilSortir.R005Spesial as SR005Spesial,HasilSortir.R005Clawmeat as SR005Clawmeat,HasilSortir.R006Jumbo as SR006Jumbo,HasilSortir.R006Lump as SR006Lump,HasilSortir.R006Backfin as SR006Backfin,HasilSortir.R006Spesial as SR006Spesial,HasilSortir.R006Clawmeat as SR006Clawmeat,HasilSortir.ShellJumbo as SShellJumbo,HasilSortir.ShellLump as SShellLump,HasilSortir.ShellBackfin as SShellBackfin,HasilSortir.ShellSpesial as SShellSpesial,HasilSortir.ShellClawmeat as SShellClawmeat,HasilSortir.AirJumbo as SAirJumbo,HasilSortir.AirLump as SAirLump,HasilSortir.AirBackfin as SAirBackfin,HasilSortir.AirSpesial as SAirSpesial,HasilSortir.AirClawmeat as SAirClawmeat,HasilSortir.RijekKembaliJumbo as SRijekKembaliJumbo,HasilSortir.RijekKembaliLump as SRijekKembaliLump,HasilSortir.RijekKembaliBackfin as SRijekKembaliBackfin,HasilSortir.RijekKembaliSpesial as SRijekKembaliSpesial,HasilSortir.RijekKembaliClawmeat as SRijekKembaliClawmeat,HasilSortir.NoAcc as SNoAcc,HasilSortir.Suplayer as SSuplayer,HasilSortir.Area as SArea,TabelRevisi.NoNota as RNoNota,TabelRevisi.TanggalTerima as RTanggalTerima,TabelRevisi.TanggalSortir as RTanggalSortir,TabelRevisi.TanggalNota as RTanggalNota,TabelRevisi.KodeSuplayer as RKodeSuplayer,TabelRevisi.NamaRekening as RNamaRekening,TabelRevisi.NoRekening as RNoRekening,TabelRevisi.PenluJumbo as RPenluJumbo,TabelRevisi.PenluJUS as RPenluJUS,TabelRevisi.PenluBackfin as RPenluBackfin,TabelRevisi.PenluLump as RPenluLump,TabelRevisi.PenluSuperLump as RPenluSuperLump,TabelRevisi.PenluSpesial as RPenluSpesial,TabelRevisi.PenluClawmeat as RPenluClawmeat,TabelRevisi.PenluCF as RPenluCF,TabelRevisi.CanJbColosal as RCanJbColosal,TabelRevisi.CanJumbo as RCanJumbo,TabelRevisi.CanJUS as RCanJUS,TabelRevisi.CanJUSB as RCanJUSB,TabelRevisi.CanLump as RCanLump,TabelRevisi.CanBackfin as RCanBackfin,TabelRevisi.CanSuperLump as RCanSuperLump,TabelRevisi.CanSpesial as RCanSpesial,TabelRevisi.CanClawmeatUtuh as RCanClawmeatUtuh,TabelRevisi.CanClawmeatHancur as RCanClawmeatHancur,TabelRevisi.CanClawmeatCarpus as RCanClawmeatCarpus,TabelRevisi.CanCF as RCanCF,TabelRevisi.CanLebihSpesial as RCanLebihSpesial,TabelRevisi.CanLebihClawmeat as RCanLebihClawmeat,TabelRevisi.BJumbo as RBJumbo,TabelRevisi.BLump as RBLump,TabelRevisi.BBackfin as RBBackfin,TabelRevisi.BSpesial as RBSpesial,TabelRevisi.BClawmeat as RBClawmeat,TabelRevisi.R001Jumbo as RR001Jumbo,TabelRevisi.R001Lump as RR001Lump,TabelRevisi.R001Backfin as RR001Backfin,TabelRevisi.R001Spesial as RR001Spesial,TabelRevisi.R001Clawmeat as RR001Clawmeat,TabelRevisi.R004Jumbo as RR004Jumbo,TabelRevisi.R004Lump as RR004Lump,TabelRevisi.R004Backfin as RR004Backfin,TabelRevisi.R004Spesial as RR004Spesial,TabelRevisi.R004Clawmeat as RR004Clawmeat,TabelRevisi.R005Jumbo as RR005Jumbo,TabelRevisi.R005Lump as RR005Lump,TabelRevisi.R005Backfin as RR005Backfin,TabelRevisi.R005Spesial as RR005Spesial,TabelRevisi.R005Clawmeat as RR005Clawmeat,TabelRevisi.R006Jumbo as RR006Jumbo,TabelRevisi.R006Lump as RR006Lump,TabelRevisi.R006Backfin as RR006Backfin,TabelRevisi.R006Spesial as RR006Spesial,TabelRevisi.R006Clawmeat as RR006Clawmeat,TabelRevisi.ShellJumbo as RShellJumbo,TabelRevisi.ShellLump as RShellLump,TabelRevisi.ShellBackfin as RShellBackfin,TabelRevisi.ShellSpesial as RShellSpesial,TabelRevisi.ShellClawmeat as RShellClawmeat,TabelRevisi.HargaJbColosal as RHargaJbColosal,TabelRevisi.HargaJumbo as RHargaJumbo,TabelRevisi.HargaJUS as RHargaJUS,TabelRevisi.HargaJUSB as RHargaJUSB,TabelRevisi.HargaBackfin as RHargaBackfin,TabelRevisi.HargaLump as RHargaLump,TabelRevisi.HargaSuperLump as RHargaSuperLump,TabelRevisi.HargaSpesial as RHargaSpesial,TabelRevisi.HargaClawUtuh as RHargaClawUtuh,TabelRevisi.HargaClawHancur as RHargaClawHancur,TabelRevisi.HargaClawCarpus as RHargaClawCarpus,TabelRevisi.HargaCF as RHargaCF,TabelRevisi.HargaAddJbColosal as RHargaAddJbColosal,TabelRevisi.HargaAddJumbo as RHargaAddJumbo,TabelRevisi.HargaAddJUS as RHargaAddJUS,TabelRevisi.HargaAddBackfin as RHargaAddBackfin,TabelRevisi.HargaAddLump as RHargaAddLump,TabelRevisi.HargaAddSuperLump as RHargaAddSuperLump,TabelRevisi.HargaAddSpesial as RHargaAddSpesial,TabelRevisi.HargaAddClawmeat as RHargaAddClawmeat,TabelRevisi.HargaBJumbo as RHargaBJumbo,TabelRevisi.HargaBBackfin as RHargaBBackfin,TabelRevisi.HargaBLump as RHargaBLump,TabelRevisi.HargaBSpesial as RHargaBSpesial,TabelRevisi.HargaBClawmeat as RHargaBClawmeat,TabelRevisi.HargaRijekPutih as RHargaRijekPutih,TabelRevisi.HargaRijekMerah as RHargaRijekMerah,TabelRevisi.RijekKembaliJumbo as RRijekKembaliJumbo,TabelRevisi.RijekKembaliLump as RRijekKembaliLump,TabelRevisi.RijekKembaliBackfin as RRijekKembaliBackfin,TabelRevisi.RijekKembaliSpesial as RRijekKembaliSpesial,TabelRevisi.RijekKembaliClawmeat as RRijekKembaliClawmeat', $ctanggal, $cnama);
        if ($arsuppl) {
            foreach ($arsuppl as $asup) {
                $rctglb = $asup['RTanggalTerima'];
                $rcnama = $asup['SSuplayer'];
                $rcarea = $asup['SArea'];
                $rckode = '';
                $arsupbr = $this->gudangcrypt->cekdetsuppbaru($rcarea, $rcnama);
                if ($arsupbr) {
                    foreach ($arsupbr as $bsup) {
                        $rckode = $bsup['idsupb'];
                        $rbseri = $bsup['idseri'];
                    }
                }

                $rctotbeli = $asup['RCanJbColosal']+$asup['RCanJumbo']+$asup['RCanJUS']+$asup['RCanJUSB']+$asup['RCanLump']+$asup['RCanBackfin']+$asup['RCanSuperLump']+$asup['RCanSpesial']+$asup['RCanClawmeatUtuh']+$asup['RCanClawmeatHancur']+$asup['RCanClawmeatCarpus']+$asup['RCanCF']+$asup['RCanLebihSpesial']+$asup['RCanLebihClawmeat']+$asup['RR001Jumbo']+$asup['RR001Lump']+$asup['RR001Backfin']+$asup['RR001Spesial']+$asup['RR001Clawmeat']+$asup['RR004Jumbo']+$asup['RR004Lump']+$asup['RR004Backfin']+$asup['RR004Spesial']+$asup['RR004Clawmeat']+$asup['RR005Jumbo']+$asup['RR005Lump']+$asup['RR005Backfin']+$asup['RR005Spesial']+$asup['RR005Clawmeat']+$asup['RR006Jumbo']+$asup['RR006Lump']+$asup['RR006Backfin']+$asup['RR006Spesial']+$asup['RR006Clawmeat'];
                $rctotrec = $asup['RPenluJumbo']+$asup['RPenluJUS']+$asup['RPenluBackfin']+$asup['RPenluLump']+$asup['RPenluSuperLump']+$asup['RPenluSpesial']+$asup['RPenluClawmeat']+$asup['RPenluCF'];
                $rctotprod = $asup['SCanJbColosal']+$asup['SCanJumbo']+$asup['SCanJUS1']+$asup['SCanJUS2']+$asup['SCanBfdariJumbo']+$asup['SCanFlakedariJumbo']+$asup['SCanLump']+$asup['SCanBfFlake']+$asup['SCanBfSmall']+$asup['SCanBfShrd']+$asup['SCanSpesialA']+$asup['SCanSpesialEx']+$asup['SCanSpesialPlus']+$asup['SCanClawmeatUtuh']+$asup['SCanClawmeatHancur']+$asup['SCanClawmeatCarpus']+$asup['SCanCF']+$asup['SAddJbColosal']+$asup['SAddJumbo']+$asup['SAddJUS']+$asup['SAddLump']+$asup['SAddBackfin']+$asup['SAddSpesialA']+$asup['SAddSpesialEx']+$asup['SAddSpesialPlus']+$asup['SAddClawmeat']+$asup['SR001Jumbo']+$asup['SR001Lump']+$asup['SR001Backfin']+$asup['SR001Spesial']+$asup['SR001Clawmeat']+$asup['SR004Jumbo']+$asup['SR004Lump']+$asup['SR004Backfin']+$asup['SR004Spesial']+$asup['SR004Clawmeat']+$asup['SR005Jumbo']+$asup['SR005Lump']+$asup['SR005Backfin']+$asup['SR005Spesial']+$asup['SR005Clawmeat']+$asup['SR006Jumbo']+$asup['SR006Lump']+$asup['SR006Backfin']+$asup['SR006Spesial']+$asup['SR006Clawmeat']+$asup['SRijekKembaliJumbo']+$asup['SRijekKembaliLump']+$asup['SRijekKembaliBackfin']+$asup['SRijekKembaliSpesial']+$asup['SRijekKembaliClawmeat'];
                $rctotshell = $asup['SShellJumbo']+$asup['SShellLump']+$asup['SShellBackfin']+$asup['SShellSpesial']+$asup['SShellClawmeat']+$asup['SAirJumbo']+$asup['SAirLump']+$asup['SAirBackfin']+$asup['SAirSpesial']+$asup['SAirClawmeat'];

                if (strpos(strtolower($asup['SSuplayer']), 'rc') === false || strpos(strtolower($asup['SSuplayer']), 'rc') === false) {
                    if (strpos(strtolower($rcarea), 'adura') !== false && strpos(strtolower($rcarea), 'hz') === false) {
                        $rcarea = 'Madura';
                    } elseif (strpos(strtolower($rcarea), 'ateng') !== false && strpos(strtolower($rcarea), 'hz') === false) {
                        $rcarea = 'Jateng';
                    } elseif (strpos(strtolower($rcarea), 'embang') !== false && strpos(strtolower($rcarea), 'hz') === false) {
                        $rcarea = 'Jateng';
                    } elseif (strpos(strtolower($rcarea), 'atim') !== false && strpos(strtolower($rcarea), 'hz') === false) {
                        $rcarea = 'Jatim';
                    } elseif (strpos(strtolower($rcarea), 'alima') !== false) {
                        $rcarea = 'Kalimantan';
                    } elseif (strpos(strtolower($rcarea), 'kasar') !== false) {
                        $rcarea = 'Makasar';
                    } elseif (strpos(strtolower($rcarea), 'endari') !== false) {
                        $rcarea = 'Kendari';
                    } elseif (strpos(strtolower($rcarea), 'ntb') !== false) {
                        $rcarea = 'NTB';
                    } elseif (strpos(strtolower($rcarea), 'hz') !== false) {
                        $rcarea = 'HZ';
                    }
                } else {
                    $rcarea = 'HZ';
                }


                $subdatas = array(
                  'rcakses' => $rbakses,
                  'rckdsupp' => $rckode,
                  'rcarsupp' => $rcarea,
                  'rctgl_terima'=>$asup['RTanggalTerima'],
                  'rcsuplayer'=>$asup['SSuplayer'],
                  'rctgl_terima'=>$asup['RTanggalTerima'],
                  'rcajclkpr'=>$asup['SCanJbColosal'],
                  'rcajmbkpr'=>$asup['SCanJumbo']+$asup['SAddJumbo']+$asup['SAddJUS'],
                  'rcajsakpr'=>$asup['SCanJUS1'],
                  'rcajsbkpr'=>$asup['SCanJUS2']+$asup['SCanFlakedariJumbo']+$asup['SAddJbColosal'],
                  'rcabffkpr'=>$asup['SCanBfdariJumbo']+$asup['SCanBfFlake']+$asup['SAddBackfin'],
                  'rcabfskpr'=>$asup['SCanBfSmall'],
                  'rcalmpkpr'=>$asup['SCanLump']+$asup['SAddLump'],
                  'rcaspakpr'=>$asup['SCanSpesialA']+$asup['SAddSpesialA'],
                  'rcaspxkpr'=>$asup['SCanSpesialEx']+$asup['SAddSpesialEx'],
                  'rcaspskpr'=>$asup['SCanSpesialPlus']+$asup['SCanBfShrd']+$asup['SAddSpesialPlus'],
                  'rcaclukpr'=>$asup['SCanClawmeatUtuh'],
                  'rcaclhkpr'=>$asup['SCanClawmeatHancur']+$asup['SAddClawmeat'],
                  'rcaclskpr'=>$asup['SCanClawmeatCarpus'],
                  'rcaclckpr'=>$asup['SCanCF'],
                  'rcbjmbkpr'=>$asup['SR001Jumbo']+$asup['SR004Jumbo'],
                  'rcbbfnkpr'=>$asup['SR001Backfin']+$asup['SR004Backfin'],
                  'rcblmpkpr'=>$asup['SR001Lump']+$asup['SR004Lump'],
                  'rcbsplkpr'=>$asup['SR001Spesial']+$asup['SR004Spesial'],
                  'rcbclwkpr'=>$asup['SR001Clawmeat']+$asup['SR004Clawmeat'],
                  'rcrpthkpr'=>$asup['SR005Jumbo']+$asup['SR005Backfin']+$asup['SR005Lump']+$asup['SR005Spesial']+$asup['SR006Jumbo']+$asup['SR006Backfin']+$asup['SR006Lump']+$asup['SR006Spesial'],
                  'rcrmrhkpr'=>$asup['SR005Clawmeat']+$asup['SR006Clawmeat'],

                  'rcajclkbl'=>$asup['RCanJbColosal'],
                  'rcajmbkbl'=>$asup['RCanJumbo'],
                  'rcajsakbl'=>$asup['RCanJUS'],
                  'rcajsbkbl'=>$asup['RCanJUSB'],
                  'rcabffkbl'=>$asup['RCanBackfin'],
                  'rcabfskbl'=>$asup['RCanLump'],
                  'rcalmpkbl'=>$asup['RCanSuperLump'],
                  'rcaspakbl'=>$asup['RCanSpesial']+$asup['RCanLebihSpesial'],
                  'rcaspxkbl'=>0,
                  'rcaspskbl'=>0,
                  'rcaclukbl'=>$asup['RCanClawmeatUtuh'],
                  'rcaclhkbl'=>$asup['RCanClawmeatHancur']+$asup['RCanLebihClawmeat'],
                  'rcaclskbl'=>$asup['RCanClawmeatCarpus'],
                  'rcaclckbl'=>$asup['RCanCF'],
                  'rcbjmbkbl'=>$asup['RR001Jumbo']+$asup['RR004Jumbo'],
                  'rcbbfnkbl'=>$asup['RR001Backfin']+$asup['RR004Backfin'],
                  'rcblmpkbl'=>$asup['RR001Lump']+$asup['RR004Lump'],
                  'rcbsplkbl'=>$asup['RR001Spesial']+$asup['RR004Spesial'],
                  'rcbclwkbl'=>$asup['RR001Clawmeat']+$asup['RR004Clawmeat'],
                  'rcrpthkbl'=>$asup['RR005Jumbo']+$asup['RR005Backfin']+$asup['RR005Lump']+$asup['RR005Spesial']+$asup['RR006Jumbo']+$asup['RR006Backfin']+$asup['RR006Lump']+$asup['RR006Spesial'],
                  'rcrmrhkbl'=>$asup['RR005Clawmeat']+$asup['RR006Clawmeat'],

                  'rcajclprs'=>round(100*$asup['SCanJbColosal']/$rctotprod, 2),
                  'rcajmbprs'=>round((100*$asup['SCanJumbo']+$asup['SAddJumbo']+$asup['SAddJUS'])/$rctotprod, 2),
                  'rcajsaprs'=>round(100*$asup['SCanJUS1']/$rctotprod, 2),
                  'rcajsbprs'=>round((100*$asup['SCanJUS2']+$asup['SCanFlakedariJumbo']+$asup['SAddJbColosal'])/$rctotprod, 2),
                  'rcabffprs'=>round((100*$asup['SCanBfdariJumbo']+$asup['SCanBfFlake']+$asup['SAddBackfin'])/$rctotprod, 2),
                  'rcabfsprs'=>round(100*$asup['SCanBfSmall']/$rctotprod, 2),
                  'rcalmpprs'=>round((100*$asup['SCanLump']+$asup['SAddLump'])/$rctotprod, 2),
                  'rcaspaprs'=>round((100*$asup['SCanSpesialA']+$asup['SAddSpesialA'])/$rctotprod, 2),
                  'rcaspxprs'=>round((100*$asup['SCanSpesialEx']+$asup['SAddSpesialEx'])/$rctotprod, 2),
                  'rcaspsprs'=>round((100*$asup['SCanSpesialPlus']+$asup['SCanBfShrd']+$asup['SAddSpesialPlus'])/$rctotprod, 2),
                  'rcacluprs'=>round(100*$asup['SCanClawmeatUtuh']/$rctotprod, 2),
                  'rcaclhprs'=>round((100*$asup['SCanClawmeatHancur']+$asup['SAddClawmeat'])/$rctotprod, 2),
                  'rcaclsprs'=>round(100*$asup['SCanClawmeatCarpus']/$rctotprod, 2),
                  'rcaclcprs'=>round(100*$asup['SCanCF']/$rctotprod, 2),
                  'rcbjmbprs'=>round((100*$asup['SR001Jumbo']+$asup['SR004Jumbo'])/$rctotprod, 2),
                  'rcbbfnprs'=>round((100*$asup['SR001Backfin']+$asup['SR004Backfin'])/$rctotprod, 2),
                  'rcblmpprs'=>round((100*$asup['SR001Lump']+$asup['SR004Lump'])/$rctotprod, 2),
                  'rcbsplprs'=>round((100*$asup['SR001Spesial']+$asup['SR004Spesial'])/$rctotprod, 2),
                  'rcbclwprs'=>round((100*$asup['SR001Clawmeat']+$asup['SR004Clawmeat'])/$rctotprod, 2),

                  'rcajclhrg'=>$asup['RHargaJbColosal'],
                  'rcajmbhrg'=>$asup['RHargaJumbo'],
                  'rcajsahrg'=>$asup['RHargaJUS'],
                  'rcajsbhrg'=>$asup['RHargaJUSB'],
                  'rcabffhrg'=>$asup['RHargaBackfin'],
                  'rcalmphrg'=>$asup['RHargaLump'],
                  'rcaspahrg'=>$asup['RHargaSuperLump'],
                  'rcaspshrg'=>$asup['RHargaSpesial'],
                  'rcacluhrg'=>$asup['RHargaClawUtuh'],
                  'rcaclhhrg'=>$asup['RHargaClawHancur'],
                  'rcaclshrg'=>$asup['RHargaClawCarpus'],
                  'rcaclchrg'=>$asup['RHargaCF'],
                  'rcbjmbhrg'=>$asup['RHargaBJumbo'],
                  'rcbbfnhrg'=>$asup['RHargaBLump'],
                  'rcblmphrg'=>$asup['RHargaBLump'],
                  'rcbsplhrg'=>$asup['RHargaBSpesial'],
                  'rcbclwhrg'=>$asup['RHargaBClawmeat'],
                  'rcrpthhrg'=>$asup['RHargaRijekPutih'],
                  'rcrmrhhrg'=>$asup['RHargaRijekMerah'],

                  'rckg_prod'=>round($rctotprod, 2),
                  'rckg_pemb'=>round($rctotbeli, 2),
                  'rckg_reciv_tnp_shell'=>round($rctotrec-$rctotshell, 2),

                  'rckg_rjk_kembali'=>round($asup['SRijekKembaliJumbo']+$asup['SRijekKembaliBackfin']+$asup['SRijekKembaliLump']+$asup['SRijekKembaliSpesial']+$asup['SRijekKembaliClawmeat'], 2),
                );
                $this->gudangcrypt->tbh_drepo('osjrepc', $subdatas);
            }
        }
    }

    public function go_route($isine){
      $hsl_key = $this->gudangcrypt->routekey($isine,'e');
      echo $hsl_key;
    }


    public function repjrc()
    {
        $vtglaw = '';
        $vtglak = '';
        $vnama = '';
        $varea = '';
        $vakses = '';
        $cekakses = false;
        $vtglaw = $this->input->post('vtga');
        $vtglak = $this->input->post('vtgk');
        $vnama = $this->input->post('vcnm');
        $varea = $this->input->post('vcar');
        $vakses = $this->input->post('vkey');
        if ($vakses!='') {
            $cekakses = $this->gudangcrypt->gdatalog($vakses);
        }
        if ($cekakses) {
            $ctanggal = $vtglak==''?$vtglaw.$vtglaw:$vtglaw.$vtglak;
            if ($vtglaw!='') {
                $this->ssn_repdua($ctanggal, $vnama, $varea, $vakses);
                $hrepdua = $this->gudangcrypt->grepb($ctanggal, $vnama, $varea, $vakses);
                if ($hrepdua) {
                    $datarc = $this->gudangcrypt->grepb($ctanggal, $vnama, $varea, $vakses);
                    echo json_encode($datarc);
                } else {
                    echo 'ZONK';
                }
            }
        } else {
            echo 'BENTROK';
        }
    }

    public function detrepjrc()
    {
        $vdetail = $this->input->post('idrec');
        $vakses = get_cookie('kodeku');
        if (!$vdetail || $vdetail=='') {
            $vdetail = get_cookie('nobyr');
        }
        $hrepdua = $this->gudangcrypt->gdetrepb($vdetail, $vakses);
        if ($hrepdua) {
            echo json_encode($hrepdua);
        } else {
            echo 'ZONK';
        }
    }

    public function bukagerbang()
    {
        $ukey = $this->input->post('skey');
        $uclient = $this->input->post('scln');
        if ($ukey !='' && $uclient !='') {
            $rbakses = get_cookie('kodeku');
            $cnamacl = $this->gudangcrypt->cek_user($rbakses);
            if ($cnamacl) {
                $this->catet($cnamacl['clnama']." sukses login. Standby.... ");
            }
            $cesesnow = $this->gudangcrypt->lht_uses($ukey);
            if ($cesesnow) {
                $simses = array(
                'rxakses'=>$uclient,
                'rxstatus'=>'OPEN'
              );
                echo "OK";
                $this->gudangcrypt->upd_uses($ukey, $simses);
            }
        } else {
            echo 'ZONK!!!';
        }
    }

    public function killses()
    {
        $rbakses = get_cookie('kodeku');
        $cnamacl = $this->gudangcrypt->cek_user($rbakses);
        if ($cnamacl) {
            $this->catet($cnamacl['clnama']." clean logout.");
        }
        $this->gudangcrypt->del_univ('rbakses', $rbakses, 'osjrepb');
        $this->gudangcrypt->del_univ('rxakses', $rbakses, 'osjrepakses');
        delete_cookie('ci_session');
        delete_cookie('kodeku');
    }

    public function killmob()
    {
        $rbakses = $this->input->post('muser');
        $cnamacl = $this->gudangcrypt->cek_user($rbakses);
        if ($cnamacl) {
            $this->catet($cnamacl['clnama']." clean rekap RC.");
        }
        $this->gudangcrypt->del_univ('rbakses', $rbakses, 'osjrepb');
    }

    public function cekdesk()
    {
        $rbakses = $this->input->post('muser');
        $cdesk = $this->gudangcrypt->gdatalog($rbakses);
        if (!$cdesk) {
            echo 'OK';
        } else {
            echo 'Anda sedang aktif di desktop. Silahkan LOGOUT dulu.';
        }
    }
    public function genkode($kdparam = false)
    {
        $prcari = $this->input->post('vcari');
        if (!$kdparam) {
            $kdparam = get_cookie('ci_session');
        }
        $this->load->library('ciqrcode');
        $qr_image=$kdparam.'.png';
        $pgambar = FCPATH."sementara/".$qr_image;
        if (file_exists($pgambar)) {
            unlink($pgambar);
        }
        $params['data'] = $kdparam;
        $params['level'] = 'H';
        $params['size'] = 20;
        $params['savename'] =$pgambar;
        $this->ciqrcode->generate($params);
        $chats_gbr = "//wg.arsetontong.top/sementara/".$qr_image;
        if ($prcari!='') {
            $goqr = base64_encode(file_get_contents($pgambar));
            unlink($pgambar);
            echo $goqr;
            $cesesnow = $this->gudangcrypt->lht_uses($kdparam);
            if (!$cesesnow) {
                $simses = array(
                'rxkode'=>$kdparam
              );
                if (strlen($kdparam)>5) {
                    $this->gudangcrypt->tbh_uses($simses);
                }
            } else {
                $akses = $cesesnow['rxakses'];
                $kunci = $cesesnow['rxstatus'];
                if ($kunci == 'OPEN' && $akses !='') {
                    $this->gudangcrypt->del_cok($akses, $kdparam);
                    set_cookie('kodeku', $akses, '7200');
                    echo 'OK';
                }
            }
        } else {
            $goqr = base64_encode(file_get_contents($pgambar));
            unlink($pgambar);
            return $goqr;
        }
    }
    public function gerbang()
    {
        $this->load->view('dapurane');
    }
    public function repdua()
    {
        $rbakses = get_cookie('kodeku');
        $cnamacl = $this->gudangcrypt->cek_user($rbakses);
        if ($cnamacl) {
            $this->catet($cnamacl['clnama']." masuk repdua redirect ke gerbang");
        }
        $this->gerbang();
    }

    public function repmrc()
    {
        $this->load->view('semexcelrc');
    }

    public function repmrcnew()
    {
        $this->load->view('listmrc');
    }

    public function reppro()
    {
        $this->load->view('semexcelrc');
    }

    public function reppronew()
    {
        $this->load->view('listpro');
    }

    public function repprotest()
    {
        $this->load->view('listprotest');
    }

    public function ssn_repdua($ctanggal = false, $cnama = false, $carea = false, $vrakses = false)
    {
        $rbakses = $vrakses!=''?$vrakses:get_cookie('kodeku');
        $cnamacl = $this->gudangcrypt->cek_user($rbakses);
        if ($cnamacl) {
            $lgtgl = $ctanggal?(substr($ctanggal, 0, 2).'/'.substr($ctanggal, 2, 2).'/'.substr($ctanggal, 4, 4).'-'.substr($ctanggal, 8, 2).'/'.substr($ctanggal, 10, 2).'/'.substr($ctanggal, 12, 4)):'noRange';
            $lgnama = $cnama?$cnama:'noName';
            $lgarea = $carea?$carea:'noArea';
            $this->catet($cnamacl['clnama']." filter data Master RC dgn range ".$lgtgl." addon nama ".$lgnama." addon area ".$lgarea);
        }
        $this->gudangcrypt->del_univ('rbakses', $rbakses, 'osjrepb');
        $h1 = 1;
        $arsuppl = $this->gudangcrypt->cekdetsupplama('Area,Suplayer,TanggalTerima', $ctanggal, $cnama, $carea);
        if ($arsuppl) {
            foreach ($arsuppl as $asup) {
                $rbtglb = $asup['TanggalTerima'];
                $rbnama = $asup['Suplayer'];
                $rbarea = $asup['Area'];
                $rbkode = '';
                $arsupbr = $this->gudangcrypt->cekdetsuppbaru($rbarea, $rbnama);
                if ($arsupbr) {
                    foreach ($arsupbr as $bsup) {
                        $rbkode = $bsup['idsupb'];
                        $rbseri = $bsup['idseri'];
                    }
                }
                $ianotarea='';
                $ianotrcv='';
                $ianottgl=date('d-m-Y');
                $h2 = 0;
                $h3 = 0;
                $h4 = 0;
                $kgrm=0;
                $kgrc=0;
                $isusut=0;
                $sRcvJB=0;
                $sRcvJus=0;
                $sRcvBF=0;
                $sRcvLP=0;
                $sRcvReg=0;
                $sRcvSP=0;
                $sRcvCM=0;
                $sRcvCF=0;
                $sTotRcv=0;
                $ktgsort='';
                $kGACollosalb=0;
                $kGACollosalp=0;
                $kGAJumbob=0;
                $kGAJumbop=0;
                $kGAJus1b=0;
                $kGAJus1p=0;
                $kGAJUS2b=0;
                $kGAJUS2p=0;
                $kGABackfinb=0;
                $kGABackfinp=0;
                $kGASpesialAb=0;
                $kGASpesialAp=0;
                $kGALumpb=0;
                $kGALumpp=0;
                $kGABfSmallb=0;
                $kGABfSmallp=0;
                $kGASpesialXb=0;
                $kGASpesialXp=0;
                $kGASpesialBiasab=0;
                $kGASpesialBiasap=0;
                $kGAClawUtuhb=0;
                $kGAClawUtuhp=0;
                $kGAClawHancurb=0;
                $kGAClawHancurp=0;
                $kGAClawSayatb=0;
                $kGAClawSayatp=0;
                $kGACFb=0;
                $kGACFp=0;
                $kGBJumbob=0;
                $kGBJumbop=0;
                $kGBBackfinb=0;
                $kGBBackfinp=0;
                $kGBLumpb=0;
                $kGBLumpp=0;
                $kGBSpesialb=0;
                $kGBSpesialp=0;
                $kGBClawb=0;
                $kGBClawp=0;
                $kRJPutihb=0;
                $kRJPutihp=0;
                $kRJMerahb=0;
                $kRJMerahp=0;
                $kTotalKom=0;
                $sKomPlusMin=0;
                $sKomPerc=0;
                $sKomMNoSA=0;
                $sUplaf=0;
                $sUBel=0;
                $sPlusMin=0;
                $sAt=0;
                $jrendem =0;
                $arsjlokal = $this->susunsjlok($rbnama, $rbarea, $rbtglb);
                $arumeat = $this->susunuangmeat(false, $rbnama, $rbarea, $rbtglb);
                if ($arumeat) {
                    $sUBel = floatval($arumeat['TotalUangMeat'])+floatval($arumeat['Sterefom'])+floatval($arumeat['TotalBOP'])+floatval($arumeat['TotalTambahanLain'])+floatval($arumeat['TotalTransport']);
                    $ianotarea = $arumeat['KodeSuplayer'];
                    $ianotrcv = $arumeat['NoNota'];
                    $ianottgl = $arumeat['TanggalNota'];
                }

                $arubmi = $this->susunuangbmi($ianotrcv, $rbnama, $rbtglb);
                if ($arubmi) {
                    $sUplaf = $arubmi['UangBMI'];
                }

                if ($arsjlokal) {
                    foreach ($arsjlokal as $sjlok) {
//                        $rbtglb = $sjlok['TanggalTerima'];
                        $rbkgrm = floatval($sjlok['kgrm'])>0?floatval($sjlok['kgrm']):0;
                        $rbkgrc = floatval($sjlok['Reciv_A'])+floatval($sjlok['Reciv_B'])+floatval($sjlok['Reciv_C'])+floatval($sjlok['Reciv_D'])+floatval($sjlok['Reciv_E']);
                        $kgrm = $rbkgrm>0?$rbkgrm:0;
                        $kgrc = $rbkgrc>0?$rbkgrc:0;
                        $isusut = 0;
                        if ($rbkgrm>0) {
                            $isusut = abs(($rbkgrm-$rbkgrc)/$rbkgrm*100);
                        }
                        $h2++;
                    }
                    /*-----------------*/
                }

                $arsjalan = $this->susunsjalan(false, $rbnama, $rbarea, $rbtglb);
                if ($arsjalan) {
//                    foreach ($arsjalan as $sjln) {
                    $jreceiv = floatval($arsjalan['JB'])+floatval($arsjalan['JUS'])+floatval($arsjalan['BF'])+floatval($arsjalan['LP'])+floatval($arsjalan['REG'])+floatval($arsjalan['SP'])+floatval($arsjalan['CM'])+floatval($arsjalan['CF']);
                    if ($kgrc>0) {
                        $jrendem = ($jreceiv/$kgrc)*100;
                    }
                    $sRendm=$jrendem;
                    $sRcvJB=($arsjalan['JB'])>0?($arsjalan['JB']):0;
                    $sRcvJus=$arsjalan['JUS']>0?$arsjalan['JUS']:0;
                    $sRcvBF=$arsjalan['BF']>0?$arsjalan['BF']:0;
                    $sRcvLP=$arsjalan['LP']>0?$arsjalan['LP']:0;
                    $sRcvReg=$arsjalan['REG']>0?$arsjalan['REG']:0;
                    $sRcvSP=$arsjalan['SP']>0?$arsjalan['SP']:0;
                    $sRcvCM=$arsjalan['CM']>0?$arsjalan['CM']:0;
                    $sRcvCF=$arsjalan['CF']>0?$arsjalan['CF']:0;
                    $sTotRcv=$jreceiv;
                    $h3++;
//                    }
                }
                $arsortir = $this->susunsortir(false, $rbnama, $rbarea, $rbtglb);
                if ($arsortir) {
                    $sTotRcv = $sTotRcv<1?1:$sTotRcv;
//                    foreach ($arsortir as $asts) {
                    $ktgsort=$arsortir['TanggalSortir'];
                    $kGACollosalb=$arsortir['CanJbColosal'];
                    $kGACollosalp=100*($kGACollosalb/$sTotRcv);
                    $kGAJumbob=$arsortir['CanJumbo'];
                    $kGAJumbop=100*($kGAJumbob/$sTotRcv);
                    $kGAJus1b=$arsortir['CanJUS1'];
                    $kGAJus1p=100*($kGAJus1b/$sTotRcv);
                    $kGAJUS2b=$arsortir['CanJUS2']+$arsortir['CanFlakedariJumbo']+$arsortir['AddJbColosal'];
                    $kGAJUS2p=100*($kGAJUS2b/$sTotRcv);
                    $kGABackfinb=$arsortir['CanBFdariJumbo']+$arsortir['CanBFFlake']+$arsortir['AddJumbo']+$arsortir['AddBackfin']+$arsortir['AddJUS'];
                    $kGABackfinp=100*($kGABackfinb/$sTotRcv);
                    $kGASpesialAb=$arsortir['CanSpesialA']+$arsortir['AddSpesialA'];
                    $kGASpesialAp=100*($kGASpesialAb/$sTotRcv);
                    $kGALumpb=$arsortir['CanLump']+$arsortir['AddLump'];
                    $kGALumpp=100*($kGALumpb/$sTotRcv);
                    $kGABfSmallb=$arsortir['CanBFSmall'];
                    $kGABfSmallp=100*($kGABfSmallb/$sTotRcv);
                    $kGASpesialXb=$arsortir['CanSpesialEx']+$arsortir['AddSpesialEx'];
                    $kGASpesialXp=100*($kGASpesialXb/$sTotRcv);
                    $kGASpesialBiasab=$arsortir['CanSpesialPlus']+$arsortir['AddSpesialPlus']+$arsortir['CanBFShrd'];
                    $kGASpesialBiasap=100*($kGASpesialBiasab/$sTotRcv);
                    $kGAClawUtuhb=$arsortir['CanClawmeatUtuh'];
                    $kGAClawUtuhp=100*($kGAClawUtuhb/$sTotRcv);
                    $kGAClawHancurb=$arsortir['CanClawmeatHancur']+$arsortir['AddClawmeat'];
                    $kGAClawHancurp=100*($kGAClawHancurb/$sTotRcv);
                    $kGAClawSayatb=$arsortir['CanClawmeatCarpus'];
                    $kGAClawSayatp=100*($kGAClawSayatb/$sTotRcv);
                    $kGACFb=$arsortir['CanCF'];
                    $kGACFp=100*($kGACFb/$sTotRcv);
                    $kGBJumbob=$arsortir['Bjumbo']+$arsortir['R001Jumbo']+$arsortir['R004Jumbo'];
                    $kGBJumbop=100*($kGBJumbob/$sTotRcv);
                    $kGBBackfinb=$arsortir['Bbackfin']+$arsortir['R001Backfin']+$arsortir['R004Backfin'];
                    $kGBBackfinp=100*($kGBBackfinb/$sTotRcv);
                    $kGBLumpb=$arsortir['Blump']+$arsortir['R001Lump']+$arsortir['R004Lump'];
                    $kGBLumpp=100*($kGBLumpb/$sTotRcv);
                    $kGBSpesialb=$arsortir['Bspesial']+$arsortir['R001Spesial']+$arsortir['R004Spesial'];
                    $kGBSpesialp=100*($kGBSpesialb/$sTotRcv);
                    $kGBClawb=$arsortir['Bclawmeat']+$arsortir['R001Clawmeat']+$arsortir['R004Clawmeat'];
                    $kGBClawp=100*($kGBClawb/$sTotRcv);
                    $kRjKJumbo=$arsortir['RijekKembaliJumbo'];
                    $kRjKBackfin=$arsortir['RijekKembaliBackfin'];
                    $kRjKLump=$arsortir['RijekKembaliLump'];
                    $kRjKSpesial=$arsortir['RijekKembaliSpesial'];
                    $kRjKClawmeat=$arsortir['RijekKembaliClawmeat'];
                    $kRJPutihb=$arsortir['R005Jumbo']+$arsortir['R005Backfin']+$arsortir['R005Lump']+$arsortir['R005Spesial']+$arsortir['R006Jumbo']+$arsortir['R006Backfin']+$arsortir['R006Lump']+$arsortir['R006Spesial'];
                    $kRJPutihp=100*($kRJPutihb/$sTotRcv);
                    $kRJMerahb=$arsortir['R005Clawmeat']+$arsortir['R006Clawmeat'];
                    $kRJMerahp=100*($kRJMerahb/$sTotRcv);
                    $sKomS=$arsortir['ShellJumbo']+$arsortir['ShellLump']+$arsortir['ShellBackfin']+$arsortir['ShellSpesial']+$arsortir['ShellClawmeat'];
                    $sKomA=$arsortir['AirJumbo']+$arsortir['AirLump']+$arsortir['AirBackfin']+$arsortir['AirSpesial']+$arsortir['AirClawmeat'];
                    $kTotalKom=$sKomS+$sKomA+$kGACollosalb+$kGAJumbob+$kGAJus1b+$kGAJUS2b+$kGABackfinb+$kGASpesialAb+$kGALumpb+$kGABfSmallb+$kGASpesialXb+$kGASpesialBiasab+$kGAClawUtuhb+$kGAClawHancurb+$kGAClawSayatb+$kGACFb+$kGBJumbob+$kGBBackfinb+$kGBLumpb+$kGBSpesialb+$kGBClawb+$kRJPutihb+$kRJMerahb;
                    $sKomPlusMin=($sTotRcv-$kTotalKom);

                    $sKomPerc=$sTotRcv>0?abs($sKomPlusMin/($sKomPlusMin+$sTotRcv)):0;
                    $sKomSA=$sKomS+$sKomA;
                    $sKomMNoSA=$sKomPlusMin;
                    $sPlusMin=0;
                    $sAt=0;
                    $h4++;
//                    }
                    /*-----------------*/
                }
                $kTotalKom = $kTotalKom>0?$kTotalKom:1;
                $sKomMNoSA = $sKomMNoSA>0?$sKomMNoSA:1;

                $datas  = array(
                  'rburut'=>$h1++,
                  'rbTglTerima'=>$rbtglb,
                  'rbakses'=>$rbakses,
                  'rbkdsupp'=>$rbkode,
                  'rbNmSupp'=>$rbnama,
                  'rbArea'=>$rbarea,
                  'rbKgRM'=>$kgrm,
                  'rbKgRC'=>$kgrc,
                  'rbSusut'=>$isusut,
                  'rbRendm'=>$jrendem,
                  'rbRcvJB'=>$sRcvJB,
                  'rbRcvJus'=>$sRcvJus,
                  'rbRcvBF'=>$sRcvBF,
                  'rbRcvLP'=>$sRcvLP,
                  'rbRcvReg'=>$sRcvReg,
                  'rbRcvSP'=>$sRcvSP,
                  'rbRcvCM'=>$sRcvCM,
                  'rbRcvCF'=>$sRcvCF,
                  'rbTotRcv'=>$sTotRcv,
                  'rbGACollosalb'=>$kGACollosalb,
                  'rbGACollosalp'=>$kGACollosalp,
                  'rbGAJumbob'=>$kGAJumbob,
                  'rbGAJumbop'=>$kGAJumbop,
                  'rbGAJus1b'=>$kGAJus1b,
                  'rbGAJus1p'=>$kGAJus1p,
                  'rbGAJUS2b'=>$kGAJUS2b,
                  'rbGAJUS2p'=>$kGAJUS2p,
                  'rbGABackfinb'=>$kGABackfinb,
                  'rbGABackfinp'=>$kGABackfinp,
                  'rbGASpesialAb'=>$kGASpesialAb,
                  'rbGASpesialAp'=>$kGASpesialAp,
                  'rbGALumpb'=>$kGALumpb,
                  'rbGALumpp'=>$kGALumpp,
                  'rbGABfSmallb'=>$kGABfSmallb,
                  'rbGABfSmallp'=>$kGABfSmallp,
                  'rbGASpesialXb'=>$kGASpesialXb,
                  'rbGASpesialXp'=>$kGASpesialXp,
                  'rbGASpesialBiasab'=>$kGASpesialBiasab,
                  'rbGASpesialBiasap'=>$kGASpesialBiasap,
                  'rbGAClawUtuhb'=>$kGAClawUtuhb,
                  'rbGAClawUtuhp'=>$kGAClawUtuhp,
                  'rbGAClawHancurb'=>$kGAClawHancurb,
                  'rbGAClawHancurp'=>$kGAClawHancurp,
                  'rbGAClawSayatb'=>$kGAClawSayatb,
                  'rbGAClawSayatp'=>$kGAClawSayatp,
                  'rbGACFb'=>$kGACFb,
                  'rbGACFp'=>$kGACFp,
                  'rbGBJumbob'=>$kGBJumbob,
                  'rbGBJumbop'=>$kGBJumbop,
                  'rbGBBackfinb'=>$kGBBackfinb,
                  'rbGBBackfinp'=>$kGBBackfinp,
                  'rbGBLumpb'=>$kGBLumpb,
                  'rbGBLumpp'=>$kGBLumpp,
                  'rbGBSpesialb'=>$kGBSpesialb,
                  'rbGBSpesialp'=>$kGBSpesialp,
                  'rbGBClawb'=>$kGBClawb,
                  'rbGBClawp'=>$kGBClawp,
                  'rbRjKJumbo'=>$kRjKJumbo,
                  'rbRjKBackfin'=>$kRjKBackfin,
                  'rbRjKLump'=>$kRjKLump,
                  'rbRjKSpesial'=>$kRjKSpesial,
                  'rbRjKClawmeat'=>$kRjKClawmeat,
                  'rbRJPutihb'=>$kRJPutihb,
                  'rbRJPutihp'=>$kRJPutihp,
                  'rbRJMerahb'=>$kRJMerahb,
                  'rbRJMerahp'=>$kRJMerahp,
                  'rbKomTot'=>$kTotalKom,
                  'rbKomPlusMin'=>$sKomPlusMin,
                  'rbKomPerc'=>$sKomPerc,
                  'rbKomSA'=>$sKomSA,
                  'rbKomMNoSA'=>$sKomMNoSA,
                  'rbUplaf'=>$sUplaf,
                  'rbUBel'=>$sUBel,
                  'rbPlusMin'=>$sUplaf-$sUBel,
                  'rbAt'=>(($sUplaf-$sUBel)/$sKomMNoSA)
              );
                $subdatas = array(
                  'arbuser' => $rbakses,
                  'arbkdsupp' => $rbkode,
                  'arbarsupp' => $rbarea,
                  'arbarnota' => $ianotarea,
                  'arbnorcv' => $ianotrcv,
                  'arbtglterima' => $rbtglb,
                  'arbtglsortir' => $ktgsort,
                  'arbtglnota' => $ianottgl,
                  'arbnosri' => $rbseri
                );
                $this->gudangcrypt->tbh_drepo('osjrepb', $datas);
                if ($rbkode!='') {
                    $caddrepb = $this->gudangcrypt->cekaddb($rbakses, $rbkode, $rbarea, $rbtglb);
                    if (!$caddrepb) {
                        $this->gudangcrypt->tbh_drepoadd('ins', 'osjaddrepb', $subdatas, '', '', '');
                    } else {
                        $this->gudangcrypt->tbh_drepoadd('upd', 'osjaddrepb', $subdatas, $rbkode, $rbarea, $rbtglb);
                    }
                }
            }
        }
    }
    public function susunuangbmi($ianotrcv, $rbnama, $rbtglb)
    {
        return $this->gudangcrypt->getuangbmi($ianotrcv, $rbnama, $rbtglb);
    }
    public function susunsjlok($rbnama, $rbarea, $rbtglb)
    {
        return $this->gudangcrypt->getsjlok($rbnama, $rbarea, $rbtglb);
    }
    public function susunsjalan($pardet = false, $rbnama=false, $rbarea=false, $rbtglb=false)
    {
        if (!$pardet) {
            return $this->gudangcrypt->getsjalan($pardet, $rbnama, $rbarea, $rbtglb);
        } else {
            $rbnama = $this->input->post('vanm');
            $rbarea = $this->input->post('vaar');
            $rbtglb = $this->input->post('vatg');
            echo json_encode($this->gudangcrypt->getsjalan($pardet, $rbnama, $rbarea, $rbtglb));
        }
    }
    public function susunuangmeat($pardet=false, $rbnama=false, $rbarea=false, $rbtglb=false)
    {
        if (!$pardet) {
            return $this->gudangcrypt->getuangmeat($pardet, $rbnama, $rbarea, $rbtglb);
        } else {
            $rbnama = $this->input->post('vanm');
            $rbarea = $this->input->post('vaar');
            $rbtglb = $this->input->post('vatg');
            if ($this->gudangcrypt->getuangmeat($pardet, $rbnama, $rbarea, $rbtglb)) {
                echo json_encode($this->gudangcrypt->getuangmeat($pardet, $rbnama, $rbarea, $rbtglb));
            } else {
                echo 'Double ZONK';
            }
        }
    }

    public function susunnota($pardet=false, $rbnama=false, $rbarea=false, $rbtglb=false)
    {
        if (!$pardet) {
            return $this->gudangcrypt->getuangmeat($pardet, $rbnama, $rbarea, $rbtglb);
        } else {
            $rbnama = $this->input->post('vanm');
            $rbarea = $this->input->post('vaar');
            $rbtglb = $this->input->post('vatg');
            echo json_encode($this->gudangcrypt->getuangmeat($pardet, $rbnama, $rbarea, $rbtglb));
        }
    }

    public function susunsortir($pardet=false, $rbnama=false, $rbarea=false, $rbtglb=false)
    {
        if (!$pardet) {
            return $this->gudangcrypt->getsortir($pardet, $rbnama, $rbarea, $rbtglb);
        } else {
            $rbnama = $this->input->post('vanm');
            $rbarea = $this->input->post('vaar');
            $rbtglb = $this->input->post('vatg');
            echo json_encode($this->gudangcrypt->getsortir($pardet, $rbnama, $rbarea, $rbtglb));
        }
    }

    public function repsatu()
    {
        $this->ssn_repsatu();
        $hrepsatu = $this->gudangcrypt->grepa('mob');
        if ($hrepsatu) {
            echo json_encode($hrepsatu);

//            $this->catet("Lihat data SJ");
        }
    }

    public function ssn_repsatu()
    {
        $this->gudangcrypt->cleartb('osjrepa');
        $abdaft = $this->up_notif('', '', '', '1');
        if ($abdaft) {
            foreach ($abdaft as $daft) {
                $isprma = 0;
                $isprmb = 0;
                $isprmc = 0;
                $isprca = 0;
                $isprcb = 0;
                $isprcc = 0;
                $imprca = 0;
                $imprcb = 0;
                $imprcc = 0;
                $tbop = 0;
                $lsnota = $this->gudangcrypt->gd_nota($daft['dfkode']);
                if ($lsnota) {
                    $tbop =0;
                    foreach ($lsnota as $dnota) {
                        $cdtsp = $this->gudangcrypt->cekdetsupp($daft['dfsupplier']);
                        if ($cdtsp) {
                            $dsuppnm = $cdtsp[0]['nama'];
                            $dsuppar = $cdtsp[0]['idwil'];
                        }
                        $cdthsp = $this->gudangcrypt->lshsupp($daft['dfsupplier'], $daft['dftglnota']);
                        if ($cdthsp) {
                            $hsuprma = $cdthsp[0]['Harga_RM_A'];
                            $hsuprmb = $cdthsp[0]['Harga_RM_B'];
                            $hsuprmc = $cdthsp[0]['Harga_RM_C'];
                            $hsuprca = $cdthsp[0]['Harga_RC_A'];
                            $hsuprcb = $cdthsp[0]['Harga_RC_B'];
                            $hsuprcc = $cdthsp[0]['Harga_RC_C'];
                        }
                        $ntkod  = $dnota['ntkode'];
                        $ntket  = $dnota['ntketer'];
                        $pnil   = $dnota['ntnilai'];
                        $pdev   = $dnota['ntclient'];
                        $vnota = substr($ntket, 0, 2);
                        if ($vnota == 'RM' || $vnota == 'RC') {
                            switch ($ntket) {
                                case 'RM Sedang':
                                    $isprmb = $pnil;
                                    break;
                                case 'RM Kecil':
                                    $isprmc = $pnil;
                                    break;
                                case 'RC Besar':
                                    if ($ntkod == 'SP') {
                                        $isprca = $pnil;
                                    } else {
                                        $imprca = $pnil;
                                    }
                                    break;
                                case 'RC Sedang':
                                    if ($ntkod == 'SP') {
                                        $isprcb = $pnil;
                                    } else {
                                        $imprcb = $pnil;
                                    }
                                    break;
                                case 'RC Kecil':
                                    if ($ntkod == 'SP') {
                                        $isprcc = $pnil;
                                    } else {
                                        $imprcc = $pnil;
                                    }
                                    break;
                                default:
                                    $isprma = $pnil;
                            }
                        } else {
                            $tbop = $tbop + $pnil;
                        }
                    }
                }
                $htotrm = $isprma+$isprmb+$isprmc;
                $htotrc = $isprca+$isprcb+$isprcc;
                $htotrcm = $imprca+$imprcb+$imprcc;
//                if($htotrm <=0){
//                    $htotrm = 1;
//                }
                $susutsp = ($htotrm>0)?(($htotrm - $htotrc) / $htotrm * 100):0;
                $susutmp = ($htotrm>0)?(($htotrm - $htotrcm) / $htotrm * 100):0;

                $data  = array(
                    'raclient' => $daft['dfclient'],
                    'ratglbeli' => $daft['dftglnota'],
                    'raksupp' => $daft['dfsupplier'],
                    'ranota' => $daft['dfkode'],
                    'rafoto' => $daft['dffoto'],
                    'rastatus' => $daft['dfkonfirm'],
                    'ransupp' => $dsuppnm,
                    'raarea' => $dsuppar,
                    'rasprma' => $isprma,
                    'rasprmb' => $isprmb,
                    'rasprmc' => $isprmc,
                    'rasprca' => $isprca,
                    'rasprcb' => $isprcb,
                    'rasprcc' => $isprcc,
                    'ramprca' => $imprca,
                    'ramprcb' => $imprcb,
                    'ramprcc' => $imprcc,
                    'rassts' => $susutsp,
                    'rasstm' => $susutmp,
                    'rahgrma' => $hsuprma,
                    'rahgrmb' => $hsuprmb,
                    'rahgrmc' => $hsuprmc,
                    'rahgrca' => $hsuprca>0?$hsuprca:$hsuprma,
                    'rahgrcb' => $hsuprcb>0?$hsuprcb:$hsuprmb,
                    'rahgrcc' => $hsuprcc>0?$hsuprcc:$hsuprmc,
                    'rabop' => $tbop
                );
                $this->gudangcrypt->tbh_drepo('osjrepa', $data);
            }
        }

        // INSERT INTO sjrepa(raclient,ranota,rafoto,ratglbeli,raksupp,rastatus) VALUES (?,?,?,?,?,?)
    }

    public function rkodedaftar($ulvl = false, $length = false)
    {
        $length = 4;
        if (!$ulvl) {
            $ulvl = $this->input->post('slvl');
        }
        $characters       = $ulvl . $ulvl . $ulvl . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $data = array(
            'clidentitas' => $ulvl . $randomString,
            'clstatus' => $ulvl
        );
        $this->gudangcrypt->tbh_user($data);

        $this->catet("CreateKey ".$ulvl.$randomString);

        echo 'Level Akses: ' . $ulvl . '| Kunci: ' . $ulvl . $randomString;
    }

    public function getpos()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if (strlen($ip) > 4) {
            $detip = $this->ip_details($ip);
        } else {
            $detip = 'LOKAL';
        }
        return $detip;
    }

    //$ipaddress = getClientIP();

    public function ip_details($ip)
    {
        $json = file_get_contents("http://ipinfo.io/{$ip}/geo");
        $details = json_decode($json, true);
        return $details;
    }


    public function kirimtel($isipesan = false, $untuk = false, $tparse = false)
    {
        $tparse = !$tparse?'markdown':'html';
        $token = "b0lncG5kWWU2V1ZpU29MR2xWbUFJNmFQSkhjR1lnR0xYaWRWYTkwZUdTZ2xqMHFydVF0d2Q4bmQzaG52dnlNUA==";
        $isipesan = str_replace('%2C', ',', $isipesan);
        $isipesan = str_replace('%20', ' ', $isipesan);
        $chid = $untuk; // -243776253 | 674868958 | -397911439

        $datat = [
'chat_id' => $chid,
'parse_mode' => $tparse,
'text' => urldecode($isipesan)
];

        file_get_contents("https://api.telegram.org/bot".$this->gudangcrypt->routekey($token,'d')."/sendMessage?" . http_build_query($datat));
    }


    public function catet($arMsg)
    {
        $dirname = "./dapur0";
        $tujuan  = $dirname . "/qnotes/log_".date('Ymd').".txt";

        $stEntry="";
        $arLogData['event_datetime']='['.date('D Y-m-d h:i:s A').'] [dari_IP '.$_SERVER['REMOTE_ADDR'].']';
        if (is_array($arMsg)) {
            foreach ($arMsg as $msg) {
                $stEntry.=$arLogData['event_datetime']." ".$msg.PHP_EOL;
            }
        } else {
            $stEntry.=$arLogData['event_datetime']." ".$arMsg.PHP_EOL;
        }

        $stCurLogFileName=$tujuan;
        $fHandler=fopen($stCurLogFileName, 'a+');

        fwrite($fHandler, $stEntry);

        fclose($fHandler);
    }

    public function simcok($coknm = false, $cokisi = false)
    {
        if (!$coknm) {
            $coknm = $this->input->post('nmcok');
            $cokisi = $this->input->post('nlcok');
        }
        $cekcok = $this->getcok($coknm);
        if ($cekcok != '') {
            set_cookie($coknm, $cokisi, '3600');
        } else {
            $this->delcok($coknm);
            set_cookie($coknm, $cokisi, '3600');
        }
    }

    public function getcok($coknm = false)
    {
        if (!$coknm) {
            $coknm = $this->input->post('nmcok');
        }
        get_cookie($coknm);
    }

    public function delcok($coknm = false)
    {
        if (!$coknm) {
            $coknm = $this->input->post('nmcok');
        }
        delete_cookie($coknm);
    }

    public function repcgroup()
    {
        $varea = '';
        $vakses = '';
        $varea = $this->input->post('vcar');
        $vakses = $this->input->post('vkey');
        $hreptiga = $this->gudangcrypt->ggrepc($varea, $vakses);
        if ($hreptiga) {
            $datarc = $this->gudangcrypt->ggrepc($varea, $vakses);
            echo json_encode($datarc);
        } else {
            echo 'ZONK';
        }
    }

    public function fillgrid()
    {
        $vtglaw = '';
        $vtglak = '';
        $vnama = '';
        $varea = '';
        $vakses = '';
        $cekakses = false;
        $vtglaw = $this->input->post('vtga');
        $vtglak = $this->input->post('vtgk');
        $vnama = $this->input->post('vcnm');
        $varea = $this->input->post('vcar');
        $vakses = $this->input->post('vkey');
        $ctanggal = $vtglak==''?$vtglaw.$vtglaw:$vtglaw.$vtglak;
        $list = $this->gudangcrypt->fillgrid($ctanggal, $vnama, $varea, $vakses);

        $data = array();
        $no = $_POST['start'];
        $nmsub = '';
        foreach ($list as $jurnal) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('d/m/Y', strtotime($jurnal->rctgl_terima));
            $row[] = $jurnal->rcsuplayer;
            $row[] = $jurnal->rcajclprs;
            $row[] = $jurnal->rcajmbprs;
            $row[] = $jurnal->rcajsaprs;
            $row[] = $jurnal->rcajsbprs;
            $row[] = $jurnal->rcabffprs;
            $row[] = $jurnal->rcabfsprs;
            $row[] = $jurnal->rcalmpprs;
            $row[] = $jurnal->rcaspaprs;
            $row[] = $jurnal->rcaspxprs;
            $row[] = $jurnal->rcaspsprs;
            $row[] = $jurnal->rcacluprs;
            $row[] = $jurnal->rcaclhprs;
            $row[] = $jurnal->rcaclsprs;
            $row[] = $jurnal->rcaclcprs;
            $row[] = $jurnal->rcbjmbprs;
            $row[] = $jurnal->rcbbfnprs;
            $row[] = $jurnal->rcblmpprs;
            $row[] = $jurnal->rcbsplprs;
            $row[] = $jurnal->rcbclwprs;
            $row[] = $jurnal->rcrpthprs;
            $row[] = $jurnal->rcrmrhprs;
            $row[] = $jurnal->rckg_reciv_tnp_shell;
            $row[] = $jurnal->rckg_prod;
            $row[] = $jurnal->rckg_pemb;
            $row[] = $jurnal->rcroot_bmi;
            $row[] = $jurnal->rcroot_pemb;
            $row[] = $jurnal->rcprod_prs;
            $row[] = $jurnal->rcbytotkg_transp;
            $row[] = $jurnal->rcbytotkg_sub;
            $row[] = $jurnal->rckg_prod_rcvg;
            $row[] = $jurnal->rckg_prod_pmbln;
            $row[] = $jurnal->rcpnl_root;
            $row[] = $jurnal->rcbytotnota_transp;
            $row[] = $jurnal->rcbytotnota_sub;
            $row[] = $jurnal->rcbiaya_armada;
            $row[] = $jurnal->rckg_rjk_kembali;
            $row[] = $jurnal->rcarsupp;
//            }
            $data[] = $row;
        }
        $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->gudangcrypt->count_all($ctanggal, $vnama, $varea, $vakses),
        "recordsFiltered" => $this->gudangcrypt->count_filtered($ctanggal, $vnama, $varea, $vakses),
        "data" => $data
    );
        echo json_encode($output);
    }
}
