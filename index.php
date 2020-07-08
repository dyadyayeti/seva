<?php

require_once('connect.php');


$db = new MySqlDB();


$sql = "SELECT * 
FROM `news`
WHERE `id` > 0";

$result = $db->getDataList($sql);
/*echo '<pre>';
print_r($result);
echo '</pre>';*/

if(isset($_POST) && !empty($_POST))
{
	if((isset($_POST['tittle']) && $_POST['tittle'] != '') && (isset($_POST['short_text']) && $_POST['short_text'] != '') && (isset($_POST['text']) && $_POST['text'] != ''))
	{
		$result = false;
		
		if(isset($_POST['id']))
		{
			$sqlData = [
				'tittle' => $_POST['tittle'], 
				'short_text' => $_POST['short_text'], 
				'text' => $_POST['text'],
				'updated_at' => time()
			];
			$id = intval($_POST['id']);
			$result = $db->updateRow('news', $sqlData, $id);
		}
		else
		{
			$sqlData = [
				'tittle' => $_POST['tittle'], 
				'short_text' => $_POST['short_text'], 
				'text' => $_POST['text'], 
				'status' => 0, 
				'created_at' => time(), 
				'updated_at' => 0
			];
			$result = $db->insertRow('news', $sqlData);
		}
		
		if($result)
		{
			header('Location: http://localhost/course/php/crud/index.php');
		}
		else
		{
			echo 'Ошибка!';
		}
	}
	elseif (isset($_POST['search_q']) && !empty($_POST['search_q'])) {
           
           $a = $_POST['search_q'];

           $a = trim($a);

		   $a = strip_tags($a);

           $a = $db->search($a);

           /*echo '<pre>';
			print_r($a);
			echo '</pre>';*/
          //$result = $db->search('news', $a);
	 }
	else
		{
			echo '<h2>'.'ОШИБКА Вы ничего не ввели в поиск!'.'</h2>';
		}

}

if(isset($_GET) && !empty($_GET))
{
	if((isset($_GET['delete'])) && (isset($_GET['id'])))
	{
		$id = intval($_GET['id']);
		
		if($id > 0)
		{
			$result = $db->deleteRow('news', $id);
		
			if($result)
			{
				header('Location: http://localhost/course/php/crud/index.php');
			}
			else
			{
				echo 'Ошибка!';
			}
		}
		else
		{
			echo 'Ошибка!';
		}
	}
}
	 
/*echo '<pre>';
print_r($result);
echo '</pre>';*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>123</title>
	<link rel="stylesheet" type="text/css" href="crud.css">
</head>
<body class="body">
	</div>
<div class="block2">
<!-- тут будет поиск --> 
     <h2>ТЕКСТОВЫЙ ПОИСК</h2>
	<form method="post" action="index.php">
	<input type="search" name="search_q"/></br>
	</br>
	<input type="submit" value="Поиск"/></br>

</form>

</div>


    <div class="block3">
      <div>
		<h3>Новость (Позиция)</h3>
		<?php
		if(isset($_GET['edit']) && isset($_GET['id']))
		{
			$id = intval($_GET['id']);
		
			if($id > 0)
			{
				$sqlData = [
					'tittle', 
					'short_text', 
					'text',
				];
		
				$row = $db->getOneRow($id, 'news', $sqlData);
				?>
				<!-- редактирование -->
				<form name="news" method="post" action="index.php">
					<table>
					<tr>
						<td><span class="letter">Заголовок: </span></td>
						<td><input type="text" name="tittle" value="<?= $row['tittle']; ?>"></td>
					</tr>
					<tr>
						<td><span class="letter">Краткое описание: </span></td>
						<td><textarea name="short_text" cols="40" rows="10" value="<?= $row['short_text']; ?>"><?= $row['short_text']; ?></textarea></td>
					</tr>
					<tr>
						<td><span class="letter">Контент: </span></td>
						<td><textarea name="text" cols="40" rows="10" value="<?= $row['text']; ?>"><?= $row['text']; ?></textarea></td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" name="save" value="Сохранить редактированное!"></td>
						<td><input type="hidden" name="id" value="<?= $id; ?>"></td>
					</tr>
					</table>
				</form>
				<?php
			}
			else
			{
				echo 'Ошибка!';
			}
		}
		else
		{
		?>
			<form name="news" method="post" action="index.php">
				<table>
				<tr>
					<td><span>Заголовок: </span></td>
					<td><input type="text" name="tittle"></td>
				</tr>
				<tr>
					<td><span>Краткое описание: </span></td>
					<td><textarea name="short_text" cols="40" rows="10">Краткое описание</textarea></td>
				</tr>
				<tr>
					<td><span>Контент: </span></td>
					<td><textarea name="text" cols="40" rows="10">Контент</textarea></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" name="save" value="Добавить"></td>
				</tr>
				</table>
			</form>
		<?php	
		}
		?>
	</div>	
    
      </div>


<div class="block4">
	<table border="5" style="margin-top:30px;margin-left:44px; background-color: #fffff; border-radius: 10px;";>
		<h1>Список новостей (все позиции) </h1>
 
    <?php 
    if(isset($result) && is_array($result) && !empty($result)) 
    {
      foreach($result as $key => $newsData) 
      {
        ?>
          
         		<tr height="60" align="center"> 
         			<td width="510"> <?= $newsData['short_text']?></td>
         			    <td ><a href="index.php?edit&id=<?= $newsData['id']; ?>"><button type="submit" name="submit" class="btn2">✏</button></a></td>
         			<td width="45"><a href="index.php?delete&id=<?= $newsData['id']?>"><button type="submit" name="submit" class="btn1">✖</button></a></td>
                </tr>
          
        <?php
      }
    }
    ?>
    </table></div>
    <div class="block6">footer</div>

</body>
</html>
