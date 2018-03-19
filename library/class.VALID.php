<?
  /**
  всё что связано с проверкой значений на правильность
  */
class VALID{
	
	public static function str($str,$mode,$default = '') {
		switch($mode) {
			case 'bool':
				return bool($str);
			case 'int':
				return (int)$str;
			case 'int_':
				return preg_replace('/[^0-9_]/i','',$str);
			case 'inteng':
				return preg_replace('/[^0-9a-z]/i','',$str);
			case 'inteng_':
				return preg_replace('/[^0-9a-z_]/i','',$str);
			case 'inteng_pt':
				return preg_replace('/[^0-9a-z_\.]/i','',$str);
			case 'fullstr':
				return strip_tags($str);
			case 'strongstr':
				return preg_replace('/[^0-9a-zа-яёА-ЯЁ,_ \.-]/iu','',$str);
			case 'url':
				return strtolower(preg_replace('/[^0-9a-zA-Z0-9]/','',$str));
			case 'datetime':
				return preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/',$str) ? $str : $default;
			case 'ish':
				return $str;
			}	
	}
	
	// валидация входящих через иной массив
	public static function arr($arr,$inx,$mode,$default = '') {
		if(!isSet($arr[$inx])) return $default;
		return self:: str($arr[$inx],$mode,$default);		
		}
	// валидация входящих через пост
	public static function post($inx,$mode,$default = '') {
		if(!isSet($_POST[$inx])) return $default;
		return self:: str($_POST[$inx],$mode,$default);		
		}
	// валидация входящих через get
	public static function get($inx,$mode,$default = '') {
		if(!isSet($_GET[$inx])) return $default;
		return self:: str($_GET[$inx],$mode,$default);		
		}
}