		
		<h1>Отчет о списанном времени по всем сотрудникам</h1>
		<table class="tableReport">
			<tr>
				<th>ФИО</th>
				<th>Время</th>
			</tr>
			<? if($items){foreach($items as $row){?>
			<tr>
				<td><?=$row['fio']?></td>				
				<td><?=$row['time']?></td>
			</tr>
			<?}}?>
		</table>
		