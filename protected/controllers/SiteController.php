<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		
                $prog = $this->getProgramInstance();
                
                //var_dump($prog);    
                
                $this->setProgramInstance($prog);
                
                
                $this->redirect(array('write'));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{

		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
			//	$headers="From: {$model->email}\r\nReply-To: {$model->email}";
			//	mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
			//	Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
        
        public function actionExecute(){
            
            if(is_null(Yii::app()->user->getState('program'))){
                $this->redirect(array('write'));
            }
            $dp=new CArrayDataProvider($this->getProgramInstance()->mainMemory->memoryArea,array(
                    'keyField'=>false,
                    'pagination'=>array(
                        'pageSize'=>30,
                    ),
                ));
            
            $this->render('execute',array('dataProvider'=>$dp));
        }
        
        public function actionBootstrap(){
            if(Yii::app()->request->isPostRequest)
            {
                $prog = $this->getProgramInstance();
                $prog->bootstrap();
                $this->setProgramInstance($prog);
                
                $this->dumpInfoAsJson($prog);
            }
            else
                throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');

        }
        public function actionfetchfirst(){
            if(Yii::app()->request->isPostRequest)
            {
                $prog = $this->getProgramInstance();
                $prog->fetchFirst();
                $this->setProgramInstance($prog);
                
                $this->dumpInfoAsJson($prog);
            }
            else
                throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');

        }
        private function dumpInfoAsJson(Program $prog){
            $array = $prog->dumpInfo();

            $json = json_encode($array);
            echo $json;
            Yii::app()->end();
        }
        public function actionWrite()
        {
            
            $model = new NewInstructionForm;
            
            if(isset($_POST['ajax']) && $_POST['ajax']==='add-instruction-form')
	    {
			echo CActiveForm::validate($model);
			Yii::app()->end();
            }
            
            if(isset($_POST['NewInstructionForm'])){
                
                $params = $_POST['NewInstructionForm'];
                
                $mnemonic = $params['mnemonic'];
                
                $param1     = $params['target_param']==="" ? null : $params['target_param'];
                
                $indirection1 = $params['target_param_indirection']==="0" ? false : true;
                
                $constant1    = $params['target_constant']==="" ? null : $params['target_constant'];
                
                $param2         = $params['source_param']==="" ? null : $params['source_param'];
                
                $indirection2 = $params['source_param_indirection']==="0" ? false : true;
                
                $constant2    = $params['source_constant']==="" ? null : $params['source_constant'];
                
                $vo = new VOInstruction($mnemonic,$param1,$indirection1,$constant1,$param2,$indirection2,$constant2);
                
                $lines = Factory::returnInstructionAndPossibleConstants($vo);
                
                $prog = $this->getProgramInstance();
                foreach ($lines as $line){
                    $prog->appendToMemory($line);
                }
                $this->setProgramInstance($prog);
                
//                echo '<pre>';
//                var_dump($vo);
//                echo '</pre>';
            }
            $prog = $this->getProgramInstance();

            $dp=new CArrayDataProvider($prog->mainMemory->memoryArea,array(
                    'keyField'=>false,
                    'pagination'=>array(
                        'pageSize'=>30,
                    ),
                ));
            $this->render('write',array(
                'dataProvider'=>$dp,
                'model'=>$model
            ));
            
        }
        private function getProgramInstance(){
            
                if(is_null(Yii::app()->user->getState('program'))){
                   $prog = new Program(); 
                   //$prog->mainMemory->append(new Instruction('ADD','R1','R2'));
    
                }else{
                    $prog = Yii::app()->user->getState('program');
                }
                return $prog;
                
        }
        private function setProgramInstance(Program $prog){
            Yii::app()->user->setState('program',$prog);
        }
        
        public function actionFoo(){
            $this->render('foo');
        }
        
}