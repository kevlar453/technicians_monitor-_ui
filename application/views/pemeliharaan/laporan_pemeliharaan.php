<!DOCTYPE html>
<html>

<head>
    <title>
        <?= $title_web; ?>
    </title>
    <style>
        .line-title {
            border: 0;
            border-style: inset;
            border-top: 1px solid #000;
        }

        .border-table {
            font-family: Arial, Helvetica, sans-serif;
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            font-size: 12px;
        }

        .border-table th {
            border: 1 solid #000;
            font-weight: bold;
            background-color: #e1e1e1;
        }

        .border-table td {
            border: 1 solid #000;
        }
    </style>
</head>

<body>
    <img src="<?= base_url('assets_style/image/holding.png') ?>" style="position: absolute; width: 80px; height: 50px; margin-top: 15px;">
    <img src="<?= base_url('assets_style/image/logo-ptpn7.png') ?>" style="position: absolute; width: 80px; height: auto; float: right;">
    <table style="width: 110%;">
        <tr>
            <td align="center">
                <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:150%; font-size:14pt;"><strong><span style="font-family:'Times New Roman';">PT. PERKEBUNAN NUSANTARA VII (PERSERO)</span></strong></p>
                <p style="margin-top:0pt; margin-bottom:0pt; text-align:center; line-height:150%;"><span style="font-family:Arial;">Jl. Teuku Umar No.300 Bandar Lampung - 35141 Provinsi Lampung - Indonesia <br>
                        Telp : 0721-702233, Email : sekretariat@ptpn7.com
                    </span></p>
            </td>
        </tr>
    </table>
    <?php
    $d = $this->db->query("SELECT * FROM tbl_user WHERE id_user = '$idbo'")->row();
    ?>
    <hr class="line-title">
    <p align="center">
        <b>LAPORAN PEMELIHARAAN KELAPA SAWIT</b>
    </p>
    <p align="center">
        <b>Unit <?php echo $d->asal_unit; ?></b>
    </p>
    <p align="center">
        Periode Tanggal <?= date('d F Y', strtotime($tanggal['tgl_awal']))  ?> s/d <?= date('d F Y', strtotime($tanggal['tgl_akhir']))  ?>
    </p>
    <table class="border-table">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Unit</th>
            <th>Kegiatan Pemeliharaan</th>
            <th>Luas</th>
            <th>Jumlah Tenaga Kerja</th>
        </tr>
        <?php $no = 1;
        foreach ($pemeliharaan as $isi) : ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= date('d M Y', strtotime($isi->tanggal)) ?></td>
                <td><?= $isi->lokasi ?></td>
                <td><?= $isi->nama_pemeliharaan ?></td>
                <td><?= $isi->luas ?></td>
                <td><?= $isi->jumlah_tenaga_kerja ?></td>
            </tr>
        <?php endforeach ?>
    </table>
    <?php
    $e = $this->db->query("SELECT nama_pic FROM tbl_unit WHERE nama_client = '$lokasi'")->row();
    ?>
    <div style="float:right;">
        <br>
        Bandar Lampung, <?= date('d F Y')  ?>
        <br>Kepala Unit <?php echo $d->asal_unit; ?> <br><br><br>
        <p><u><b><?php echo $e->nama_pic ?></b></u></p>
    </div>
</body>

</html>
