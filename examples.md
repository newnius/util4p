# utilities for php

Require PHP version >= 5.4.0

## utils

```php
/* require */
require_once();

/* get client real ip */
$ip = cr_get_client_ip();

/* extract param from GET */
$key = 'id';
$default = -1;
$value = cr_get_GET($key, $default);

/* extract param from POST */
$key = 'title';
$value = cr_get_POST($key);
```

## AccessController

```php
/* require */
require_once('util4p/AccessController.class.php');

/* init access map */
function init_accessMap()
{
	// $operation => array of roles
	$map = array(
		/* user */
		'user.get' => array('admin', 'user'),
		'user.add' => array('admin')
	);
	AccessController::setMap($map);
}

/* validate */
echo AccessController::hasAccess('normal', 'user.add'));//false

echo AccessController::hasAccess('normal', 'user.get'));//true
```

## CRLogger

```php
/* require */
require_once('CRObject.class.php');
require_once('CRLogger.class.php');

/* log */
$uid = 1;
$log = new CRObject();
$log->set('scope', $uid);
$log->set('tag', 'user.login');
$content = array('uid' => $uid, 'code' => 0);
$log->set('content', json_encode($content));
CRLogger::log($log);

/* query */
$uid = 1;
$rule->set('scope', $uid);
$rule->set('tag', 'user.login');
$res['code'] = Code::SUCCESS;
$res['count'] = CRLogger::getCount($rule);
$res['logs'] = CRLogger::search($rule);
var_dump($res);
```

## CRObject

```php
/* require */
require_once('CRObject.class.php');

/* set */
$post = new CRObject();
$post->set('author', 'newnius');
$post->set('category', 1);
$post->set('archived', false);

/* get */
$post->get('author'); // newnius
$post->get('unknown', 'default_val'); //default_val
$post->getBool('archived'); //false
```

## MysqlPDO
```php
/* require */
require_once('CRObject.class.php');
require_once('MysqlPDO.class.php');

/* init */
$config = new CRObject();
$config->set('host', '127.0.0.1');
$config->set('port', 3306);
$config->set('db', 'quora');
$config->set('user', 'root');
$config->set('password', 'PASSWORD');
$config->set('show_error', false);
MysqlPDO::configure($config);

/* prepare params */
$id = 1;
$author = 'Newnius';
$title = 'Breaking News about Tesla !';
$content = 'Tesla is building a factory in China...';
$category = 1;

/* do INSERT */
$params = array($author, $title, $content, $category);
$sql = 'INSERT INTO `quora` (`author`, `title`, `content`, `category`) VALUES (?, ?, ?, ?)';
$count = (new MysqlPDO())->execute($sql, $params);
echo $count === 1;

/* do SELECT */
$params = array($id);
$sql = 'SELECT `id`, `author`, `title`, `content`, `category` FROM `quora` WHERE `id` = ?';
$posts = (new MysqlPDO())->executeQuery($sql, $params);
var_dump($posts);

/* do UPDATE */
$params = array($author, $title, $content, $category, $id);
$sql = 'UPDATE `quora` SET `author` = ?, `title` = ?, `content` = ?, `category` = ? WHERE `id` = ?';
$count = (new MysqlPDO())->execute($sql, $params);
echo $count === 1;
```

## Random

```php
/* require */
require_once('Random.class.php');

/* generate */
$token = Random::randomString(32);

$rnd_int = Random::randomInt(1, 10);
```

## RateLimiter

```php
/* require */
require_once('CRObject.class.php');
require_once('RateLimityer.class.php');

/* define limits */
$rules = array(
    array('interval' => 300, 'degree' => 200),
    array('interval' => 3600, 'degree' => 500),
    array('interval' => 86400, 'degree' => 1000)
);
$config = new CRObject();
$config->set('key_prefix', 'quora');
$config->set('rules', $rules);
RateLimiter::configure($config);

/* increase */
RateLimiter::increase(20);

/* query */
if (RateLimiter::getFreezeTime() > 0) {
	echo 'TOO FAST!';
}
```

## RedisDAO.class.php

```php
/* require */
require_once('predis/autoload.php');
require_once('util4p/CRObject.class.php');
require_once('util4p/RedisDAO.class.php');

/* init */
$config = new CRObject();
$config->set('scheme', 'tcp');
$config->set('host', 'localhost');
$config->set('port', 6379);
$config->set('show_error', false);
RedisDAO::configure($config);

/* execute */
$redis = RedisDAO::instance();
if ($redis === null) {
    return false;
}
$key = 'post::1';
$value = 'Breaking News about Tesla !';
$result = $redis->set($redis_key, $key, $value);
$redis->disconnect();
echo $result;
```

## ReSession.class.php

```php
/* require */
require_once('predis/autoload.php');
require_once('ReSession.class.php');

/* init */
$config = new CRObject();
$config->set('time_out', 3600);
$config->set('bind_ip', false);
Session::configure($config);

/* put */
$uid = 1;
$role = 'admin';
Session::put('uid', $uid);
Session::put('role', $role);

/* get */
Session::get('uid'); //$uid

/* expire */
Session::expire();
```

## Session.class.php
*IN DEV*

```php
/* require */
require_once('Session.class.php');

/* init */
$config = new CRObject();
$config->set('time_out', 3600);
$config->set('bind_ip', false);
Session::configure($config);

/* put */
$uid = 1;
$role = 'admin';
Session::put('uid', $uid);
Session::put('role', $role);

/* get */
Session::get('uid'); //$uid

/* expire */
Session::expire();
```

## SQLBuilder.class.php

```php
/* require */
require_once('CRObject.class.php');
require_once('SQLBuilder.class.php');

/* define select */
$selected_rows = array('id', 'author', 'title', 'content', 'category');
$where_arr = array('id' => '?');
$builder = new SQLBuilder();
$builder->select('quora', $selected_rows);
$builder->where($where_arr);
$sql = $builder->build();
echo $sql; // SELECT `id`, `author`, `title`, `content`, `category` FROM `quora` WHERE `id` = ?

/* define insert */
$key_values = array('author' => '?', 'title' => '?', 'content' => '?', 'category' => '?');
$builder = new SQLBuilder();
$builder->insert('quora', $key_values);
$sql = $builder->build();
echo $sql; // INSERT INTO `quora` (`author`, `title`, `content`, `category`) VALUES (?, ?, ?, ?)

/* define update */
$key_values = array('author' => '?', 'title' => '?', 'content' => '?', 'category' => '?');
$where_arr = array('id' => '?');
$builder = new SQLBuilder();
$builder->update('quora', $key_values);
$builder->where($where_arr);
$sql = $builder->build(); // UPDATE `quora` SET `author` = ?, `title` = ?, `content` = ?, `category` = ? WHERE `id` = ?
```

## Validator

```php
/* require */
require_once('Validator.class.php');

/* validate email */
$email = 'admin@example.com';
if()Validator::isEmail($email)){
    echo 'valid email';
}

/* validate IPv4 */
$ip = '8.8.8.8';
if()Validator::isIP($ip)){
    echo 'valid IPv4';
}
```