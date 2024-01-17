<?php
/* Smarty version 3.1.47, created on 2024-01-17 09:27:24
  from '/Applications/MAMP/htdocs/prestatest/themes/classic/templates/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.47',
  'unifunc' => 'content_65a78f6c5fc023_03274684',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2f6a73acc0bd4e7e122ceef15042f09ad667ca26' => 
    array (
      0 => '/Applications/MAMP/htdocs/prestatest/themes/classic/templates/index.tpl',
      1 => 1701340511,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65a78f6c5fc023_03274684 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_99954481765a78f6c5f9348_89978920', 'page_content_container');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_content_top'} */
class Block_194084232465a78f6c5f9a24_66175593 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'hook_home'} */
class Block_89935431265a78f6c5fa9f0_56732751 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php echo $_smarty_tpl->tpl_vars['HOOK_HOME']->value;?>

          <?php
}
}
/* {/block 'hook_home'} */
/* {block 'page_content'} */
class Block_165404641465a78f6c5fa3c7_93700182 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_89935431265a78f6c5fa9f0_56732751', 'hook_home', $this->tplIndex);
?>

        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_99954481765a78f6c5f9348_89978920 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content_container' => 
  array (
    0 => 'Block_99954481765a78f6c5f9348_89978920',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_194084232465a78f6c5f9a24_66175593',
  ),
  'page_content' => 
  array (
    0 => 'Block_165404641465a78f6c5fa3c7_93700182',
  ),
  'hook_home' => 
  array (
    0 => 'Block_89935431265a78f6c5fa9f0_56732751',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-home">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_194084232465a78f6c5f9a24_66175593', 'page_content_top', $this->tplIndex);
?>


        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_165404641465a78f6c5fa3c7_93700182', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
}
