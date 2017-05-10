 <?php
 //引入模板引擎
    require 'MiniSmarty.class.php';
    //实例化模板类
    $minismarty = new MiniSmarty();
    //缓存开关
    $minismarty->caching = true;
    
    //定义变量
    $webname = '迷你版Smarty测试1';
    $author = 'suhua';
    $title = '这是一个测试标题';
    $content = '这是一段测试内容';
    
    //注入变量
    $minismarty->assign('webname', $webname);
    $minismarty->assign('author', $author);
    $minismarty->assign('title', $title);
    $minismarty->assign('content', $content);
    
    //启动编译模板文件
    $minismarty->display('demo.tpl');
	?>