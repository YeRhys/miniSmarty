<?php
/**
 * MiniSmarty模板引擎
 * @link http://www.cnblogs.com/isuhua/
 * @author 华仔_suhua <weibo.com/suhua123>
 * @package MiniSmarty
 * @version 0.0.0.1
 */
class MiniSmary_Compile{
    //模板内容
    private $content = '';
    
    //构造函数
    public function __construct($tpl_file) {
		//file_get_contents()  读取一个文件中的内容,包括远程文件!!(模板文件)
        $this->content = file_get_contents($tpl_file);
    }
    
    //解析普通变量，如把{$name}解析成$this->_tpl_var['name']
    public function parseVar() {
        $pattern = '/\{\$([\w\d]+)\}/';
		//preg_match() 函数用于进行正则表达式匹配，成功返回 1 ，否则返回 0 。
        if (preg_match($pattern, $this->content)) {
            $this->content = preg_replace($pattern, '<?php echo \$this->_tpl_var["$1"]?>', $this->content);
        }
    }
    
    //这里可以自定义其他解析器...
    
    //模板编译
    public function parse($parse_file) {
        //调用普通变量解析器
        $this->parseVar();
        //这里可以调用其他解析器...
        
        //编译完成后，生成编译文件
        if (!file_put_contents($parse_file, $this->content)) {
            exit('编译文件生成出错！');
        }
    }
}
?>