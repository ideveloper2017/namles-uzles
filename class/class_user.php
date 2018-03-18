<?php

class Users
{

    public static $db;
    public $logged_in = null;
    public $uid = 0;
    public $userid = 0;
    public $username;
    public $sesid;
    public $email;
    public $name;
    public $membership_id = 0;
    public $memused = 0;
    public $access = null;
    public $userlevel;
    public $memvalid = null;
    public $avatar;
    public $last;
    private $lastlogin = "NOW()";

    public function __construct()
    {
        self::$db = Registry::get("DataBase");
        $this->startSession();
    }


    private function startSession()
    {
        if (strlen(session_id()) < 1)
            session_start();

        $this->logged_in = $this->logincheck();

        if (!$this->logged_in) {
            $this->username = $_SESSION['BPACMS_username'] = "Guest";
            $this->sesid = sha1(session_id());
            $this->userlevel = 0;
        }

    }

    private function loginCheck()
    {
        if (isset($_SESSION['BPACMS_username']) && $_SESSION['BPACMS_username'] != "Guest") {

            $row = $this->getUserInfo($_SESSION['BPACMS_username']);
            $this->uid = $row->id;
            $this->userid = $row->id;
            $this->username = $row->username;
            $this->name = $row->fname . ' ' . $row->lname;
            $this->group_name = $row->group_name;
            $this->email = $row->email;
            $this->avatar = $row->avatar;
            $this->userlevel = $row->userlevel;
            $this->last = $row->lastlogin;
            $this->sesid = sha1(session_id());
            return true;
        } else {
            return false;
        }
    }


    private function getUserInfo($username)
    {
        $username = sanitize($username);
        $username = self::$db->escape($username);
        $sql = "select u.*,up.*,g.name as group_name  from users u inner join user_profiles up on u.id=up.user_id
                                          inner join user_groups g on g.group_id=u.userlevel
                                         where  u.username='{$username}' or u.email='{$username}'";


        $row = self::$db->first($sql);
        if (!$username)
            return false;

        return ($row) ? $row : 0;

    }


    public static function getUsers($no_guest = false)
    {
        $users = array();
        $sql = "select * from users\n";

        $result = self::$db->query($sql);

        while ($user = self::$db->fetch($result)) {
            $sel = ($user->id == $no_guest) ? "selected=\"selected\"" : "";
            print "<option value=\"" . $user->uid . "\"" . $sel . ">";
            print $user->username . "</option>\n";
        }
    }

    public static function getGroups($no_guests = false)
    {
        $groups = array();
        $sql = "SELECT group_id, title, name, isadmin
                FROM user_groups\n";
        if (!$no_guests) {
            $sql .= "WHERE name <> 'guest'\n";
        }
        $sql .= "ORDER BY isadmin desc";
        $result = self::$db->query($sql);
        if (!$no_guests) {
            print "<option value='0' selected='selected'>Все группы</option>";
        }
        if (self::$db->numrows($result)) {
            while ($group = self::$db->fetch($result)) {
                $sel = ($group->group_id == $no_guests) ? " selected=\"selected\"" : "";

                print "<option value=\"" . $group->group_id . "\"" . $sel . ">";
                print $group->title . "</option>\n";


            }
        }

        return $groups;

    }

    public function is_Admin()
    {
        return ($this->userlevel == 1 or $this->userlevel == 2);
    }

    public function login($username, $password)
    {

        if ($username == "" && $password == "") {
            Core::$msgs['username'] = "Foydalanuvchi nomi va parolni kiriting.";
        } else {
            $status = $this->checkStatus($username, $password);
            switch ($status) {
                case 0:
                    Core::$msgs['username'] = "Логин ва пароль маълумотлар базасидан топиламади";
                    break;

                case 1:
                    Core::$msgs['username'] = "Сизни логиннингиз кириш учун тақиқланди!!!";
                    break;

                case 2:
                    Core::$msgs['username'] = "Сизни логинингиз актив холат эмас";
                    break;

                case 3:
                    Core::$msgs['username'] = "Сизни электрон манзилингизни текшириш керак";
                    break;
            }
        }
        if (empty(Core::$msgs) && $status == 5) {
            $row = $this->getUserInfo($username);
            $this->uid = $_SESSION['uid'] = $row->id;
            $this->username = $_SESSION['BPACMS_username'] = $row->username;
            $this->name = $_SESSION['BPACMS_name'] = $row->fname . ' ' . $row->lname;
            $this->email = $_SESSION['email'] = $row->email;
//            $this->address = $_SESSION['address'] = $row->address;
//            $this->city = $_SESSION['cityname'] = $row->cityname;
            $this->cityid = $_SESSION['cityid'] = $row->city_id;
            $this->phone = $_SESSION['phone'] = $row->phone_number;
            $this->userlevel = $_SESSION['userlevel'] = $row->userlevel;
            $this->created = $_SESSION['created'] = $row->created_at;
            $this->last = $_SESSION['last'] = $row->lastlogin;

            $data = array(
                'lastlogin' => $this->lastlogin,
                'lastip' => $_SERVER['REMOTE_ADDR'],
            );
            self::$db->update("users", $data, "username='{$username}'");
            return true;
        } else {

            Core::msgStatus();
        }
    }

    public function checkStatus($username, $password)
    {
        $username = self::$db->escape($username);
        $password = self::$db->escape($password);
        $sql = "select password, active from users u inner join user_profiles up on up.user_id=u.id where u.username='{$username}' or u.email='{$username}'";
        $result = self::$db->query($sql);
        if (self::$db->numrows($result) == 0)
            return 0;

        $row = self::$db->first($sql);
        $entered_pass = md5($password);

        switch ($row->active) {
            case "b":
                return 1;
                break;

            case "n":
                return 2;
                break;

            case "t":
                return 3;
                break;

            case "y" && $entered_pass == $row->password:
                return 5;
                break;
        }
    }

    public function getUserList()
    {
        if (!empty(Core::$post['filter'])) {
            $where = "where u.username like '%" . Core::$post['filter'] . "%'";
            $_SESSION['filter'] = Core::$post['filter'];
        } else {
            $_SESSION['filter'] = "";
        }

        $pager = Registry::get("Paginator");
        $counter = self::$db->numrows(self::$db->query("select * from users u left join user_profiles up on up.user_id=u.id left join user_groups ug on ug.group_id=u.userlevel {$where}\n"));
        $pager->items_total = $counter;
        $pager->default_ipp = 10;
        $pager->paginate();
        if ($counter == 0) {
            $pager->limit = null;
        }
        $sql = "select u.*, u.id as uid,up.*,ug.title as `group` from users u left join user_profiles up on up.user_id=u.id left join user_groups ug on ug.group_id=u.userlevel {$where}\n" . $pager->limit;

        $rows = self::$db->fetch_all($sql);
        return ($rows) ? $rows : 0;
    }

    public function getGroupList()
    {
        if (!empty(Core::$post['filter'])) {
            $where = "where title like '%" . Core::$post['filter'] . "%'";
        }
        $pager = Registry::get("Paginator");
        $counter = self::$db->numrows(self::$db->query("select * from user_groups {$where}\n"));
        $pager->items_total = $counter;
        $pager->default_ipp = 10;
        $pager->paginate();
        if ($counter == 0) {
            $pager->limit = null;
        }
        $sql = "select * from user_groups {$where}\n";
        $rows = self::$db->fetch_all($sql);
        return ($rows) ? $rows : 0;
    }

    public function logout()
    {
        unset($_SESSION['BPACMS_username']);
        unset($_SESSION['email']);
        unset($_SESSION['name']);
        unset($_SESSION['uid']);
        session_destroy();
        session_regenerate_id();
        $this->logged_in = false;
        $this->username = "Guest";
        $this->userlevel = 0;
    }

    private function emailExists($email)
    {

        $sql = self::$db->query("SELECT email"
            . "\n FROM users"
            . "\n WHERE email = '" . sanitize($email) . "'"
            . "\n LIMIT 1");

        if (self::$db->numrows($sql) == 1) {
            return true;
        } else
            return false;
    }

    public function usernameExists($username)
    {
        if (@strlen(self::$db->escape($username)) < 4)
            return 1;
        $valid_uname = "/^[a-zA-Z0-9_-]{4,15}$/";
        if (!preg_match($valid_uname, $username))
            return 2;

        $sql = self::$db->query("SELECT username"
            . "\n FROM users"
            . "\n WHERE username = '" . $username . "'"
            . "\n LIMIT 1");

        $count = self::$db->numrows($sql);
        return ($count > 0) ? $count : false;
    }

    public function getUserStatus($status)
    {
        switch ($status) {
            case "y":
                $display = '<span >Активный</span>';
                break;

            case "n":
                $display = '<span >Неактивен</span>';
                break;

            case "t":
                $display = '<span >В ожидании</span>';
                break;

            case "b":
                $display = '<span >Запреть</span>';
                break;
        }

        return $display;
    }

    public static function getUserRowById($id)
    {
        $sql = "select u.*,u.id as uid,up.*,ug.title as `group` from users u left join user_profiles up on up.user_id=u.id left join user_groups ug on ug.group_id=u.userlevel where u.id=" . $id;
        $rows = self::$db->first($sql);
        return ($rows) ? $rows : 0;
    }

    public static function getGroupRowById($id)
    {
        $sql = "select * from user_groups where group_id=" . $id;
        $rows = self::$db->first($sql);
        return ($rows) ? $rows : 0;
    }

    private function userGroup($userlevel)
    {
        $rows = self::$db->fetch_all("select * from groups");
        if ($rows) {
            if ($rows->id = $userlevel) {
                $this->userlevel = $rows->id;
            }
        }

    }

    public static function proccessUser($user)
    {
        $udata = array(
            'username' => $user['username'],
            'email' => $user['email'],
            'password' => $user['password'],
            'created_at' => 'now()',
            'userlevel' => $user['group'],
            'active' => $user['active']);


        if (Core::$id) {
            self::$db->update("users", $udata, "id=" . Core::$id);
        } else {

            self::$db->insert("users", $udata);
        }
    }

    public function insertorupdateProfile($user, $id)
    {
        $img=Registry::get("DataBase")->getFieldById('avatar','user_profiles',"user_id='{$id}'");

        $pdata = array(
            'fname' => $user['fname'],
            'lname' => $user['lname'],
            'user_id' => $id,
            'city_id' => 1,
            'phone' => $user['phone'],
            'avatar' => $user['avatar']?$user['avatar']:$img,
            'birthdate' => $user['birthdate'],
            'gender' => $user['gender'],
            'website' => $user['website']
        );

        if (Core::$id) {
            self::$db->update("user_profiles", $pdata, "user_id=" . Core::$id);
        } else {
            self::$db->insert("user_profiles", $pdata);
        }
    }

    public function proccessGroup($group)
    {
        $data = array('title' => $group['title'],
            'name' => $group['name'],
            'description' => $group['description'],
            'isadmin' => $group['isadmin']);

        (Core::$id) ? self::$db->update("user_groups", $data, "group_id=" . Core::$id) : self::$db->insert("user_groups", $data);
    }


    private function isValidEmail($email)
    {
        if (function_exists('filter_var')) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return true;
            } else
                return false;
        } else
            return preg_match('/^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $email);
    }

    public function guestGroup()
    {
        $db = Registry::get("DataBase");
        $result = $db->query("SELECT group_id FROM user_groups WHERE name='guest'");
        if ($db->numrows($result)) {
            $data = $db->first("SELECT group_id FROM user_groups WHERE name='guest'");
            return $data->group_id;
        } else {
            return 0;
        }
    }

    public function register()
    {
        Core::checkPost("username", 'Пожалуйста, введите правильное имя пользователя');
        if ($value = $this->usernameExists(Core::$post['username'])) {
            if ($value == 1)
                Core::$msgs['username'] = "Имя пользователя слишком короткое (менее 4 символов).";
            if ($value == 2)
                Core::$msgs['username'] = "Недопустимые символы найдены в Псевдоним.";
            if ($value == 3)
                Core::$msgs['username'] = "Извините, этот Имя пользователя уже занято";
        }
        Core::checkPost('fname', "Имя");
        Core::checkPost('lname', "Фамилия");
        Core::checkPost('password', "Пожалуйста, введите Пароль верный");

        if (strlen(Core::$post['password']) < 6)
            Core::$msgs['pass'] = "Пароль слишком короткий (длиной менее 6 символов)";
        elseif (!preg_match("/^[a-z0-9_-]{6,15}$/", (Core::$post['password'] = trim(Core::$post['password']))))
            Core::$msgs['password'] = "Пароль вводится не алфавитно-цифровой.";
        elseif (Core::$post['password1'] != Core::$post['password1'])
            Core::$msgs['password'] = "Ваш пароль не соответствует подтвержденного пароля !.";

        Core::checkPost('email', "Пожалуйста, введите действующий адрес электронной почты");

        if ($this->emailExists(Core::$post['email']))
            Core::$msgs['email'] = "Вступил E-mail адрес уже используется.";

        if (!$this->isValidEmail(Core::$post['email']))
            Core::$msgs['email'] = "Вступил E-mail адрес не является действительным.";

        Core::checkPost('captcha', "Пожалуйста, введите код капчи.");
        if (empty(Core::$msgs)) {
            $pass = sanitize(Core::$post['password']);
            $active = "y";
            $data = array(
                'username' => sanitize(Core::$post['username']),
                'password' => md5(Core::$post['password']),
                'email' => sanitize(Core::$post['email']),
                'userlevel' => '3',
                'active' => $active,
                'created_at' => "NOW()");
            self::$db->insert("users", $data);
            $lastid = self::$db->insertId();
            $pdata = array(
                'user_id' => $lastid,
                'fname' => sanitize(Core::$post['fname']),
                'lname' => sanitize(Core::$post['lname'])
            );
            self::$db->insert("user_profiles", $pdata);

        } else {
            Core::msgStatus();
        }
    }


    public function getUserData($username)
        {

            $sql = "select u.*,up.*,g.name as group_name,UNIX_TIMESTAMP(u.lastlogin) as lastseen  from users u inner join user_profiles up on u.id=up.user_id
                                              inner join user_groups g on g.group_id=u.userlevel
                                             where  u.username='{$username}'";
            $row = self::$db->first($sql);

            return ($row) ? $row : 0;
        }

    public function updateprofile()
    {

        Core::checkPost('fname', "Имя");
        Core::checkPost('lname', "Фамилия");
        Core::checkPost('email', "Электронной почты");
        Core::checkPost('birthdate', "Дата рождения");
        Core::checkPost('phone', "Телефон");

        if (!$this->isValidEmail($_POST['email']))
            Core::$msgs['email'] = "Вступил E-mail адрес не является действительным.";

        if (!empty($_FILES['avatar']['name'])) {
            if (!preg_match("/(\.jpg|\.png|\.gif)$/i", $_FILES['avatar']['name'])) {
                Core::$msgs['avatar'] = "Незаконное тип файла. Только JPG и PNG файлов типа разрешается";
            }
            if ($_FILES["avatar"]["size"] > 307200) {
                Core::$msgs['avatar'] = "Загружено изображение больше, чем 300 Кб";
            }
            $file_info = getimagesize($_FILES['avatar']['tmp_name']);
            if (empty($file_info))
                Core::$msgs['avatar'] = "Незаконное тип файла. Только JPG и PNG файлов типа разрешается";

        }


        if (empty(Core::$msgs)) {

            $data=array(
                'update_at'=>'now()',
                'email' => sanitize($_POST['email']),
                'fb_link' => sanitize($_POST['fb_link']),
                'tw_link' => sanitize($_POST['tw_link']),
                'gp_link' => sanitize($_POST['gp_link']),
                'vk_link' => sanitize($_POST['vk_link']),
            );
            $pdata = array(

                'lname' => sanitize($_POST['lname']),
                'fname' => sanitize($_POST['fname']),
                'phone' => sanitize($_POST['phone']),
                'birthdate'=>sanitize($_POST['birthdate']),
                'website'=>sanitize($_POST['website']),
                'info' => sanitize($_POST['info']),
                'gender' => 'm',
                'city_id' => '1',
                'newsletter' => intval($_POST['newsletter'])
            );

            $userpass = self::$db->getValueById("password", "users", $this->uid);
            if ($_POST['password'] != "") {
                $data['password'] = md5($_POST['password']);
            } else
                $data['password'] = $userpass;


            if (!empty($_FILES['avatar']['name'])) {
                Uploads::$width =80;
                Uploads::$height = 80;
                $thumbdir = UPLOADS . "avatars/";
                $tName = "IMG_" . Registry::get("Core")->randName();
                $text = substr($_FILES['avatar']['name'], strrpos($_FILES['avatar']['name'], '.') + 1);
                $thumbName = $thumbdir . $tName . "." . strtolower($text);
                if ($avatar = self::$db->getFieldById("avatar", "user_profiles", "user_id=".$this->uid)) {
                    @unlink($thumbdir . $avatar);
                }
                move_uploaded_file($_FILES['avatar']['tmp_name'], $thumbName);
                $pdata['avatar'] = $tName . "." . strtolower($text);
                Uploads::$ufile = $thumbName;
                Uploads::$output = $thumbdir . $pdata['avatar'];
                Uploads::$delete_original = true;
                Uploads::doResize();
            }

            self::$db->update("users", $data, "id=" . $this->uid);
            self::$db->update("user_profiles", $pdata, "user_id=" . $this->uid);

            if (self::$db->affected()) {
                Core::msgInfo("Вы успешно обновили ваш профиль", false);
            } else {
               Core::msgAlert("Ничто не обрабатывать", false);
            }
        }else{
            Core::msgStatus();
        }

    }
}

?>