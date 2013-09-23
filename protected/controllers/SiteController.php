<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return [
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => [
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ],
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => [
                'class' => 'CViewAction',
            ],
        ];
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $prog = $this->getProgramInstance();

        $this->setProgramInstance($prog);

        $this->render('index', []);
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
            $this->redirect(['write']);
        }
        
        $prog = $this->getProgramInstance();

        $dp = new CArrayDataProvider($prog->mainMemory->memoryArea, [
            'keyField' => false,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        $this->render('execute', [
            'dataProvider' => $dp,
        ]);
    }

    public function actionErase_memory() {
        $prog = new Program;
        $this->setProgramInstance($prog);
        $this->redirect('write');
    }

    public function actionRun_next_instruction() {
        if (Yii::app()->request->isPostRequest) {
            $prog = $this->getProgramInstance();
            $prog->runNextInstruction();
            $this->setProgramInstance($prog);
            $this->dumpInfoAsJson($prog);
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionRun_next_microinstruction() {
        if (Yii::app()->request->isPostRequest) {
            $prog = $this->getProgramInstance();
            $prog->runNextMicroinstruction();
            $this->setProgramInstance($prog);
            $this->dumpInfoAsJson($prog);
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionRun_everything() {
        if (Yii::app()->request->isPostRequest) {
            $prog = $this->getProgramInstance();
            $prog->run();
            $this->setProgramInstance($prog);
            $this->dumpInfoAsJson($prog);
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionReset() {
        if (Yii::app()->request->isPostRequest) {
            $prog = $this->getProgramInstance();
            
            $prog->resetRegisters();
            $prog->resetFlags();
            $prog->resetLog();
            $prog->resetAuxiliaryVariables();
            
            $this->setProgramInstance($prog);
            $this->dumpInfoAsJson($prog);
        }
    }

    private function dumpInfoAsJson(Program $prog) {
        $array = $prog->dumpInfo();

        foreach ($array as &$v) {
            //if (is_array($v))
            //    continue;
            if (is_object($v))
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

            $param1 = $params['source_param'] === "" ? null : $params['source_param'];

            $indirection1 = $params['source_param_indirection'] === "0" ? false : true;

            $constant1 = $params['source_constant'] === "" ? null : $params['source_constant'];

            $param2 = $params['target_param'] === "" ? null : $params['target_param'];

            $indirection2 = $params['target_param_indirection'] === "0" ? false : true;

            $constant2 = $params['target_constant'] === "" ? null : $params['target_constant'];

            $vo = new VOInstruction($mnemonic, $param1, $indirection1, $constant1, $param2, $indirection2, $constant2);

            $lines = Factory::returnInstructionAndPossibleConstants($vo);

            $prog = $this->getProgramInstance();
            foreach ($lines as $line) {
                $prog->appendToMemory($line);
            }
            $this->setProgramInstance($prog);
        }
        $prog = $this->getProgramInstance();

        $dp = new CArrayDataProvider($prog->mainMemory->memoryArea, [
            'keyField' => false,
            'pagination' => [
                'pageSize' => 30,
        ]]);
        $this->render('write', [
            'dataProvider' => $dp,
            'model' => $model
        ]);
    }
    /**
     * 
     * @return Program
     */
    private function getProgramInstance() {

        if (is_null(Yii::app()->user->getState('program')))
            $prog = new Program();
        else
            $prog = Yii::app()->user->getState('program');

        return $prog;
    }

    private function setProgramInstance(Program $prog) {
        Yii::app()->user->setState('program', $prog);
    }

}