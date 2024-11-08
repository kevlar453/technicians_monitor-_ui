<?php if (!defined('BASEPATH')) exit('No direct script acess allowed'); ?>
<script>
    function fetch() {
        var get = document.getElementById("get").value;
        document.getElementById("put").value = get;
    }
</script>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-plus" style="color:green"> </i> Tambah Wilayah
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
            <li class="active"><i class="fa fa-plus"></i>&nbsp; Tambah Wilayah</li>
        </ol>
    </section>
    <section class="content">
        <?php if (!empty($this->session->flashdata())) {
            echo $this->session->flashdata('pesan');
        } ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="<?php echo base_url('wilayah/add'); ?>" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nama Wilayah</label>
                                        <input type="text" class="form-control" name="nama_wilayah" required="required" placeholder="Kota ABCDEF" id="nama_wilayah">
                                    </div>
                                    <div class="form-group">
                                        <label>Lambang Wilayah <small style="color:red">[.jpg|.jpeg]* wajib</small></label>
                                        <input type="file" accept="image/*" name="foto" onchange="readURL(this);">

                                        <br />
                                        <img src="" style="height:auto;width:210px;" class="img-responsive"  id="gambar" alt="#">
                                    </div>
                                    <div class="form-group">
                                        <label>Warna Pengenal</label>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="warna" id="put" class="form-control">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="color" id="get" onchange="fetch()">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Latitude</label>
                                        <input type="text" class="form-control" name="latitude" required="required" id="latitude">
                                    </div>
                                    <div class="form-group">
                                        <label>Longitude</label>
                                        <input type="text" class="form-control" name="longitude" required="required" id="longitude">
                                    </div>
                                    <div class="form-group">
                                        <label>GeoJSON</label>
                                        <textarea name="unit_geojson" rows="5" class="form-control" required="required" id="geojson"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="pull-right">
                                <button type="submit" class="btn btn-primary btn-md">Submit</button>
                        </form>
                        <a href="<?= base_url('wilayah'); ?>" class="btn btn-danger btn-md">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
      </section>
    </div>
<script type="text/javascript">
  var isilat = document.getElementById('latitude');
  var isilon = document.getElementById('longitude');
  var isigeojson = document.getElementById('geojson');

  isilat.onkeyup = isigeoj;
  isilon.onkeyup = isigeoj;

  function isigeoj(){
    var isigeo = '{\
    "type": "FeatureCollection",\
    "features": [\
      {\
        "type": "Feature",\
        "properties": {},\
        "geometry": {\
          "coordinates": ['+isilon.value+','+isilat.value+'],\
          "type": "Point"\
        }\
      }\
    ]\
  }';
  isigeojson.value = isigeo;
  }

  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $('#gambar').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }
  
  </script>
