<?php
$this->breadcrumbs = [
    'Writing the Program'
];

Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . '/css/app/write.css');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . '/js/site/app/write.js');
?>

<div class="container">

    <div class="container-fluid well">
        <div class="row-fluid" style="text-align:center;">
            <h2>Step 1 of 2: Writing the Program</h2>
        </div>

        <div class="row-fluid">

            <div class="span5 well" style="padding-bottom:0;">
                <div class="row-fluid" style="text-align: center;">
                    <div class="subtitle">
                        New Instruction
                    </div>
                </div>
                <br />
                <div class="row-fluid form">

                    <?php
                    $form = $this->beginWidget('CActiveForm', [
                        'id' => 'add-instruction-form',
                        'htmlOptions' => [
                            'style' => 'padding-bottom:0;',
                        ],
                        //'enableClientValidation'=>false,
                        'enableAjaxValidation' => true,
                        'focus' => [$model, 'mnemonic'],
                        'clientOptions' => [
                            'validateOnSubmit' => true
                            ]]);
                    ?>

                    <div class="controls controls-row">
                        <?php echo $form->errorSummary($model, null, null, ['class' => 'alert alert-error']); ?>    

                    </div>


                    <div class="controls control-group">

                        <div class="row-fluid">

                            <?php echo $form->labelEx($model, 'mnemonic'); ?>
                            <?php
                            echo $form->dropDownList($model, 'mnemonic', Instruction::getValidInstructions(), [
                                'empty' => 'Please Select',
                                'class' => "span12",
                            ]);
                            ?>
                            <?php echo $form->error($model, 'mnemonic'); ?>
                        </div>


                    </div>

                    <div class="controls control-group">
                        <div class="controls">
                            <?php echo $form->labelEx($model, 'source_param'); ?>
                        </div>
                        <div class="controls controls-row">

                            <div class="span1">
                                <?php
                                echo $form->checkBox($model, 'source_param_indirection', [
                                    'class' => 'param-indirection',
                                    'rel' => 'tooltip',
                                    'title' => 'check this box to use this register/constant indirectly']);
                                ?>
                            </div>
                            <div class="span8">
                                <?php
                                echo $form->dropDownList($model, 'source_param', Program::getValidTargetableRegisters(), [
                                    'empty' => 'Please Select',
                                    'style' => "width:100%;"
                                ]);
                                ?>
                            </div>
                            <div class="span3">
                                <?php
                                echo $form->textField($model, 'source_constant', [
                                    'style' => 'display:none;',
                                    'class' => 'input-mini'
                                ]);
                                ?>

                            </div>
                        </div>

                    </div>  
                    <div class="controls control-group">
                        <div class="controls">
                            <?php echo $form->labelEx($model, 'target_param'); ?>
                        </div>
                        <div class="controls controls-row">
                            <div class="span1">
                                <?php
                                echo $form->checkBox($model, 'target_param_indirection', [
                                    'class' => 'param-indirection',
                                    'rel' => 'tooltip',
                                    'title' => 'check this box to use this register/constant indirectly']);
                                ?>

                            </div>

                            <div class="span8">
                                <?php
                                echo $form->dropDownList($model, 'target_param', Program::getValidTargetableRegisters(), [
                                    'empty' => 'Please Select',
                                    'style' => "width:100%;"
                                ]);
                                ?>
                            </div>
                            <div class="span3">
                                <?php
                                echo $form->textField($model, 'target_constant', [
                                    'style' => 'display:none;',
                                    'class' => 'input-mini'
                                ]);
                                ?>

                            </div>
                        </div>
                    </div>
                    <br />
                    <br />
                    <div class="controls controls-row">
                        <?php
                        echo CHtml::submitButton('add instruction', [
                            'class' => 'btn btn-primary span6 offset3',
                            'id' => 'add-instruction-button'
                        ]);
                        ?>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
            <div class="well span7">
                <div class="subtitle">Memory Contents</div>
                <br />
                <?php
                $this->widget('bootstrap.widgets.BootGridView', [
                    'type' => 'striped bordered',
                    'id' => 'instructions-grid',
                    'selectableRows' => null,
                    'dataProvider' => $dataProvider,
                    'filter' => null,
                    'template' => "{items}",
                    'htmlOptions' => [
                        'style' => 'margin:8px;'
                    ],
                    'columns' => [
                        [
                            'header' => 'Address',
                            'value' => '$row',
                            'htmlOptions' => [
                                'style' => 'width:50px;'
                            ]
                        ],
                        [
                            'header' => 'Content',
                            'value' => '$data->humanReadableForm()'
                    ]]]);
                ?>
                <br />

                <div class="controls controls-row">

                    <div class="span4 offset1">

                        <?php
                        echo Chtml::htmlButton('erase contents', [
                            'class' => 'btn btn-danger span12',
                            'onclick' => 'window.location.href="' . Yii::app()->createUrl('site/erase_memory') . '";',
                            'rel'=>'tooltip',
                            'title'=>"Erase all memory contents and start anew."
                        ])
                        ?>
                    </div>

                    <div class="span6">

                        <?php
                        echo Chtml::htmlButton('run', [
                            'class' => 'btn btn-primary span12',
                            'onclick' => 'window.location.href="' . Yii::app()->createUrl('site/execute') . '";'
                        ])
                        ?>
                    </div>

                </div>




            </div>

        </div>
    </div>
</div>

