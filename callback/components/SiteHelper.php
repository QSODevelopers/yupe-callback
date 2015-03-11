<?php 

class SiteHelper
{
	public function transformPath($path){
		if($path && $path!=''){
			return str_replace(array('\\','|'), '/', $path);
		}else{
			throw new CHttpException(404, 'Путь не может быт пустым значением, идиот');
		}
	}
}

?>