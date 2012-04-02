<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'new-instruction-form-write-form',
	'enableAjaxValidation'=>false,
)); ?>

        <?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'mnemonic'); ?>
		<?php echo $form->textField($model,'mnemonic'); ?>
		<?php echo $form->error($model,'mnemonic'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'target_reg'); ?>
		<?php echo $form->textField($model,'target_reg'); ?>
		<?php echo $form->error($model,'target_reg'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'source_reg'); ?>
		<?php echo $form->textField($model,'source_reg'); ?>
		<?php echo $form->error($model,'source_reg'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'source_constant'); ?>
		<?php echo $form->textField($model,'source_constant'); ?>
		<?php echo $form->error($model,'source_constant'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'target_constant'); ?>
		<?php echo $form->textField($model,'target_constant'); ?>
		<?php echo $form->error($model,'target_constant'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'target_reg_indirection'); ?>
		<?php echo $form->textField($model,'target_reg_indirection'); ?>
		<?php echo $form->error($model,'target_reg_indirection'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'source_reg_indirection'); ?>
		<?php echo $form->textField($model,'source_reg_indirection'); ?>
		<?php echo $form->error($model,'source_reg_indirection'); ?>
	</div>
        

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->