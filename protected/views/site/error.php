<?php
$this->pageTitle = Yii::app()->name . ' - Error';
$this->breadcrumbs = array(
    'Error',
);
?>

<div class="container">

    <div class="well">
        <div class=>

        <h2>Error <?php echo $code; ?></h2>
        </div>

        <div class="error">
            <?php echo CHtml::encode($message); ?>
        </div>
    </div>

</div>
