<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 16/06/17
 * Time: 10:26
 */

namespace App\Models;


class Section extends Models
{
    public function getSection()
    {
        $res= $this->select("1=1");
        $tab=array();
        foreach ($res as $k=>$v) {
            array_push($tab,array('id_section'=>$v['id_section'], 'nom_section'=>$v['nom_section']));
        }
        return $tab;
    }

}