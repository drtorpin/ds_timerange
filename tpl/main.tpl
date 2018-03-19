<!DOCTYPE HTML>
<html>
	<head>
		<title><?=$title?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link rel="stylesheet" type="text/css" href="<?=$rootDir?>/css/main.css">
	</head>
	<body>
		<ul>
			<li><a href="<?=$prjDir?>/form/">Форма учёта рабочего времени</a></li>
			<li><a href="<?=$prjDir?>/report/">Табель учета времени сотрудников</a></li>			
			<li><a href="<?=$prjDir?>/statistic/">Отчет о списанном времени по всем сотрудникам</a></li>
		</ul>
		<?=$content?>
	</body>
</html>