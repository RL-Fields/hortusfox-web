<?php

class ForageLocationLogModel extends \Asatru\Database\Model {
    public static function getForLocation($location_id)
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE location_id = ? ORDER BY entry_date DESC, id DESC', [$location_id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function addEntry($location_id, $entry_date, $note)
    {
        try {
            static::raw('INSERT INTO `@THIS` (location_id, entry_date, note) VALUES(?, ?, ?)', [$location_id, $entry_date, $note]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function removeEntry($id)
    {
        try {
            static::raw('DELETE FROM `@THIS` WHERE id = ?', [$id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
