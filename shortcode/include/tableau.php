<div class="row ms-2 ps-1 d-none d-xl-flex meteo-installation">
  <section class="p-2 row container-fluid bg-white">
    <div class="row col-sm-auto">
      <img style="max-width: 75px; max-height: 75px" width="75px" height="75px" src="<?= $path ?>/imageIsActive/tsb.png">
    </div>
    <div class="row col-sm">
      <div class="container-fluid px-0">
        <?php foreach (array_chunk($result1, 2) as $val) { ?>
          <div class="row">
            <?php foreach ($val as $v) { ?>
              <div class="col-1">
                <?php
                if ($v->isActive == 1) {
                ?>
                  <img class="isActiveImg" src="<?= $path ?>/imageIsActive/green.png">
                <?php
                } else { ?>
                  <img class="isActiveImg" src="<?= $path ?>/imageIsActive/red.png">
                <?php
                }
                ?>
              </div>
              <div class="col"><?= $v->nom_ins ?></div>
            <?php } ?>
          </div>
        <?php } ?>
      </div>
    </div>
  </section>
</div>
