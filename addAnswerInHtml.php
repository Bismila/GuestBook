<?php
try {
    $user = "root";
    $pass = "";
    $db = new PDO('mysql:host=localhost;dbname=guestbook', $user, $pass);
    $stmt = $db->query('SELECT * FROM guest');
    $row_count = $stmt->rowCount();
  //  echo $row_count.' rows selected';

    $idSubMenuAdmin=0;

    $rows = $stmt->fetchAll();
    $count = count($rows);
    if($row_count>0) {
        echo "<div id=\"all_send-msg\"";
        foreach($rows as $row)
        {
            $idSubMenuAdmin = $row['id_msg'];
            if($row['hide']=='show')
            {
                echo "<br/>";
                echo "<div id=\"menuDiv\" class=\"menuDivClass\">
						<a href=\"#\" class=\"adminSubMenu\" id=\"answerAdmin" . $idSubMenuAdmin . "\">Ответить</a>|
						<a href=\"#\" class=\"adminSubMenu\" id=\"hideThisEntry" . $idSubMenuAdmin . "\">Скрыть</a>|
						<a href=\"#\" class=\"adminSubMenu\" id=\"edit" . $idSubMenuAdmin . "\">Редактировать</a>|
						<a href=\"#\" class=\"adminSubMenu\" id=\"deleteThisEntry" . $idSubMenuAdmin . "\">Удалить</a>
					</div>" .
                    "<div id=\"send-msg\">" .
                         "<div id=\"conteiner$idSubMenuAdmin\">
                                 <h2 id=\"sendNameSbj\">" . $row['sbj'] . "</h2>
                                 <p id=\"sendName\">Автор: " . $row["name"] . "</p>
                                 <p id=\"sendNameCity\">город: " . $row['city'] . "</p>
                                 <p id=\"sendNameTextMsg$idSubMenuAdmin\">" . $row['msg'] . "</p>
                                 <p id=\"puttime\">" . $row['puttime'] . "</p>
                                 <p id=\"sendNameEmail\">" . $row['email'] . "</p>
                                 <p id=\"sendNameUrl\">" . $row['url'] . "</p>
                           </div>";//закрыли conteiner...
                if ($row['answer'] != "") {
                    echo "<div id=\"adminAnswer\" class=\"adminAnswerClass\"><p id=\"nameAdmin\">Admin:</p>" . $row['answer'] . "</div>";
                }
                echo "</div>";//закрыли send-msg
            }
            else if($row['hide']=='hide')
            {
                echo "<div id=\"menuDiv\" class=\"menuDivClass\">
						<a href=\"#\" class=\"adminSubMenu\" id=\"answerAdmin" . $idSubMenuAdmin . "\">Ответить</a>|
						<a href=\"#\" class=\"adminSubMenu\" id=\"showThisEntry" . $idSubMenuAdmin . "\">Отобразить</a>|
						<a href=\"#\" class=\"adminSubMenu\" id=\"edit" . $idSubMenuAdmin . "\">Редактировать</a>|
						<a href=\"#\" class=\"adminSubMenu\" id=\"deleteThisEntry" . $idSubMenuAdmin . "\">Удалить</a>
					</div>" .
                    "<div id=\"send-msg\" style='border:hidden'>" .
                        "<div id=\"conteiner$idSubMenuAdmin\" hidden>
                             <h2 id=\"sendNameSbj\">" . $row['sbj'] . "</h2>
                             <p id=\"sendName\">Автор: " . $row["name"] . "</p>
                             <p id=\"sendNameCity\">город: " . $row['city'] . "</p>
                             <p id=\"sendNameTextMsg\">" . $row['msg'] . "</p>
                             <p id=\"puttime\">" . $row['puttime'] . "</p>
                             <p id=\"sendNameEmail\">" . $row['email'] . "</p>
                             <p id=\"sendNameUrl\">" . $row['url'] . "</p>
                         </div>";//закрыли conteiner...
                if ($row['answer'] != "") {
                    echo "<div id=\"adminAnswer\" class=\"adminAnswerClass\" hidden><p id=\"nameAdmin\">Admin:</p>" . $row['answer'] . "</div>";
                }
                echo "</div>";//закрыли send-msg
                continue;
            }
        }
        echo "</div>";//закрыли all_send-msg

    }
    $stmt = null;
    $db = null;
}
catch(PDOException $e)
{
    die("Error: ".$e->getMessage());
}
/**/
?>