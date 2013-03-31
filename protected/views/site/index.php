<div class="container">
    <div class="well" style="text-align:center;">
        
        <div class="row-fluid">

            <h2>Microinstruction Simulator</h2>
        </div>

        <div class="row-fluid">
            <br />
            <br />
            <div class="row-fluid">

                    <?php echo CHtml::htmlButton('Write a Program', [
                        'class' => 'btn btn-large btn-primary span4 offset4',
                        'style'=>'height:70px;',
                        'onclick'=>'window.location.href="' . Yii::app()->createUrl('site/write') . '";'
                        ]); ?>
                    <br /><br />
                    

            </div>
            <br />
            <br />
            <br />
            <br />
            <div class="row-fluid">


                    <p>Coming soon:</p>
                    <div>
                        <?php
                        echo CHtml::htmlButton('Introduction Video', [
                            'class' => 'btn btn-large btn-info span4 offset4',
                            'disabled' => "disabled",
                        ]);
                        ?>
                        <br /><br /><br />
                    </div>

                    <div>

                        <?php
                        echo CHtml::htmlButton('Documentation', [
                            'class' => 'btn btn-large btn-info span4 offset4',
                            'disabled' => "disabled",
                        ]);
                        ?>

                    </div>
                    <br />
                    <br />
                    <br />
                    <div>

                        <?php
                        echo CHtml::htmlButton('Source Code', [
                            'class' => 'btn btn-large btn-info span4 offset4',
                            'disabled' => "disabled",
                        ]);
                        ?>

                    </div>
                    <br />
                    <br />

            </div>

        </div>

    </div>
</div>





