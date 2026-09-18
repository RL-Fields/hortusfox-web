<?php

class ForageLocationModel extends \Asatru\Database\Model {
    public static function getForCategory($category_id)
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE category_id = ? ORDER BY created_at ASC', [$category_id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function getDetails($id)
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE id = ? LIMIT 1', [$id])?->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function addLocation($category_id, $name, $latitude, $longitude, $notes, $last_visited)
    {
        try {
            static::raw('INSERT INTO `@THIS` (category_id, name, latitude, longitude, notes, last_visited) VALUES(?, ?, ?, ?, ?, ?)', [$category_id, $name, $latitude, $longitude, $notes, $last_visited]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function editLocation($id, $name, $latitude, $longitude, $notes, $last_visited)
    {
        try {
            static::raw('UPDATE `@THIS` SET name = ?, latitude = ?, longitude = ?, notes = ?, last_visited = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?', [$name, $latitude, $longitude, $notes, $last_visited, $id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function removeLocation($id)
    {
        try {
            static::raw('DELETE FROM `@THIS` WHERE id = ?', [$id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
