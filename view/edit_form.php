<form action="edit_script" enctype="multipart/form-data" id="auth_form" method="post">
    <h1>Редактирование</h1>
    <div>
        <label for="password">Пароль:</label>
        <input type="password" name="password" autocomplete="new-password">
    </div>
    <div>
        <label for="status">Статус:</label>
        <select name="status" id="status">
            <option value="1" <?php echo isset($user_info["status"]) && $user_info["status"] == 1 ? "selected" : ""?>>В сети</option>
            <option value="2" <?php echo isset($user_info["status"]) && $user_info["status"] == 2 ? "selected" : ""?>>Не в сети</option>
            <option value="3" <?php echo isset($user_info["status"]) && $user_info["status"] == 3 ? "selected" : ""?>>Не беспокоить</option>
        </select>
    </div>
    <div class="image-select">
        <img id="output" src="static/<?php echo isset($user_info["pfp"]) ? $user_info['pfp'] : "user-default.png"?>" alt="Profile avatar">
        <div>
            <input accept="image/*" type="file" limit="20000" name="profile_image" id="profile_image">
            <button>
                Сбросить
            </button>
        </div>
    </div>
    <div>
        <input type="submit" value="Редактировать">
    </div>
</form>
<script>
    document.querySelector('#profile_image').addEventListener('change', (e) => {
        let output = document.getElementById("output");
        output.src = URL.createObjectURL(e.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    });
    document.querySelector('#profile_image + button').addEventListener('click', (e) => {
        e.preventDefault();
        document.querySelector('#profile_image').files[0] = null;
        let output = document.getElementById("output");
        output.src = 'static/user-default.png';
        output.onload = function() {
            URL.revokeObjectURL(output.src);
        }
    });
</script>