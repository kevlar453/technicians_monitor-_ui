<?php if (!defined('BASEPATH')) exit('No direct script acess allowed'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-edit" style="color:green"> </i> Daftar Absen
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
            <li class="active"><i class="fa fa-file-text"></i>&nbsp; Daftar Absen</li>
        </ol>
    </section>
    <section class="content">
        <?php if (!empty($this->session->flashdata())) {
            echo $this->session->flashdata('pesan');
        } ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <br />
                            <table id="example1" class="table table-bordered table-striped table" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Foto</th>
                                        <th>Teknisi</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Wilayah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($hadir as $isi) {
//                                       if(date('Y-m-d',strtotime($isi->absen_tgl)) == date('Y-m-d')) {
                                      ?>
                                        <tr>
                                            <td><?= $no; ?></td>
                                            <td>
                                                <center>
                                                    <?php if (!empty($isi->absen_fto !== "-")) { ?>
                                                        <img src="<?php echo 'data:image/png;base64,' . $isi->absen_fto; ?>" alt="#" class="img-responsive" style="height:auto;width:100px;" />
                                                    <?php } else { ?>
                                                        <!--<img src="" alt="#" class="user-image" style="border:2px solid #fff;"/>-->
                                                        <i class="fa fa-user fa-3x" style="color:#333;"></i>
                                                    <?php } ?>
                                                </center>
                                            </td>
                                            <td><?= $isi->nama; ?></td>
                                            <td><?= $isi->absen_tgl; ?></td>
                                            <td><?= $isi->absen_jam; ?></td>
                                            <td><?= $isi->asal_wilayah; ?></td>
                                        </tr>
                                    <?php $no++;
//                                    }
                                    }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
