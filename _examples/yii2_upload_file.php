<?php

// загрузка

class NewsCreateForm extends CompositeForm
{
    public function formName()
    {
        return 'news';
    }
    
    public function __construct(array $config = [])
    {
        $this->photo = new PhotoForm();
        $this->photos = new PhotosForm();
        parent::__construct($config);
    }
    
    protected function internalForms()
    {
        return [
            'photo',
            'photos'
        ];
    }
}
class PhotoForm extends Model
{
    public $file;
    
    public function formName()
    {
        return 'file';
    }
    
    public function rules(): array
    {
        return [
            ['file', 'required'],
            ['file', 'file', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'maxSize' => 1024 * 1024],
        ];
    }
    
    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->file = UploadedFile::getInstanceByName('file');
            return true;
        }
        
        return false;
    }
}
class PhotosForm extends Model
{
    public $files;
    
    public function formName()
    {
        return 'files';
    }
    
    public function rules(): array
    {
        return [
            ['files', 'each', 'rule' => ['file', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'maxSize' => 1024 * 1024]]
        ];
    }
    
    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->files = UploadedFile::getInstancesByName('files');
            
            return true;
        }
        
        return false;
    }
}

?>

// форма
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
<?= $form->field($model->photo, 'file')->widget(kartik\widgets\FileInput::class, [
    'options' => [
        'name'   => 'file',
        'accept' => 'image/*',
    ]
]) ?>
<?= $form->field($model->photos, 'files[]')->widget(kartik\widgets\FileInput::class, [
    'options' => [
        'name'     => 'files[]',
        'accept'   => 'image/*',
        'multiple' => true
    ]
]) ?>

