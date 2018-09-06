<?php
require_once "db.class.php";

class TO extends DBase
{
    static function getMarks()
    {
        return parent::select("select * from `_to_makes` order by matchcode");
    }
    
    static function getModels($mark)
    {
        return parent::select("
SELECT DISTINCT
  _to_models.fulldescription as modelname,
  _to_models.constructioninterval as years,
  _to_parts.ModelID as id FROM _to_makes
 LEFT JOIN _to_parts ON _to_makes.id = _to_parts.MakeID
 LEFT JOIN _to_models ON _to_models.id = _to_parts.ModelID
 WHERE _to_makes.matchcode = '$mark'
 ORDER BY _to_models.fulldescription asc")  ;
    }
    
    static function getTypes($mark, $model)
    {
        return parent::select("SELECT DISTINCT _to_cars.fulldescription as typename, _to_cars.constructioninterval as years, _to_cars.id as id FROM _to_makes LEFT JOIN _to_parts ON _to_makes.id = _to_parts.MakeID left JOIN _to_cars ON _to_cars.id = _to_parts.TypeID AND _to_cars.modelid = _to_parts.ModelID WHERE _to_makes.matchcode = '$mark' AND _to_cars.modelid = '$model'");
    }
    
    static function getParts($mark, $model, $type)
    {
        return parent::select("SELECT _to_partnames.description as partname, _to_makes.description as brand, _to_parts.OEnumber as OEnumber, _to_parts.PartTypeID as PartTypeID FROM _to_parts LEFT JOIN _to_partnames ON _to_partnames.id = _to_parts.PartTypeID left JOIN _to_makes ON _to_makes.id = _to_parts.MakeID WHERE _to_parts.ModelID = '$model' and _to_parts.TypeID = '$type'");
    }
    
    static function getCarname($mark, $model, $type)
    {
        return parent::query("SELECT DISTINCT _to_cars.fulldescription as carname, _to_cars.constructioninterval as years FROM _to_parts left JOIN _to_cars ON _to_cars.id = _to_parts.TypeID WHERE _to_parts.ModelID = '$model' and _to_parts.TypeID = '$type'");
    }
    
    static function getModelname($mark, $model)
    {
        return parent::query("SELECT _to_models.fulldescription as modelname FROM _to_models WHERE _to_models.id = '$model'");
    }
    
    static function getMarkname($mark)
    {
        return parent::query("SELECT _to_makes.fulldescription AS markname FROM _to_makes WHERE _to_makes.matchcode = '$mark'");
    }
    
    static function getMarkID($mark)
    {
        return parent::query("SELECT _to_makes.id as id FROM _to_makes WHERE _to_makes.matchcode = '$mark'");
    }
}

?>
