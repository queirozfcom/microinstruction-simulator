<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
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
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionExecute() {

        if (is_null(Yii::app()->user->getState('program'))) {
            $this->redirect(array('write'));
        }
        $prog = $this->getProgramInstance();
        
        $dp = new CArrayDataProvider($prog->mainMemory->memoryArea, array(
                    'keyField' => false,
                    'pagination' => array(
                        'pageSize' => 30,
                    ),
                ));

        $this->render('execute', array(
            'dataProvider' => $dp,
        ));
    }

    public function actionRun_next_instruction() {
        if (Yii::app()->request->isPostRequest) {
            $prog = $this->getProgramInstance();
            $prog->runNextInstruction();
            $this->setProgramInstance($prog);
            $this->dumpInfoAsJson($prog);
        }else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

//        public function actionBootstrap(){
//            
//      }
//        public function actionFetchfirst(){
//            if(Yii::app()->request->isPostRequest)
//            {
//                $prog = $this->getProgramInstance();
//                $prog->fetchFirst();
//                $this->setProgramInstance($prog);
//                $this->dumpInfoAsJson($prog);
//            }
//            else
//                throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
//
//        }
    public function actionReset() {
        if (Yii::app()->request->isPostRequest) {
            $prog = $this->getProgramInstance();
            $prog->reset();
            $this->setProgramInstance($prog);
            $this->dumpInfoAsJson($prog);
        }
    }

    private function dumpInfoAsJson(Program $prog) {
        $array = $prog->dumpInfo();

        foreach ($array as &$v) {
            if (is_array($v))
                continue;
            $v = "<span rel='tooltip' title='{$v->humanReadableForm()}'>{$v->__toString()}</span>";
        }

        $json = json_encode($array);
        echo $json;
        Yii::app()->end();
    }

    public function actionWrite() {

        $model = new NewInstructionForm;

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'add-instruction-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['NewInstructionForm'])) {

            $params = $_POST['NewInstructionForm'];

            $mnemonic = $params['mnemonic'];

            $param1 = $params['target_param'] === "" ? null : $params['target_param'];

            $indirection1 = $params['target_param_indirection'] === "0" ? false : true;

            $constant1 = $params['target_constant'] === "" ? null : $params['target_constant'];

            $param2 = $params['source_param'] === "" ? null : $params['source_param'];

            $indirection2 = $params['source_param_indirection'] === "0" ? false : true;

            $constant2 = $params['source_constant'] === "" ? null : $params['source_constant'];

            $vo = new VOInstruction($mnemonic, $param1, $indirection1, $constant1, $param2, $indirection2, $constant2);

            $lines = Factory::returnInstructionAndPossibleConstants($vo);

            $prog = $this->getProgramInstance();
            foreach ($lines as $line) {
                $prog->appendToMemory($line);
            }
            $this->setProgramInstance($prog);

//                echo '<pre>';
//                var_dump($vo);
//                echo '</pre>';
        }
        $prog = $this->getProgramInstance();

        $dp = new CArrayDataProvider($prog->mainMemory->memoryArea, array(
                    'keyField' => false,
                    'pagination' => array(
                        'pageSize' => 30,
                    ),
                ));
        $this->render('write', array(
            'dataProvider' => $dp,
            'model' => $model
        ));
    }

    private function getProgramInstance() {

        if (is_null(Yii::app()->user->getState('program'))) {
            $prog = new Program();
        } else {
            $prog = Yii::app()->user->getState('program');
        }
        return $prog;
    }

    private function setProgramInstance(Program $prog) {
        Yii::app()->user->setState('program', $prog);
    }

}