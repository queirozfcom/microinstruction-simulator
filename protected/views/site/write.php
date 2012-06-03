<?php       

Yii::app()->getClientScript()->registerCssFile('css/app/write.css');

Yii::app()->getClientScript()->registerCssFile('css/common/common.css');

Yii::app()->getClientScript()->registerScriptFile('js/site/app/write.js');

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
                <div id="error-div">
                    <?php echo $form->error($model,'mnemonic',array('style'=>'color:red !important;')); ?>
                    <?php echo $form->error($model,'target_param',array('style'=>'color:red !important;')); ?>
                    <?php echo $form->error($model,'target_constant',array('style'=>'color:red !important;')); ?>
                    <?php echo $form->error($model,'source_constant',array('style'=>'color:red !important;')); ?>
                    
                    <?php //echo $form->errorSummary($model); ?>
                    <?php echo $form->error($model,'source_param',array('style'=>'color:red !important;')); ?>
                </div>
                <div class="row">
                        <?php echo $form->labelEx($model,'mnemonic'); ?>
                        <?php echo $form->dropDownList($model,'mnemonic',Instruction::getValidInstructions(),array(
                            'empty'=>'Please Select',
                            'class'=>'select-input'
                        )); ?>
                        
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model,'target_param'); ?>
                    
                    <a href="#" rel="tooltip" title="check this box to use this register/constant indirectly">
                    <?php echo $form->checkBox($model,'target_param_indirection',array('class'=>'param-indirection')); ?>
                    </a>    
                    <?php echo $form->dropDownList($model,'target_param', Program::getValidTargetableRegisters(),array(
                            'empty'=>'Please Select',
                        )); ?>
                    <?php echo $form->textField($model,'target_constant',
                                array(
                                    'style'=>'float:right;position:relative;right:-25px;bottom:37px;display:none;',
                                    'class'=>'input-mini'
                                    )); ?>
                </div>  
                
                <div class="row">
                    <?php echo $form->labelEx($model,'source_param'); ?>
                    
                    <a href="#" rel="tooltip" title="check this box to use this register/constant indirectly">
                        <?php echo $form->checkBox($model,'source_param_indirection',array('class'=>'param-indirection')); ?>
                    </a> 
                    <?php echo $form->dropDownList($model,'source_param', Program::getValidTargetableRegisters(),array(
                            'empty'=>'Please Select',
                        )); ?>
                    <?php echo $form->textField($model,'source_constant',
                                array(
                                    'style'=>'float:right;position:relative;right:-25px;bottom:37px;display:none;',
                                    'class'=>'input-mini'
                        )); ?>                    
                        
                </div>
                <br />
                <div class="row">
                    <?php  $this->widget('bootstrap.widgets.BootButton', array(
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
                    'htmlOptions'=>array(
                        'style'=>'margin:8px;'
                    ),
                    'columns'=>array(
                        array(
                            'name'=>'Address', 
                            'header'=>'Address',
                            'value'=>'$row',
                            'htmlOptions'=>array(
                                'style'=>'width:50px;'
                                )
                            ),
                        array(
                            'name'=>'Content', 
                            'header'=>'Content',
                            'value'=>'$data->humanReadableForm()'
                            ),
                    ),
                )); 
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

