<?php

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

foreach ($result as $val) { ?>
    <article class="content-meteo gap-2 d-flex">
        <img width="270px" height="192px" src="<?= $val->url_web ?>">
        <section class="d-flex flex-column">
            <section class="d-flex mb-2 gap-2 flex-row bg-white">
                <div class="flex-row">
                    <p class="mb-0"><?= "Météo à " . date('H:i', strtotime($val->heure_bul)); ?></p>
                    <div>
                        <img class="w-auto" src="<?= $path ?>imageMeteo/<?= $val->id_met ?>.png">
                        <span><?= $val->temperature_bul ?>°</span>
                    </div>
                </div>
                <div class="flex-row">
                    <p class="mb-0"><?= strftime('%A %d %B ', strtotime($val->date_bul)) ?></p>
                    <p class="mb-0">État des pistes : <?= $val->etat_pst ?></p>
                    <p class="mb-0">Enneigement : <?= $val->etat_nge ?></p>
                </div>
            </section>
            <section class="d-flex flex-column bg-white">
                <div>Installations :</div>
                <div class="d-flex flex-row">
                    <img width="75px" height="75px" src="<?= $path ?>/imageIsActive/tsb.png">
                    <table class="flex-grow-1" cellspacing="0">
                        <?php foreach (array_chunk($result1, 2) as $val) { ?>
                            <tr>
                                <?php foreach ($val as $v) { ?>
                                    <td>
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
                                    </td>
                                    <td>&nbsp;</td>
                                    <td><?= $v->nom_ins ?></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </section>
        </section>
    </article>
<?php

}
