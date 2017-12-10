<?php 

class PainelController{

	public function actionIndex(){

	   return	$this->render('index',[
       	]);
	}

	public function actionLogin(){
       return $this->render('login',[
       	]);
	}
}