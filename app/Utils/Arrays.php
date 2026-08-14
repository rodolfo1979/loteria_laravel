<?php

namespace App\Utils;

class Arrays
{
    /**
     * Toma un arreglo por referencia | Retorna uno nuevo
     * Excluye las llaves que no se encuentren en  este
     *
     * @param $array array - Arreglo a modificar
     * @param $values string | array - Valor(es) a excluir en el arreglo
     * @param bool $byref - Retornara un nuevo arreglo o modificara el original
     * @return array|bool
     */
    public static function Exclude(&$array, $values, $byref = true)
    {
        if ($byref)
            $_array = &$array;
        else
            $_array = $array;

        if (is_array($values) == false)
            $values = array($values);

        foreach ($values as $value) {
            unset($_array[$value]);
        }

        if ($byref == false) return $_array;
        return true;
    }

    /**
     * Toma un arreglo por referencia | Retorna uno nuevo
     * Anexa las llaves y valores proporcionados
     *
     * @param $array array - Arreglo a modificar
     * @param $values
     * @param bool $byref - Retornara un nuevo arreglo o modificara el original
     * @return array|bool
     */
    public static function Append(&$array, $values, $byref = true)
    {
        if ($byref)
            $_array = &$array;
        else
            $_array = $array;

        foreach ($values as $key => $value) {
            $_array[$key] = $value;
        }

        if ($byref == false) return $_array;
        return true;
    }

    /**
     * Toma un arreglo por referencia | Retorna uno nuevo
     * Renombra las llaves con valores proporcionados
     *
     * @param $array array - Arreglo a modificar
     * @param $values array - Arreglo asociativo, de llaves a modificar
     * @param string $prefix prefijo
     * @param bool $byref - Retornara un nuevo arreglo o modificara el original
     * @return array|bool
     */
    public static function Rename(&$array, $values, $prefix = "", $byref = true)
    {
        if ($byref)
            $_array = &$array;
        else
            $_array = $array;

        foreach ($values as $key => $value) {
            if (isset($_array[$key])) {
                $new_key = $prefix . $value;
                $old_key_value = $_array[$key];
                $_array[$new_key] = $old_key_value;
                unset($_array[$key]);
            }
        }
        if ($byref == false) return $_array;
        return true;
    }

    public static function IsEmptyRecursive(&$InputVariable)
    {
        $Result = true;

        if (is_array($InputVariable) && count($InputVariable) > 0) {
            foreach ($InputVariable as $Value) {
                $Result = $Result && self::IsEmptyRecursive($Value);
            }
        } else {
            $Result = empty($InputVariable);
        }
        return $Result;
    }

    /**
     * Toma un arreglo por referencia | Retorna uno nuevo
     * Mantiene unicamente las llaves proporcionadas
     *
     * @param $array array - Arreglo a modificar
     * @param $values string | array - Valor(es) a conservar en el arreglo
     * @param bool $byref - Retornara un nuevo arreglo o modificara el original
     * @return array | bool
     */
    public static function KeepOnly(&$array, $values, $byref = true)
    {
        if (is_array($values) == false)
            $values = array($values);

        if ($byref)
            $_array = &$array;
        else
            $_array = $array;

        foreach ($_array as $key => $value) {
            if (!in_array($key, $values))
                unset($_array[$key]);
        }

        if ($byref == false) return $_array;
        return true;
    }

    public static function ArrayKeysExists(&$array, $keys)
    {
        return count(array_intersect_key(array_flip($keys), $array)) === count($keys);
    }

    public static function isAssociative(&$arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public static function groupAndNest(&$arr, $fields)
    {
        $x = new ArrayGrouping();
        return $x->run($arr, $fields);
    }

    public static function getAssociativeKeyByIndex(&$arr, $index)
    {
        $keys = array_keys($arr);
        return $keys[$index];
    }

    public static function letterToNumber($letter)
    {
        $alphabet = range('A', 'Z');
        return array_search($letter, $alphabet);
    }

    public static function numberToLetter($number, $uppercase = true)
    {
        $alphabet = range('A', 'Z');
        return $uppercase ? $alphabet[$number] : strtolower($alphabet[$number]);
    }

    public static function appendValuesOnArrayByKey(&$arr1Result, &$arr2, $arr1Key, $newKey, $newValue, $specifyKeys = array())
    {
        if (empty($specifyKeys)) {
            foreach ($arr2 as $k => $v) {
                if (isset($arr1Result[$k]))
                    $specifyKeys[$k] = $k;
            }
        }

        if (self::isAssociative($specifyKeys) == false) {
            $_keys = array();
            foreach ($specifyKeys as $k) {
                $_keys[$k] = $k;
            }
        } else {
            $_keys = $specifyKeys;
        }


        foreach ($_keys as $a1k => $a2k) {
            if (isset($arr1Result[$a1k]) && isset($arr2[$a2k])) {
                self::findByKeyAndAppendValue($arr1Result[$a1k], $arr1Key, $arr2[$a2k], $newKey, $newValue);
            }
        }
    }

    /**
     * Intersect Array1 with Array2 and change values in Array1 on matches values
     *
     * @param $searchOn array The array that should contain our values
     * @param $find array
     * @param $keys string | array
     * @param $newKey string
     * @param $newValue * data type or function
     */
    public static function onIntersectAppendValue(&$searchOn, $find, $keys, $newKey, $newValue)
    {
        // Keys that we well search to determine if is the same item
        $_keys = (\is_string($keys)) ? [$keys] : $keys;

        // Iterate over the first array and evaluate agaisnt the second one
        foreach ($searchOn as &$item) {


            foreach ($find as &$f) {
                $found = true;
                foreach ($_keys as $k) {
                    // If any of the keys does not match, break this loop and continue with next item in  the second array
                    if ($item[$k] !== $f[$k]) {
                        $found = false;
                        break;
                    }
                }

                // We found this key, so we don't need this element for future iterations
                if ($found) {
                    $item[$newKey] = $newValue;
                    unset($f);
                }
            }

        }
    }

    public static function findByKeyAndAppendValue(&$searchOn, $searchOnKey, $searchValue, $newKey, $newValue)
    {
        foreach ($searchOn as &$i) {
            if (isset($i[$searchOnKey]) AND $i[$searchOnKey] == $searchValue)
                self::addKey($i, $newKey, $newValue);
        }
    }

    public static function addKey(&$array, $key, $value)
    {
        $array[$key] = $value;
    }

    public static function getIndexByArrayColumValue(&$arr, $column, $value)
    {
        $iter = 0;
        foreach ($arr as &$i) {
            if (isset($i[$column]) AND $i[$column] == $value) {
                return $iter;
            }
             $iter++;
        }

        return -1;
    }

    /// CREAR UN ARRAY UNICO y AGRUPUPAR POR EL VALOR DE UN KEY
    public static function uniqueMultidimArray($array, $key) {

        $temp_array = array();

        $i = 0;

        $key_array = array();

        foreach($array as $val) {

            if (!in_array($val[$key], $key_array)) {

                $key_array[$i] = $val[$key];

                $temp_array[$i] = $val;

            }

            $i++;

        }

        return $temp_array;

    }

    /// ORDENAR UN ARRAY POR KEY
    public static function arraySort($array, $key, $order = SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $key) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }


    // BUSQUEDA RECURSIVA DE LA DATA
    public static function searchRecursive($array, $key, $value)
    {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value)
                $results[] = $array;

            foreach ($array as $subarray)
                $results = array_merge($results, self::searchRecursive($subarray, $key, $value));
        }

        return $results;
    }


}
