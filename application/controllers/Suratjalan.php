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
        $gb_header = 'data:image/jpeg;base64,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';

        $isimsg = '<!DOCTYPE html>
        <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
        <head>
        	<meta charset="UTF-8">
        	<meta name="viewport" content="width=device-width,initial-scale=1">
        	<meta name="x-apple-disable-message-reformatting">
        	<title></title>
        	<!--[if mso]>
        	<noscript>
        		<xml>
        			<o:OfficeDocumentSettings>
        				<o:PixelsPerInch>96</o:PixelsPerInch>
        			</o:OfficeDocumentSettings>
        		</xml>
        	</noscript>
        	<![endif]-->
        	<style>
        		table, td, div, h1, p {font-family: Arial, sans-serif;}
        	</style>
        </head>
        <body style="margin:0;padding:0;">
        	<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
        		<tr>
        			<td align="center" style="padding:0;">
        				<table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
        					<tr>
        						<td align="center" style="padding:40px 0 30px 0;background:#70bbd9;">
                      <img src="'.$gb_header.'" alt="" width="300" style="height:auto;display:block;" />
        						</td>
        					</tr>
        					<tr>
        						<td style="padding:36px 30px 42px 30px;">
        							<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
        								<tr>
        									<td style="padding:0 0 36px 0;color:#153643;">
                            <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">Visitor Message</h1>
                            <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">'.$this->input->post('message').'</p>
        									</td>
        								</tr>
        							</table>
        						</td>
        					</tr>
        					<tr>
        						<td style="padding:30px;background:#ee4c50;">
        							<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
        								<tr>
        									<td style="padding:0;width:50%;" align="left">
        										<p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                              &reg; AR Setontong Official Website 2024<br/>'.$this->input->post('email').'
        										</p>
        									</td>
        									<td style="padding:0;width:50%;" align="right">
        									</td>
        								</tr>
        							</table>
        						</td>
        					</tr>
        				</table>
        			</td>
        		</tr>
        	</table>
        </body>
        </html>';

        // Email content
        $this->email->from('arsetontong@arsetontong.top', $this->input->post('name'));
        $this->email->to('ymakarius@gmail.com'); // Recipient's email
        $this->email->subject('Visitor AR Setontong');
        $this->email->message($isimsg);

        // Send email
        if ($this->email->send()) {
            echo 'Email sent successfully!';
            redirect('/');
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
