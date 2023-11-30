<?php
/* Smarty version 3.1.34-dev-7, created on 2023-11-30 11:54:33
  from '/Applications/MAMP/htdocs/prestatest/themes/classic/templates/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_656869e9b829e2_10826478',
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
function content_656869e9b829e2_10826478 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1106892335656869e9b7fee5_14406821', 'page_content_container');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_content_top'} */
class Block_1356432474656869e9b805c4_30822469 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'hook_home'} */
class Block_1062664816656869e9b81524_89269914 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php echo $_smarty_tpl->tpl_vars['HOOK_HOME']->value;?>

          <?php
}
}
/* {/block 'hook_home'} */
/* {block 'page_content'} */
class Block_19747340656869e9b80ed5_03631240 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1062664816656869e9b81524_89269914', 'hook_home', $this->tplIndex);
?>

        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_1106892335656869e9b7fee5_14406821 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content_container' => 
  array (
    0 => 'Block_1106892335656869e9b7fee5_14406821',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_1356432474656869e9b805c4_30822469',
  ),
  'page_content' => 
  array (
    0 => 'Block_19747340656869e9b80ed5_03631240',
  ),
  'hook_home' => 
  array (
    0 => 'Block_1062664816656869e9b81524_89269914',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-home">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1356432474656869e9b805c4_30822469', 'page_content_top', $this->tplIndex);
?>


        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19747340656869e9b80ed5_03631240', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
}
