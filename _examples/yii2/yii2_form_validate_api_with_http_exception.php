<?php
// for api with exception

class ApiForm extends Model
{
    
    /**
     * @throws ValidationException
     */
    public function afterValidate()
    {
        if ($this->hasErrors())
        {
            foreach ($this->getErrors() as $k => $v)
            {
                throw new ValidationException($k, $v[0]);
            }
        }
        parent::afterValidate();
    }
    
    /**
     * @param array $params
     *
     * @return static
     */
    public static function loadAndValidate($params = [])
    {
        $form = new static($params);
        $form->load(\Yii::$app->request->post(), '');
        $form->validate();
        
        return $form;
    }
    
}

class ValidationException extends HttpException
{
    public function __construct(string $field = null, string $message = null, int $code = 0, \Exception $previous = null)
    {
        Response::addFieldsToResponse([
            'field' => $field,
            'type'  => 'validation'
        ]);
        
        parent::__construct(400, $message, $code, $previous);
    }
    
}

class Response
{
    
    public static function successCreated($data)
    {
        return self::responseSuccess($data, 200);
    }
    
    public static function success($data)
    {
        return self::responseSuccess($data, 200);
    }
    
    public static function successMessage($message)
    {
        $temp = [
            'message' => $message
        ];
        
        return self::responseSuccess($temp, 200);
    }
    
    public static function responseSuccess($data, $code = null, $text = null)
    {
        $response = \Yii::$app->response;
        $response->setStatusCode($code, $text);
        $response->data = $data;
        
        return $response;
    }
    
    public static function responseError($data, $code = null, $text = null)
    {
        
        /** @var \yii\web\Response $response */
        $response = \Yii::$app->response;
        $response->setStatusCode($code, $text);
        $response->data = $data;
        
        return $response;
    }
    
    public static function responseItems($data, $code = 200)
    {
        $temp = [
            'items' => $data
        ];
        
        return self::responseSuccess($temp, $code);
    }
    
    public static function responseItem($data, $code = 200)
    {
        $temp = [
            'item' => $data
        ];
        
        return self::responseSuccess($temp, $code);
    }
    
    /**
     * Вешаем еще одно событие на ответ, и мерджим массивы
     * @param array $fields
     */
    public static function addFieldsToResponse(array $fields)
    {
        \Yii::$app->response->on(\Yii::$app->response::EVENT_BEFORE_SEND, function (Event $event) use($fields) {
            $response = $event->sender;
            $response->data['data'] = array_merge($response->data['data'], $fields);
        });
    }
    
    public static function count($count)
    {
        return self::success(['count' => $count]);
    }
    
}
