<?php

/**
 * of documentation
 *  https://www.yiiframework.com/doc/guide/2.0/en/tutorial-core-validators#core-validators
 */

class Rules extends Form
{
    public function rules()
    {
        $condition = function($model){
            return $model->type == PostType::EVENT && !$model->draft;
        };
        
        $onNotDraftCondition = function($model){
            return !$model->draft;
        };
        
        return [
    
            [
                'country_id',
                'exist',
                'targetClass'     => Countries::class,
                'targetAttribute' => 'country_id',
                'message'         => 'Укажите страну из списка',
            ],

            [
                'country_id',
                'unique',
                'targetClass'     => Countries::class,
                'targetAttribute' => 'country_id',
                'message'         => 'Укажите страну из списка',
            ],
            
            ['archiveAt', 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            
            [
                'post_date', 'datetime', 'format' => 'php:Y-m-d H:i:s', 'min' => gmdate('Y-m-d H:i:s'),
                'tooSmall'                        => Yii::t('yii', '{attribute} must be no less than {min}.', [
                    'min' => Yii::t('message', 'current time')
                ])
            ],
            
            ['permittedCategoryIds', 'each', 'rule' => ['exist', 'targetClass' => Category::class, 'targetAttribute' => 'id']],
            
            ['advertUrl', 'url'],
            
            ['permittedCategoryIds', CategoryIdsValidator::class],
            
            ['type', 'in', 'range' => [1, 2, 3, 4]],
            
            ['title', 'required', 'when' => $onNotDraftCondition],
            [
                'title',
                'default',
                'value' => 'title cannot be empty'
            ],
            
            [['forAgencies', 'forModels', 'forSponsors'], 'default', 'value' => false],
            
            ['archiveAt', 'compare', 'compareAttribute' => 'publishAt', 'operator' => '>'],
            
            ['archiveAt', 'compare', 'compareAttribute' => 'publishAt', 'operator' => '>'],
            
            [
                'categoryIds', 'filter', 'skipOnEmpty' => true, 'filter' => function($categoryIds){
                return array_unique($categoryIds);
            }
            ],
            
            [
                'agenda', 'filter', 'filter' => function($data){
                return array_map(function($item){
                    return [
                        'title' => $item['title'] ?? null,
                        'start' => $item['start'] ?? null,
                        'end'   => $item['end'] ?? null,
                    ];
                }, $data);
            }, 'skipOnError'                 => true, 'skipOnEmpty' => true, 'when' => function(self $model){
                return $model->type == PostType::EVENT && !$model->draft && !$model->hasErrors('startAt')
                    && !$model->hasErrors('endAt');
            }
            ],
            
            ['cities', 'default', 'value' => []],
            ['cities', 'validateCities'],
            
            
            ['contacts', ArrayLengthValidator::class, 'length' => 5],
            
            [
                'needRegister', 'filter', 'filter' => function($data){
                return new ArrayExpression($data);
            }, 'skipOnEmpty'                       => true, 'skipOnError' => true, 'when' => $condition
            ]
        ];
    },
    
    /**
     * @param $attribute
     * @param $params
     */
    public function validateCities($attribute, $params)
    {
        if (!empty($this->$attribute) && empty($this->countryIds)) {
            $this->addError("countryIds", \Yii::t('yii', '{attribute} cannot be blank.', [
                'attribute' => $this->getAttributeLabel('countryIds')
            ]));
            
            return;
        }
        
        foreach ($this->$attribute as $key => $city) {
            if (!$this->checkIfEmpty($attribute, $key, 'id', Yii::t('message', 'City ID'))) {
                if (!$this->checkIfEmpty($attribute, $key, 'countryId', Yii::t('message', 'Country ID'))) {
                    if (!in_array($city['countryId'], $this->countryIds)) {
                        $this->addError("{$attribute}[$key][countryId]", Yii::t('message', 'Country was not selected.'));
                    } else {
                        $this->checkIfExist(
                            $city['id'], "{$attribute}[$key][id]", Yii::t('message', 'City ID'),
                            City::class,
                            'id'
                        );
                    }
                }
            }
        }
    }
    
    /**
     * @param $attribute
     * @param $key
     * @param $index
     * @param $attributeLabel
     *
     * @return bool
     */
    public function checkIfEmpty($attribute, $key, $index, $attributeLabel)
    {
        if (!isset($this->$attribute[$key][$index]) || (isset($this->$attribute[$key][$index]) && $this->$attribute[$key][$index] === '')) {
            $this->addError("{$attribute}[{$key}][{$index}]", \Yii::t('yii', '{attribute} cannot be blank.', [
                'attribute' => $attributeLabel
            ]));
            
            return true;
        }
        
        return false;
    }
    
    /**
     * @param $value
     * @param $attributeName
     * @param $attributeLabel
     * @param $targetClass
     * @param $targetAttribute
     * @param null $filter
     *
     * @return bool
     */
    public function checkIfExist($value, $attributeName, $attributeLabel, $targetClass, $targetAttribute, $filter = null)
    {
        $validator = new ExistValidator([
            'targetClass'     => $targetClass,
            'targetAttribute' => $targetAttribute,
            'filter'          => $filter
        ]);
        $error = "";
        
        if (!$validator->validate($value, $error)) {
            $this->addError("{$attributeName}", str_replace(
                    \Yii::t('yii', 'the input value'),
                    $attributeLabel,
                    $error)
            );
            
            return false;
        }
        
        return true;
    }
}

use common\models\Category;
use yii\validators\Validator;

class CategoryIdsValidator extends Validator
{
    /**
     * @var bool whether the filter should be skipped if an array input is given.
     * If true and an array input is given, the filter will not be applied.
     */
    public $skipOnArray = false;
    /**
     * @var bool this property is overwritten to be false so that this validator will
     * be applied when the value being validated is empty.
     */
    public $skipOnEmpty = true;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
    
    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        
        $parentIds = Category::find()->select(['parentId'])->where([
            'id' => $value
        ])->column();
        
        $model->$attribute = array_values(array_diff($value, $parentIds));
    }
}

use yii\base\InvalidConfigException;

class ArrayLengthValidator extends Validator
{
    public $length;
    public $skipOnError = true;
    
    public function init()
    {
        if (empty($this->length)) {
            throw new InvalidConfigException("Length has to be specified.");
        }
        
        if (!is_integer($this->length)) {
            throw new InvalidConfigException("Length has to be numeric.");
        }
    }
    
    public function validateAttribute($model, $attribute)
    {
        if (!is_array($model->$attribute)) {
            $this->addError($model, $attribute, \Yii::t('yii', '{attribute} is invalid.', [
                'attribute' => $model->getAttributeLabel($attribute)
            ]));
        }
        
        if (sizeof($model->$attribute) > $this->length) {
            $this->addError($model, $attribute, \Yii::t('yii', '{attribute} must be no greater than {max}.', [
                'attribute' => $model->getAttributeLabel($attribute),
                'max'       => $this->length
            ]));
        }
    }
}

