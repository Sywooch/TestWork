<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Профиль пользователя '.Html::encode($user->username);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
<h1><?= Html::encode($user->username) ?></h1>
	<h3 class="balance">Мой баланс: <?= $user->balance ?></h3>
<p>
<?= Html::a('Сделать перевод', ['/site/transaction'], ['class' => 'btn btn-success pull-right']) ?><br>
</p>
<h2>
Мои переводы:
</h2>
<?= GridView::widget([
        'dataProvider' => $transaction,
        'summary' => 'Показаны переводы <strong>{begin}-{end}</strong> из <strong>{totalCount}</strong>.',
        'columns' => [
            'id',
            'sender_name',
            'recipient_name',
            'sum',
        ],
]); ?>
</div>
