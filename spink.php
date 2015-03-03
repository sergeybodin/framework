<?php
    /** файл который загружает модуль инициализации v 3.04
     *  property of http://www.gostsoft.ru/
     */
    // устанавливаем флаг безопасности, для защиты от прямого запуска конфигурационных файлов
    define('SEQURITY_CHECK',true);
    
    $allSql = array();
    extract($_GET);
	/** установка доменной зоны
     */
    $_hh = explode('.',$_SERVER['HTTP_HOST']);
    define('DOMAINS',$_hh[count($_hh)-1]);
    /** установка путей к скриптам
     */
    $local = false;
	switch(DOMAINS){
		case 'lc':
			$paths = array(
                __DIR__.'/spink/',
                __DIR__,
                'z:/'
            );
            $include_path = implode(';',$paths);
            $local = true;
			break;
		case 'ru':
            $paths = array(
                str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']).'/',
                '/var/www/bodin/data/www/'
            );
            $include_path = implode(':',$paths);
			break;
	}
    define('LOCAL',$local);
    
	ini_set('include_path',$include_path);
    /** установка домена
     */
    define('DOMAIN',$_hh[count($_hh)-2].'.'.DOMAINS);
    /** при установке сессий на разных подддоменах данные сессии будут читатся на всех поддоменах
     */
    ini_set('session.cookie_domain','.'.DOMAIN);
    //
    require_once 'class/common.php';
    // определяем переменную в которую будем грузить контент
    $load = '';
    /** загрузка конфигурации
     */
    require_once 'config.php';
    $cfg = new Config;
    $cfg->start(DOMAINS);
    /** имя активной функции
     */
    $setup = $cfg->loadSetup();
    /** загрузка БД
     */
	require_once 'class/db.php';
    $db = new DB(Config::$db);
    $db->onDB('cfg');
    
    $cfg->load($setup);