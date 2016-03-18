<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * Markdown 编辑器 <a href="https://pandao.github.io/editor.md/" target="_blank">Editor.md</a> for Typecho
 * 
 * @package EditorMD
 * @author DT27
 * @version 1.0.0
 * @link https://dt27.org
 */
class EditorMD_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('admin/write-post.php')->richEditor = array('EditorMD_Plugin', 'Editor');
        Typecho_Plugin::factory('admin/write-page.php')->richEditor = array('EditorMD_Plugin', 'Editor');
        Typecho_Plugin::factory('Widget_Archive')->footer = array('EditorMD_Plugin','footerJS');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {

        $isActive = new Typecho_Widget_Helper_Form_Element_Radio('isActive',
            array(
                '1' => '是',
                '0' => '否',
            ),'1', _t('是否启用 Edirot.md 编辑器'), NULL);
        $form->addInput($isActive);
    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 插入编辑器
     */
    public static function Editor()
    {
        $options = Helper::options();
        $cssUrl = $options->pluginUrl.'/EditorMD/css/editormd.css';
        $jsUrl = $options->pluginUrl.'/EditorMD/js/editormd.js';
        ?>
        <link rel="stylesheet" href="<?php echo $cssUrl; ?>" />
        <script>
            var emojiPath = '<?php echo $options->pluginUrl; ?>';
        </script>
        <script type="text/javascript" src="<?php echo $jsUrl; ?>"></script>
        <script>
            $('#text').wrap("<div id='text-editormd'></div>");
            postEditormd = editormd("text-editormd", {
                width: "100%",
                height: 640,
                path : '<?php echo $options->pluginUrl ?>/EditorMD/lib/',
                emoji: true,
                toolbarAutoFixed : false
            });
        </script>
        <?php
    }
    /**
     * emoji 解析器
     */
    public static function footerJS()
    {
        $options = Helper::options();
        $cssUrl = $options->pluginUrl.'/EditorMD/css/emojify.min.css';
        $jsUrl = $options->pluginUrl.'/EditorMD/js/emojify.min.js';
        ?>
        <link rel="stylesheet" href="<?php echo $cssUrl; ?>" />
        <script type="text/javascript" src="<?php echo $jsUrl; ?>"></script>
        <script>
            emojify.setConfig({
                img_dir: 'https:' == document.location.protocol ? "https://staticfile.qnssl.com/emoji-cheat-sheet/1.0.0" : "http://cdn.staticfile.org/emoji-cheat-sheet/1.0.0",
                blacklist: {
                    'ids': [],
                    'classes': ['no-emojify'],
                    'elements': ['^script$', '^textarea$', '^pre$', '^code$']
                },
            });
            emojify.run();
        </script>
        <?php
    }
}
