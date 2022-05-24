<?php
date_default_timezone_set("Europe/Zurich");

$path = plugin_dir_url(dirname(__FILE__));

//Faire des requêtes pour ramener les informations depuis la base des données
global $wpdb;
$queryMeteo = <<<SQL
SELECT `id_met`, `etat_met` FROM `{$wpdb->prefix}bs_meteo`;       
SQL;
$queryPistes = <<<SQL
 SELECT `id_pst`, `etat_pst` FROM `{$wpdb->prefix}bs_pistes`;
SQL;
$queryNeige = <<<SQL
SELECT `id_nge`, `etat_nge` FROM `{$wpdb->prefix}bs_neige`;    
SQL;
$queryWebcam = <<<SQL
SELECT `id_web`, `nom_web`, `act_web`, `def_web` FROM `{$wpdb->prefix}bs_webcam`;
SQL;

//Enregistrer les informations provenant de la base de données dans des "array"
$result_met = $wpdb->get_results($queryMeteo);
$result_pst = $wpdb->get_results($queryPistes);
$result_nge = $wpdb->get_results($queryNeige);
$result_web = $wpdb->get_results($queryWebcam);
?>
<div class="test">
    <h1 id="titleH1">Chasseral-Snow</h1>
</div>

<div class="divlaInDate">
    <!--    choisisseur des dates-->
    <label id="dateLa"> Choisir une date : </label>
    <input name="datePicker" type="text" id="datePicker" readonly="readonly">

    <!--     Bouton pour ouvrir le modal des gestions des webcams -->
    <button type="button" class="btn btn-outline-primary webcamButModAr" data-toggle="modal" data-target="#gestionWebcamModal">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-camera-video-fill webcamButMod" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M0 5a2 2 0 0 1 2-2h7.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 4.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 13H2a2 2 0 0 1-2-2V5z" />
        </svg>
    </button>

</div>

<div>
    <form method="post" action="/wp-content/plugins/informationMeteo/admin_html/function.php" id="meteoBulletinForm">
        <div class="row">
            <div class="col">
                <h2 id="BulletinMeteoId" class="bulletinInstallationTitreClass">Bulletin météo</h2>
            </div>
        </div>

        <!-- Choisisseur de temps-->
        <div class="row">
            <div class="col-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-clock bulIcon" viewBox="0 0 16 16">
                    <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                </svg>
            </div>
            <div class="col">
                <input name="timeInput" type="time" id="timeInput" class="form-control" value="07:30">
            </div>
        </div>

        <!-- Choisisseur de température -->
        <div class="row">
            <div class="col-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-thermometer-half bulIcon" viewBox="0 0 16 16">
                    <path d="M9.5 12.5a1.5 1.5 0 1 1-2-1.415V6.5a.5.5 0 0 1 1 0v4.585a1.5 1.5 0 0 1 1 1.415z" />
                    <path d="M5.5 2.5a2.5 2.5 0 0 1 5 0v7.55a3.5 3.5 0 1 1-5 0V2.5zM8 1a1.5 1.5 0 0 0-1.5 1.5v7.987l-.167.15a2.5 2.5 0 1 0 3.333 0l-.166-.15V2.5A1.5 1.5 0 0 0 8 1z" />
                </svg>
            </div>
            <div class="col align-self-center">
                <input name="tempInputName" type="number" id="tempInput" class="form-control">
            </div>
        </div>

        <!--        Sélectionneur de l'état de météo-->
        <div class="row">
            <div class="col-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-cloud-snow bulIcon" viewBox="0 0 16 16">
                    <path d="M13.405 4.277a5.001 5.001 0 0 0-9.499-1.004A3.5 3.5 0 1 0 3.5 10.25H13a3 3 0 0 0 .405-5.973zM8.5 1.25a4 4 0 0 1 3.976 3.555.5.5 0 0 0 .5.445H13a2 2 0 0 1-.001 4H3.5a2.5 2.5 0 1 1 .605-4.926.5.5 0 0 0 .596-.329A4.002 4.002 0 0 1 8.5 1.25zM2.625 11.5a.25.25 0 0 1 .25.25v.57l.501-.287a.25.25 0 0 1 .248.434l-.495.283.495.283a.25.25 0 0 1-.248.434l-.501-.286v.569a.25.25 0 1 1-.5 0v-.57l-.501.287a.25.25 0 0 1-.248-.434l.495-.283-.495-.283a.25.25 0 0 1 .248-.434l.501.286v-.569a.25.25 0 0 1 .25-.25zm2.75 2a.25.25 0 0 1 .25.25v.57l.501-.287a.25.25 0 0 1 .248.434l-.495.283.495.283a.25.25 0 0 1-.248.434l-.501-.286v.569a.25.25 0 1 1-.5 0v-.57l-.501.287a.25.25 0 0 1-.248-.434l.495-.283-.495-.283a.25.25 0 0 1 .248-.434l.501.286v-.569a.25.25 0 0 1 .25-.25zm5.5 0a.25.25 0 0 1 .25.25v.57l.501-.287a.25.25 0 0 1 .248.434l-.495.283.495.283a.25.25 0 0 1-.248.434l-.501-.286v.569a.25.25 0 1 1-.5 0v-.57l-.501.287a.25.25 0 0 1-.248-.434l.495-.283-.495-.283a.25.25 0 0 1 .248-.434l.501.286v-.569a.25.25 0 0 1 .25-.25zm-2.75-2a.25.25 0 0 1 .25.25v.57l.501-.287a.25.25 0 0 1 .248.434l-.495.283.495.283a.25.25 0 0 1-.248.434l-.501-.286v.569a.25.25 0 1 1-.5 0v-.57l-.501.287a.25.25 0 0 1-.248-.434l.495-.283-.495-.283a.25.25 0 0 1 .248-.434l.501.286v-.569a.25.25 0 0 1 .25-.25zm5.5 0a.25.25 0 0 1 .25.25v.57l.501-.287a.25.25 0 0 1 .248.434l-.495.283.495.283a.25.25 0 0 1-.248.434l-.501-.286v.569a.25.25 0 1 1-.5 0v-.57l-.501.287a.25.25 0 0 1-.248-.434l.495-.283-.495-.283a.25.25 0 0 1 .248-.434l.501.286v-.569a.25.25 0 0 1 .25-.25z" />
                </svg>
            </div>
            <div class="col align-self-center">
                <select name="meteoSelect" id="meteoSelect">
                    <?php
                    foreach ($result_met as $val) {
                    ?>
                        <option value="<?= $val->id_met ?>"><?= $val->etat_met ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <!-- Sélectionneur de l'état des pistes -->
        <div class="row">
            <div class="col-2">
                <img class="imageSkie bulIcon" src="<?= $path ?>/imageIsActive/tsb.png">
            </div>
            <div class="col">
                <select name="pistesSelect" id="pistesSelect">
                    <?php
                    foreach ($result_pst as $val) {
                    ?>
                        <option value="<?= $val->id_pst ?>"><?= $val->etat_pst ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <!-- Sélectionneur de l'état de neige -->
        <div class="row">
            <div class="col-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-snow2 bulIcon" viewBox="0 0 16 16">
                    <path d="M8 16a.5.5 0 0 1-.5-.5v-1.293l-.646.647a.5.5 0 0 1-.707-.708L7.5 12.793v-1.086l-.646.647a.5.5 0 0 1-.707-.708L7.5 10.293V8.866l-1.236.713-.495 1.85a.5.5 0 1 1-.966-.26l.237-.882-.94.542-.496 1.85a.5.5 0 1 1-.966-.26l.237-.882-1.12.646a.5.5 0 0 1-.5-.866l1.12-.646-.884-.237a.5.5 0 1 1 .26-.966l1.848.495.94-.542-.882-.237a.5.5 0 1 1 .258-.966l1.85.495L7 8l-1.236-.713-1.849.495a.5.5 0 1 1-.258-.966l.883-.237-.94-.542-1.85.495a.5.5 0 0 1-.258-.966l.883-.237-1.12-.646a.5.5 0 1 1 .5-.866l1.12.646-.237-.883a.5.5 0 0 1 .966-.258l.495 1.849.94.542-.236-.883a.5.5 0 0 1 .966-.258l.495 1.849 1.236.713V5.707L6.147 4.354a.5.5 0 1 1 .707-.708l.646.647V3.207L6.147 1.854a.5.5 0 1 1 .707-.708l.646.647V.5a.5.5 0 0 1 1 0v1.293l.647-.647a.5.5 0 1 1 .707.708L8.5 3.207v1.086l.647-.647a.5.5 0 1 1 .707.708L8.5 5.707v1.427l1.236-.713.495-1.85a.5.5 0 1 1 .966.26l-.236.882.94-.542.495-1.85a.5.5 0 1 1 .966.26l-.236.882 1.12-.646a.5.5 0 0 1 .5.866l-1.12.646.883.237a.5.5 0 1 1-.26.966l-1.848-.495-.94.542.883.237a.5.5 0 1 1-.26.966l-1.848-.495L9 8l1.236.713 1.849-.495a.5.5 0 0 1 .259.966l-.883.237.94.542 1.849-.495a.5.5 0 0 1 .259.966l-.883.237 1.12.646a.5.5 0 0 1-.5.866l-1.12-.646.236.883a.5.5 0 1 1-.966.258l-.495-1.849-.94-.542.236.883a.5.5 0 0 1-.966.258L9.736 9.58 8.5 8.866v1.427l1.354 1.353a.5.5 0 0 1-.707.708l-.647-.647v1.086l1.354 1.353a.5.5 0 0 1-.707.708l-.647-.647V15.5a.5.5 0 0 1-.5.5z" />
                </svg>
            </div>
            <div class="col">
                <select name="neigeSelect" id="neigeSelect">
                    <?php
                    foreach ($result_nge as $val) {
                    ?>
                        <option value="<?= $val->id_nge ?>"><?= $val->etat_nge ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <!--        Sélectionneur des webcams-->
        <div class="row">
            <div class="col-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-camera-video-fill bulIcon" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M0 5a2 2 0 0 1 2-2h7.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 4.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 13H2a2 2 0 0 1-2-2V5z" />
                </svg>
            </div>
            <div class="col" id="webcamSelectId">
                <select name="webcamSelect" id="webcamSelect">
                    <?php
                    foreach ($result_web as $val) {
                        if ($val->def_web==1) {
                    ?>
                            <option value="<?= $val->id_web ?>"><?= $val->nom_web ?></option>
                    <?php
                        }
                    }
                    foreach ($result_web as $val) {
                        if ($val->act_web == 1) {
                            ?>
                            <option value="<?= $val->id_web ?>"><?= $val->nom_web ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <!--        saisisseur de texte-->
        <div class="row">
            <div class="col-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-chat-dots bulIcon" viewBox="0 0 16 16">
                    <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                    <path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9.06 9.06 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.437 10.437 0 0 1-.524 2.318l-.003.011a10.722 10.722 0 0 1-.244.637c-.079.186.074.394.273.362a21.673 21.673 0 0 0 .693-.125zm.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6c0 3.193-3.004 6-7 6a8.06 8.06 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a10.97 10.97 0 0 0 .398-2z" />
                </svg>
            </div>
            <div class="col">
                <input name="txtInput" type="text" id="txtInput" class="form-control">
            </div>
        </div>

        <!--        Bouton de validation-->
        <div class="row">
            <input type="submit" name="validerBulMet" id="validerBulMet" value="Valider" />
        </div>
    </form>
</div>
