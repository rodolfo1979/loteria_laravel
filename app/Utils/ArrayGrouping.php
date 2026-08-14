<?php

namespace App\Utils;

class ArrayGrouping
{
    private $agregated = array(
        'SUM' => array(),
        'MAX' => array(),
        'MIN' => array(),
        'COUNT' => array(),
    );

    private $agregation_by_group = array();

    private $_arr;
    private $_fields;
    private $_renamed_fields;

    private $_raw = array();
    private $_result = array();

    private function getParentName($current, $field_position)
    {
        $parent_name = "";
        for ($j = 0; $j < $field_position; $j++) {
            $parent_group_name = Arrays::getAssociativeKeyByIndex($this->_fields, $j);
            $parent_field_name = $this->_fields[$parent_group_name][0];
            $parent_value = $current[$parent_field_name];

            $parent_name .= $parent_group_name . "_" . $parent_value . "_";
        }
        return strlen($parent_name) == 0 ? "" : substr($parent_name, 0, -1);
    }

    function run(&$arr, $fields)
    {
        $this->_arr = $arr;
        $this->_fields = $fields;
        $this->_renamed_fields = array();

        foreach ($this->_fields as $n => $p) {
            $this->_renamed_fields[$n] = array();

            foreach ($p as $f) {

                $this->_renamed_fields[$n][$f] = array(
                    "field" => $f,
                    "rename" => $f
                );
                $parts = \explode(" ", $f);

                $t_parts = count($parts);
                if ($t_parts > 1 && $t_parts < 4) {
                    if ($t_parts === 2)
                        $this->_renamed_fields[$n][$f] = array("field" => $parts[0], "rename" => $parts[1]);
                    else if (strcasecmp($parts[1], 'AS') === 0)
                        $this->_renamed_fields[$n][$f] = array("field" => $parts[0], "rename" => $parts[2]);
                }

            }
            // Todo, conservar referencia al original
        }

        // Iteramos sobre cada elemento del arreglo original
        foreach ($this->_arr as &$current) {

            // Iteramos sobre cada sub nivel de la estructura proporcionada
            for ($i = 0; $i < count($this->_fields); $i++) {

                // Obtenemos el elemento padre (si lo hay)
                $parent_name = $this->getParentName($current, $i);

                // Nombre del elemnto actual de la anidacion
                $group = Arrays::getAssociativeKeyByIndex($this->_fields, $i); //Grupo actual

                // Agrupacion anidada por id's
                $tmp_current_field_name = $this->getCurrentFieldName($current, $group, $i, $parent_name);

                //todo ultimo nivel, dar opcion de valores unicos / duplicados
                if (!isset($this->_raw[$tmp_current_field_name])) {

                    $this->_raw[$tmp_current_field_name] = array();

                    // Indexamos las funciones de agregacion de esta agrupacion
                    $this->indexAgregattedFieldsByGroup($parent_name, $tmp_current_field_name, $group);

                    // Agregamos los valores al campo correspondiente
                    $this->appendFieldValue($current, $tmp_current_field_name, $group);

                    if ($i > 0) {

                        // Creamos la agrupacion en este elemento,
                        if (isset($this->_raw[$parent_name][$group]) == false)
                            $this->_raw[$parent_name][$group] = array();

                        // Agregamos el elemento a la lista correspondiente
                        if (!empty($this->_raw[$tmp_current_field_name]))
                            $this->_raw[$parent_name][$group][] = &$this->_raw[$tmp_current_field_name];

                    } else {
                        $this->_result[$group][] = &$this->_raw[$tmp_current_field_name];
                    }
                }

                //Agregacion
                $this->runAgregattedFunctions($current, $tmp_current_field_name, $group);
            }
        }

        return $this->_result;

    }


    /*Agregated*/
    private function sum($alias, $value)
    {
        if (!isset($this->agregated['SUM'][$alias])) {
            $this->agregated['SUM'][$alias] = 0;
        }

        $this->agregated['SUM'][$alias] += $value;
        return $this->agregated['SUM'][$alias];
    }

    private function max($alias, $value)
    {
        if (!isset($this->agregated['MAX'][$alias]) || $value > $this->agregated['MAX'][$alias]) {
            $this->agregated['MAX'][$alias] = $value;
        }

        return $this->agregated['MAX'][$alias];
    }

    private function min($alias, $value)
    {
        if (!isset($this->agregated['MIN'][$alias]) || $value < $this->agregated['MIN'][$alias]) {
            $this->agregated['MIN'][$alias] = $value;
        }

        return $this->agregated['MIN'][$alias];
    }

    private function count($alias, $value)
    {

        if (!isset($this->agregated['COUNT'][$alias])) {
            $this->agregated['COUNT'][$alias] = 0;
        }

        if (isset($value))
            $this->agregated['COUNT'][$alias]++;

        return $this->agregated['COUNT'][$alias];
    }

    private function getCurrentFieldName($current, $group, $field_position, $parent_name)
    {
        $current_group = $this->_fields[$group][0]; //Indice 0, el valor distintivo
        $current_group = $this->_renamed_fields[$group][$current_group]["field"];

        $record_current_field_value = $current[$current_group];

        if ($field_position > 0) {
            return $parent_name . "_" . $group . "_" . $record_current_field_value;
        } else {
            return $group . "_" . $record_current_field_value;
        }
    }

    private function indexAgregattedFieldsByGroup($parent_name, $tmp_current_field_name, $group)
    {
        if (!isset($this->agregation_by_group[$group])) {
            $this->agregation_by_group[$group] = array();

            foreach ($this->_fields[$group] as $g) {
                foreach ($this->agregated as $func_name => $val) {
                    $patron = "/$func_name\\((.*?)\\)/";
                    $alias = "/$func_name\\((.*?)\\)/";
                    if (preg_match($patron, $g)) {

                        preg_match_all($patron, $g, $_res);
                        $a = preg_split($alias, $g, -1, PREG_SPLIT_NO_EMPTY);
                        $a = trim($a[0]);
                        $field = $_res[1][0];
                        $alias = $a;

                        $this->agregation_by_group[$group][] = array(
                            "alias" => $alias,
                            "temp_name" => $tmp_current_field_name,
                            "field" => $field,
                            "function" => $func_name
                        );
                    }
                }
            }

        }

    }

    //Agregamos los valores de esta tupla si existen
    private function appendFieldValue($current, $tmp_current_field_name, $group)
    {
        // Iteramos sobre los valores del grupo
        foreach ($this->_fields[$group] as $g) {

            // Obtenemos el nombre original
            $original_field = $this->_renamed_fields[$group][$g]["field"];

            // Si dentro de la tupla iterada, se encuentra la llave buscada... (Excluye las funciones de agregacion)
            if (isset($current[$original_field])) {
                // Obtenemos los datos final, que es el alias y lo asignamos a los valores devueltos
                $renamed = $this->_renamed_fields[$group][$g]["rename"];

                $this->_raw[$tmp_current_field_name][$renamed] = $current[$original_field];
            }
        }
    }

    private function runAgregattedFunctions($current, $tmp_current_field_name, $group)
    {

        foreach ($this->agregation_by_group[$group] as $g) {
            $this->_raw[$tmp_current_field_name][$g['alias']] = call_user_func_array(array($this, $g['function']), array($tmp_current_field_name . $g['alias'], $current[$g['field']]));
        }
    }

}
