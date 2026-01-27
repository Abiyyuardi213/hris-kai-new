<?php

namespace App\Services;

class Terbilang
{
    public static function make($nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = self::make($nilai - 10) . " belas";
        } else if ($nilai < 100) {
            $temp = self::make($nilai / 10) . " puluh" . self::make($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . self::make($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = self::make($nilai / 100) . " ratus" . self::make($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . self::make($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = self::make($nilai / 1000) . " ribu" . self::make($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = self::make($nilai / 1000000) . " juta" . self::make($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = self::make($nilai / 1000000000) . " milyar" . self::make(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = self::make($nilai / 1000000000000) . " trilyun" . self::make(fmod($nilai, 1000000000000));
        }
        return $temp;
    }
}
