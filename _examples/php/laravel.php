<?php


// QUERY BUILDER

Models::select(["models.year_to"])
    ->with(['modifications.attributeList',])
    ->where('models.slug', $model_slug)
    ->join('manufacturers', function($join) use ($manufacturer_slug){
        $join->on('models.manufacturerid', '=', 'manufacturers.id')
            ->where('manufacturers.matchcode', $manufacturer_slug);
    });