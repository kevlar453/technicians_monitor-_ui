<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GudangCrypt extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('cookie', 'url'));
        $this->dbmain = $this->load->database('default', true);
//        $this->dbmonit = $this->load->database('monit', true);
//        $this->dbcirebon = $this->load->database('cirebon', true);
    }

    public $table = 'osjrepc';

    public $column_search = array('rcsuplayer','rcarsupp');
    public $column_order = array('rckdsupp',null);
    public $order = array('rcarsupp' => 'desc');

    public function mobsess($paridentitas = false){
      // create session
      $kode_com = '';

      // Loop through each character, selecting only odd-indexed elements
      for ($i = 0; $i < strlen($paridentitas); $i++) {
          // Check if the index is odd (considering 1-based positioning as "odd")
          if (($i % 2) == 0) {
              $kode_com .= $paridentitas[$i];
          }
      }
      $this->session->set_userdata('com_id', $kode_com);
    }



    public function cek_user($kyid = false)
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->join('tbl_perusahaan','tbl_perusahaan.com_kode=tbl_user.set_com');
        if (strlen($kyid)==12) {
            $this->db->where('clidentitas', $kyid);
        } else {
            $this->db->where('cluuid', $kyid);
        }
        $this->db->where('set_com',$this->session->userdata('com_id'));
        $jclient = $this->db->get();
        if ($jclient->num_rows() >0) {
            return $jclient->row_array();
        } else {
          return false;
        }
    }

    public function upd_user($vdata=false, $data=false)
    {
      $this->db->where('set_com',$this->session->userdata('com_id'));
        $this->db->where('UPPER(clidentitas)', strtoupper($vdata));
        return $this->db->update('tbl_user', $data);
    }

    public function lssupp($vids = false)
    {
        $this->db->select('*');
        $this->db->from('tasks');
        $this->db->where('title',$vids);
        $this->db->where('left(start_date,10)',date('Y-m-d'));
        $this->db->join('tbl_client','tbl_client.kode_client=tasks.description');
        $this->db->where('tasks.set_com',$this->session->userdata('com_id'));
        $qsupp = $this->db->get();
        if ($qsupp->result()) {
            return $qsupp->result();
        } else {
          return 0;
        }
    }

    public function getTeknisi($filterData = FALSE,$param = FALSE){
      $this->db->select('*');
      $this->db->from('tbl_user');
      if($filterData && $param == 1){
        $this->db->like('nama',$filterData);
      } else if($filterData && $param == 0){
        $this->db->where('clidentitas',$filterData);
      }
      $this->db->where('level','technician');
      $this->db->where('set_com',$this->session->userdata('com_id'));
      $this->db->order_by('nama','asc');
      $query = $this->db->get();
      return $query->result_array();
    }

    public function getClient($filterData = FALSE,$param = FALSE){
      $this->db->select('*');
      $this->db->from('tbl_client');
      if($filterData && $param == 1){
        $this->db->like('nama_client',$filterData);
      } else if($filterData && $param == 0){
        $this->db->where('kode_client',$filterData);
      }
      $this->db->where('set_com',$this->session->userdata('com_id'));
      $this->db->order_by('nama_client','asc');
      $query = $this->db->get();
      return $query->result_array();
    }


//++++++++++++++ SARING
    public function struktable($nmtable = false)
    {
        $fields = $this->db->list_fields($nmtable);

        return $fields;
    }

    public function ldatatable($nmtable = false)
    {
        $isitb = $this->db->field_data($nmtable);

        return $isitb;
    }

    public function cleartb($ntable = false)
    {
        $this->db->truncate($ntable);
    }

    public function gdatalog($vakss = false)
    {
        $this->db->select('*');
        $this->db->from('osjrepakses');
        if ($vakss) {
            if (strlen($vakss)==5) {
                $this->db->where('rxakses', $vakss);
                $this->db->where('rxstatus', 'OPEN');
            } else {
                $this->db->where('rxkode', $vakss);
            }
        } else {
            $this->db->join('osjclient', 'osjclient.clidentitas=osjrepakses.rxakses');
            $this->db->where('rxakses<>', '');
        }
        $qakses = $this->db->get();
        if ($vakss) {
            if ($qakses->result()) {
                return $qakses->row_array();
            } else {
                return false;
            }
        } else {
            if ($qakses->result()) {
                return $qakses->result_array();
            }
        }
    }

    public function mimpsjl($nsupp = false, $arsel = false)
    {
        $this->db->select($arsel);
        $this->db->from('SJLokal');
        $this->db->where('Suplayer', $nsupp);
        $qimp = $this->db->get();
        if ($qimp->result()) {
            return $qimp->result_array();
        }
    }

    public function mimpsjs($nsupp = false, $arsel = false)
    {
        $this->db->select($arsel);
        $this->db->from('SuratJalan');
        $this->db->where('Suplayer', $nsupp);
        $qimp = $this->db->get();
        if ($qimp->result()) {
            return $qimp->result_array();
        }
    }

    public function tbh_uses($data)
    {
        return $this->db->insert('osjrepakses', $data);
    }

    public function upd_uses($cdata, $data)
    {
        if (strlen($cdata) == 5) {
            $this->db->where('rxakses', $cdata);
        } else {
            $this->db->where('rxkode', $cdata);
        }
        $this->db->update('osjrepakses', $data);
    }

    public function lht_uses($cdata)
    {
        $this->db->where('rxkode', $cdata);
        $qses = $this->db->get('osjrepakses');
        if ($qses->num_rows()>0) {
            return $qses->row_array();
        } else {
            return false;
        }
    }

    public function grepa($tambil = false)
    {
        $bulan = date("m");
        $tahun = date("Y");
        $this->db->select('*');
        $this->db->from('osjrepa');
//        $this->db->where('MONTH(ratglbeli)',$bulan);
        $this->db->where('YEAR(ratglbeli)', $tahun);
//        $this->db->order_by('ratglbeli','asc');
//        $this->db->order_by('raarea','asc');
//        $this->db->order_by('ransupp','asc');
        $qitem = $this->db->get();
        if ($qitem->result()) {
            return $qitem->result_array();
        }
    }

    public function grepb($tambil = false, $vnama = false, $varea = false, $vakses = false)
    {
        $jtgl = strlen($tambil);
        $ctgl1 = substr($tambil, 4, 4).'-'.substr($tambil, 2, 2).'-'.substr($tambil, 0, 2);
        $ctgl2 = substr($tambil, 12, 4).'-'.substr($tambil, 10, 2).'-'.substr($tambil, 8, 2);

        $tangg = date("Y-m-d");
        $bulan = date("m");
        $tahun = date("Y");
        $this->db->select('*');
        $this->db->from('osjrepb');
        if ($jtgl==4) {
            $this->db->where('YEAR(rbTglTerima)', $sjtgl);
        } elseif ($jtgl==2) {
            $this->db->where('MONTH(rbTglTerima)', $sjtgl);
        } elseif ($jtgl==10) {
            $this->db->where('rbTglTerima', $sjtgl);
        } elseif ($jtgl == 16) {
            $this->db->where('rbTglTerima >=', $ctgl1);
            $this->db->where('rbTglTerima <=', $ctgl2);
        }
        if ($vnama || $vnama !='') {
            $this->db->like('rbNmSupp', $vnama);
        }
        if ($varea || $varea !='') {
            $this->db->like('rbArea', $varea);
        }
        if ($vakses || $vakses !='') {
            $this->db->where('rbakses', $vakses);
        }
        $this->db->order_by('rbTglTerima', 'desc');
        $this->db->order_by('rbNmSupp', 'asc');
        $this->db->order_by('rbArea', 'asc');
        $qitem = $this->db->get();
        if ($qitem->result()) {
            return $qitem->result_array();
        }
    }

    public function grepc($tambil = false, $vnama = false, $varea = false, $vakses = false)
    {
        $jtgl = strlen($tambil);
        $ctgl1 = substr($tambil, 4, 4).'-'.substr($tambil, 2, 2).'-'.substr($tambil, 0, 2);
        $ctgl2 = substr($tambil, 12, 4).'-'.substr($tambil, 10, 2).'-'.substr($tambil, 8, 2);

        $tangg = date("Y-m-d");
        $bulan = date("m");
        $tahun = date("Y");
        $this->db->select('*');
        $this->db->from('osjrepc');
        $this->db->where('rctgl_terima', $ctgl1);
        if ($varea || $varea !='') {
            $this->db->like('rcarsupp', $varea);
        }
        if ($vakses || $vakses !='') {
            $this->db->where('rcakses', $vakses);
        }
        $this->db->order_by('rctgl_terima', 'desc');
        $this->db->order_by('rcsuplayer', 'asc');
        $this->db->order_by('rcarsupp', 'asc');
        $qitem = $this->db->get();
        if ($qitem->result()) {
            return $qitem->result_array();
        }
    }

    public function ggrepc($varea = false, $vakses = false)
    {
        $this->db->select('rcsuplayer,rcarsupp,SUM(rcajclprs) as rcajclprs,SUM(rcajmbprs) as rcajmbprs,SUM(rcajsaprs) as rcajsaprs,SUM(rcajsbprs) as rcajsbprs,SUM(rcabffprs) as rcabffprs,SUM(rcabfsprs) as rcabfsprs,SUM(rcalmpprs) as rcalmpprs,SUM(rcaspaprs) as rcaspaprs,SUM(rcaspxprs) as rcaspxprs,SUM(rcaspsprs) as rcaspsprs,SUM(rcacluprs) as rcacluprs,SUM(rcaclhprs) as rcaclhprs,SUM(rcaclsprs) as rcaclsprs,SUM(rcaclcprs) as rcaclcprs,SUM(rcbjmbprs) as rcbjmbprs,SUM(rcbbfnprs) as rcbbfnprs,SUM(rcblmpprs) as rcblmpprs,SUM(rcbsplprs) as rcbsplprs,SUM(rcbclwprs) as rcbclwprs,SUM(rcrpthprs) as rcrpthprs,SUM(rcrmrhprs) as rcrmrhprs,SUM(rckg_reciv_tnp_shell) as rckg_reciv_tnp_shell,SUM(rckg_prod) as rckg_prod,SUM(rckg_pemb) as rckg_pemb,SUM(rcroot_bmi) as rcroot_bmi,SUM(rcroot_pemb) as rcroot_pemb,SUM(rcprod_prs) as rcprod_prs,SUM(rcbytotkg_transp) as rcbytotkg_transp,SUM(rcbytotkg_sub) as rcbytotkg_sub,SUM(rckg_prod_rcvg) as rckg_prod_rcvg,SUM(rckg_prod_pmbln) as rckg_prod_pmbln,SUM(rcpnl_root) as rcpnl_root,SUM(rcbytotnota_transp) as rcbytotnota_transp,SUM(rcbytotnota_sub) as rcbytotnota_sub,SUM(rcbiaya_armada) as rcbiaya_armada,SUM(rckg_rjk_kembali) as rckg_rjk_kembali');
        $this->db->from('osjrepc');
        $this->db->where('rcarsupp', $varea);
        $this->db->where('rcakses', $vakses);
        $this->db->group_by('rcarsupp');
        $qitem = $this->db->get();
        if ($qitem->result()) {
            return $qitem->result_array();
        }
    }

    public function getpl($tambil = false, $cvarh = false)
    {
        $jtgl = strlen($tambil);
        $ctgl1 = substr($tambil, 4, 4).'-'.substr($tambil, 2, 2).'-'.substr($tambil, 0, 2);
        $ctgl2 = substr($tambil, 12, 4).'-'.substr($tambil, 10, 2).'-'.substr($tambil, 8, 2);

        $this->db->select('HargaJbColosal,HargaJumbo,HargaJUS,HargaJUSB,HargaBackfin,HargaLump,HargaSuperLump,HargaBFSmall,HargaSpesial,HargaClawUtuh,HargaClawHancur,HargaClawCarpus,HargaCF,HargaAddJbColosal,HargaAddJumbo,HargaAddJUS,HargaAddBackfin,HargaAddLump,HargaAddSuperLump,HargaAddSpesial,HargaAddClawmeat,HargaBJumbo,HargaBBackfin,HargaBLump,HargaBSpesial,HargaBClawmeat,HargaRijekPutih,HargaRijekMerah');
        $this->db->from('DataSuplayer1');
        $this->db->where('NamaSuplayer', $cvarh);
        $this->db->where('DATE(TglAktif)<=', $ctgl1);
        $this->db->order_by('TglAktif', 'desc');
        $this->db->limit(1);
        $qitem = $this->db->get();
        if ($qitem->result()) {
            return $qitem->result_array();
        }
    }

    public function gdetrepb($kdambil = false, $kdident = false)
    {
        $this->db->select('*');
        $this->db->from('osjrepb');
        $this->db->join('osjsupplier', 'osjsupplier.idsupb=osjrepb.rbkdsupp');
        $this->db->join('osjaddrepb', 'osjaddrepb.arbkdsupp=osjrepb.rbkdsupp');
        $this->db->where('rbkdsupp', substr($kdambil, 0, 16));
        $this->db->where('rburut', substr($kdambil, 16));
        $this->db->where('rbakses', $kdident);
        $qitem = $this->db->get();
        if ($qitem->result()) {
            return $qitem->result_array();
        }
    }

    public function getuangbmi($ianotrcv = false, $smsupp = false, $tglsupp = false)
    {
        $this->db->select('UangBMI');
        $this->db->from('TabelData');
        $this->db->where('Suplayer', $smsupp);
        $this->db->where('TanggalTerima', $tglsupp);
        $this->db->where('NoNota', $ianotrcv);
        $qitem = $this->db->get();
        if ($qitem->result()) {
            return $qitem->row_array();
        }
    }
    public function getuangmeat($pardet = false, $smsupp = false, $rbarea=false, $tglsupp = false)
    {
        if ($pardet) {
            $this->db->select('TabelData.UangBMI,TabelRevisi.*');
        } else {
            $this->db->select('TotalUangMeat,Sterefom,TotalBOP,TotalTambahanLain,TotalTransport,KodeSuplayer,NoNota,TanggalNota,HargaJbColosal,HargaJumbo,HargaJUS,HargaJUSB,HargaBackfin,HargaLump,HargaSuperLump,HargaSpesial,HargaClawUtuh,HargaClawHancur,HargaClawCarpus,HargaCF,SaldoDP,SaldoPinjSarana ,SaldoKasKecil,SaldoTabungan,SaldoLebihDP ,SaldoLebihTransfer');
        }

        $this->db->from('TabelRevisi');
        if ($pardet) {
            $this->db->join('TabelData', 'TabelData.NoNota=TabelRevisi.NoNota');
        }
        $this->db->where('TabelRevisi.NamaSuplayer', $smsupp);
        $this->db->where('TabelRevisi.TanggalTerima', $tglsupp);
        $qitem = $this->db->get();
        if ($qitem->result()) {
            if ($pardet) {
                return $qitem->result_array();
            } else {
                return $qitem->row_array();
            }
        }
    }

    public function getsjlok($sjsupp = false, $sjare = false, $sjtgl = false)
    {
        $jtgl = strlen($sjtgl);
        $ctgl1 = substr($sjtgl, 4, 4).'-'.substr($sjtgl, 2, 2).'-'.substr($sjtgl, 0, 2);
        $ctgl2 = substr($sjtgl, 12, 4).'-'.substr($sjtgl, 10, 2).'-'.substr($sjtgl, 8, 2);

        $this->db->select('DISTINCT(Suplayer) as Suplayer,SJ_RC_Besar as kgrm,Reciv_A as Reciv_A,Reciv_B as Reciv_B,Reciv_C as Reciv_C,Reciv_D as Reciv_D,Reciv_E as Reciv_E,TanggalTerima');
        $this->db->from('SJLokal');
        $this->db->where('UPPER(Suplayer)', strtoupper($sjsupp));
        $this->db->where('UPPER(Area)', strtoupper($sjare));
        if ($jtgl==4) {
            $this->db->where('YEAR(TanggalTerima)', $sjtgl);
        } elseif ($jtgl==2) {
            $this->db->where('MONTH(TanggalTerima)', $sjtgl);
        } elseif ($jtgl==10) {
            $this->db->where('TanggalTerima', $sjtgl);
        } elseif ($jtgl == 16) {
            $this->db->where('TanggalTerima >=', $ctgl1);
            $this->db->where('TanggalTerima <=', $ctgl2);
        }
        $this->db->group_by('Suplayer');
        $this->db->group_by('Area');
        $this->db->group_by('TanggalTerima');
        $qitem = $this->db->get();
        if ($qitem->result()) {
            return $qitem->result_array();
        }
    }

    public function getsjalan($pardet = false, $sjsupp = false, $sjare = false, $sjtgl = false)
    {
        $jtgl = strlen($sjtgl);
        $ctgl1 = substr($sjtgl, 4, 4).'-'.substr($sjtgl, 2, 2).'-'.substr($sjtgl, 0, 2);
        $ctgl2 = substr($sjtgl, 12, 4).'-'.substr($sjtgl, 10, 2).'-'.substr($sjtgl, 8, 2);
        $this->db->select('SUM(PenluJumbo) as JB,SUM(PenluJUS) as JUS,SUM(PenluBackfin) as BF,SUM(PenluLump) as LP,SUM(PenluSuperLump) as REG,SUM(PenluSpesial) as SP,SUM(PenluClawmeat) as CM,SUM(PenluCF) as CF');
        $this->db->from('SuratJalan');
        $this->db->where('UPPER(Suplayer)', strtoupper(str_replace('+', ' ', $sjsupp)));
        $this->db->where('UPPER(Area)', strtoupper($sjare));
        if ($jtgl==4) {
            $this->db->where('YEAR(TanggalTerima)', $sjtgl);
        } elseif ($jtgl==2) {
            $this->db->where('MONTH(TanggalTerima)', $sjtgl);
        } elseif ($jtgl==10) {
            $this->db->where('TanggalTerima', $sjtgl);
        } elseif ($jtgl == 16) {
            $this->db->where('TanggalTerima >=', $ctgl1);
            $this->db->where('TanggalTerima <=', $ctgl2);
        }
        $this->db->group_by('Suplayer');
        $this->db->group_by('Area');
        $this->db->group_by('TanggalTerima');
        $qitem = $this->db->get();
        if ($qitem->result()) {
            if ($pardet) {
                return $qitem->result_array();
            } else {
                return $qitem->row_array();
            }
        }
    }

    public function getsortir($pardet = false, $sjsupp = false, $sjare = false, $sjtgl = false)
    {
        $jtgl = strlen($sjtgl);
        $ctgl1 = substr($sjtgl, 4, 4).'-'.substr($sjtgl, 2, 2).'-'.substr($sjtgl, 0, 2);
        $ctgl2 = substr($sjtgl, 12, 4).'-'.substr($sjtgl, 10, 2).'-'.substr($sjtgl, 8, 2);
        $blni = date("m")-1;
        $this->db->select('CanJbColosal,CanJumbo,CanJUS1,CanJUS2, CanBFdariJumbo,CanFlakedariJumbo,CanLump,CanBFFlake,CanBFSmall,CanBFShrd,CanSpesialA,CanSpesialEx, CanSpesialPlus,CanClawmeatMerus,CanClawmeatUtuh,CanClawmeatHancur,CanClawmeatCarpus,CanCF,Bjumbo,Blump,Bbackfin,Bspesial,Bclawmeat,R001Jumbo,R001Lump,R001Backfin,R001Spesial,R001Clawmeat,R004Jumbo,R004Lump,R004Backfin,R004Spesial,R004Clawmeat,R005Jumbo,R005Lump,R005Backfin,R005Spesial,R005Clawmeat,R006Jumbo,R006Lump, R006Backfin,R006Spesial,R006Clawmeat,ShellJumbo,ShellLump,ShellBackfin,ShellSpesial,ShellClawmeat,AirJumbo,AirLump,AirBackfin,AirSpesial, AirClawmeat, AddJbColosal, AddJumbo, AddJUS , AddLump, AddBackfin, AddSpesialA, AddSpesialEx , AddSpesialPlus, AddClawmeat, TanggalSortir,RijekKembaliJumbo,RijekKembaliLump,RijekKembaliBackfin,RijekKembaliSpesial,RijekKembaliClawmeat');
        $this->db->from('HasilSortir');
        $this->db->where('UPPER(Suplayer)', strtoupper($sjsupp));
        $this->db->where('UPPER(Area)', strtoupper($sjare));
        if ($jtgl) {
            $this->db->where('TanggalTerima', $sjtgl);
        }
        $this->db->group_by('Suplayer');
        $this->db->group_by('Area');
        $this->db->group_by('TanggalTerima');
        $qitem = $this->db->get();
        if ($qitem->result()) {
            if ($pardet) {
                return $qitem->result_array();
            } else {
                return $qitem->row_array();
            }
        }
    }

    public function ceksupppro($prmsupp = false, $sjtgl = false, $snama = false)
    {
        $jtgl = strlen($sjtgl);
        $ctgl1 = substr($sjtgl, 4, 4).'-'.substr($sjtgl, 2, 2).'-'.substr($sjtgl, 0, 2);
        $ctgl2 = substr($sjtgl, 12, 4).'-'.substr($sjtgl, 10, 2).'-'.substr($sjtgl, 8, 2);
        if ($prmsupp) {
            $this->db->select($prmsupp);
        } else {
            $this->db->select('*');
        }
        $this->db->from('HasilSortir');
        if ($prmsupp) {
            $this->db->join('TabelRevisi', 'TabelRevisi.NamaSuplayer=HasilSortir.Suplayer AND DATE(TabelRevisi.TanggalSortir)=DATE(HasilSortir.TanggalSortir)');
        }
        $this->db->where('HasilSortir.TanggalSortir', $ctgl1);
        if ($snama && $snama!='') {
            $this->db->like('HasilSortir.Suplayer', $snama);
        }
        $this->db->group_by('HasilSortir.Suplayer');
        $this->db->group_by('HasilSortir.Area');
        $this->db->group_by('HasilSortir.TanggalSortir');
        $qsupp = $this->db->get();
        if ($qsupp->result()) {
            return $qsupp->result_array();
        }
    }


    public function cekdetsupplama($prmsupp = false, $sjtgl = false, $snama = false, $sarea = false)
    {
        $jtgl = strlen($sjtgl);
        $ctgl1 = substr($sjtgl, 4, 4).'-'.substr($sjtgl, 2, 2).'-'.substr($sjtgl, 0, 2);
        $ctgl2 = substr($sjtgl, 12, 4).'-'.substr($sjtgl, 10, 2).'-'.substr($sjtgl, 8, 2);
        if ($prmsupp) {
            $this->db->select($prmsupp);
        } else {
            $this->db->select('*');
        }
        $this->db->from('HasilSortir');
        if ($jtgl==4) {
            $this->db->where('YEAR(TanggalTerima)', $sjtgl);
        } elseif ($jtgl==2) {
            $this->db->where('MONTH(TanggalTerima)', $sjtgl);
        } elseif ($jtgl==10) {
            $this->db->where('TanggalTerima', $sjtgl);
        } elseif ($jtgl == 16) {
            $this->db->where('TanggalTerima >=', $ctgl1);
            $this->db->where('TanggalTerima <=', $ctgl2);
        }
        if ($snama && $snama!='') {
            $this->db->like('Suplayer', $snama);
        }
        if ($sarea && $sarea!='') {
            $this->db->like('Area', $sarea);
        }
        $this->db->group_by('Suplayer');
        $this->db->group_by('Area');
        $this->db->group_by('TanggalTerima');
        $qsupp = $this->db->get();
        if ($qsupp->result()) {
            return $qsupp->result_array();
        }
    }

    public function cekdetsuppbaru($carea = false, $cnama = false)
    {
        $this->db->select('*');
        $this->db->from('osjsupplier');
        $this->db->where('idwil', $carea);
        $this->db->where('nama', $cnama);
        $qsupp = $this->db->get();
        if ($qsupp->result()) {
            return $qsupp->result_array();
        }
    }


    public function cekdetsupp($vids = false, $prmsupp = false)
    {
        if ($prmsupp) {
            $this->db->select($prmsupp);
        } else {
            $this->db->select('*');
        }
        $this->db->from('osjsupplier');

        $this->db->join('osjdsupplier', 'osjdsupplier.idsupb=osjsupplier.idsupb');
        if ($vids && $vids !='') {
            $this->db->where('osjsupplier.idsupb', $vids);
        }
        $this->db->order_by('idwil', 'desc');
        $this->db->order_by('nama', 'asc');
        $qsupp = $this->db->get();
        if ($qsupp->result()) {
            return $qsupp->result_array();
        }
    }



    public function cek_panit($cjen = false, $cket = false)
    {
        $this->db->select('pnnilai');
        $this->db->from('osjpanitera');
        $this->db->where('pnjenis', $cjen);
        $this->db->where('pnketer', $cket);
        $jclient = $this->db->get();
        if ($jclient) {
            return $jclient->row_array();
        }
    }

    public function cek_webuser($cjen = false)
    {
        $this->db->select('rxstatus');
        $this->db->from('osjrepakses');
        $this->db->where('rxakses', $cjen);
        $jclient = $this->db->get();
        if ($jclient) {
            return $jclient->row_array();
        }
    }

    public function del_user($kyid = false)
    {
        $this->db->where('clidentitas', $kyid);
        $this->db->delete('osjclient');
    }

    public function del_univ($klm = false, $vklm = false, $ntbl = false, $vcl = false)
    {
        if ($vcl) {
            if ($ntbl == 'osjdaft') {
                $this->db->where('dfclient', $vcl);
            } elseif ($ntbl == 'osjnota') {
                $this->db->where('ntclient', $vcl);
            }
        }
        $this->db->where($klm, $vklm);
        return $this->db->delete($ntbl);
    }

    public function del_cok($klm = false, $clm = false)
    {
        $this->db->where('rxakses', $klm);
        $this->db->where('rxkode<>', $clm);
        return $this->db->delete('osjrepakses');
    }

    public function upd_univ($data = false, $tbjen = false, $klm = false, $vklm = false, $vcl = false)
    {
        if ($vcl) {
            $this->db->where('dfclient', $vcl);
        }
        $this->db->where($klm, $vklm);
        return $this->db->update($tbjen, $data);
    }

    public function tbh_user($data)
    {
        return $this->db->insert('osjclient', $data);
    }

    public function tbh_dnota($data)
    {
        return $this->db->insert('osjdaft', $data);
    }

    public function tbh_drepo($gotable, $data)
    {
        return $this->db->insert($gotable, $data);
    }

    public function tbh_drepoadd($visi, $gotable, $data, $rbkode, $rbarea, $rbtglb)
    {
        if ($visi == 'ins') {
            return $this->db->insert($gotable, $data);
        } else {
            $this->db->where('arbkdsupp', $rbkode);
            $this->db->where('arbarsupp', $rbarea);
            $this->db->where('arbtglterima', $rbtglb);
            return $this->db->update($gotable, $data);
        }
    }

    public function tbh_nota($data)
    {
        return $this->db->insert('osjnota', $data);
    }

    public function tbh_rekap($data)
    {
        $this->db->insert('osjrekap', $data);
    }

    public function upd_rekap($data, $cdata)
    {
        $this->db->where('KdNota', $cdata);
        $this->db->update('osjrekap', $data);
    }

    public function cekaddb($rbakses=false, $rbkode=false, $rbarea=false, $rbtglb=false)
    {
        $this->db->select('*');
        $this->db->from('osjaddrepb');
        $this->db->where('arbuser', $rbakses);
        $this->db->where('arbkdsupp', $rbkode);
        $this->db->where('arbarsupp', $rbarea);
        $this->db->where('arbtglterima', $rbtglb);
        $qitem = $this->db->get();
        if ($qitem->result_array()) {
            return $qitem->row_array();
        }
    }

    public function cekrekap($kdnot = false)
    {
        $this->db->select('*');
        $this->db->from('osjrekap');
        if ($kdnot) {
            $this->db->where('KdNota', $kdnot);
        }
        $qitem = $this->db->get();
        if ($qitem->result()) {
            return $qitem->result_array();
        }
    }

    public function gd_dafsj($ctgdaft = false, $cpdev = false, $cpsup = false, $cpnmr = false)
    {
        $this->db->select('*');
        $this->db->from('osjdaft');
        if ($ctgdaft) {
            $this->db->where('left(dftanggal,10)', $ctgdaft);
        }
        if ($cpdev) {
            $this->db->where('dfclient', $cpdev);
            $this->db->where('dfsupplier', $cpsup);
            $this->db->where('dfnota', $cpnmr);
        }
        $qdaft = $this->db->get();
        if ($qdaft->result_array()) {
            return $qdaft->result_array();
        }
    }

    public function gd_nota($ckdnota = false)
    {
        $this->db->select('*');
        $this->db->from('osjnota');
        if ($ckdnota) {
            $this->db->where('ntnota', $ckdnota);
        }
        $qdaft = $this->db->get();
        if ($qdaft->result_array()) {
            return $qdaft->result_array();
        }
    }

    public function lsuser($kduser = false)
    {
        $this->db->select('*');
        $this->db->from('osjclient');
        $this->db->where('cluuid', $kduser);
        $qitem = $this->db->get();
        if ($qitem->result()) {
            return $qitem->result_array();
        }
    }

    public function lshsupp($ksup = false, $vtgtrm = false)
    {
        $this->db->select('TglAktif,idsupb,Harga_RM_A,Harga_RM_B,Harga_RM_C,Harga_RM_D,Harga_RC_A,Harga_RC_B,Harga_RC_C,Harga_RC_D');
        $this->db->from('osjdsupplier');
        $this->db->where('idsupb', $ksup);
        if ($vtgtrm) {
            $this->db->where('TglAktif<=', $vtgtrm);
        }
        $this->db->group_by('idsupb');
        $this->db->order_by('tglaktif', 'desc');
//        $this->db->limit(1);
        $qsupp = $this->db->get();
        if ($qsupp->result()) {
            return $qsupp->result_array();
        }
    }


    public function lsgrsupp()
    {
        $this->db->select('idwil,COUNT(idsupb) as jmsupp');
        $this->db->from('osjsupplier');
        $this->db->group_by('idwil');
        $this->db->order_by('idwil', 'asc');
        $qsupp = $this->db->get();
        if ($qsupp->result()) {
            return $qsupp->result();
        }
    }

    public function cek_supp($vids = false)
    {
        $this->db->select('*');
        $this->db->from('osjsupplier');
        $this->db->join('osjdsupplier', 'osjdsupplier.idsupb=osjsupplier.idsupb');
        $this->db->where('osjsupplier.idsupb', $vids);
        $this->db->order_by('idwil', 'desc');
        $this->db->order_by('nama', 'asc');
        $qsupp = $this->db->get();
        if ($qsupp->result()) {
            return $qsupp->result();
        }
    }


    public function lsitem()
    {
        $this->db->select('*');
        $this->db->from('osjitem');
        $qitem = $this->db->get();
        if ($qitem->result()) {
            return $qitem->result();
        }
    }
    // supp group start
    public function cleansupp()
    {
        $this->db->where('idwil IS NULL');
        $this->db->or_where('idwil', '');
        $this->db->or_where('idwil', '-');
        $this->db->delete('osjsupplier');
    }

    public function cleanharga()
    {
        $this->db->where('Harga_RM_A IS NULL');
        $this->db->where('Harga_RM_B IS NULL');
        $this->db->where('Harga_RM_C IS NULL');
        $this->db->where('Harga_RM_D IS NULL');
        $this->db->where('Harga_RC_A IS NULL');
        $this->db->where('Harga_RC_B IS NULL');
        $this->db->where('Harga_RC_C IS NULL');
        $this->db->where('Harga_RC_D IS NULL');
        $this->db->delete('osjdsupplier');
    }

    public function allgsupraw()
    {
        $this->db->select('Area as arsupp');
        $this->db->from('DataSuplayer1');
        $this->db->where('LENGTH(Area)>', 1);
        $this->db->group_by('Area');
        $qitem = $this->db->get();
        if ($qitem->result()) {
            return $qitem->result_array();
        }
    }

    public function cekgsup($ckode = false, $cjen = false)
    {
        if ($cjen) {
            $this->db->select('ctkode');
        } else {
            $this->db->select('*');
        }
        $this->db->from('osjwilcenter');
        if ($cjen) {
            $this->db->where('UPPER(ctarea)', $ckode);
        } else {
            $this->db->where('LEFT(ctkode,2)', $ckode);
            $this->db->group_by('LEFT(ctarea,2)');
        }
        $qitem = $this->db->get();
        if ($qitem->result()) {
            if ($cjen) {
                return $qitem->row_array();
            } else {
                return $qitem->num_rows();
            }
        }
    }

    public function tbh_gsupp($data)
    {
        return $this->db->insert('osjwilcenter', $data);
    }
    // group supp end


    public function tbh_srepd($data)
    {
        return $this->db->insert('osjrepd', $data);
    }


    // detail supp START
    public function allsupraw()
    {
        $this->db->select('MIN(DATE(TglAktif)) as tglaktif,Area as arsupp,NamaSuplayer as nmsupp,KodeSuplayer as kdsupp,NoRekening,NamaRekening,Bank,NPWP,HP,NoSeri');
        $this->db->from('DataSuplayer1');
        $this->db->where('LENGTH(Area) >', 1);
        $this->db->group_by('NamaSuplayer');
        $this->db->group_by('Area');
//        $this->db->order_by('TglAktif', 'desc');
        $qitem = $this->db->get();
        if ($qitem->result()) {
            return $qitem->result_array();
        }
    }

    public function allsuph($nsup = false, $asup = false)
    {
        $this->db->select('TglAktif as tglaktif,Area as arsupp,NamaSuplayer as nmsupp,KodeSuplayer as kdsupp,HargaRMA,HargaRMB,HargaRMC,HargaRMD,HargaRCA,HargaRCB,HargaRCC,HargaRCD');
        $this->db->from('DataSuplayer1');
        $this->db->where('UPPER(Area)', strtoupper($asup));
        $this->db->where('UPPER(NamaSuplayer)', strtoupper($nsup));
        $this->db->order_by('TglAktif', 'desc');
        $qitem = $this->db->get();
        if ($qitem->result()) {
            return $qitem->result_array();
        }
    }

    public function ceksup($ckode = false, $nama = false, $area = false)
    {
        $this->db->select('*');
        $this->db->from('osjsupplier');
        if ($nama) {
            $this->db->where('UPPER(nama)', $nama);
        }
        if ($area) {
            $this->db->where('UPPER(idwil)', $area);
        }
        if ($ckode) {
            $this->db->like('idsupb', $ckode);
        }
        $qitem = $this->db->get();
        if ($qitem->result()) {
            return $qitem->num_rows();
        }
    }

    public function tbh_dsupp($data)
    {
        return $this->db->insert('osjsupplier', $data);
    }

    public function tbh_hsupp($data)
    {
        return $this->db->insert('osjdsupplier', $data);
    }

    public function caridetdaft($cksupp = false, $ckode = false, $ctglnota = false, $cclient = false, $cvarsr = false)
    {
        $crsup = array();
        $this->db->select('*');
        $this->db->from('osjdaft');
//        $this->db->join('osjsupplier', 'osjsupplier.idsupb=osjdaft.dfsupplier');
//        $this->db->join('osjdsupplier', 'osjdsupplier.idsupb=osjsupplier.idsupb');
        if ($cksupp) {
            $this->db->where('dfsupplier', $cksupp);
            if ($ckode) {
                $this->db->where('dfkode', $ckode);
            }
            if ($cclient) {
                $this->db->where('dfclient', $cclient);
            }
            if ($ctglnota) {
                $this->db->where('dftglnota', $ctglnota);
            }
        }
        $this->db->order_by('dfurut', 'desc');
        $query = $this->db->get();
        $hcsup = $query->num_rows();
        if ($hcsup >= 1) {
            if ($cvarsr) {
                $crsup = $hcsup;
            } else {
                $crsup = $query->result_array();
            }
            return $crsup;
        } else {
            return false;
        }
    }

    public function caridetnota($ckode = false, $cclient = false)
    {
        $crsup = array();
        $this->db->select('*');
        $this->db->from('osjnota');
        if ($ckode) {
            $this->db->where('ntnota', $ckode);
            if ($cclient) {
                $this->db->where('ntclient', $cclient);
            }
        }
        $this->db->limit(1);
        $query = $this->db->get();
        $hcsup = $query->num_rows();
        if ($hcsup >= 1) {
            $crsup = $query->result_array();
            return $crsup;
        } else {
            return false;
        }
    }



    // detail supp END

    public function caridetsup($idsup = false)
    {
        $crsup = array();
        $this->dbmain->select('*');
        $this->dbmain->from('mst_spl');
        $this->dbmain->where('idsupb', $idsup);
        $query = $this->dbmain->get();
        $hcsup = $query->num_rows();
        if ($hcsup >= 1) {
            $crsup = $query->row_array();
            return $crsup;
        } else {
            return false;
        }
    }


    public function gdata($plant)
    {
        $this->dbmain->select('*');
        $this->dbmain->from('data_user');
        $this->dbmain->where('plant', $plant);
        $this->dbmain->where('dlevel', 2);
        $this->dbmain->group_by('dtimei');
        $qgdata = $this->dbmain->get();
        if ($qgdata->result_array()) {
            return $qgdata->result_array();
        }
    }

    public function gdatasp($idkor)
    {
        $this->dbmain->select('*');
        $this->dbmain->from('kirim_harian');
        $this->dbmain->where('tgkrm', date('Y-m-d', now()));
        $this->dbmain->where('dtimei', $idkor);
        $qgdatasp = $this->dbmain->get();
        if ($qgdatasp->result_array()) {
            return $qgdatasp->result_array();
        } else {
            return false;
        }
    }

    public function routekey($string = false, $action = 'e', $tbkey = false)
    {
        $secret_key = '12345abcdeCDE';
        $main_key = hash('sha256', $secret_key);
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = $main_key;
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
        if ($action=='e') {
            $output=base64_encode(openssl_encrypt($string, $encrypt_method, $tbkey?$tbkey:$key, 0, $iv));
        } elseif ($action=='d') {
            $output=openssl_decrypt(base64_decode($string), $encrypt_method, $tbkey?$tbkey:$key, 0, $iv);
        }
        return $output;
    }

    public function rdnum($length = false)
    {
        if (!$length) {
            $length = 10;
        }
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function rdchr($length = false)
    {
        if (!$length) {
            $length = 10;
        }
        $characters = '123456789QWERTYUIPLKJHGFDSZXCVBNM';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function count_all($ctanggal, $vnama, $varea, $vakses)
    {
        /*
                $cvar = strlen($jgmas);
                $ctrx = '';
                if ($cvar > 2) {
                    $jms = substr($jgmas, 0, 2);
                    $gmas = substr($jgmas, 2);
                    if (strlen($gmas)>0) {
                        $ctrx = $gmas;
                    }
                } else {
                    $jms = substr($jgmas, 0, 2);
                    $gmas = '';
                }
        */
        $this->db->from($this->table);
        $this->db->where('rcakses', $vakses);
        /*
                $artrans = array('5b','5c','6b','6c','7b','7c');
                if ($cvar > 2 && array_search($jms, $artrans) === false) {
                    $this->dbmain->where(array("substring_index(substring_index(Item,':',2),':',-1)" => $jms));
                }
                if ($ctrx != '') {
                    if (substr($jms, 0, 1)=='5') {
                        $this->dbmain->where(array('PO_No' => $ctrx));
                    } elseif (substr($jms, 0, 1)=='6') {
                        $this->dbmain->where(array('PPBJ_No' => $ctrx));
                    } else {
                        $this->dbmain->where(array('LPB_No' => $ctrx));
                    }
                }
        */
        return $this->db->count_all_results();
    }

    public function count_filtered($ctanggal, $vnama, $varea, $vakses)
    {
        $this->_get_datatables_query($ctanggal, $vnama, $varea, $vakses);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function fillgrid($ctanggal, $vnama, $varea, $vakses)
    {
        $this->_get_datatables_query($ctanggal, $vnama, $varea, $vakses);
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();
        return $query->result();

        exit;
    }

    public function _get_datatables_query($ctanggal, $vnama, $varea, $vakses)
    {
        /*
                $cvar = strlen($jgmas);
                $ctrx = '';
                if ($cvar > 2) {
                    $jmas = substr($jgmas, 0, 2);
                    $gmas = substr($jgmas, 2);
                    if (strlen($gmas)>0) {
                        $ctrx = $gmas;
                    }
                } else {
                    $jmas = substr($jgmas, 0, 2);
                    $gmas = '';
                }
        */
        $this->db->select('*');
        $this->db->from('osjrepc');
        $this->db->where('rcakses', $vakses);
        /*
                $artrans = array('5b','5c','6b','6c','7b','7c');
                if ($cvar > 2 && array_search($jmas, $artrans) === false) {
                    $this->dbmain->where(array("substring_index(substring_index(Item,':',2),':',-1)" => $gmas));
                }
                if ($ctrx != '') {
                    if (substr($jmas, 0, 1)=='5') {
                        $this->dbmain->where(array('PO_No' => $ctrx));
                    } elseif (substr($jmas, 0, 1)=='6') {
                        $this->dbmain->where(array('PPBJ_No' => $ctrx));
                    } else {
                        $this->dbmain->where(array('LPB_No' => $ctrx));
                    }
                }
        */

        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i===0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    /*grafik KPI START*/

    public function clarea1($idth = false, $idbl = false)
    {
        $this->db->select('DATE(TanggalTerima) as tgltrm');
        $this->db->from('TabelRevisi');
        if ($idth && $idbl) {
            $this->db->where('MONTH(TanggalTerima)', $idbl);
            $this->db->where('YEAR(TanggalTerima)', $idth);
        } else {
            $this->db->where('MONTH(TanggalTerima)', date('m'));
            $this->db->where('YEAR(TanggalTerima)', date('Y'));
        }
        $this->db->group_by('DATE(TanggalTerima)');
//        $this->db->group_by('Area');
        $qtgl = $this->db->get();
        if ($qtgl->result_array()) {
            return $qtgl->result();
        }
    }

    public function clarea2($idth = false, $idbl = false)
    {
        $this->db->select('SUM(PenluJumbo) as JB,SUM(PenluJUS) as JUS,SUM(PenluBackfin) as BF,SUM(PenluLump) as LP,SUM(PenluSuperLump) as REG,SUM(PenluSpesial) as SP,SUM(PenluClawmeat) as CM,SUM(PenluCF) as CF,SUM(CanJbColosal) as CJC,SUM(CanJumbo) as CJB,SUM(CanJUS) as CJ1,SUM(CanJUSB) as CJ2,SUM(CanLump) as CLP,SUM(CanBackfin) as CBF,SUM(CanSuperLump) as CLP,SUM(CanSpesial) as CSP,SUM(CanClawmeatUtuh) as CCU,SUM(CanClawmeatHancur) as CCH,SUM(CanClawmeatCarpus) as CCC,SUM(CanCF) as CCF,SUM(R001Jumbo) as 1JB,SUM(R001Lump) as 1LP,SUM(R001Backfin) as 1BF,SUM(R001Spesial) as 1SP,SUM(R001Clawmeat) as 1CM,SUM(R004Jumbo) as 4JB,SUM(R004Lump) as 4LP,SUM(R004Backfin) as 4BF,SUM(R004Spesial) as 4SP,SUM(R004Clawmeat) as 4CM,SUM(ShellJumbo) as SJB,SUM(ShellLump) as SLP,SUM(ShellBackfin) as SBF,SUM(ShellSpesial) as SSP,SUM(ShellClawmeat) as SCM,CAP, Area,NamaSuplayer as Suplayer');
        $this->db->from('TabelRevisi');
        if ($idth && $idbl) {
            $this->db->where('MONTH(TanggalTerima)', $idbl);
            $this->db->where('YEAR(TanggalTerima)', $idth);
        } else {
            $this->db->where('MONTH(TanggalTerima)', date('m'));
            $this->db->where('YEAR(TanggalTerima)', date('Y'));
        }
        $this->db->group_by('Area');
        $qdbt = $this->db->get();
        if ($qdbt->result_array()) {
            $isidar = $qdbt->result_array();
            return $isidar;
        } else {
            return false;
        }
    }

    public function clarea3($cuser = false)
    {
        $this->db->select('SUM(rdtbgra) as rdtbgra,rdtggra,SUM(rdtbgrb) as rdtbgrb,rdtggrb,SUM(rdtbrjk) as rdtbrjk, rdtgrjk,SUM(rdtbsel) as rdtbsel, rdtgsel,SUM(rdtbkbl) as rdtbkbl, rdtgkbl,SUM(rdtblan) as rdtblan, rdtglan,SUM(rdtbtot) as rdtbtot, rdtgtot,SUM(rdtbcap) as rdtbcap, rdtgcap,rdtgljum,rdtglawl,rdtglakr,rdketer');
        $this->db->from('osjrepd');
        $this->db->where('rduser', $cuser);
        $this->db->group_by('rdketer');
        $this->db->order_by('rdtbtot', 'desc');
        $qdbt = $this->db->get();
        if ($qdbt->result_array()) {
            $isidar = $qdbt->result_array();
            return $isidar;
        } else {
            return false;
        }
    }

    public function ctargetbeli($idkpi = false, $idarea = false, $idth = false, $idbl = false)
    {
        $crsup = array();
        $this->db->select('cbnama,cbtarget');
        $this->db->from('osjvarkpi');
        if ($idkpi) {
            $this->db->where('cbkpi', $idkpi);
        }
        if ($idth && $idbl) {
            $this->db->where('cbbulan', $idbl);
            $this->db->where('cbtahun', $idth);
        } else {
            $this->db->where('cbbulan', date('m'));
            $this->db->where('cbtahun', date('Y'));
        }
        $this->db->where('cbarea', $idarea);
        $query = $this->db->get();
        $hcsup = $query->num_rows();
        if ($hcsup >= 1) {
            $crsup = $query->row_array();
            return $crsup;
        } else {
            return false;
        }
    }


    /*grafik KPI END*/
}
