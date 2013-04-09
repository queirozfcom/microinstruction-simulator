<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . '/css/bootstrap-simple.min.css'); ?>
        <?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . '/css/common/common.css'); ?>
        <?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . '/css/form.css'); ?>
        
        <?php Yii::app()->getClientScript()->registerCoreScript('jquery'); ?>
        <?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/bootstrap.min.js'); ?>

        <?php Yii::app()->getClientScript()->registerScript('trigger-tooltips', '
$(document).ready(function(){
    $(\'[rel="tooltip"]\').tooltip();
});
', CClientScript::POS_END); ?>

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>

        <div class="container">

            <div class="row-fluid">



                <div id="mainmenu">
                    <?php
                    $this->widget('bootstrap.widgets.BootNavbar', array(
                        'fixed' => true,
                        'brand' => 'Microinstruction Simulator',
                        'brandUrl' => '#',
                        'collapse' => true, // requires bootstrap-responsive.css
                        'items' => array(
                            array(
                                'class' => 'bootstrap.widgets.BootMenu',
                            ),
//                            array(
//                                'class' => 'bootstrap.widgets.BootMenu',
//                                'htmlOptions' => array('class' => 'pull-right'),
//                                'items' => array(
//                                    array('label' => 'About', 'url' => '#', 'items' => array(
//                                            array('label' => 'Author', 'url' => '#'),
//                                            array('label' => 'Credits', 'url' => '#'),
//                                            '---',
//                                            array('label' => 'Contact', 'url' => '#'),
//                                    )),
//                                ),
//                            ),
                        ),
                    ));
                    ?>
                </div><!-- mainmenu -->

            </div>

            <div class="row-fluid">


                <?php if (isset($this->breadcrumbs)): ?>
                    <?php
                    $this->widget('bootstrap.widgets.BootBreadcrumbs', array(
                        'links' => $this->breadcrumbs,
                    ));
                    ?>
                <?php endif ?>
            </div>
            
            
            <?php echo $content; ?>

            <div class="clear"></div>

            <div id="footer">
                Free as in "free beer"
                <br />
                    <?php echo date('Y'); ?>
                <br/>
                <?php echo Yii::powered(); ?>
            </div><!-- footer -->

        </div><!-- page -->

    </body>
</html>
