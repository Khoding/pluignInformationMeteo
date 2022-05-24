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
            <div class="meteo-container">
                <div class="webcam-container">
                    <img class="webcam-meteo" height="250px" src="<?= $val->url_web ?>">
                </div>
                <div class="meteo-data-container">
                    <div class="meteo-data-header">
                        <span>
                            <?= strftime('%A %d %B ', strtotime($val->date_bul)) ?>
                        </span>
                        <span>
                            Météo à <?= date('H:i', strtotime($val->heure_bul)); ?>
                        </span>
                    </div>
                    <div class="div2">
                        Température :
                        <span><?= $val->temperature_bul ?>°</span>
                    </div>
                    <div class="div3">État des pistes : <?= $val->etat_pst ?></div>
                    <div class="div4">Enneigement : <?= $val->etat_nge ?></div>
                    <div class="div5"><?= $val->texte_bul; ?></div>
                </div>
                <div class="meteo-installations-container">d </div>
            </div>
            <article class="content-meteo gap-2 container-fluid">
                <div class="row col-sm row-md">
                    <img class="webcam-meteo col ps-xl-0 pe-lg-1 mb-xl-0 mb-1" src="<?= $val->url_web ?>">
                    <section class="data-meteo-container col row-xl">
                        <section class="data-meteo p-2 container-fluid bg-white">
                            <div class="row d-lg-block d-xl-flex row-xl">
                                <div class="row col-sm">
                                    <p class="fw-bold mb-0"><?= strftime('%A %d %B ', strtotime($val->date_bul)) ?></p>
                                    <p class="fw-bold mb-0">
                                        Température :
                                        <span><?= $val->temperature_bul ?>°</span>
                                    </p>
                                </div>
                                <div class="row col-sm">
                                    <p class="fw-bold mb-0"><?= "Météo à " . date('H:i', strtotime($val->heure_bul)); ?></p>
                                    <p class="fw-bold mb-0">État des pistes : <?= $val->etat_pst ?></p>
                                    <p class="fw-bold mb-0">Enneigement : <?= $val->etat_nge ?></p>
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
