<form action="login_submit" method="POST">
    <input type="hidden" name="fw" value="<?php echo base_url();?>article/displist/0">
    <div><span>用户名：</span><input type="text" name="username"></div>
    <div><span>密码：</span><input type="password" name="password"></div>
    <div><input type="submit" value="登录"></div>
</form>