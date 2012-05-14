<?php

Yii::app()->clientScript->registerCoreScript('jquery.ui');

Yii::app()->getClientScript()->registerScriptFile('js/site/app/execute.js');

Yii::app()->getClientScript()->registerCssFile('/ext/css/lighty/jquery-ui.css');
Yii::app()->getClientScript()->registerCssFile('css/app/execute.css');
Yii::app()->getClientScript()->registerCssFile('css/common/common.css');?>
<div class="ui-widget">
    <div class="ui-widget-content ui-corner-all" id="main-div">
        
        <div class="step-div">Step 2 of 2: Executing the Program</div>
        
        <div class="ui-widget-content app-half-div ui-corner-all" id="left-div">
            <div class="subtitle">Memory Contents</div>
            <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider'=>$dataProvider,
                    'summaryText'=>false,
                    'id'=>'instruction-grid',
                    'htmlOptions'=>array(
                      'style'=>'margin:8px;'  
                    ),
                    'selectableRows'=>1,
                    'selectionChanged'=>'function(id){alert(\'foo\')}',
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
            ?>
            <?php
                $this->widget('zii.widgets.jui.CJuiButton', array(
                    'name'=>'bootstrap-button',
                    'themeUrl'=>'/ext/css',
                    'theme'=>'lighty',        
                    'caption'=>'bootstrap',
                    'htmlOptions'=>array(
                        'targeturl'=>  $this->createUrl('site/registers')
                    )
                ));
            ?>
            
        </div>
        <div class="ui-widget-content app-half-div ui-corner-all" id="right-div">
            <div class="subtitle">Current Microprogram</div>
        </div>
        
        <div class="ui-widget-content app-half-div ui-corner-all" id="bottom-div">
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
                        11111111111111111111111111111111
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
                        11111111111111111111111111111111
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

