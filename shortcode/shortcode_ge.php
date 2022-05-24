<?php
if (!function_exists('shortcode_chasseralSnow_general')) {
    function shortcode_chasseralSnow_general()
    {
        global $wpdb;
        $query = <<<SQL
            SELECT
                `b`.`heure_bul`, `b`.`date_bul`, `b`.`temperature_bul`, `b`.`id_met`, `p`.`etat_pst`, `n`.`etat_nge`, `w`.`url_web`, `b`.`texte_bul`
            FROM
                `{$wpdb->prefix}bs_bulletin` AS `b`
            LEFT JOIN
                    `{$wpdb->prefix}bs_meteo` AS `m`
                        ON `b`.`id_met` = `m`.`id_met`
            LEFT JOIN
                    `{$wpdb->prefix}bs_pistes` AS `p`
                        ON `b`.`id_pst` = `p`.`id_pst`
            LEFT JOIN
                    `{$wpdb->prefix}bs_neige` AS `n`
                        ON `b`.`id_nge` = `n`.`id_nge`
            LEFT JOIN
                    `{$wpdb->prefix}bs_webcam` AS `w`
                        ON `b`.`id_web` = `w`.`id_web`
            ORDER BY
                     `b`.`id_bul`
                        DESC LIMIT 1
SQL;

        $query1 = <<< SQL
            SELECT
                `i`.`id_ins`
                , `i`.`nom_ins`
                , IF(`ia`.`date_ins` IS NULL, FALSE, TRUE) AS `isActive`
            FROM `{$wpdb->prefix}bs_installations` AS `i`
            LEFT JOIN (
                SELECT
                    `id_ins`
                    , IF(
                        max(`date_ins`) = CURRENT_DATE(),
                        CURRENT_DATE(),
                        NULL
                    ) AS `date_ins`
                FROM `{$wpdb->prefix}bs_installations_active`
                GROUP BY `id_ins`
            ) AS `ia`
            ON `i`.`id_ins` = `ia`.`id_ins`;
SQL;
        $result = $wpdb->get_results($query);
        $result1 = $wpdb->get_results($query1);

        $path = plugin_dir_url(dirname(__FILE__));

        ob_start();
        $mobile = true;
        foreach ($result as $val) { ?>
            <article class="content-meteo gap-2 container-fluid">
                <div class="row col-sm row-md mb-2">
                    <img class="webcam-meteo col pe-lg-1 mb-2 mb-lg-0" src="<?= $val->url_web ?>">
                    <section class="col ps-lg-1">
                        <section class="mb-2 p-2 container-fluid h-100 bg-white">
                            <div class="row d-lg-block d-xl-flex row-xl">
                                <div class="row col-sm">
                                    <p class="fw-bold"><?= "Météo à " . date('H:i', strtotime($val->heure_bul)); ?></p>
                                    <p class="fw-bold"><?= strftime('%A %d %B ', strtotime($val->date_bul)) ?></p>
                                </div>
                                <div class="row col-sm">
                                    <p class="fw-bold">
                                        Température :
                                        <span><?= $val->temperature_bul ?>°</span>
                                    </p>
                                    <p class="fw-bold">État des pistes : <?= $val->etat_pst ?></p>
                                    <p class="fw-bold">Enneigement : <?= $val->etat_nge ?></p>
                                </div>
                            </div>
                        </section>
                    </section>
                </div>
                </section>
                <section class="d-flex flex-column flex-sm-row gap-5 h-100 bg-white p-2">
                    <div class="d-flex flex-row flex-sm-column">
                        <div>Installations :</div>
                        <img width="100px" height="100px" src="<?= $path ?>/imageIsActive/tsb.png">
                    </div>
                    <div class="d-flex flex-row">
                        <div class="container-fluid">
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
                </section>
            </article>
<?php
        }
        $output = ob_get_clean();
        return $output;
    }

    add_shortcode('shortcode_chasseralSnow_ge', 'shortcode_chasseralSnow_general');
}
