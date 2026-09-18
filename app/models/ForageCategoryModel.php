<?php

class ForageCategoryModel extends \Asatru\Database\Model {
    private static $months = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'];

    private static $types = [
        'berry_fruit' => 'Berries & Fruit',
        'fungus' => 'Fungi',
        'leafy_herb' => 'Leafy Greens & Herbs',
        'seaweed' => 'Seaweed',
        'nut_seed' => 'Nuts & Seeds',
        'flower' => 'Flowers',
        'root' => 'Roots'
    ];

    private static $habitats = [
        'coastal' => 'Coastal',
        'woodland' => 'Woodland',
        'hedgerow_verge' => 'Hedgerow & Verge',
        'moorland_heathland' => 'Moorland & Heathland',
        'wetland_riverside' => 'Wetland & Riverside',
        'farmland_wolds' => 'Farmland & Wolds'
    ];

    public static function monthName($num)
    {
        return self::$months[(int)$num] ?? '';
    }

    public static function getTypes()
    {
        return self::$types;
    }

    public static function typeName($key)
    {
        return self::$types[$key] ?? '';
    }

    public static function getHabitats()
    {
        return self::$habitats;
    }

    public static function habitatName($key)
    {
        return self::$habitats[$key] ?? '';
    }

    public static function getAll()
    {
        try {
            return static::raw('SELECT * FROM `@THIS` ORDER BY name ASC');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function getAllFiltered($type = null, $habitat = null, $month = null)
    {
        try {
            $sql = 'SELECT * FROM `@THIS` WHERE 1=1';
            $bindings = [];

            if (!empty($type)) {
                $sql .= ' AND type = ?';
                $bindings[] = $type;
            }

            if (!empty($habitat)) {
                $sql .= ' AND habitat = ?';
                $bindings[] = $habitat;
            }

            if (!empty($month)) {
                $sql .= ' AND (
                    season_start_month IS NULL
                    OR season_end_month IS NULL
                    OR (season_start_month <= season_end_month AND ? BETWEEN season_start_month AND season_end_month)
                    OR (season_start_month > season_end_month AND (? >= season_start_month OR ? <= season_end_month))
                )';
                $bindings[] = $month;
                $bindings[] = $month;
                $bindings[] = $month;
            }

            $sql .= ' ORDER BY name ASC';

            return static::raw($sql, $bindings);
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

    public static function addCategory($name, $season_start_month, $season_end_month, $notes, $grocy_name = null, $harvested = 0, $type = null, $habitat = null)
    {
        try {
            static::raw('INSERT INTO `@THIS` (name, season_start_month, season_end_month, notes, grocy_name, harvested, type, habitat) VALUES(?, ?, ?, ?, ?, ?, ?, ?)', [$name, $season_start_month, $season_end_month, $notes, $grocy_name, $harvested, $type, $habitat]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function editCategory($id, $name, $season_start_month, $season_end_month, $notes, $grocy_name = null, $harvested = 0, $type = null, $habitat = null)
    {
        try {
            static::raw('UPDATE `@THIS` SET name = ?, season_start_month = ?, season_end_month = ?, notes = ?, grocy_name = ?, harvested = ?, type = ?, habitat = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?', [$name, $season_start_month, $season_end_month, $notes, $grocy_name, $harvested, $type, $habitat, $id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function setPhoto($id, $photo)
    {
        try {
            static::raw('UPDATE `@THIS` SET photo = ? WHERE id = ?', [$photo, $id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function removeCategory($id)
    {
        try {
            ForageCategoryPhotoModel::clearForCategory($id);
            ForageLocationModel::raw('DELETE FROM `@THIS` WHERE category_id = ?', [$id]);
            static::raw('DELETE FROM `@THIS` WHERE id = ?', [$id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
