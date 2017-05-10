<?php
/**
 * MiniSmarty模板引擎
 * @version 0.0.0.1
 */
class MiniSmarty {
    //模板文件
    public $template_dir = 'templates';
    //编译文件
    public $compile_dir = 'templates_c';
    //缓存文件
    public $cache_dir = 'cache';
    //模板变量
    public $_tpl_var = array();
    //是否开启缓存
    public $caching = false;
    
    public function __construct() {
        $this->checkDir();
    }
    
    //检查目录是否建好
    private function checkDir() {
		//判断给定文件名是否是一个目录
        if (!is_dir($this->template_dir)) {
            exit('模板文件目录templates不存在！请手动创建');
        }
        if (!is_dir($this->compile_dir)) {
            exit('编译文件目录templates_c不存在！请手工创建！');
        }
        if (!is_dir($this->cache_dir)) {
            exit('缓存文件目录'.$this->cache_dir.'不存在！请手工创建！');
        }
    }
    
    //模板变量注入方法
    public function assign($tpl_var, $var = null) {
        if (isset($tpl_var) && !empty($tpl_var)) {
            $this->_tpl_var[$tpl_var] = $var;
        } else {
            exit('模板变量名没有设置好');
        }
    }
    
    //文件编译
    public function display($file) {
        //模板文件
        $tpl_file  = $this->template_dir.'/'.$file;
        if (!file_exists($tpl_file)) {
            exit('ERROR:模板文件不存在！');
        }
        //编译文件
        $parse_file = $this->compile_dir.'/'.md5($file).$file.'.php';
        
        //只有当编译文件不存在或者是模板文件被修改过了 编译文件时间小于模板文件时间就重新编译
        //才重新编译文件
        if (!file_exists($parse_file) || filemtime($parse_file) < filemtime($tpl_file)) {
            include 'MiniSmarty_Compile.class.php';
            $compile = new MiniSmary_Compile($tpl_file);
            $compile->parse($parse_file);
        }
        
        //开启了缓存才加载缓存文件，否则直接加载编译文件
        if ($this->caching) {
            //缓存文件
            $cache_file = $this->cache_dir.'/'.md5($file).$file.'.html';
            //只有当缓存文件不存在，或者编译文件已被修改过 缓存文件小于编译文件的时间就重新生成缓存文件
            //重新生成缓存文件
            if (!file_exists($cache_file) || filemtime($cache_file) < filemtime($parse_file)) {
                //引入缓存文件
                include $parse_file;
                //缓存内容  得到当前缓冲区的内容并删除当前输出缓。
                $content = ob_get_clean();
                //生成缓存文件 file_put_contents将一个字符串写入文件
                if (!file_put_contents($cache_file, $content)) {
                    exit('缓存文件生成出错！');
                }
            }
            //载入缓存文件
            include $cache_file;
        } else {
            //载入编译文件
            include $parse_file;
        }
    }
}
?>