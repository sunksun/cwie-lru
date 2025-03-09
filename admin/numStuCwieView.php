<!-- modal-default -->
<div class="modal fade" id="modal-default<?php echo htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8'); ?>">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo htmlspecialchars($row["major"], ENT_QUOTES, 'UTF-8'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><label>ปีการศึกษา : </label> <?php echo htmlspecialchars($row["year"], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><label>จำนวน นศ. ออกฝึกประสบการวิชาชีพ (ระบบปกติ) : </label> <?php echo htmlspecialchars($row["num_practice"], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><label>จำนวน นส. สหกิจศึกษา : </label> <?php echo htmlspecialchars($row["num_cwie"], ENT_QUOTES, 'UTF-8'); ?> </p>
        <p><label>จำนวนบัณฑิต CWIE : </label> <?php echo htmlspecialchars($row["num_pundit"], ENT_QUOTES, 'UTF-8'); ?> </p>
        <p><label>จำนวนบัณฑิต CWIE ที่ได้งานทำ : </label> <?php echo htmlspecialchars($row["num_pundit_job"], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><label>จำนวนบัณฑิต CWIE ที่ได้งานทำในสถานประกอบการ : </label> <?php echo htmlspecialchars($row["num_pundit_job_work"], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><label>หมายเหตุ : </label> <?php echo htmlspecialchars($row["note"], ENT_QUOTES, 'UTF-8'); ?></p>
        <?php if (!empty($row["filename"])): ?>
          <img src="img_act_cwie/<?php echo htmlspecialchars($row["filename"], ENT_QUOTES, 'UTF-8'); ?>" class="img-rounded" alt="" width="150">
        <?php endif; ?>
      </div>
      <div class="modal-footer justify-content-end">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>