<?php

/**
 * Util class for building csv string from 2D array
 */
class CsvBuilder {
    public static function getCsvString($rows) {
        $f = fopen('php://memory', 'r+');
        foreach ($rows as $row) {
            fputcsv($f, $row);
        }
        rewind($f);
        return stream_get_contents($f);
    }

    public static function printCsvString($rows) {
        $f = fopen('php://memory', 'w');
        foreach ($rows as $row) {
            fputcsv($f, $row);
        }
        rewind($f);
        return fpassthru($f);
    }
}