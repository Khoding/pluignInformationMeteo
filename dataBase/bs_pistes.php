<?php
//ajouter des type de pistes
global $wpdb;
$table_name = $wpdb->prefix . "bs_pistes";

//supprimer la table "bs_pistes" s'il existe avant.
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

//ajouter la table
$sql = "CREATE TABLE  $table_name(
        id_pst mediumint(9) NOT NULL ,
        etat_pst VARCHAR(30),
        UNIQUE KEY id (id_pst)                                                                                                                                                                                                   
    )";
$wpdb->query($sql);


//table pour ajouter les état des pistes
$pistesArray = array(
    "0"=>"veuillez sélectionner",
    "1"=>"Praticable",
    "2"=>"Bon",
    "3"=>"Très bon",
    "4"=>"Impraticable",
    "5"=>"Fermé",
    "6"=>"Praticable à bon",
    "7"=>"Bon à praticable",
);

foreach ($pistesArray as $key => $etat){
    $wpdb->insert($table_name, array('id_pst' => $key, 'etat_pst' => $etat));
}