<div class="row content-block chat-form">
    <h1><?php echo $this->pageTitle ?></h1>

    <form action="<?php echo ('add' == $type) ? Yii::app()->createUrl('/chat/addpost') : Yii::app()->createUrl('/chat/editpost/id/' . $this->chat->id) ?>" method="post" id="chat-form">
        <?php if (isset($errors) && !empty($errors)):?>
        <div class="alert alert-error">
            <?php foreach ($errors as $message): ?>
            <p><?php echo $message ?></p>
            <?php endforeach ?>
        </div>
        <?php endif ?>

        <div class="control-group<?php if (!$chat->validate(array('name'))):?> error<?php endif ?>">
            <label>Название</label>
            <input type="text" class="span3" placeholder="Введите название чата" name="chat[name]" id="chat-name" value="<?php echo $chat->name ?>">
        </div>

        <?php if ('edit' == $type):?>
        <div class="control-group">
            <p>Для того чтобы пригласить друзей в чат - отправьте им ссылку: <span class="input-xlarge uneditable-input">http://<?php echo $_SERVER['SERVER_NAME'] . '/' . $this->chat->uri ?></span></p>
        </div>
        <?php endif ?>

        <div class="control-group">
            <button type="submit" class="btn"><?php if ('add' == $type):?>Добавить<?php else:?>Сохранить<?php endif ?></button>
        </div>
    </form>
</div>