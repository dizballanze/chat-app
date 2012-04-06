<?php if (Yii::app()->user->isGuest):?>
<div class="hero-unit">
    <h1>Приветствуем вас!</h1>
    <p>Для того чтобы создать чат необходимо пройти простую регистрацию. Вы можете зарегистрироваться указав ваш email или при помощи аккаунта в социальных сетях. Процесс регистрации не займет много времени.</p>
    <p><a class="btn btn-primary btn-large" href="/registration">Присоеденится &raquo;</a></p>
</div>

<div class="row">
    <div class="span6">
        <h1 class="">Войти через email</h1>
        <form action="<?php echo Yii::app()->createUrl('user/loginpost') ?>" method="post" id="login-form">
            <?php if (Yii::app()->user->hasFlash('login-error')):?>
                <div class="alert alert-error">Неправильный логин и/или пароль</div>
            <?php endif ?>
            <label>E-mail / Ник</label>
            <input type="text" name="login[login]" id="login-login" class="span3" placeholder="Введите ваш Email-адрес или Ник" value="<?php if (Yii::app()->user->hasFlash('login')) echo Yii::app()->user->getFlash('login') ?>">
            <label>Пароль</label>
            <input type="password" name="login[password]" id="login-password" class="span3" placeholder="Введите ваш пароль">
            <label class="checkbox">
                <input type="checkbox" id="login-remember" name="login[remember]"> Запомнить
            </label>
            <button type="submit" class="btn">Войти</button>
        </form>
    </div>
    <div class="span6">
        <h1>Войти через социальные сети</h1>
        <a href="<?php echo Yii::app()->createUrl('/user/service/service/twitter') ?>"><img src="<?php echo Yii::app()->request->baseUrl; ?>/img/tw_login.png" alt="Twitter login"></a>
    </div>
</div>


<?php endif ?>