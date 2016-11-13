<?php
//$answer = ''; $name= ''; $msg='';
if(isset($_POST['id_msg'])){
    $id_msg = $_POST['id_msg'];
}
if(isset($_POST['loginUser'])){
    $loginUser = $_POST['loginUser'];
}
if(isset($_POST['answer'])){
    $answer = $_POST['answer'];
}
if(isset($_POST['name'])){
    $name =  $_POST['name'];
}
if(isset($_POST['city'])){
    $city =  $_POST['city'];
}
if(isset($_POST['email'])){
    $mail =  $_POST['email'];
}
if(isset($_POST['url_adress'])){
    $url =  $_POST['url_adress'];
}
if(isset($_POST['sbj'])){
    $sbj = $_POST['sbj'];
}
if(isset($_POST['msg'])){
    $msg =  $_POST['msg'];
}
//$flagHidden = false;
if(isset($_POST['hidden'])){
    $hidden = $_POST['hidden'];
    $flagHidden=true;
}
if(isset($_POST['commandDel'])){
    $commandDel = $_POST['commandDel'];
}
if(isset($_POST['currentTextMsg']))
{
    $currentTextMsg = $_POST['currentTextMsg'];
}
$time =  date("Y-m-d h:i:sa");

$data = array();
$flag=false;
$flagEdit=false;
try
{
    $user = "root";
    $pass = "";
    $db = new PDO('mysql:host=localhost;dbname=guestbook', $user, $pass);

    if($name!="" && $msg!="") {// Добавляем в таблицу
        $db->exec("INSERT INTO guest (name, city, email, url, sbj, msg, puttime) VALUES (" . $db->quote($name) . "," . $db->quote($city) . "," . $db->quote($mail) . "," . $db->quote($url) . "," . $db->quote($sbj) . "," . $db->quote($msg) . "," . $db->quote($time) . ")");
       // goto selectMsg; //переходим к метке selectMsg - откуда добавленно пользователем смс будет тут же выводиться на экран
    }
    //Добавляем ответ администратора в БД по соответствующему id_msg
    if($answer != ""){
        $updateStr= $db->exec("UPDATE guest SET answer='".$answer."' WHERE id_msg=".$id_msg);
        $flag=true;
    }
    //Редактируем текст смс клиента
    if(isset($currentTextMsg)&& $currentTextMsg!="")
    {
        $editMsg = $db->exec("UPDATE guest SET msg='".$currentTextMsg."' WHERE id_msg=".$id_msg);
        //$flagEdit=true;
        $msg = $currentTextMsg;
    }
    //Скрываем запись, которую отметил админ, меняя в БД поле hide на hidden
   // if($flagHidden){
    if(isset($hidden)&& $hidden=='hide'){
        $hideAnswer= $db->exec("UPDATE guest SET hide='".$hidden."' WHERE id_msg=".$id_msg);
        $flagHidden=false;

        exit();
    }
    else if(isset($hidden) && $hidden=='show'){
        $hidden='show';
        $hideAnswer= $db->exec("UPDATE guest SET hide='".$hidden."' WHERE id_msg=".$id_msg);
       // $flagHidden=true;

    }

    //Удаление записи из БД по id_msg
    if(isset($commandDel) && $commandDel=='del')
    {
        $del=$db->exec("DELETE FROM guest WHERE id_msg=".$id_msg);
    }


    //Выводим добавленную клиента запись у клиента и у админа
   // selectMsg://НАША МЕТКА selectMsg
    $stmt = $db->query('SELECT * from guest');
    $idSubMenuAdmin=0;
    //Установка fetch mode
     $stmt->setFetchMode(PDO::FETCH_ASSOC);
     while($row = $stmt->fetch())
     {
         array_push($data, $row);
         $idSubMenuAdmin = $row['id_msg'];
     }
     echo "<div id=\"all_send-msg\">";
     echo " <div id=\"menuDiv\" class=\"menuDivClass\" hidden>
						<a href=\"#\" class=\"adminSubMenu\" id=\"answerAdmin'.$idSubMenuAdmin.'\">Ответить</a>|
						<a href=\"#\" class=\"adminSubMenu\" id=\"hideThisEntry'.$idSubMenuAdmin.'\">Скрыть</a>|
						<a href=\"#\" class=\"adminSubMenu\" id=\"edit'.$idSubMenuAdmin.'\">Редактировать</a>|
						<a href=\"#\" class=\"adminSubMenu\" id=\"deleteThisEntry'.$idSubMenuAdmin.'\">Удалить</a>
					</div>".
             "<div id=\"send-msg\"><div id=\"conteiner$idSubMenuAdmin\">".
				"<h2 id=\"sendNameSbj\">$sbj</h2>
					<p id=\"sendName\">Автор: $name</p>
					<p id=\"sendNameCity\">город: $city</p>
					<p id=\"sendNameTextMsg$idSubMenuAdmin\">$msg</p>
					<p id=\"puttime\">$time</p>
					<p id=\"sendNameEmail\">$mail</p>
					<p id=\"sendNameUrl\">$url</p>
					</div>
				</div>";
    echo "</div>";
    if($flag)//если вопрос есть то $flag=true значит добавляем ответ админа в браузере
        echo "<div id=\"adminAnswer\" class=\"adminAnswerClass\"><p id=\"nameAdmin\">$loginUser</p> $answer</div>";


    //закрываем соединение с БД
     $stmt = null;
     $db = null;
}

catch(PDOException $e)
{
    die("Error: ".$e->getMessage());
}
?>

