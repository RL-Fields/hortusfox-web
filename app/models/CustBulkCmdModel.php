<?php

/**
 * Class CustBulkCmdModel
 * 
 * Manages custom bulk commands for plants
 */ 
class CustBulkCmdModel extends \Asatru\Database\Model {
    /**
     * @return mixed
     */
    public static function getCmdList()
    {
        try {
            return static::raw('SELECT * FROM `@THIS`');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $label
     * @param $attribute
     * @param $datatype
     * @param $styles
     * @return void
     * @throws \Exception
     */
    public static function addCmd($label, $attribute, $datatype, $styles, $combo_values = null)
    {
        try {
            static::raw('INSERT INTO `@THIS` (label, attribute, datatype, styles, combo_values) VALUES(?, ?, ?, ?, ?)', [
                $label, $attribute, $datatype, $styles, $combo_values
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $label
     * @param $attribute
     * @param $datatype
     * @param $styles
     * @return void
     * @throws \Exception
     */
    public static function editCmd($id, $label, $attribute, $datatype, $styles, $combo_values = null)
    {
        try {
            static::raw('UPDATE `@THIS` SET label = ?, attribute = ?, datatype = ?, styles = ?, combo_values = ? WHERE id = ?', [
                $label, $attribute, $datatype, $styles, $combo_values, $id
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
    public static function removeCmd($id)
    {
        try {
            static::raw('DELETE FROM `@THIS` WHERE id = ?', [$id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Turns the stored 'one per line, value or value=Label' text into the
     * {id, name} shape setBulkComboValues() in app.js expects.
     *
     * @param $raw
     * @return array
     */
    public static function parseComboValues($raw)
    {
        $out = [];
        if (!$raw) {
            return $out;
        }
        foreach (preg_split('/\r\n|\r|\n/', $raw) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            if (strpos($line, '=') !== false) {
                [$value, $label] = array_map('trim', explode('=', $line, 2));
            } else {
                $value = $label = $line;
            }
            $out[] = ['id' => $value, 'name' => $label];
        }
        return $out;
    }
}