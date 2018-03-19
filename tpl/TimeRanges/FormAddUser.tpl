		<link rel="stylesheet" type="text/css" href="<?=$rootDir?>/css/jquery.datetimepicker.css">
		
		<h1>Форма учёта рабочего времени</h1>		
		<?if(isSet($message)){?>
			<div class="message"><?=$message?></div>
		<?}?>
		
		<form method="post">
		<div class="formRow">
			<div class="label">Разработчик</div>
			<div>
				<select name="user">
					<option value="0">--</option>
					<? if($users){foreach($users as $row){?>
					<option value="<?=$row['id']?>"  <?=((!empty($values['user']) AND $values['user'] == $row['id']) ? 'selected' : '')?>>
						<?=$row['fio']?> - <?=$row['workposition_title']?></div>
					</option>
					<?}}?>
				</select>
			</div>
			<? if(!empty($error['user'])) {?>
			<div class="error"><?=$error['user']?></div>
			<?}?>			
		</div>

		<div class="formRow">
			<div class="label">Проект/Задача</div>
			<div>
				<select name="task">
					<option value="0">--</option>
					<? if($projects){foreach($projects as $row){?>
					<option value="<?=$row['id']?>"  <?=((!empty($values['task']) AND $values['task'] == $row['id']) ? 'selected' : '')?>>
						<?=$row['project_title']?> - <?=$row['task_title']?></div>
					</option>
					<?}}?>
				</select>
			</div>
			<? if(!empty($error['task'])) {?>
			<div class="error"><?=$error['task']?></div>
			<?}?>
		</div>
		
		<div class="formRow">
			<div class="label">Дата начала работы</div>
			<div><input type="text" name="date_from" id="date_from" value="<?=(!empty($values['date_from']) ? $values['date_from'] : '')?>"></div>
			<? if(!empty($error['date_from'])) {?>
			<div class="error"><?=$error['date_from']?></div>
			<?}?>			
		</div>
		
		<div class="formRow">
			<div class="label">Дата завершения работы</div>
			<div><input type="text" name="date_to" id="date_to" value="<?=(!empty($values['date_to']) ? $values['date_to'] : '')?>"></div>
			<? if(!empty($error['date_to'])) {?>
			<div class="error"><?=$error['date_to']?></div>
			<?}?>
		</div>
		
		<div class="formRow">
			<div><input type="submit" class="button" value="Сохранить"></div>
		</div>		
		</form>
		
		<script type="text/javascript" src="<?=$rootDir?>/js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="<?=$rootDir?>/js/jquery.datetimepicker.js"></script>
		<script type="text/javascript" src="<?=$rootDir?>/js/timeranges_user.js"></script>
