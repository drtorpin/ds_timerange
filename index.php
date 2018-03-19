<?
ini_set('display_errors', 0);
// @session_start();
// ini_set('display_errors', 1);
// error_reporting(E_ALL & ~E_NOTICE);
$DIR = dirname(__FILE__);

include_once($DIR.'/library/class.DB.php');
include_once($DIR.'/library/class.VALID.php');

/**  роутер */
class RouteCore
{
    static function start()
    {
		$url = self::DetectUrl();
		$routes = explode('/', $url);					
		// определяем имя контроллера
		$controller = !empty($routes[0])
								? preg_replace('/[^0-9a-z]/','',$routes[0])
								: '';
		if(!$controller)
		{
			self::RedirectUrl( self::getDefaultController() );
		}
		$method = !empty($routes[1])
								? preg_replace('/[^0-9a-z]/','',$routes[1])
								: '';		
		$classType = 'Controller';
		$controller = self::FormatClassName($controller,$classType);
		// если контроллер не определён, перенаправляем на дефолтный 
		if(!class_exists($controller)) {
			$controller = 'Error_404'.$classType;
			}
		$ctrllr = new $controller($method,$routes);		
	}	
	/** контроллер по умолчанию */
	static function getDefaultController()
	{
        return 'timerange';		
	}
	/** определяем url без стартовой директории */	
	static function DetectUrl()
	{
        // если сайт лежит не в корне, а в директории, находим эту директорию 
		$url = str_replace(self::GetRootDir(),
						'',
						$_SERVER['REDIRECT_URL']
						);
		return trim($url,'/');		
	}
	/** определяем корневой узел сслыки */
	static function GetRootDir() {
		return str_replace(
						str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']) ,
						'',
						str_replace('\\','/',dirname(__FILE__) ));		
	}
	
	/** форматируем имя класса */	
	static function FormatClassName($className,$classType)
	{
		return ucfirst(strtolower($className)).$classType;
	}
	/** редирект */
	static function RedirectUrl($dir) {
        $host = 'http://'.$_SERVER['HTTP_HOST'].self::GetRootDir().'/'.$dir;
        header('HTTP/1.1 301 Redirect');
		header('Location: '.$host);		
	}    
}

// =========================================
// =========================================
// ОБЛАСТЬ КОНТРОЛЛЕРОВ
// =========================================
// =========================================
abstract class Controller
{
	/** получаем данные для шаблона из модели */
	protected function get_data($class_name,$routes)
	{
		$model_name = $class_name.'Model';
		if(class_exists($model_name)) 
		{
			$model = new $model_name($routes);
			return $model->get_combine_data();
			
		}		
		// добавить исключение
	}
	/** выводим данные из модели в представление */
	protected function make_view($class_name,$data)
	{		
		$view_name = $class_name.'View';
		if(class_exists($view_name)) 
		{
			$view = new $view_name($data);
			
		}
		// добавить исключение		
	}	
}

class Error_404Controller extends Controller
{
		function __construct($model,$routes)
		{
		$this->make_view('Error_404','');
		}
}

class TimerangeController extends Controller
{
	function __construct($model,$routes)
	{
		// реестр разрешённых моделей с алиасами
		$models = Array('form' => 'FormAddUser',
								   'report' => 'TimeRangesReport',
									'statistic' => 'TimeAllUsersReport',
									'user' => 'TimeUserReport'
									);							
		$model_key = $model
								? $model
								: key($models);
		$class_name = !empty($models[$model_key])
								? $models[$model_key]
								: 'Error_404';		
		$this->make_view($class_name,
									$this->get_data($class_name,$routes)
									);
	}
	
}
// =========================================
// =========================================
// ОБЛАСТЬ МОДЕЛЕЙ
// =========================================
// =========================================

abstract class Model {
	protected $DB;
	
	protected function set_db()
	{
			$this->DB = DB::init();
	}
	
	public function get_data()
	{
		return [];
	}
	
	public function get_error()
	{
		return [];
	}

	public function get_tpl()
	{
		return [];
	}
	
	public function get_combine_data()
	{
	return Array('data' => $this->get_data(),
						  'error' => $this->get_error()
						);
	}
}

class TimeRangesModel extends Model {
	private $data = Array();
	private $error = Array();
	
	
	function __construct($routes)
	{
	$this->set_db();	
	return $this->make_data($routes);
	}	
	
	// геттеры --------------------------------------------------	
	/** геттер данные для вставки в шаблон */
	public function get_data()
	{
		return $this->data;
	}
	/** геттер данные для вставки в шаблон */
	public function get_error()
	{
		return $this->error;
	}
	// сеттеры --------------------------------------------------	
	/** сеттер данные для вставки в шаблон */
	protected function set_data($index,$value)
	{
		$this->data[$index] = $value;
	}
	/** сеттер ошибок */
	protected function set_error($value,$index = false)
	{
		if(!$index)
		{
			$this->error[] = $value;
			return;
		}		
		$this->error[$index] = $value;
	}
	protected function make_data($routes)
	{
		return false;
	}
	
}

class FormAddUserModel extends TimeRangesModel
{
	protected function make_data($routes)
	{		
	$values['date_from'] = date('Y-m-d H:i:00');
	
	$this->add_new_range($values);
	$SQL = "SELECT					
					`user`.id `id`,
					CONCAT(`family`,' ',`name`,' ',`second_name`) `fio`,
					`cw`.title `workposition_title`
				FROM
					ctrl_users `user`
					join ctrl_workpositions `cw`
						ON `cw`.id = `user`.workposition_id
				ORDER BY
					`user`.`family`";
	$Rows = $this->DB->getAll($SQL);
	$this->set_data('users',$Rows);	
	
	$SQL = "SELECT					
					`task`.`id` `id`,
					`prj`.`title` `project_title`,
					`task`.`title` `task_title`
				FROM
					`ctrl_projects_tasks` `prj`
					join `ctrl_projects_tasks` `task`
						ON `task`.pid = `prj`.id
				WHERE
					`prj`.`pid` = 0
				ORDER BY
					`prj`.`title`
					";
	$Rows = $this->DB->getAll($SQL);
	$this->set_data('projects',$Rows);	
	$this->set_data('values',$values);	
	}
	// добавить новый диапазон
	private function add_new_range(&$values)
	{
		if(empty($_POST)) return false;
		$DATE_FROM = VALID::post('date_from','datetime',$values['date_from']);
		$DATE_TO = VALID::post('date_to','datetime','');
		$USER = VALID::post('user','int','' );
		$TASK = VALID::post('task','int','' );
		
		$this->check_user($USER);
		$this->check_task($TASK);
		$this->check_time($DATE_FROM,$DATE_TO);
	 	 
		if($this->get_error())	{
			$values = Array('date_from' => $DATE_FROM,
									 'date_to' => $DATE_TO,
									 'user' => $USER,
									 'task' => $TASK
									 );
			return false;
			}							
		$lid = $this->DB->insert("INSERT INTO	
													ctrl_timeranges
											(user_id,task_id,datefrom,dateto)
											VALUES
											(?,?,?,?)",								
											Array($USER,$TASK,$DATE_FROM,$DATE_TO)
											);
		$this->set_data('message',
								($lid ? "Добавлен диапазон {$DATE_FROM} - {$DATE_TO}" : "Ошибка добавления: {$DATE_FROM} - {$DATE_TO}")
								);
	}
	// проверить пользователя
	private function check_user($id)
	{
		if(!$id) {
		  $this->set_error('Не определён разработчик','user');
		  return false;
		  }
		$SQL = "SELECT id FROM ctrl_users WHERE id = '{$id}'";
		$Row = $this->DB->getOne($SQL);
		if(!$Row) 
			$this->set_error('Нет такого разработчика','user');
	}
	// проверить задачу
	private function check_task($id)	
	{
		if(!$id) {
		 $this->set_error('Не выбрана задача','task');		
		return false;
		}
		$SQL = "SELECT id FROM ctrl_projects_tasks WHERE id = '{$id}'";
		$Row = $this->DB->getOne($SQL);
		if(!$Row) 
			$this->set_error('Нет такой задачи','task');		
	}
	// проверить временные интервалы
	private function check_time($DATE_FROM,$DATE_TO)	
	{	 
		if(!$DATE_FROM)
		 $this->set_error('Не определена дата начала','date_from');
		
		if(!$DATE_TO)
		 $this->set_error('Не определена дата завершения','date_to');
		
		if($this->get_error())	return false;
		
		if($DATE_TO<$DATE_FROM)
		$this->set_error('Дата завершения не может быть старше даты начала','date_to');
	}

}

class TimeAllUsersReportModel extends TimeRangesModel
{
	protected function make_data($routes)
	{	
	$SQL = "SELECT					
					SEC_TO_TIME(sum(unix_timestamp(`ct`.`dateto`) - unix_timestamp(`ct`.`datefrom`))) `time`,
					CONCAT(`family`,' ',`name`,' ',`second_name`) `fio`
				FROM
					`ctrl_timeranges` `ct`
					join `ctrl_users` `user`
						ON `user`.`id` = `ct`.`user_id`
					join `ctrl_projects_tasks` `task`
						ON `task`.`id` = `ct`.`task_id`
					join `ctrl_projects_tasks` `prj`
						ON `prj`.`id` = `task`.`pid`
				GROUP BY
					`ct`.`user_id`";
	$Rows = $this->DB->getAll($SQL);
	if(!$Rows)
	{
		 $this->set_error('Нет записей');
	}
	$this->set_data('items',$Rows);	
	}
}

class TimeRangesReportModel extends TimeRangesModel
{
	protected function make_data($routes)
	{	
	$SQL = "SELECT					
					`ct`.`id` `id`,
					`ct`.`user_id` `user_id`,
					DATE_FORMAT(`ct`.`datefrom`,'%d.%m.%Y') `date`,
					SEC_TO_TIME(unix_timestamp(`ct`.`dateto`) - unix_timestamp(`ct`.`datefrom`)) `time`,
					`task`.`title` `task_title`,
					`prj`.`title` `project_title`,
					CONCAT(`family`,' ',`name`,' ',`second_name`) `fio`,
					`cw`.`title` `workposition_title`
				FROM
					`ctrl_timeranges` `ct`
					join `ctrl_users` `user`
						ON `user`.`id` = `ct`.`user_id`
					join `ctrl_workpositions` `cw`
						ON `cw`.`id` = `user`.`workposition_id`
					join `ctrl_projects_tasks` `task`
						ON `task`.`id` = `ct`.`task_id`
					join `ctrl_projects_tasks` `prj`
						ON `prj`.`id` = `task`.`pid`
				ORDER BY
					`ct`.`id` DESC
				LIMIT 100";
	$Rows = $this->DB->getAll($SQL);
	if(!$Rows)
	{
		 $this->set_error('Нет записей');
	}
	$this->set_data('items',$Rows);	
	}
	
}

class TimeUserReportModel extends TimeRangesModel
{
	
	protected function make_data($routes)
	{	
	$user_id = VALID::arr($routes,2,'int');
	if(!$user_id)
	{
		 $this->set_error('Не определён пользователь');
	}
	
	$SQL = "SELECT					
					SEC_TO_TIME(unix_timestamp(`ct`.`dateto`) - unix_timestamp(`ct`.`datefrom`)) `time`,
					`task`.`title` `task_title`,
					`prj`.`title` `project_title`,
					CONCAT(`family`,' ',`name`,' ',`second_name`) `fio`
				FROM
					`ctrl_timeranges` `ct`
					join `ctrl_users` `user`
						ON `user`.`id` = `ct`.`user_id`
					join `ctrl_projects_tasks` `task`
						ON `task`.`id` = `ct`.`task_id`
					join `ctrl_projects_tasks` `prj`
						ON `prj`.`id` = `task`.`pid`
				WHERE
					`ct`.`user_id` = '{$user_id}'
				GROUP BY
					`ct`.`task_id`";
	$Rows = $this->DB->getAll($SQL);
	if($user_id AND !$Rows)
	{
		 $this->set_error('Нет записей');
	}
	$this->set_data('items',$Rows);	
	}
}

// =========================================
// =========================================
// ОБЛАСТЬ ПРЕДСТАВЛЕНИЙ
// =========================================
// =========================================

abstract class View {	
	private $tpl; // имя шаблона для контента
	private $pageTpl; // имя шаблона страницы
	private $dirTpls = '/tpl/'; // директория с шаблонами
	protected $tplDir = 'TimeRanges/'; // папка с шаблонами
	
	// геттеры --------------------------------------------------	
	/** геттер имя шаблона */
	public function get_tpl()
	{
		return $this->tpl;
	}
	/** геттер имя шаблона */
	public function get_pageTpl()
	{
		return $this->pageTpl;
	}
	/** геттер имя шаблона */
	public function get_dirTpls()
	{
		return $this->dirTpls;
	}
	// сеттеры --------------------------------------------------	
	/** сеттер имя шаблона */
	protected function set_tpl($tpl_name)
	{
		$this->tpl = $tpl_name;
	}
	/** сеттер имя шаблона */
	protected function set_pageTpl($tpl_name)
	{
		$this->pageTpl = $tpl_name;
	}	
	
	// общие методы --------------------------------------------------		
	
	protected function build_template($data)
	{
		// заполнение шаблона контента
		$Content = $this->fill_template2buffer($this->get_tpl(),
																$data
																);
		// формирование полной HTML страницы
		$this->fill_template($this->get_pageTpl(), Array('content' => $Content,
																				'title' => $data['title'],
																				'rootDir' => $data['rootDir'],
																				'prjDir' => $data['prjDir']
																				));
	}
	
	protected function fill_template($tplPath, $data)
	{				
		if($data) extract($data);
		if($data) extract($data);
		include_once($tplPath);
	}
	
	protected function fill_template2buffer($tplPath,$data)
	{
		ob_start();
		$this->fill_template($tplPath,$data);		
		// $buffer = ob_get_contents();
		return ob_get_clean();
	}
	
	protected function make_build_page($tplName,$data,$pageTpl)
	{		
		$data['rootDir'] = RouteCore::GetRootDir();
		$data['prjDir'] = $data['rootDir'].'/'.RouteCore::getDefaultController();
		$dir = dirname(__FILE__).$this->get_dirTpls();
		$this->set_tpl($dir.$this->tplDir.$tplName);
		$this->set_pageTpl($dir.$pageTpl);
		$this->build_template($data);
	}	
	
}

class Error_404View extends View
{
		function __construct($data)
		{
		$this->tplDir = '';
		$this->make_build_page('page404.tpl',Array('title' => 'Error 404'),'main.tpl');
		}
}
	

/** общее представление для проекта */
class TimeRangesView extends View
{
	protected function build_page($tplName,$data)
	{		
		$data['title'] = $data['title'].' | табель рабочего времени';
		$this->make_build_page($tplName,$data,'main.tpl');
	}
}

/** представления для разделов проекта */
class TimeAllUsersReportView extends TimeRangesView
{
	function __construct($data)
	{
		$data['title'] = 'Отчёт';
		$this->build_page('TimeAllUsersReport.tpl',$data);
	}
}

class TimeUserReportView extends TimeRangesView
{
	function __construct($data) //  TimeUserReportModel // TimerangeController
	{
		$data['title'] = 'Разработчик';
		$this->build_page('TimeUserReport.tpl',$data);
	}

}

class TimeRangesReportView extends TimeRangesView
{
	function __construct($data)
	{
		$data['title'] = 'Общий табель';
		$this->build_page('TimeRangesReport.tpl',$data);
	}	
}

class FormAddUserView extends TimeRangesView
{
	function __construct($data)
	{
		$data['title'] = 'Добавить';
		$this->build_page('FormAddUser.tpl',$data);
	}	

}


RouteCore::start();
