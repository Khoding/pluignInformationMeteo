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
        $mobile = false;
        foreach ($result as $val) { ?>
            <article class="content-meteo gap-2 container-fluid">
                <div class="row col-sm row-md">
                    <img class="webcam-meteo col ps-xl-0 pe-lg-1 mb-xl-0 mb-1" src="<?= $val->url_web ?>">
                    <section class="col row-xl mb-1 ps-lg-0 pe-xl-1">
                        <section class="data-meteo p-2 container-fluid bg-white">
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
                        <?php
                        if (!$mobile) {
                            include("include/tableau.php");
                        }
                        ?>
                    </section>
                    <?php
                    $mobile = true;
                    if ($mobile) {
                        include("include/tableau-mobile.php");
                    }
                    ?>
                </div>
            </article>
<?php
        }
        $output = ob_get_clean();
        return $output;
    }

    add_shortcode('shortcode_chasseralSnow_ge', 'shortcode_chasseralSnow_general');
}
