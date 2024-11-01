<form id="auth_form" action="create_script" enctype="multipart/form-data" method="post" autocomplete="off">
    <h1>Создание пользователя</h1>
    <div>
        <label for="mail">Электронная почта:</label>
        <input type="email" name="mail" autocomplete="off">
    </div>
    <div>
        <label for="name">Пароль:</label>
        <input type="password" name="password" autocomplete="new-password">
    </div>
    <div>
        <label for="status">Статус:</label>
        <select name="status" id="status">
            <option value="1">В сети</option>
            <option value="2">Не в сети</option>
            <option value="3">Не беспокоить</option>
        </select>
    </div>
    <div class="image-select">
        <img id="output" src="static/user-default.png" alt="Profile avatar">
        <div>
            <input value="default" accept="image/*" type="file" limit="20000" name="profile_image" id="profile_image">
            <button>
                Сбросить
            </button>
        </div>
    </div>
    <div>
        <input type="submit" value="Создать">
    </div>
</form>
<script>
    document.querySelector('.image-select input').addEventListener('change', (e) => {
        let output = document.getElementById("output");
        output.src = URL.createObjectURL(e.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src); // free memory
        }
    });
</script>