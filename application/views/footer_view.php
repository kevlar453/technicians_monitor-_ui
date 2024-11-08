<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="clearfix"></div>
<footer class="main-footer">
  <div id="mycredit"><strong> Copyright &copy; <?php echo date('Y'); ?> Air Conditioner Service Monitoring System
    </strong>


  </div>
</footer>

<div id="logout"></div>

<script src="<?php echo base_url(); ?>assets_style/assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url() ?>assets_style/assets/plugins/pace/pace.min.js"></script>
<script src="<?= base_url() ?>assets_style/leaflet/leaflet.js"></script>
<script src="<?php echo base_url(); ?>assets_style/assets/dist/js/sweetalert2.js"></script>
<script src="<?php echo base_url(); ?>assets_style/assets/dist/js/Control.Geocoder.js"></script>
<script src="<?php echo base_url(); ?>assets_style/assets/dist/js/easy-button.js"></script>
<script src="<?= base_url() ?>assets_style/leaflet/dist/Control.MiniMap.min.js"></script>
<script src="<?php echo base_url(); ?>assets_style/assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>assets_style/assets/bower_components/bootstrap/dist/js/bootstrap.js"></script>
<script src="<?php echo base_url(); ?>assets_style/assets/plugins/summernote/summernote-lite.js"></script>
<script src="<?php echo base_url(); ?>assets_style/assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- custom jQuery -->
<script src="<?php echo base_url(); ?>assets_style/assets/dist/js/custom.js"></script>

<!-- Logout Ajax -->
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets_style/assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url(); ?>assets_style/assets/dist/js/demo.js"></script>
<!-- PACE -->
<script src="<?php echo base_url(); ?>assets_style/assets/bower_components/PACE/pace.min.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url(); ?>assets_style/assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets_style/assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url(); ?>assets_style/assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url(); ?>assets_style/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- offline -->
<?php if($title_web == 'Pencarian Klien | ACS Monit'){?>
  <script src="<?= base_url() ?>assets_style/leaflet/leaflet.ajax.js"></script>
<?php }?>

<?php if($title_web == 'Jadwal Teknisi | ACS Monit'){?>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/locales-all.min.js"></script>

  <script>
    $(document).ready(function() {
        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
          locale: 'id',
          themeSystem: 'bootstrap',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
              },
              events: {
                url: '<?= site_url('jadwal/get_tasks') ?>',
                method: 'GET',
                failure: function() {
                  Swal.fire({
                    title: "Error!",
                    text: "Tidak dapat menampilkan jadwal!",
                    icon: "error"
                  });
                }
            },
            dateClick: function(info) {
                // Check if the selected date is in the past
                var selectedDate = new Date(info.dateStr);
                var today = new Date();
                today.setHours(0, 0, 0, 0);  // Set time to midnight for comparison

                if (selectedDate < today) {
                    Swal.fire({
                      title: "Error!",
                      text: "Hanya boleh menambah jadwal untuk sekarang atau akan datang!",
                      icon: "error"
                    });
                    return;  // Prevent opening the modal
                }

                $('#modalTitle').html('<b>Tambah Jadwal</b>');
                $('#taskId').val('');
                $('#taskForm')[0].reset();
                $('#start_date').val(info.dateStr + 'T00:00');
                $('#end_date').val(info.dateStr + 'T23:59');
                $('#deleteTaskBtn').hide();
                $('#isitek').attr('style','display:block');
                $('#isicli').attr('style','display:block');
                $('#taskModal').modal('show');
            },
            eventClick: function(info) {
                var task = info.event;
                var now = new Date();
                var st_tgl = new Date(task.start);
                st_tgl.setDate(st_tgl.getDate() + 1);
                var nd_tgl = new Date(task.end);
                nd_tgl.setDate(nd_tgl.getDate() + 1);
                console.log(task.start.toISOString().slice(0, 16));

                if (gantgl(task.start).substr(0,10) < gantgl(now).substr(0,10)) {
                    Swal.fire({
                      title: "Error!",
                      text: "Hanya boleh merubah jadwal sekarang atau akan datang!",
                      icon: "error"
                    });
                    return;  // Prevent opening the modal
                }

                $('#modalTitle').html('<b>Edit Jadwal</b>');
                $('#taskId').val(task.id);
                $('#title').val(task.title);
                $('#isitek').attr('style','display:none');
                $('#description').val(task.extendedProps.description);
                $('#isicli').attr('style','display:none');
                $('#start_date').val(st_tgl.toISOString().slice(0, 16));
                $('#end_date').val(nd_tgl.toISOString().slice(0, 16));
                $('#deleteTaskBtn').show();
                $('#taskModal').modal('show');
            },
            eventDidMount: function(info) {
            // Custom render the event with title and description
            if (info.event.extendedProps.description) {
                $(info.el).find('.fc-event-title').append('<br/><small style="color:green;font-weight:bolder;">' + info.event.extendedProps.description + '</small>');
            }
        }
        });
        calendar.render();

        // Save Task (Add/Update)
        $('#saveTaskBtn').click(function() {
            var taskId = $('#taskId').val();
            var startDate = new Date($('#start_date').val());
            var now = new Date();

            // Check if the start date is in the past
            if (gantgl(startDate).substr(0,10) < gantgl(now).substr(0,10)) {
                Swal.fire({
                  title: "Error!",
                  text: "Hanya boleh menyimpan jadwal sekarang atau akan datang!",
                  icon: "error"
                });
                return;
            }

            var url = taskId ? '<?= site_url('jadwal/update_task') ?>/' + taskId : '<?= site_url('jadwal/add_task') ?>';
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                  title:$('#title').val(),
                  description:$('#description').val(),
                  start_date:$('#start_date').val(),
                  end_date:$('#end_date').val()
                },
                success: function(response) {
                    Swal.fire({
                      title: "Good Job!",
                      text: "Jadwal tersimpan!",
                      icon: "success"
                    });
                    $('#taskId').val('');
                    $("#title").val('').trigger('change');
                    $("#description").val('').trigger('change');
                    $('#taskModal').modal('hide');
                    calendar.refetchEvents();
                },
                error: function() {
                    Swal.fire({
                      title: "Error!",
                      text: "Gagal menyimpan jadwal!",
                      icon: "error"
                    });
                    $('#taskId').val('');
                    $('#title').val('');
                    $('#description').val('');
                }
            });
        });

        // Delete Task
        $('#deleteTaskBtn').click(function() {
            var taskId = $('#taskId').val();
            var startDate = new Date($('#start_date').val());
            var now = new Date();

            // Check if the task start date is in the past
            if (gantgl(startDate).substr(0,10) < gantgl(now).substr(0,10)) {
                alert("You cannot delete tasks in the past: "+gantgl(startDate).substr(0,10));
                return;
            }

            if (confirm('Are you sure you want to delete this task?')) {
                $.ajax({
                    url: '<?= site_url('jadwal/delete_task') ?>/' + taskId,
                    method: 'POST',
                    success: function(response) {
                        Swal.fire({
                          title: "Good Job!",
                          text: "Jadwal berhasil dihapus!",
                          icon: "success"
                        });
                        $('#taskModal').modal('hide');
                        calendar.refetchEvents();
                    },
                    error: function() {
                        Swal.fire({
                          title: "Error!",
                          text: "Gagal menghapus jadwal!",
                          icon: "error"
                        });
                    }
                });
            }
        });
    });
$('.pilteknisi').select2({
  tags: true,
  multiple: true,
  tokenSeparators: [',', ' '],
  initSelection: function(element, callback) {
      },
placeholder: "Pilih Teknisi",
  ajax: {
    url: '/jadwal/listTeknisi',
    type: "post",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        searchTerm: params.term,
        param:1
      };
    },
    processResults: function (data) {
      return {
        results: $.map(data, function(obj) {
          return {
              id: obj.clidentitas,
              text: obj.nama
          };
        })
      };
    },
    cache: true
  }
});

$('.pilclient').select2({
  tags: true,
  multiple: false,
  tokenSeparators: [',', ' '],
  initSelection: function(element, callback) {
      },
placeholder: "Pilih Klien",
  ajax: {
    url: '/jadwal/listClient',
    type: "post",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        searchTerm: params.term,
        param:1
      };
    },
    processResults: function (data) {
      return {
        results: $.map(data, function(obj) {
          return {
              id: obj.kode_client,
              text: obj.nama_client
          };
        })
      };
    },
    cache: true
  }
});

$('#start_date').on('change',function(){
  $('#end_date').val($('#start_date').val());
});

  </script>

<?php }?>


<script type="text/javascript">
  $(document).ajaxStart(function() {
    Pace.restart();
  });
var table;

</script>

<?php if($title_web == 'Tambah Company | ACS Monit'){?>
  <script type="text/javascript">

    function genkodeid(parkodeid){
      $.ajax({
        url: '/suratjalan/getkodeid',
        type: 'POST',
        data: jQuery.param({
          kode_com: parkodeid
        })
      })
      .done(function(data) {
        $('#clidentitas').val(data);
        $('#pass').val(data);
      })
    }

      function readURL(input) {
          if (input.files && input.files[0]) {
            console.log(input.files);
            var isipic = 'p'+input.id;

              var reader = new FileReader();

              reader.onload = function (e) {
                $('#'+isipic).attr('src', e.target.result);
                console.log(e.target.result);
//                isipic.src = e.target.result;
              }

              reader.readAsDataURL(input.files[0]);
          }
      }
  </script>
<?php }?>


<?php if($title_web == 'Tambah User | ACS Monit'){?>
  <script type="text/javascript">
      $(document).ready(function() {
          $("#asal_wilayah").hide();
          tampil_wilayah();
      });

      function readURL(input) {
          if (input.files && input.files[0]) {
              var reader = new FileReader();

              reader.onload = function (e) {
                  $('#gambar').attr('src', e.target.result);
              }

              reader.readAsDataURL(input.files[0]);
          }
      }


      function tampil_wilayah() {
          $("#level").change(function() {
              var a = $("#level").val();

              if (a == "technician") {
                  $("#asal_wilayah").show();
              } else {
                  $("#asal_wilayah").hide();
              }
          });
      }
  </script>
<?php }?>

<?php if($title_web == 'Edit User | ACS Monit'){?>
  <script type="text/javascript">
      $(document).ready(function() {
        $("#asal_wilayah").hide();
        tampil_wilayah();
          <?php if ($user->level == 'technician') { ?>
              $("#asal_wilayah").show();
              tampil_wilayah();
          <?php } else { ?>
              $("#asal_wilayah").hide();
              tampil_wilayah();
          <?php } ?>

      });

      function readURL(input) {
          if (input.files && input.files[0]) {
              var reader = new FileReader();

              reader.onload = function (e) {
                  $('#gambar').attr('src', e.target.result);
              }

              reader.readAsDataURL(input.files[0]);
          }
      }


      function tampil_wilayah() {
          $("#level").change(function() {
              var a = $("#level").val();

              if (a == "technician") {
                  $("#asal_wilayah").show();
              } else {
                  $("#asal_wilayah").hide();
              }
          });
      }
  </script>
<?php }?>


<?php if($title_web == 'Dashboard | ACS Monit'){?>
  <script>
    var unit = L.layerGroup();
    var peta1 = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
        '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
      id: 'mapbox/streets-v11',
      accessToken: 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw'
    });

    var peta2 = L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
      attribution: 'google'
    });

    var peta3 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });

    var map = L.map('map', {
      center: [-2.4098402753312764, 122.551855487749],
      zoom: 5,
      layers: [peta3]
    });

    var baseLayers = {
      "Grayscale": peta1,
      "Satelite": peta2,
      "Streets": peta3
    };

    //Home Button
    var homebutton = L.easyButton('fa-home fa-lg', function() {
      map.setView([-2.4098402753312764, 122.551855487749], 7);
    }, 'home position', {
      position: 'topright'
    });
    L.control.layers(baseLayers).addTo(map);
    L.control.scale().addTo(map);
    homebutton.addTo(map);
    //Search
    L.Control.geocoder().addTo(map);

    //Minimap
    var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    var osmAttrib = 'Map data &copy; OpenStreetMap contributors';
    var osm2 = new L.TileLayer(osmUrl, {
      minZoom: 0,
      maxZoom: 13,
      attribution: osmAttrib
    });
    var miniMap = new L.Control.MiniMap(osm2, {
      toggleDisplay: true
    }).addTo(map);

    //Unit marker
    <?php if ($this->session->userdata('level') == 'technician') { ?>
      <?php foreach ($unit as $isi) { ?>
        L.marker([<?= $isi->latitude ?>, <?= $isi->longitude ?>], {
          icon: L.icon({
            iconUrl: '<?php echo 'data:image/png;base64,' .  $isi->foto;  ?>',
            iconSize: [40, 40],
          })
        }).addTo(map).on('click', function() {
          Swal.fire({
            imageUrl: '<?php echo 'data:image/png;base64,' .  $isi->foto; ?>',
            title: '<span class="text-uppercase">Nama Unit : <?= $isi->nama_client ?></span>',
            html: "Alamat : <?= $isi->alamat ?><br>Nama PIC : <?= $isi->nama_pic ?>",
            imageHeight: 180,
            showCancelButton: true,
            cancelButtonText: "Tutup",
            confirmButtonText: "Lihat Detail",
            confirmButtonColor: "Green"
          }).then((result) => {
            if (result.isConfirmed) {
              window.location = '<?= base_url('client/detail/' . $isi->id_unit) ?>';
            }
          })
        });
      <?php } ?>
    <?php } elseif ($this->session->userdata('level') == 'administrator' || $this->session->userdata('level') == 'manajemen') { ?>
      <?php foreach ($unit_admin as $isi) { ?>
        L.marker([<?= $isi->latitude ?>, <?= $isi->longitude ?>], {
          icon: L.icon({
            iconUrl: '<?php echo 'data:image/png;base64,' .  $isi->foto;  ?>',
            iconSize: [40, 40],
          })
        }).addTo(map)
        .bindPopup('<img src="<?php echo 'data:image/png;base64,' .  $isi->foto; ?>" style="object-fit: cover;width:10vw;height:10vw;"/><br/><span class="text-uppercase">Klien : \
          <?= $isi->nama_client ?></span><br/>Alamat : <?= $isi->alamat ?><br> Nama PIC : <?= $isi->nama_pic ?><br><button class="btn btn-success" onclick="window.location.replace(\'<?= 'client/detail/' . $isi->id_unit ?>\');">Detail</button>');
      <?php } ?>
    <?php } ?>
  </script>
<?php }?>


<?php if($title_web == 'Detail Wilayah | ACS Monit'){?>
  <script>
  	var unit = L.layerGroup();
  	var peta1 = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
  		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
  			'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
  			'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
  		id: 'mapbox/streets-v11'
  	});


  	var peta2 = L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
  		attribution: 'google'
  	});

  	var peta3 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  	});

  	var map = L.map('map', {
  		center: [<?= $wilayah->latitude ?>, <?= $wilayah->longitude ?>],
  		zoom: 10,
  		layers: [peta2, unit]
  	});

  	var baseLayers = {
  		"Grayscale": peta1,
  		"Satelite": peta2,
  		"Streets": peta3
  	};

  	var overlays = {
  		"Unit": unit,
  	};

  	L.control.layers(baseLayers, overlays).addTo(map);

  	L.geoJSON({
  		"type": "FeatureCollection",
  		"features": [<?= $wilayah->unit_geojson; ?>]
  	}, {
  		style: {
  			color: "black",
  			fillOpacity: 0.4,
  			weight: 1.5,
  			opacity: 1,
  			fillColor: "<?= $wilayah->warna ?>"
  		},
  	}).addTo(unit)

    <?php if ($this->session->userdata('level') == 'administrator' || $this->session->userdata('level') == 'manajemen') { ?>
  		<?php if(!empty($unit_admin)) { foreach ($unit_admin as $isi) {
  			if($wilayah->nama_wilayah == $isi->asal_wilayah){
  			?>
  			L.marker([<?= $isi->latitude ?>, <?= $isi->longitude ?>], {
  				icon: L.icon({
  					iconUrl: '<?php echo 'data:image/png;base64,' .  $isi->foto;  ?>',
  					iconSize: [40, 40],
  				})
  			}).addTo(map)
  			.bindPopup('<img src="<?php echo 'data:image/png;base64,' .  $isi->foto; ?>" style="object-fit: cover;width:10vw;height:10vw;"/><br/><span class="text-uppercase">Klien : \
  				<?= $isi->nama_client ?></span><br/>Alamat : <?= $isi->alamat ?><br> Nama PIC : <?= $isi->nama_pic ?><br><button class="btn btn-success" onclick="window.location.replace(\'<?= '/client/detail/' . $isi->id_unit ?>\');">Detail</button>');
  			<?php } ?>
  			<?php }} ?>
  	<?php } ?>
  </script>
<?php }?>


<?php if($title_web == 'Data Klien | ACS Monit'){?>
  <?php foreach ($unit as $isi) : ?>
      <div class="modal fade" id="del<?= $isi['id_unit'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-sm">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title" id="exampleModalLabel"><b>Hapus Klien</b></h4>
                  </div>
                  <div class="modal-body">
                      Yakin ingin menghapus klien <strong><?= $isi['nama_client'] ?></strong> ?
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                      <a class="btn btn-danger" href="<?= base_url('client/del/' . $isi['id_unit']); ?>">Hapus</a>
                  </div>
              </div>
          </div>
      </div>
  <?php endforeach; ?>
<?php }?>


<?php if($title_web == 'Tambah Klien | ACS Monit'){?>
  <script type="text/javascript">
  var isilat = document.getElementById('latitude');
  var isilon = document.getElementById('longitude');
  var isigeojson = document.getElementById('geojson');
  var setClient = document.getElementById('nama_client');

  isilat.onkeyup = isigeoj;
  isilon.onkeyup = isigeoj;
  setClient.onkeyup = isigeoj;

  function isigeoj(){
  $('#nama_pic').val('PIC-'+setClient.value);
  $('#alamat').val('Wilayah '+setClient.value);
  $('#serial').val('SER-'+setClient.value);
  $('#model').val('MOD-'+setClient.value);
  $('#numlabel').val('NUM-'+setClient.value);
  $('#periode').val('PER-'+setClient.value);
  console.log('Coord: '+isilon.value+', '+isilat.value);
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
<?php }?>

  <?php if($title_web == 'Detail Klien | ACS Monit'){?>


  <?php foreach ($dunit as $isi) :
    $aumur = round((time()-strtotime($isi->unit_install))/(3600*24*365.25));
    ?>
      <div class="modal fade" id="spec-<?= $isi->unit_kode ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title" id="exampleModalLabel"><b>Detail <?= $isi->unit_merk.'-'.$isi->unit_model ?></b></h4>
                  </div>
                  <div class="modal-body">
                    <table class="table">
                      <tr>
                        <td style="font-weight:bold;">Merk</td>
                        <td style="font-weight:bold;">:</td>
                        <td><?= $isi->unit_merk; ?></td>
                      </tr>
                      <tr>
                        <td style="font-weight:bold;">Serial</td>
                        <td style="font-weight:bold;">:</td>
                        <td><?= $isi->unit_serial; ?></td>
                      </tr>
                      <tr>
                        <td style="font-weight:bold;">Model</td>
                        <td style="font-weight:bold;">:</td>
                        <td><?= $isi->unit_model; ?></td>
                      </tr>
                      <tr>
                        <td style="font-weight:bold;">Label Number</td>
                        <td style="font-weight:bold;">:</td>
                        <td><?= $isi->unit_numlabel; ?></td>
                      </tr>
                      <tr>
                        <td style="font-weight:bold;">Periode</td>
                        <td style="font-weight:bold;">:</td>
                        <td><?= $isi->unit_periode; ?></td>
                      </tr>
                      <tr>
                        <td style="font-weight:bold;">First Installment</td>
                        <td style="font-weight:bold;">:</td>
                        <td><?= date('d-m-Y',strtotime($isi->unit_install)).' <strong>('.$aumur.' yrs)</strong>'; ?></td>
                      </tr>
                    </table>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
      </div>
  <?php endforeach; ?>

  <input type="text" class="form-control" name="ui_install" required="required" id="ui_install">
  <input type="text" class="form-control" name="ui_merk" required="required" id="ui_merk">
  <input type="text" class="form-control" name="ui_serial" required="required" id="ui_serial">
  <input type="text" class="form-control" name="ui_model" required="required" id="ui_model">
  <input type="text" class="form-control" name="ui_numlabel" required="required" id="ui_numlabel">
  <input type="text" class="form-control" name="ui_periode" required="required" id="ui_periode">


<script>

  table = $('#tblvisit').DataTable({
    "lengthMenu": [[50,100, 200, -1], [50,100, 200, "All"]],
    "paging": true,
    "destroy": true,
    "responsive": true,
      "language":{
          "decimal":        ",",
          "emptyTable":     "Belum ada data",
          "info":           "Data ke _START_ s/d _END_ dari _TOTAL_ data",
          "infoEmpty":      "Data ke 0 s/d 0 dari 0 data",
          "infoFiltered":   "(Disaring dari _MAX_ data)",
          "infoPostFix":    "",
          "thousands":      ".",
          "lengthMenu":     "Tampilkan _MENU_ data",
          "loadingRecords": "Memuat...",
          "search":         "Cari:",
          "zeroRecords":    "Tidak ada data yang cocok",
          "paginate": {
              "first":      "Awal",
              "last":       "Akhir",
              "next":       ">",
              "previous":   "<"
          },
          "aria": {
              "sortAscending":  ": activate to sort column ascending",
              "sortDescending": ": activate to sort column descending"
          }
      },
      "processing": true,
//      "serverSide": true,
      "order": [],
      "dom": '<"top"Blf>rt<"bottom"lip><"clear">',
      "buttons": [
          "pageLength",
        ],
        scrollY: 600,
        scroller: {
          loadingIndicator: true
        }
  });

function simunit(){
    $.ajax({
      url: '/client/sim_unit',
      type: 'POST',
      data: jQuery.param({
        sn_kdclient : $('#base_client').val(),
        sn_install : $('#ui_install').val(),
        sn_merk : $('#ui_merk').val(),
        sn_serial : $('#ui_serial').val(),
        sn_model : $('#ui_model').val(),
        sn_numlabel : $('#ui_numlabel').val(),
        sn_periode : $('#ui_periode').val()
      })
    })
    .done(function(data) {
//      alert(data);
      location.reload();
    });

}

  var client = L.layerGroup();
	var peta1 = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
			'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
			'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		id: 'mapbox/streets-v11'
	});


	var peta2 = L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
		attribution: 'google'
	});

	var peta3 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	});

	var map = L.map('map', {
    center: [<?= $client->latitude ?>, <?= $client->longitude ?>],
		zoom: 18,
		scrollWheelZoom: false,
    layers: [peta3, client]
	});

	var baseLayers = {
		"Grayscale": peta1,
		"Satelite": peta2,
		"Streets": peta3
	};

	var overlays = {
    "Client": client,
	};

	L.control.layers(baseLayers, overlays).addTo(map);

	L.geoJSON({
		"type": "FeatureCollection",
    "features": [<?= $client->unit_geojson; ?>]
	}, {
		style: {
			color: "black",
			fillOpacity: 0.4,
			weight: 1.5,
			opacity: 1,
      fillColor: "<?= $client->warna ?>"
		},
  }).addTo(client);
</script>
<?php }?>

<?php if($title_web == 'Pencarian Klien | ACS Monit'){?>
  <script>
      var unit = L.layerGroup();
      var panen = L.layerGroup();
      var pemeliharaan = L.layerGroup();
      var jalan = L.layerGroup();
      var pencurian = L.layerGroup();
      var bencana = L.layerGroup();
      var peta1 = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
          attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
              '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
              'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
          id: 'mapbox/streets-v11',
          accessToken: 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw'
      });


      var peta2 = L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
          attribution: 'google'
      });

      var peta3 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      });
      var map;
      var markers = [];
      var distanceAB = 0;
      var lat1 = 0;
      var lon1 = 0;
      var lat2 = 0
      var lon2 = 0;
      var dlat = 0;
      var dlon = 0;

      <?php foreach ($unit as $isi) { ?>
          map = L.map('map', {
              center: [<?= $isi->latitude ?>, <?= $isi->longitude ?>],
              zoom: 14,
              layers: [peta3, unit]
          });
      <?php } ?>

      var baseLayers = {
          "Grayscale": peta1,
          "Satelite": peta2,
          "Streets": peta3
      };

      var overlays = {
          "Unit": unit,
          "Panen": panen,
          "Pemeliharaan": pemeliharaan,
          "Jalan": jalan,
          "Pencurian": pencurian,
          "Bencana Alam": bencana,
      };

      <?php foreach ($unit as $isi) { ?>
          var homebutton = L.easyButton('fa-home fa-lg', function() {
              map.setView([<?= $isi->latitude ?>, <?= $isi->longitude ?>], 14);
          }, 'home position', {
              position: 'topright'
          });
      <?php } ?>
      L.control.layers(baseLayers, overlays).addTo(map);
      L.control.scale().addTo(map);
      homebutton.addTo(map);
      L.Control.geocoder().addTo(map);

      var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
      var osmAttrib = 'Map data &copy; OpenStreetMap contributors';
      var osm2 = new L.TileLayer(osmUrl, {
          minZoom: 0,
          maxZoom: 13,
          attribution: osmAttrib
      });
      var miniMap = new L.Control.MiniMap(osm2, {
          toggleDisplay: true
      }).addTo(map);

      function haversine(lat1, lon1, lat2, lon2) {
          lat1 = (lat1 * Math.PI) / 180;
          lon1 = (lon1 * Math.PI) / 180;
          lat2 = (lat2 * Math.PI) / 180;
          lon2 = (lon2 * Math.PI) / 180;

          // Haversine formula
          dlat = lat2 - lat1;
          dlon = lon2 - lon1;

          const a =
              Math.sin(dlat / 2) * Math.sin(dlat / 2) +
              Math.cos(lat1) * Math.cos(lat2) *
              Math.sin(dlon / 2) * Math.sin(dlon / 2);
          const c = 2 * Math.asin(Math.sqrt(a));

          const radius = 6371;

          return c * radius;
      }


      //Unit
      <?php if ($this->session->userdata('level') == 'operator') { ?>
      <?php foreach ($unit as $isi) { ?>
          L.geoJSON({
              "type": "FeatureCollection",
              "features": [<?= $isi->unit_geojson; ?>]
          }, {
              style: {
                  color: "black",
                  fillOpacity: 0.4,
                  weight: 1.5,
                  opacity: 1,
                  fillColor: "<?= $isi->warna ?>"
              },
          }).addTo(unit)
          .bindPopup('<img src="<?php echo 'data:image/png;base64,' .  $isi->foto; ?>" style="object-fit: cover;width:10vw;height:10vw;"/><br/><span class="text-uppercase">Klien : \
            <?= $isi->nama_client ?></span><br/>Alamat : <?= $isi->alamat ?><br> Nama PIC : <?= $isi->nama_pic ?>');

            const geojson = <?= $isi->unit_geojson; ?>;

            // Extract coordinates
            const coordinates = geojson.features[0].geometry.coordinates;
            const longitude = coordinates[0];
            const latitude = coordinates[1];

            lat1 =  latitude;
            lon1 = longitude;


      <?php } ?>
      <?php } elseif ($this->session->userdata('level') == 'administrator' || $this->session->userdata('level') == 'manajemen') { ?>
        <?php foreach ($unit as $isi) { ?>
            L.geoJSON({
                "type": "FeatureCollection",
                "features": [<?= $isi->unit_geojson; ?>]
            }, {
                style: {
                    color: "black",
                    fillOpacity: 0.4,
                    weight: 1.5,
                    opacity: 1,
                    fillColor: "<?= $isi->warna ?>"
                },
            }).addTo(unit)
            .bindPopup('<img src="<?php echo 'data:image/png;base64,' .  $isi->foto; ?>" style="object-fit: cover;width:10vw;height:10vw;"/><br/><span class="text-uppercase">Klien : \
              <?= $isi->nama_client ?></span><br/>Alamat : <?= $isi->alamat ?><br> Nama PIC : <?= $isi->nama_pic ?><br><button class="btn btn-success" onclick="window.location.replace(\'<?= '/client/detail/' . $isi->id_unit ?>\');">Detail</button>');
        <?php } ?>
      <?php } ?>

      $.ajax({
        url: '/monitor/up_index',
        type: 'POST',
      })
      .done(function(data) {
        var isiDt = JSON.parse(data);
        var i = 0;
        var pjgDt = isiDt.length,i;
        if(pjgDt > 0){
          for (i = 0; i <= pjgDt - 1; i++) {
            var tekfoto = isiDt[i].absen_fto;
            var teknama = isiDt[i].nama;
            var tekwil = isiDt[i].asal_wilayah;
            var tekjam = isiDt[i].absen_jam;


            distanceAB = haversine(
              lat1,
              lon1,
              (isiDt[i].absen_up_lat == '0' && isiDt[i].absen_up_lon == '0') ? isiDt[i].absen_lat : isiDt[i].absen_up_lat,
              (isiDt[i].absen_up_lat == '0' && isiDt[i].absen_up_lon == '0') ? isiDt[i].absen_lon : isiDt[i].absen_up_lon
            );

            if(distanceAB <=100){
              marker = new L.marker([
                (isiDt[i].absen_up_lat == '0' && isiDt[i].absen_up_lon == '0') ? isiDt[i].absen_lat : isiDt[i].absen_up_lat,
                (isiDt[i].absen_up_lat == '0' && isiDt[i].absen_up_lon == '0') ? isiDt[i].absen_lon : isiDt[i].absen_up_lon
              ], {
                icon: L.icon({
                  iconUrl: 'data:image/png;base64,' + tekfoto,
                  iconSymbol: 5,
                  iconSize: [40, 40],
                  className: 'usrico'
                })
              }).addTo(map)
              .bindPopup('<img src="data:image/png;base64,'+isiDt[i].absen_fto+'"  style="object-fit: cover;width:10vw;height:10vw;"/><br/>Teknisi: '+isiDt[i].nama+'<br/>No. Kontak: '+isiDt[i].telepon+'<br> Jarak: '+ distanceAB.toFixed(2) +' km');
              markers.push(marker);
            }



          }
        }
        $('.leaflet-marker-icon').css({'border-radius':'50%'});
      })

  </script>
<?php }?>

<?php if($title_web == 'Peta | ACS Monit'){?>
<script>
    var unit = L.layerGroup();
    var peta1 = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        id: 'mapbox/streets-v11',
        accessToken: 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw'
    });


    var peta2 = L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
        attribution: 'google'
    });

    var peta3 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });

    var map = L.map('map', {
        center: [-2.4098402753312764, 122.551855487749],
        zoom: 5,
        layers: [peta3]
    });

    var baseLayers = {
        "Grayscale": peta1,
        "Satelite": peta2,
        "Streets": peta3
    };

    //Home Button
    var homebutton = L.easyButton('fa-home fa-lg', function() {
        map.setView([-4.916607888089007, 115.57656318971232], 7);
    }, 'home position', {
        position: 'topright'
    });
    L.control.layers(baseLayers).addTo(map);
    L.control.scale().addTo(map);
    homebutton.addTo(map);
    //Search
    L.Control.geocoder().addTo(map);

    //Minimap
    var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    var osmAttrib = 'Map data &copy; OpenStreetMap contributors';
    var osm2 = new L.TileLayer(osmUrl, {
        minZoom: 0,
        maxZoom: 13,
        attribution: osmAttrib
    });
    var miniMap = new L.Control.MiniMap(osm2, {
        toggleDisplay: true
    }).addTo(map);

    <?php if ($this->session->userdata('level') == 'manajemen') { ?>
      <?php foreach ($wilayah as $wil) { ?>
          L.geoJSON({
              "type": "FeatureCollection",
              "features": [<?= $wil->unit_geojson; ?>]
          }, {
              style: {
                  color: "black",
                  fillOpacity: 0.4,
                  weight: 1.5,
                  opacity: 1,
                  fillColor: "<?= $wil->warna ?>"
              },
          }).addTo(map)
          .bindPopup('<img src="<?php echo 'data:image/png;base64,' .  $wil->foto; ?>" style="object-fit: cover;width:10vw;height:10vw;"/><br/><span class="text-uppercase">Wilayah : \
            <?= $wil->nama_wilayah ?></span><br/><button class="btn btn-danger" onclick="window.location.replace(\'<?= 'wilayah/detail/' . $wil->id_wil ?>\');">Detail</button>');
      <?php } ?>
    <?php } ?>


    //Unit marker
    <?php foreach ($unit as $isi) { ?>

        L.marker([<?= $isi->latitude ?>, <?= $isi->longitude ?>], {
            icon: L.icon({
                iconUrl: '<?php echo'data:image/png;base64,' .  $isi->foto;  ?>',
                iconSize: [30, 30],
                className: 'usrico'
            })
        }).addTo(map)
        .bindPopup('<img src="<?php echo 'data:image/png;base64,' .  $isi->foto; ?>" style="object-fit: cover;width:10vw;height:10vw;"/><br/><span class="text-uppercase">Klien : \
          <?= $isi->nama_client ?></span><br/>Alamat : <?= $isi->alamat ?><br> Nama PIC : <?= $isi->nama_pic ?><br><button class="btn btn-success" onclick="window.location.replace(\'<?= '/client/detail/' . $isi->id_unit ?>\');">Detail</button>');

    <?php } ?>

var circle;
    //Unit marker
    <?php foreach ($hadir as $abs) { ?>
        L.marker([<?= $abs->absen_lat ?>, <?= $abs->absen_lon ?>], {
            icon: L.icon({
                iconUrl: '<?php echo'data:image/png;base64,' .  $abs->absen_fto;  ?>',
                iconSymbol: 5,
                iconSize: [40, 40],
                className: 'usrico'
            })
        }).addTo(map)
        .bindPopup('<img src="<?php echo 'data:image/png;base64,' .  $abs->absen_fto; ?>"  style="object-fit: cover;width:10vw;height:10vw;"/><br/>Teknisi: <?= $abs->nama ?><br/>Wilayah : <?= $abs->asal_wilayah ?><br> Jam Hadir : <?= $abs->absen_jam ?>');
        $('.leaflet-marker-icon').css({'border-radius':'50%'});
    <?php } ?>

    // Array to hold markers
    var markers = [];

    // Function to clear all markers
    function clearMarkers() {
      const markers = document.querySelectorAll(".leaflet-marker-icon");
      markers.forEach(marker => marker.remove());
    }

    <?php if ($this->session->userdata('level') == 'operator') { ?>
      // Update markers every 60 seconds
      setInterval(function() {
        // Clear existing markers
        clearMarkers();
        var fg = L.featureGroup().addTo(map);

        fg.clearLayers();
        markers = [];

        $.ajax({
          url: 'monitor/up_index',
          type: 'POST',
        })
        .done(function(data) {
          var isiDt = JSON.parse(data);
          var i = 0;
          var pjgDt = isiDt.length,i;
          if(pjgDt > 0){
            for (i = 0; i <= pjgDt - 1; i++) {
              var tekfoto = isiDt[i].absen_fto;
              var teknama = isiDt[i].nama;
              var tekwil = isiDt[i].asal_wilayah;
              var tekjam = isiDt[i].absen_jam;
              marker = new L.marker([
                (isiDt[i].absen_up_lat == '0' && isiDt[i].absen_up_lon == '0') ? isiDt[i].absen_lat : isiDt[i].absen_up_lat,
                (isiDt[i].absen_up_lat == '0' && isiDt[i].absen_up_lon == '0') ? isiDt[i].absen_lon : isiDt[i].absen_up_lon
              ], {
                icon: L.icon({
                  iconUrl: 'data:image/png;base64,' + tekfoto,
                  iconSymbol: 5,
                  iconSize: [40, 40],
                  className: 'usrico'
                })
              }).addTo(map)
              .bindPopup('<img src="data:image/png;base64,'+isiDt[i].absen_fto+'"  style="object-fit: cover;width:10vw;height:10vw;"/><br/>Teknisi: '+isiDt[i].nama+'<br/>Wilayah : '+isiDt[i].asal_wilayah+'<br> Jam Hadir : '+ isiDt[i].absen_jam);
              markers.push(marker);
            }
          }
          $('.leaflet-marker-icon').css({'border-radius':'50%'});
        })

            <?php foreach ($unit as $isi) { ?>

                marker = new L.marker([<?= $isi->latitude ?>, <?= $isi->longitude ?>], {
                    icon: L.icon({
                        iconUrl: '<?php echo'data:image/png;base64,' .  $isi->foto;  ?>',
                        iconSize: [30, 30],
                        className: 'usrico'
                    })
                }).addTo(map)
                .bindPopup('<img src="<?php echo 'data:image/png;base64,' .  $isi->foto; ?>" style="object-fit: cover;width:10vw;height:10vw;"/><br/><span class="text-uppercase">Klien : <?= $isi->nama_client ?></span><br/>Alamat : <?= $isi->alamat ?><br> Nama PIC : <?= $isi->nama_pic ?>');
                markers.push(marker);
                $('.leaflet-marker-icon').css({'border-radius':'50%'});
            <?php } ?>
      }, 60000);
    <?php } ?>


</script>
<?php } ?>

<script>
  $('#summernotehal').summernote({
    height: 150,
    tabsize: 1,
    direction: 'rtl',
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'underline', 'clear']],
      ['fontname', ['fontname']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['view', ['fullscreen', 'help']],
      ['table', ['table']],
    ],
  });
</script>
<!-- Select2 -->
<script>
  $(function() {
    //Initialize Select2 Elements
//    $('.select2').select2();
//$('.select').select2();setTimeout(function() {$('.select').val([dmx.parse('sc_getSpec.data.apiSpecList.data[0].LocationId').split(',')]).trigger('change')}, 2000);

  });
  // Restricts input for each element in the set of matched elements to the given inputFilter.
  (function($) {
    $.fn.inputFilter = function(inputFilter) {
      return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
        if (inputFilter(this.value)) {
          this.oldValue = this.value;
          this.oldSelectionStart = this.selectionStart;
          this.oldSelectionEnd = this.selectionEnd;
        } else if (this.hasOwnProperty("oldValue")) {
          this.value = this.oldValue;
          this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        }
      });
    };
  }(jQuery));
  // Install input filters.
  $("#uintTextBox").inputFilter(function(value) {
    return /^\d*$/.test(value);
  });
  // Install input filters.
  $("#uintTextBox2").inputFilter(function(value) {
    return /^\d*$/.test(value);
  });
  $("#uintTextBox3").inputFilter(function(value) {
    return /^\d*$/.test(value);
  });

  function reload_table() {
      table.ajax.reload(null,false); //reload datatable ajax
  }

  function gantgl(tanggalymd) {
    var utime = Math.floor(new Date(tanggalymd).getTime() / 1000);

    var bln_arr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember'];
    var date = new Date(utime * 1000);
    var year = date.getFullYear();
    var dmonth = ("0" + (parseInt(date.getMonth())+1)).slice(-2);
    var month = bln_arr[date.getMonth()+1];
    var day = ("0" + date.getDate()).slice(-2);
    var hours = ("0" + date.getHours()).slice(-2);
    var minutes = ("0" + date.getMinutes()).slice(-2);
    var seconds = ("0" + date.getSeconds()).slice(-2);
    var convdataTime1 = day + ' ' + month + ' ' + year;
    var convdataTime = year + '-' + dmonth + '-' + day + hours + ':' + minutes + ':' + seconds;
    return convdataTime;
  }


</script>
<script>
  // notifikasi gagal di hide
  //$("#notifikasi").hide();
  var logingagal = function() {
    $("#notifikasi").fadeOut('slow');
  };
  setTimeout(logingagal, 4000);
</script>

</body>

</html>
