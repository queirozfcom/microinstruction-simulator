<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        
        <?php Yii::app()->getClientScript()->registerScript('trigger-tooltips','
$(document).ready(function(){
    $(\'[rel="tooltip"]\').tooltip();
});
',  CClientScript::POS_END); ?>
        
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>

        <div class="container" id="page">

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
                        array(
                            'class' => 'bootstrap.widgets.BootMenu',
                            'htmlOptions' => array('class' => 'pull-right'),
                            'items' => array(
                                array('label' => 'Link', 'url' => '#'),
                                '---',
                                array('label' => 'Dropdown', 'url' => '#', 'items' => array(
                                        array('label' => 'Action', 'url' => '#'),
                                        array('label' => 'Another action', 'url' => '#'),
                                        array('label' => 'Something else here', 'url' => '#'),
                                        '---',
                                        array('label' => 'Separated link', 'url' => '#'),
                                )),
                            ),
                        ),
                    ),
                ));
                ?>
            </div><!-- mainmenu -->
            <?php if (isset($this->breadcrumbs)): ?>
                <?php
                $this->widget('bootstrap.widgets.BootBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                ));
                ?>
            <?php endif ?>

            <?php echo $content; ?>

            <div class="clear"></div>

            <div id="footer">
                Copyright &copy; <?php echo date('Y'); ?> by My Company.
                <br/>
                <?php echo Yii::powered(); ?>
            </div><!-- footer -->

        </div><!-- page -->

    </body>
</html>
