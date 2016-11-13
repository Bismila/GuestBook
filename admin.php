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
			$('#adminAnswerForm').hide();
			$('#auth').hide();
			$('#menuDiv').hide();
			$('#nameAdmin').hide();
			$('#editMsgForm').hide();

			$('#reg').text('Выйти ->');
			//Клик по кнопке - Выйти
			$('#reg').click(function () {
				location.href = "index.php";
			});

			//клики по кнопкам меню Ответить/Скрыть/Редактировать/Удалить
			$('.adminSubMenu').bind('click', function (event) {
				event.preventDefault();
				var currentMsg = $(this).attr("id");//определяем id по ссылке Ответить/Скрыть/Редактировать/Удалить
				var marker = currentMsg.substring(0,1);//выясняем что это мы нажали по 1й букве id - A/H-S/E/D
				switch (marker){
					case 'a'://ответить
					{
						//забираем значения из полей формы
						var loginUser = '';
						var answer = '';
						var id_msg = currentMsg.substring(11, 1000);
						//alert($('#conteiner'+id_msg).siblings().html());//ищем предка чтоб вставить форму для админа
						$('#conteiner'+id_msg).append($('#adminAnswerForm').show());//ищем предка чтоб вставить форму для админа и выводим ее

						$('#doneAdmin').click(function () {
							loginUser = 'Admin';
							answer = $("#msgAdmin").val();
							$.post(
								"sendAnswerUser.php",
								{
									'id_msg':id_msg,
									'loginUser': loginUser,
									'answer': answer,
								},

								function (data) {
									//$(this).parent().parent().append($('#adminAnswerForm').show())
									$(this).parent().parent().append(data + "<div class='clear'><br/></div>");
									//$('#msg_show').show();

									$("#msgAdmin").val('');
								});
						});
					}
						break;
					case 'h':///скрыть
					{
						event.preventDefault();
						var id_msg = currentMsg.substring(13, 1000);
						alert("('#conteiner'+id_msg).siblings().html() = "+$('#conteiner'+id_msg).siblings().html());
						alert("'#conteiner'+id_msg).html() = "+$('#conteiner'+id_msg).html());
						$('#conteiner'+id_msg).hide();
						$('#conteiner'+id_msg).siblings().hide();
						$(this).text('Отобразить');
						$(this).attr('id', 'showThisEntry'+id_msg);

							var hidden = 'hide';
						$.post(
							"sendAnswerUser.php",
							{
								'id_msg':id_msg,
								'loginUser': loginUser,
								//'answer': answer,
								'hidden':hidden
							},
							function (data) {});
					}
						break;
					case 's':///отобразить
					{
						event.preventDefault();
						var currentMsg = $(this).attr("id");
					//	alert(currentMsg);
						var id_msg = currentMsg.substring(13, 1000);
					//	$('#conteiner'+id_msg).parent().css('border','1px solid #6A5ACD');

						$('#conteiner'+id_msg).show();
						$('#conteiner'+id_msg).siblings().show();
						$(this).text('Скрыть');
						$(this).attr('id', 'hideThisEntry'+id_msg);
						//alert($(this).attr('id', 'hideThisEntry'+id_msg));
						var hidden = 'show';
						$.post(
							"sendAnswerUser.php",
							{
								'id_msg':id_msg,
								'loginUser': loginUser,
								'hidden':hidden
							},
							function (data) {});
					}
						break;
					case 'e'://редактировать
					{
						event.preventDefault();
						var id_msg = currentMsg.substring(4, 1000);//извлекаем соот-щий id_msg
					//	alert($(this).attr("id"));//определяем Id ссылки Ответить
						//alert($(('#conteiner' + id_msg)).parent().html()); //по id ищем смс которое надо удалить
						//alert($("p#sendNameTextMsg"+id_msg).attr('id'));
					//	alert($("p#sendNameTextMsg"+id_msg).html());
						var currentTextMsg = $("p#sendNameTextMsg"+id_msg).text();
						//alert(currentTextMsg);//достали нужный текст клиента для редактирования
						$("p#sendNameTextMsg"+id_msg).html($('#editMsgForm').show());//вставляем форму для редактирования
						$('#editMsg').text(currentTextMsg);//вставляем текст клиента в окно для редактирования

						//Когда нажимаем кнопку "Изменить сообщение"
						$('#editDone').click(function () {
							currentTextMsg = $("#editMsg").val();//перезаписываем смс исправленное и отправляем на сервер

							$.post(
								"sendAnswerUser.php",
								{
									'id_msg':id_msg,
									'currentTextMsg': currentTextMsg
								},

								function (data) {
									$("#editMsg").val('');
								});
						});
					}
						break;
					case 'd'://удалить
					{
						event.preventDefault();
						var id_msg = currentMsg.substring(15, 1000);//извлекаем соот-щий id_msg
						//alert($('#conteiner' + id_msg).parent().html()); //по id ищем смс которое надо удалить
						$("div#conteiner" + id_msg).parent().remove();
						$(this).parent().remove();

						$.post(
							"sendAnswerUser.php",
							{
								'id_msg': id_msg,
								'commandDel': 'del'
							},
							function (data) {});
					}
						break;

				}
			});

		});//END $(document).ready(function () {...
		//});

	</script>
</head>
<body>
<?php require_once "blocks/header.php" ?>
<!--основная часть-->
<div id="wrapper">
	<div id="leftCol">
		<!--форма для отправленных сообщений от гостей-->
		<div id="all_send-msg">
			<div id="menuDiv" class="menuDivClass" hidden>
				<a href="#" class="adminSubMenu" id="answerAdmin">Ответить</a>|
				<a href="#" class="adminSubMenu" id="hideThisEntry">Скрыть</a>|
				<a href="#" class="adminSubMenu" id="edit">Редактировать</a>|
				<a href="#" class="adminSubMenu" id="deleteThisEntry">Удалить</a>
			</div>
			<div id="send-msg" class="sendMsg" hidden>
				<div id="conteiner">
				<h2 class="oneMsg" id="sendNameSbj"></h2>
				<p class="oneMsg" id="sendName">Автор: </p>
				<p class="oneMsg" id="sendNameCity">город: </p>
				<p class="oneMsg" id="sendNameTextMsg"></p>
				<p class="oneMsg" id="puttime"></p>
				<p class="oneMsg" id="sendNameEmail"></p>
				<p class="oneMsg" id="sendNameUrl"></p>
				<div class="oneMsg" id="adminAnswer" class="adminAnswerClass">
					<p class="oneMsg" id="nameAdmin"></p>
				</div>
				</div>
			</div>  <!--id="send-msg"-->
			<div id="edit">
				<form class="editMsgClass" id="editMsgForm" name="editMsgForm" action="" method="post">
					<textarea class="editMsgClass" placeholder="Текст сообщения" id="editMsg" name="editMsg"></textarea><br>
					<input class="editMsgClass" type="submit" name="editDone" id="editDone" value="Изменить сообщение"  /><br>
					<br />
				</form>
			</div>
			<?php
			require_once "addAnswerInHtml.php";
			?>
		</div><!--id="all_send-msg"-->
		<div id="adminAnswer" class="adminAnswerClass">
			<form class="adminClass" id="adminAnswerForm" name="adminAnswerForm" action="" method="post">
				<label class="adminClass" type="text" id="admin" name="admin" value="admin"/>Admin<br>
				<textarea class="adminClass" placeholder="Текст сообщения" id="msgAdmin" name="msgAdmin"></textarea><br>
				<div id="msg_show"></div>
				<input class="adminClass" type="submit" name="doneAdmin" id="doneAdmin" value="Отправить"  /><br>
				<br />
			</form>
		</div>

	</div><!--id="leftCol"-->
	<!--правая колонка - меню и банера-->
	<?php require_once "blocks/rightCol.php" ?>
</div>
<!--подвал сайта - footer-->
<?php require_once "blocks/footer.php" ?>
</body>
</html>