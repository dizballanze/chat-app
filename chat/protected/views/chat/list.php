<div class="tabbable">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#1" data-toggle="tab">История</a></li>
        <li><a href="#2" data-toggle="tab">Мои чаты</a></li>
        <li><a href="<?php echo Yii::app()->createUrl('/chat/add') ?>"><i class="icon-plus"></i> Добавить</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="1">
            <ul class="nav nav-pills nav-stacked">
                <?php /** @var Chat $chat */ foreach ($user->used_chats as $chat):?>
                <li><a href="<?php echo Yii::app()->createUrl('/' . $chat->uri) ?>"><?php echo $chat->name ?></a></li>
                <?php endforeach ?>
            </ul>
        </div>
        <div class="tab-pane" id="2">

            <div class="row">
                <div class="span2">
                    <ul class="nav nav-pills nav-stacked">
                    <?php foreach ($user->owned_chats as $chat):?>
                    <li><a href="<?php echo Yii::app()->createUrl('/chat/edit/id/' . $chat->id) ?>" class="btn"><i class="icon-pencil"></i> Редактировать</a></li>
                    <?php endforeach ?>
                    </ul>
                </div>
                <div class="span10">
                    <ul class="nav nav-pills nav-stacked">
                        <?php foreach ($user->owned_chats as $chat):?>
                        <li> <a href="<?php echo Yii::app()->createUrl('/' . $chat->uri) ?>"><?php echo $chat->name ?></a></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>