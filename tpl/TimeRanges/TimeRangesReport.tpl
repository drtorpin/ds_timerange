		<h1>Табель учета времени сотрудников</h1>
		<table class="tableReport">
			<tr>
				<!--th>id</th-->
				<th>ФИО</th>
				<th>Должность</th>
				<th>Дата</th>
				<th>Время</th>
				<th>Проект</th>
				<th>Задача</th>
			</tr>
			<? if($items){foreach($items as $row){?>
			<tr>
				<!--td><?=$row['id']?></td-->
				<td><a href="<?=$prjDir?>/user/<?=$row['user_id']?>"><?=$row['fio']?></a></td>				
				<td><?=$row['workposition_title']?></td>
				<td><?=$row['date']?></td>
				<td><?=$row['time']?></td>
				<td><?=$row['project_title']?></td>
				<td><?=$row['task_title']?></td>
			</tr>
			<?}}?>
		</table>
