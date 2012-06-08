<?php

Yii::app()->clientScript->registerCoreScript('jquery.ui');

Yii::app()->getClientScript()->registerScriptFile('js/site/app/execute.js');

Yii::app()->getClientScript()->registerCssFile('css/app/execute.css');
Yii::app()->getClientScript()->registerCssFile('css/common/common.css');?>
<div>
    <div class="panel-div"id="main-div">
        
        <div class="step-div">Step 2 of 2: Executing the Program</div>
        
        <div class="well app-half-div" id="left-div">
            <div class="subtitle">Memory Contents</div>
            <?php $this->widget('bootstrap.widgets.BootGridView', array(
                    'type'=>'striped bordered',
                    'summaryText'=>false,
                    'id'=>'instruction-grid',
                    'dataProvider'=>$dataProvider,
                    'template'=>"{items}",
                    'htmlOptions'=>array(
                        'style'=>'margin:2px;'
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
            
                    $this->widget('bootstrap.widgets.BootButton', array(
                        'label'=>'fetch first instruction',
                        'htmlOptions'=>array(
                            'id'=>'fetch-first-instruction-button',
                           // 'style'=>'display:none;',
                            'targeturl'=>$this->createUrl('site/fetchfirst'),
                            'rel'=>'tooltip',
                            'data-title'=>'Perform fetching of first Macro from Memory and place that Macro into the Instruction Register to prepare for program Execution'
                        ),                        
                        'type'=>'primary', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                        'size'=>'normal', // '', 'large', 'small' or 'mini'
                    )); 
            
            
            /*
                $this->widget('zii.widgets.jui.CJuiButton', array(
                    'name'=>'fetch-first-instruction-button',
                    'themeUrl'=>'/ext/css',
                    'theme'=>'lighty',        
                    'caption'=>'fetch first instruction',
                    'htmlOptions'=>array(
                        'style'=>'display:none;',
                        'targeturl'=>  $this->createUrl('site/fetchfirst')
                    )
                ));
             *
             */
            
            ?>
            
        </div>
        <div class="well app-half-div ui-corner-all" id="right-div">
            <div class="subtitle">Current Instruction: <br /><span id="current-instruction-span">&nbsp;</span></div>
            <div class="subtitle">Associated Microprogram: <span id="current-microinstruction-span"><br /><br /><br /><br /></span></div>
            
        </div>
        
        <div class="well app-half-div" id="bottom-div">
            <div class="subtitle">Register Contents</div>
            
            <div id="mux-b-regs-div">
                <div class="display-div" id="r2-contents">
                    R2:<br />
                    <div class="reg-contents-input">
                        00000000000000000000000000000000
                    </div>
                </div>
                <div class="display-div" id="r3-contents" >
                    R3:<br />
                    <div class="reg-contents-input">
                        00000000000000000000000000000000
                    </div>
                </div>
                <div class="display-div" id="r4-contents">
                    R4:<br />
                    <div class="reg-contents-input">
                        00000000000000000000000000000000
                    </div>
                </div>
                <div class="display-div" id="ar2-contents">
                    AR2:
                    <div class="reg-contents-input">
                        00000000000000000000000000000000
                    </div>
                </div>
            </div>
            <div id="mux-a-regs-div">
                <div class="display-div" id="r0-contents">
                    R0:<br />
                    <div class="reg-contents-input">
                        00000000000000000000000000000000
                    </div>
                </div>
                <div class="display-div" id="r1-contents" >
                    R1:<br />
                    <div class="reg-contents-input">
                        00000000000000000000000000000000
                    </div>
                </div>
                <div class="display-div" id="pc-contents">
                    PC:<br />
                    <div class="reg-contents-input">
                        00000000000000000000000000000000
                    </div>
                </div>
                <div class="display-div" id="ar1-contents">
                    AR1:
                    <div class="reg-contents-input">
                        00000000000000000000000000000000
                    </div>
                </div>
            </div>
            <div id="memory-related-regs-div">
                <div class="display-div" id="mdr-contents">
                    MDR:<br />
                    <div class="reg-contents-input">
                        00000000000000000000000000000000
                    </div>
                </div>
                <div class="display-div" id="mar-contents" >
                    MAR:<br />
                    <div class="reg-contents-input">
                        00000000000000000000000000000000
                    </div>
                </div>
            </div>
            <br style="clear:both;" />
            <div id="ir-regs-div">
                <div class="display-div" id="ir-contents">
                    IR:<br />
                    <div class="reg-contents-input">
                        00000000000000000000000000000000
                    </div>
                </div>
            </div>    
            <br style="clear:both;" />
        </div>
    </div>

</div>

