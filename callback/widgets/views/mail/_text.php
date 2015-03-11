Заказ на обратный звонок с сайта <?php echo Yii::app()->getModule('yupi')->siteName;?>:<br />
<br />
<strong>Контактная информация</strong><br />
Имя - <?php echo $model->attributes['name']?><br />
Телефон - <?php echo $model->attributes['phone']?><br />
Дополнительная информация - <?php echo $model->attributes['text']||'Не указано'?>;