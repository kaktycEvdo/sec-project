<form id="auth_form" action="auth_script" method="post">
    <h1>Авторизация</h1>
    <div>
        <label for="mail">Электронная почта:</label>
        <input type="email" name="mail">
    </div>
    <div>
        <label for="name">Пароль:</label>
        <input type="password" name="password">
    </div>
    <div>
        <label for="captcha">Пройдите каптчу</label>
        <img src="captcha.php" alt="CAPTCHA" class="captcha-image">
        <button class="refresh-captcha">Изменить</button>
        <input type="text" id="captcha" name="captcha_challenge" pattern="[A-Z]{6}">
    </div>
    <div>
        <input type="submit" value="Авторизоваться">
    </div>
    <a href="reg">Регистрация</a>
</form>
<script>
    var refreshButton = document.querySelector(".refresh-captcha");
    refreshButton.addEventListener('click', e => {
        e.preventDefault();
        document.querySelector(".captcha-image").src = 'captcha.php?' + Date.now();
    });
</script>