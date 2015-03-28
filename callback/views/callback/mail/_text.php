Заказ на обратный звонок с сайта <?php echo Yii::app()->getModule('yupe')->siteName;?>:<br />
<br />
<strong>Контактная информация:</strong><br />
Имя - <?php echo $model->attributes['name']?><br />
E-mail - <?php echo $model->attributes['email']?><br />
Телефон - <?php echo $model->attributes['phone']?><br />
Дополнительная информация - <?php echo empty($model->attributes['text'])? 'Не указана':$model->attributes['text'];?>;