<?php

function countData($table)
{
    $db = \Config\Database::connect();
    return $db->table($table)->countAllResults();
}

function countDataBarangPinjam()
{
    $db = \Config\Database::connect();
    return $db->table('barangpinjam')->where('status', 'p')->countAllResults();
}

function countDataBarangKembali()
{
    $db = \Config\Database::connect();
    return $db->table('barangpinjam')->where('status', 'K')->countAllResults();
}

function countKondisiBarangBaik()
{
    $db = \Config\Database::connect();
    return $db->table('detail_barangpinjam')->where('detkondisi', '1')->countAllResults();
}

function countKondisiBarangRusakRingan()
{
    $db = \Config\Database::connect();
    return $db->table('detail_barangpinjam')->where('detkondisi', '2')->countAllResults();
}

function countKondisiBarangRusakBerat()
{
    $db = \Config\Database::connect();
    return $db->table('detail_barangpinjam')->where('detkondisi', '3')->countAllResults();
}
