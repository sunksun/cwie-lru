<!-- modal-default -->
<div class="modal fade" id="modal-default<?php echo $row["id"]; ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><?php echo $row["major"]; ?></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <p><label>ปีการศึกษา : </label> <?php echo $row["term"]; ?></p>
            <p><label>จำนวน นศ. ออกฝึกประสบการวิชาชีพ (ระบบปกติ) : </label> <?php echo $row["num_practice"]; ?></p>
              <p><label>จำนวน นส. สหกิจศึกษา : </label> <?php echo $row["num_cwie"]; ?> </p>
              <p><label>จำนวนบัณฑิต CWIE : </label> <?php echo $row["num_pundit"]; ?> </p>
              <p><label>จำนวนบัณฑิต CWIE ที่ได้งานทำ : </label> <?php echo $row["num_pundit_job"]; ?></p>
              <p><label>จำนวนบัณฑิต CWIE ที่ได้งานทำในสถานประกอบการ : </label> <?php echo $row["num_pundit_job_work"]; ?></p>
              <p><label>หมายเหตุ : </label> <?php echo $row["note"]; ?></p>
              <img src="img_act_cwie/<?php echo $row["filename"]; ?>" class="img-rounded" alt="" width="150">
            </div>
            <div class="modal-footer justify-content-end">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>