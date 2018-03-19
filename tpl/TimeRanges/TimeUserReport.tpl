		<h1>Статистика пользователя</h1>
		<table class="tableReport">
			<tr>
				<th>ФИО</th>
				<th>Время</th>
				<th>Проект</th>
				<th>Задача</th>
			</tr>
			<? if($items){foreach($items as $row){?>
			<tr>
				<td><?=$row['fio']?></td>				
				<td><?=$row['time']?></td>
				<td><?=$row['project_title']?></td>
				<td><?=$row['task_title']?></td>
			</tr>
			<?}}?>
		</table>
				
