<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-edit" style="color:green"> </i> Jadwal Teknisi
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
            <li class="active"><i class="fa fa-file-text"></i>&nbsp; Jadwal Teknisi</li>
        </ol>
    </section>
    <section class="content">
      <?php if (!empty($this->session->flashdata())) {
      echo $this->session->flashdata('pesan');
      } ?>
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="table-responsive">
              <div id="calendar"></div>
            </div>
          </div>
        </div>
      </div>
</section>
</div>
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="taskForm">
          <div class="modal-header">
              <span class="pull-right"><button class="btn btn-dark" data-dismiss="modal">X</button></span>
              <h4 class="modal-title" id="modalTitle"><b>Jadwal Teknisi</b></h4>
          </div>
          <div class="modal-body">
            <div class="col-sm-12">

                <input type="hidden" name="id" id="taskId">

                <div class="form-group" id="isitek">
                  <label>Teknisi</label>
                  <select class="pilteknisi form-control" multiple="multiple" name="title" id="title" style="width: 100%"></select>
                </div>
                <div class="form-group" id="isicli">
                  <label>Klien</label>
                  <select class="pilclient form-control" name="description" id="description" style="width: 100%"></select>
                </div>
                <div class="form-group">
                  <label>Waktu Kerja</label>
                  <input class="form-control" type="datetime-local" name="start_date" id="start_date" required>
                </div>
                <div class="form-group" style="display:none;">
                  <label>Waktu Akhir</label>
                  <input class="form-control" type="datetime-local" name="end_date" id="end_date" required>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" id="saveTaskBtn"  class="btn btn-success" data-dismiss="modal">Save Task</button>
            <button type="button" id="deleteTaskBtn" style="display:none;"  class="btn btn-danger" data-dismiss="modal">Delete Task</button>
          </div>
        </form>
      </div>
    </div>
  </div>
