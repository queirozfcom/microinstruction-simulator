<div class="container">
    <div class="well" style="text-align:center;">

        <div class="row-fluid">

            <h2>Microinstruction Simulator</h2>
        </div>

        <div class="row-fluid">
            
            <br />
            <br />
            
            <div class="row-fluid">

                <?php
                echo CHtml::link('Write a Program',Yii::app()->createUrl('site/write'), [
                    'class' => 'btn btn-large btn-primary span4 offset4',
                    'style' => 'height:70px;padding-top:25px;',
                ]);
                ?>
                <br /><br />


            </div>
            <br />
            <div class="row-fluid">

                <div>
                    <?php
                    echo CHtml::link('Source Code',"https://github.com/queirozfcom/microinstruction-simulator", [
                        'class' => 'btn btn-large btn-success span4 offset4',
                    ]);
                    ?>
                </div>

            </div>
            <br />

            <div class="row-fluid">

                <div>

                    <?php
                    echo CHtml::link('Documentation<br /> <span style="font-size:0.7em;">(in Portuguese)</span>',Yii::app()->createUrl('docPT'), [
                        'class' => 'btn btn-large btn-info span4 offset4',
                    ]);
                    ?>

                </div>

            </div>
            <br />
            <br />
            <br />

        </div>

    </div>

</div>
</div>





