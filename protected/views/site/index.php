<?php $this->pageTitle=Yii::app()->name; ?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<?php 
    $this->widget('zii.widgets.jui.CJuiButton', array(
		'name'=>'linktoapp',
                'buttonType'=>'link',
                'themeUrl'=>'/ext/css',
                'theme'=>'lighty',
		'caption'=>'Open App',
                 'url'=>array('site/write'),    
		
));
?>