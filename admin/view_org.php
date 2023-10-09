<!-- modal-default -->
<div class="modal fade" id="modal-default<?php echo $row["id"]; ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><?php echo $row["name"]; ?></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p><label>ที่อยู่: </label><?php echo $row["address"]; ?> <?php echo $row["subdistrict"]; ?> <?php echo $row["district"]; ?>
              <?php echo $row["province"]; ?> <?php echo $row["postcode"]; ?> </p>
              <p><label>โทรศัพท์: </label><?php echo $row["tel1"]; ?> <?php echo $row["tel2"]; ?> <label>Fax: </label><?php echo $row["fax"]; ?> </p>
              <p><label>Line ID: </label><?php echo $row["line"]; ?> <label>Facebook: </label><?php echo $row["facebook"]; ?> </p>
              <p><label>Website: </label><a href=""><?php echo $row["website"]; ?></a> <label>Email: </label><?php echo $row["email"]; ?> </p>
              <img src="img_org/<?php echo $row["logo"]; ?>" class="img-circle" alt="" width="200">
            </div>
            <div class="modal-footer justify-content-end">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>