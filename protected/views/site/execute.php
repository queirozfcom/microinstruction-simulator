<?php
$this->breadcrumbs = [
    'Writing the Program' => ['/site/write'],
    'Running the Program'
];

Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->getClientScript()->registerScriptFile('js/site/app/execute.js');
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . '/css/app/execute.css');
?>
<div class="container">
    <div class="container-fluid well">
        <div class="row-fluid" style="text-align:center;">
            <h2>Step 2 of 2: Running the Program</h2>
        </div>

        <div class="row-fluid">
            <div class="well span6">
                <div class="subtitle">Memory Contents</div>
                <?php
                $this->widget('bootstrap.widgets.BootGridView', array(
                    'type' => 'striped bordered',
                    'summaryText' => false,
                    'id' => 'instruction-grid',
                    'dataProvider' => $dataProvider,
                    'template' => "{items}",
                    'htmlOptions' => array(
                        'style' => 'margin:2px;'
                    ),
                    'columns' => array(
                        array(
                            'name' => 'Address',
                            'header' => 'Address',
                            'value' => '$row',
                            'htmlOptions' => array(
                                'style' => 'width:50px;'
                            )
                        ),
                        array(
                            'name' => 'Content',
                            'header' => 'Content',
                            'value' => '$data->humanReadableForm()'
                        ),
                    ),
                ));
                ?>

                <div class="controls controls-row">

                    <div class="controls controls-row">

                        <?php
                        $this->widget('bootstrap.widgets.BootButton', array(
                            'label' => 'reset PC',
                            'htmlOptions' => array(
                                'id' => 'reset-button',
                                'class' => 'span12',
                                'targeturl' => $this->createUrl('site/reset'),
                                'rel' => 'tooltip',
                                'data-title' => 'Reset PC (Program Counter) but keep Memory and Register Contents intact.'
                            ),
                            'type' => 'danger', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                            'size' => 'normal', // '', 'large', 'small' or 'mini'
                        ));
                        ?>
                    </div>
                    <br />
                    <div class="controls controls-row">
                        <?php
                        $this->widget('bootstrap.widgets.BootButton', array(
                            'label' => 'run next microinstruction',
                            'htmlOptions' => array(
                                'class' => 'span4',
                                'id' => 'run-next-microinstruction-button',
                                'targeturl' => $this->createUrl('site/run_next_microinstruction'),
                                'rel' => 'tooltip',
                                'data-title' => 'Run the next microinstruction.'
                            ),
                            'type' => 'warning', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                            'size' => 'normal', // '', 'large', 'small' or 'mini'
                        ));
                        ?>
                        <?php
                        $this->widget('bootstrap.widgets.BootButton', array(
                            'label' => 'run next instruction',
                            'htmlOptions' => array(
                                'class' => 'span4',
                                'id' => 'run-next-instruction-button',
                                'targeturl' => $this->createUrl('site/run_next_instruction'),
                                'rel' => 'tooltip',
                                'data-title' => 'Fetch the next Instruction from Memory and run it.'
                            ),
                            'type' => 'inverse', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                            'size' => 'normal', // '', 'large', 'small' or 'mini'
                        ));
                        ?>

                        <?php
                        $this->widget('bootstrap.widgets.BootButton', array(
                            'label' => 'run program',
                            'htmlOptions' => array(
                                'class' => 'span4',
                                'id' => 'run-everything-button',
                                'targeturl' => $this->createUrl('site/run_everything'),
                                'rel' => 'tooltip',
                                'data-title' => 'Run the whole thing. Program stops automatically when the PC reaches the last valid Memory line.'
                            ),
                            'type' => 'success', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                            'size' => 'medium', // '', 'large', 'small' or 'mini'
                        ));
                        ?>

                    </div>



                </div>

            </div>
            <div class="well span6" style="min-height: 337px;">
    <!--<div class="subtitle">Current Instruction: <br /><span id="current-instruction-span">&nbsp;</span></div>-->
    <!--<div class="subtitle">Associated Microprogram: <span id="current-microinstruction-span"><br /><br /><br /><br /></span></div>-->
                <div class="subtitle" id="log">
                    LOG:<br />
                    <div id="log-contents">

                    </div>
                </div>
            </div>
        </div>    




        <div class="well app-half-div" id="bottom-div">
            <div class="subtitle">Register Contents</div>

            <div id="mux-b-regs-div">
                <div class="display-div" id="r2-contents">
                    R2:<br />
                    <div class="reg-contents-input">
                        <span rel="tooltip" title="0">00000000000000000000000000000000</span>
                    </div>
                </div>
                <div class="display-div" id="r3-contents" >
                    R3:<br />
                    <div class="reg-contents-input">
                        <span rel="tooltip" title="0">00000000000000000000000000000000</span>
                    </div>
                </div>
                <div class="display-div" id="r4-contents">
                    R4:<br />
                    <div class="reg-contents-input">
                        <span rel="tooltip" title="0">00000000000000000000000000000000</span>
                    </div>
                </div>
                <div class="display-div" id="ar2-contents">
                    AR2:
                    <div class="reg-contents-input">
                        <span rel="tooltip" title="0">00000000000000000000000000000000</span>
                    </div>
                </div>
            </div>
            <div id="mux-a-regs-div">
                <div class="display-div" id="r0-contents">
                    R0:<br />
                    <div class="reg-contents-input">
                        <span rel="tooltip" title="0">00000000000000000000000000000000</span>
                    </div>
                </div>
                <div class="display-div" id="r1-contents" >
                    R1:<br />
                    <div class="reg-contents-input">
                        <span rel="tooltip" title="0">00000000000000000000000000000000</span>
                    </div>
                </div>
                <div class="display-div" id="pc-contents">
                    PC:<br />
                    <div class="reg-contents-input">
                        <span rel="tooltip" title="0">00000000000000000000000000000000</span>
                    </div>
                </div>
                <div class="display-div" id="ar1-contents">
                    AR1:
                    <div class="reg-contents-input">
                        <span rel="tooltip" title="0">00000000000000000000000000000000</span>
                    </div>
                </div>
            </div>
            <div id="memory-related-regs-div">
                <div class="display-div" id="mdr-contents">
                    MDR:<br />
                    <div class="reg-contents-input">
                        <span rel="tooltip" title="0">00000000000000000000000000000000</span>
                    </div>
                </div>
                <div class="display-div" id="mar-contents" >
                    MAR:<br />
                    <div class="reg-contents-input">
                        <span rel="tooltip" title="0">00000000000000000000000000000000</span>
                    </div>
                </div>

            </div>
            <br style="clear:both;" />
            <div id="ir-regs-div">
                <div class="display-div" id="ir-contents">
                    IR:<br />
                    <div class="reg-contents-input">
                        <span rel="tooltip" title="0">00000000000000000000000000000000</span>
                    </div>
                </div>
            </div>    
            <br style="clear:both;" />
        </div>
    </div>

</div>

