<?php

namespace common\dictionaries;

use Webmozart\Assert\Assert;
use Yii;

abstract class BaseDictionary
{
    abstract public static function all(): array;

    public static function get($key): string
    {
        $all = static::all();

        Assert::keyExists($all, $key);

        return Yii::$app->formatter->asRaw($all[$key]);
    }

    public static function keys(): array
    {
        return array_keys(static::all());
    }
}



namespace common\dictionaries;

use Yii;

class NewsStatus extends BaseDictionary
{
    const IN_MODERATION = 1;
    const REJECTED = 2;
    const PUBLISHED = 3;
    
    public static function all(): array
    {
        return [
            self::IN_MODERATION => Yii::t('app', 'In moderation'),
            self::REJECTED => Yii::t('app', 'Rejected'),
            self::PUBLISHED => Yii::t('app', 'Published'),
        ];
    }
}



