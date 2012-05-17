<?php       
//Yii::app()->clientScript->registerCoreScript('jquery.ui');

Yii::app()->getClientScript()->registerCssFile('css/app/write.css');
//Yii::app()->getClientScript()->registerCssFile('css/select/ui-selectmenu.css');

Yii::app()->getClientScript()->registerCssFile('css/common/common.css');

Yii::app()->getClientScript()->registerScriptFile('js/site/app/write.js');
//Yii::app()->getClientScript()->registerScriptFile('js/select/ui-selectmenu.js');

?>

<div class="ui-widget">
    <div class="panel-div ui-corner-all " id="main-div">
        
        <div class="step-div">Step 1 of 2: Writing the Program</div>
        
        <div class="well app-half-div ui-corner-all" id="left-div">
            <div class="subtitle">New Instruction</div>
           
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
                    <a href="#" rel="tooltip" title="check this box to use this register/constant indirectly">
                        <?php echo $form->checkBox($model,'target_reg_indirection',array('class'=>'param-indirection')); ?>
                    </a>    
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
                    <a href="#" rel="tooltip" title="check this box to use this register/constant indirectly">
                        <?php echo $form->checkBox($model,'source_reg_indirection',array('class'=>'param-indirection')); ?>
                    </a> 
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
                    <?php  
                    
//                    $this->widget('zii.widgets.jui.CJuiButton', array(
//                        'name'=>'submit',
//                        'caption'=>'Submit',
//                        )); 
  
                   $this->widget('bootstrap.widgets.BootButton', array(
                        'label'=>'add instruction',
                        'htmlOptions'=>array(
                            'id'=>'add-instruction-button',
                            
                        ),                        
                        'type'=>'primary', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                        'size'=>'normal', // '', 'large', 'small' or 'mini'
                    )); 
                    
                    
                    ?>
                </div>
                
     
             </div>  
            <?php $this->endWidget(); ?>
            
        </div>
        <div class="well app-half-div ui-corner-all" id="right-div">
            <div class="subtitle">Memory Contents</div>
            <?php $this->widget('bootstrap.widgets.BootGridView', array(
                    'type'=>'striped bordered',
                    'dataProvider'=>$dataProvider,
                    'template'=>"{items}",
                    'columns'=>array(
                        array(
                            'name'=>'Address', 
                            'header'=>'Address',
                            'value'=>'$row'
                            ),
                        array(
                            'name'=>'Content', 
                            'header'=>'Content',
                            'value'=>'$data->humanReadableForm()'
                            ),
                    ),
                )); 
            ?>
            
            
            <?php /*
           
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider'=>$dataProvider,
                    'id'=>'memory-grid',
                    'summaryText'=>false,
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
             * */

            ?>
        <?php 
        
//        $this->widget('zii.widgets.jui.CJuiButton', array(
//                    'name'=>'start_execution',
//                    'buttonType'=>'link',
//           //         'themeUrl'=>'/ext/css',
//           //         'theme'=>'lighty',
//                    'caption'=>'Proceed on to execution',
//                    'url'=>array('site/execute'),   
//                    'htmlOptions'=>array(
//                        'style'=>'float:right;right:180px;margin-bottom:20px;'
//                    )

//        ));
        
                   $this->widget('bootstrap.widgets.BootButton', array(
                        'label'=>'proceed on to execution',
                        'htmlOptions'=>array(
                            'onclick'=>'window.location.href="'.Yii::app()->createUrl('site/execute').'";',
                            'foo'=>'bar',
                            'href'=>'foo',
                        ),                        
                        'type'=>'primary', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                        'size'=>'normal', // '', 'large', 'small' or 'mini'
                    )); 
        
        
        ?>
            
        </div>

        <br style="clear:both;"/>
    </div>

</div>

