<?php
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Immobilien'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="immobilien-create">



    <?=
    $this->render('_form', [
        'model' => $model,
        'model_Dateianhang' => $model_Dateianhang
    ])
    ?>

</div>
