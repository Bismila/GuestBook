<!DOCTYPE html>
<html>
<head>
	<?php
	$title = "Гостевая книга";
	require_once "blocks/head.php"
    ?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script type="text/javascript">

    	$(document).ready(function () {

			$('.loginUser').val("");
			$('.passwordUser').val();
//прячем форму авторизации админа
			$('.modalContent').hide();
			$('.modalOverlay').hide();

			$('#menuDiv').hide();
			$('.menuDivClass').hide();

//нажатие кнопки - Авторизация
			$('#auth').click(function (event) {
				event.preventDefault();//отменяем действие по умолчанию
				$('.modalContent').show();
				$('.modalOverlay').show();
				$(".modalContent").animate({top:'30%', right:'30%', opacity:'2'}, 1500);
				$('[name=login]').focus();
			});
//нажатие кнопки Закрыть на всплывающей форме Авторизации админа
			$('.modalContentClose').click(function (event) {
				event.preventDefault();//отменяем действие по умолчанию
				$(".modalContent").animate({top:'10%', right:'0%', opacity: '0'}, 1000);
				//$('.modalContent').hide();
				$('.modalOverlay').hide();
				$('#error').hide();
				$('.passwordUser').val("");
			});


//нажатие кнопки Войти на всплывающей форме Авторизации админа
			$('#enterAdmin').bind('click',function (event) {
				event.preventDefault();//отменяем действие по умолчанию
				if($('.loginUser').val() == 'admin' && $('.passwordUser').val()=='admin')
				{
					location.href = "admin.php";
				}
				else
				{
					$('#error').html("<div id='error'>"+"Введите необходимые данные"+"</div>");
				}
			});
			
    		$("#done").click(function (event) {
				event.preventDefault();//отменяем действие по умолчанию
    			$('#msg_show').hide();//если ошибок нет - этого дива не будет видно

				//забираем значения из полей формы
    			var loginUser = $('.loginUser').val();
    			var name = $("#name").val();
    			var city = $("#city").val();
    			var email = $("#email").val();
    			var url_adress = $("#url_adress").val();
    			var sbj = $("#sbj").val();
    			var msg = $("#msg").val();
    			var answer = "";
    			var fail = "";
    			//проверяем на валидацию
    			if (name == "" || name < 3)
    				fail = "Графу \"Имя\" обязательно надо заполнить!<br/>Имя не меньше 3x символов!";
    			else if (msg == "") fail = "Графа \"Тест сообщения\" - обязательна к заполнению!";

    			if (fail != "") {//если есть ошибка
    				$('#msg_show').html(fail + "<div class='clear'><br/></div>");
    				$('#msg_show').show();// - покажется смс
    				return false;
    			}
    			$.post(
				"sendAnswerUser.php",
				{
					'name': name,
					'city': city,
					'url_adress': url_adress,
					'email':email,
					'sbj': sbj,
					'msg': msg,
					'answer': answer
				},

				function (data) {

					$('#all_send-msg').append(data + "<div class='clear'><br/></div>");
					$('.menuDivClass').hide();

					$('#msg_show').html("Сообщение отправлено" + "<div class='clear'><br/></div>");
					$('#msg_show').show();

					$("#name").val('');
					$("#city").val('');
					$("#email").val('');
					$("#url_adress").val('');
					$("#sbj").val('');
					$("#msg").val('');
				});

    		}); //END $("#done").click(function () {...

    	});//END $(document).ready(function () {...
	</script>
</head>
<body>
	<?php require_once "blocks/header.php" ?>
	<!--основная часть-->
	<div id="wrapper">
		<div id="leftCol">
		<!--	форма для аторизации админа - ВСПЛЫВАЮЩЕЕ ОКНО -->
			<div class="modalContent" id="modalContentId">
				<button type="button" class="modalContentClose" title="Закрыть" id="modalContentCloseId">Закрыть</button>
				<h2 class="modalContentTitle" id="modalContentTitleId">
					Вход для Администратора
				</h2>
				<!--<p>Введите свой логин и пароль</p>-->
				<form class="loginForm" id="loginFormId"> <!--method="post" action="sendAnswerUser"-->
					<input name="login" class="loginUser" id="loginUserId" type="text" placeholder="Ваш логин">
					<input name="password" class="passwordUser" type="password" placeholder="Ваш пароль">
					<div id="error"></div>
					<button name="enterAdmin" class="btn" id="enterAdmin" type="submit">Войти</button>
				</form>
			</div>
			<div class="modalOverlay"></div>
			<!--форма для отправленных сообщений от гостей-->
			<div id="all_send-msg">
				<!--<div id="menuDiv" class="menuDivClass">
					<a href="#">Ответить</a>|
					<a href="#">Скрыть</a>|
					<a href="#">Редактировать</a>|
					<a href="#">Удалить</a>
				</div>
				<div id="send-msg" hidden>
					<h2 id="sendNameSbj"></h2>
					<p id="sendName">Автор: </p>
					<p id="sendNameCity">город: </p>
					<p id="sendNameTextMsg"></p>
					<p id="puttime"></p>
					<p id="sendNameEmail">mail: </p>
					<p id="sendNameUrl">url: </p>
					<div id="adminAnswer" class="adminAnswerClass">
						<p id="nameAdmin"></p>
					</div>
				</div> --> <!--id="send-msg"-->
				<?php
				require_once "addAnswerInHtml.php";
				?>

			</div><!--id="all_send-msg"-->
			<div id="frmClient">
					<input type="text" placeholder="Имя" id="name" name="name" class="client"/>
					<br />
					<input type="text" placeholder="Город" id="city" name="city" class="client"/>
					<br />
					<input type="text" placeholder="e-mail" id="email" name="email" class="client" />
					<br />
					<input type="text" placeholder="URL" id="url_adress" name="url_adress" class="client"/>
					<br />
					<input type="text" placeholder="Заголовок сообщения" id="sbj" name="sbj" class="client"/>
					<br />
					<textarea placeholder="Текст сообщения" id="msg" class="client" name="msg"></textarea>
					<br />
					<div id="msg_show">

					</div>
					<input type="submit" name="done" id="done" value="Отправить" class="client"/>
					<br />
			</div>
		</div><!--id="leftCol"-->
		<!--правая колонка - меню и банера-->
		<?php require_once "blocks/rightCol.php" ?>
	</div>
	<!--подвал сайта - footer-->
	<?php require_once "blocks/footer.php" ?>
</body>
</html>