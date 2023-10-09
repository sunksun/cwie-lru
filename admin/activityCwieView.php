<!-- modal-default -->
<div class="modal fade" id="modal-default<?php echo $row["id"]; ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><?php echo $row["activity_type"]; ?></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p><label>ชื่อกิจกรรม : </label> <?php echo $row["activity_name"]; ?></p>
              <p><label>สาขาวิชา : </label> <?php echo $row["course"]; ?> </p>
              <p><label>วันที่ดำเนินการ : </label> <?php echo $row["activity_date"]; ?> </p>
              <p><label>จำนวนผู้เข้าร่วม : </label> <?php echo $row["amount"]; ?></p>
              <p><label>หมายเหตุ : </label> <?php echo $row["note"]; ?></p>
              <p><label>วันที่บันทึกข้อมูล : </label> <?php echo $row["date_regis"]; ?></p>
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