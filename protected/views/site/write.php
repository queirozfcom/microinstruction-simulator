<?php       
Yii::app()->clientScript->registerCoreScript('jquery.ui');

Yii::app()->getClientScript()->registerCssFile('css/app/style.css');
Yii::app()->getClientScript()->registerCssFile('css/select/ui-selectmenu.css');

Yii::app()->getClientScript()->registerScriptFile('js/site/app/script.js');
Yii::app()->getClientScript()->registerScriptFile('js/select/ui-selectmenu.js');

?>
<h2>Microinstruction Simulator</h2>

<div class="ui-widget">
    <div class="ui-widget-content ui-corner-all" id="main-div">
        
        <div class="step-div">Step 1 of 2: Writing the Program</div>
        
        <div class="ui-widget-content app-half-div ui-corner-all" id="left-div">
            
           
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'add-instruction-form',
            //'enableClientValidation'=>true,
            'enableAjaxValidation'=>true,
            'focus'=>array($model,'mnemonic'),
            'clientOptions'=>array(
                    'validateOnSubmit'=>true
            ),
        )); 
        ?>           
            
            <div id="controls-div">
                
                <?php echo $form->errorSummary($model); ?>

                <div class="row">
                        <?php echo $form->labelEx($model,'mnemonic'); ?>
                        <?php echo $form->dropDownList($model,'mnemonic',Instruction::getValidInstructions(),array(
                            'empty'=>'Please Select',
                            'class'=>'select-input'
                        )); ?>
                        <?php echo $form->error($model,'mnemonic',array('style'=>'color:red !important;')); ?>
                </div>

                <div class="row">
                        <?php echo $form->labelEx($model,'target_reg'); ?>
                        <?php echo $form->checkBox($model,'target_reg_indirection'); ?>
                        <?php echo $form->textField($model,'target_constant',
                                array(
                                    'style'=>'float:right;position:relative;top:17px;display:none;',
                                    'size'=>'8'
                                    )); ?>
                        <?php echo $form->error($model,'target_constant',array('style'=>'color:red !important;')); ?>            
                        
                        <?php echo $form->dropDownList($model,'target_reg', Program::getValidTargetableRegisters(),array(
                            'empty'=>'Please Select',
                            'class'=>'select-input'
                        )); ?>
                        <?php echo $form->error($model,'target_reg',array('style'=>'color:red !important;')); ?>
                </div>  
                
                <div class="row">
                        <?php echo $form->labelEx($model,'source_reg'); ?>
                        <?php echo $form->checkBox($model,'source_reg_indirection'); ?>
                        <?php echo $form->textField($model,'source_constant',
                                array(
                                    'style'=>'float:right;position:relative;top:17px;display:none;',
                                    'size'=>'8'
                                    )); ?>
                        <?php echo $form->error($model,'source_constant',array('style'=>'color:red !important;')); ?>
                        <?php echo $form->dropDownList($model,'source_reg', Program::getValidTargetableRegisters(),array(
                            'empty'=>'Please Select',
                            'class'=>'select-input'
                        )); ?>
                        <?php echo $form->error($model,'source_reg',array('style'=>'color:red !important;')); ?>
                </div>
                <br />
                <div class="row">
                    <?php $this->widget('zii.widgets.jui.CJuiButton', array(
                        'name'=>'submit',
                        'caption'=>'Submit',
                        'themeUrl'=>'/ext/css',
                        'theme'=>'lighty',
                        )); 
                    
                    ?>
                </div>
                
     
             </div>  
            <?php $this->endWidget(); ?>
            
        </div>
        <div class="ui-widget-content app-half-div ui-corner-all" id="right-div">
            <?php 
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider'=>$dataProvider,
                    'htmlOptions'=>array(
                      'style'=>'margin:8px;'  
                    ),
                    'columns'=>array(
                        array(
                            'name'=>'Address',
                            'value'=>'$row',
                        ),
                        array(
                            'name'=>'Content',
                            'value'=>'$data->humanReadableForm()',
                        )
                    )
    
                ));
                //var_dump($dataProvider->getData());
            ?>
        <?php 
        $this->widget('zii.widgets.jui.CJuiButton', array(
                    'name'=>'start_execution',
                    'buttonType'=>'link',
                    'themeUrl'=>'/ext/css',
                    'theme'=>'lighty',
                    'caption'=>'Proceed on to execution',
                    'url'=>array('site/write'),   
                    'htmlOptions'=>array(
                        'style'=>'float:right;right:180px;margin-bottom:20px;'
                    )

        ));
        ?>
        </div>

        <br />
    </div>

</div>

