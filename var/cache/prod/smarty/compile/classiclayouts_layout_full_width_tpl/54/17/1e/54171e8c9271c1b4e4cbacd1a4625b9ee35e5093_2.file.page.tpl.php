<?php
/* Smarty version 3.1.47, created on 2023-12-12 14:16:54
  from '/Applications/MAMP/htdocs/prestatest/themes/classic/templates/page.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.47',
  'unifunc' => 'content_65785d46af09c5_85296897',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '54171e8c9271c1b4e4cbacd1a4625b9ee35e5093' => 
    array (
      0 => '/Applications/MAMP/htdocs/prestatest/themes/classic/templates/page.tpl',
      1 => 1701349402,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65785d46af09c5_85296897 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_106576326965785d46aea8e1_53206634', 'content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['layout']->value);
}
/* {block 'page_title'} */
class Block_192591021965785d46aeb7d9_59488578 extends Smarty_Internal_Block
{
public $callsChild = 'true';
public $hide = 'true';
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <header class="page-header">
          <h1><?php 
$_smarty_tpl->inheritance->callChild($_smarty_tpl, $this);
?>
</h1>
        </header>
      <?php
}
}
/* {/block 'page_title'} */
/* {block 'page_header_container'} */
class Block_144324291965785d46aeaf54_82333089 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_192591021965785d46aeb7d9_59488578', 'page_title', $this->tplIndex);
?>

    <?php
}
}
/* {/block 'page_header_container'} */
/* {block 'page_content_top'} */
class Block_173331565165785d46aedf00_88997951 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'page_content'} */
class Block_68884975565785d46aee7b9_99766765 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Page content -->
        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_148767964865785d46aed8a6_51165255 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <div id="content" class="page-content card card-block">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_173331565165785d46aedf00_88997951', 'page_content_top', $this->tplIndex);
?>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_68884975565785d46aee7b9_99766765', 'page_content', $this->tplIndex);
?>

      </div>
    <?php
}
}
/* {/block 'page_content_container'} */
/* {block 'page_footer'} */
class Block_108656914365785d46aefb44_00404647 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Footer content -->
        <?php
}
}
/* {/block 'page_footer'} */
/* {block 'page_footer_container'} */
class Block_43695323365785d46aef517_42861476 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <footer class="page-footer">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_108656914365785d46aefb44_00404647', 'page_footer', $this->tplIndex);
?>

      </footer>
    <?php
}
}
/* {/block 'page_footer_container'} */
/* {block 'content'} */
class Block_106576326965785d46aea8e1_53206634 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_106576326965785d46aea8e1_53206634',
  ),
  'page_header_container' => 
  array (
    0 => 'Block_144324291965785d46aeaf54_82333089',
  ),
  'page_title' => 
  array (
    0 => 'Block_192591021965785d46aeb7d9_59488578',
  ),
  'page_content_container' => 
  array (
    0 => 'Block_148767964865785d46aed8a6_51165255',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_173331565165785d46aedf00_88997951',
  ),
  'page_content' => 
  array (
    0 => 'Block_68884975565785d46aee7b9_99766765',
  ),
  'page_footer_container' => 
  array (
    0 => 'Block_43695323365785d46aef517_42861476',
  ),
  'page_footer' => 
  array (
    0 => 'Block_108656914365785d46aefb44_00404647',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


  <section id="main">

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_144324291965785d46aeaf54_82333089', 'page_header_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_148767964865785d46aed8a6_51165255', 'page_content_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_43695323365785d46aef517_42861476', 'page_footer_container', $this->tplIndex);
?>


  </section>

<?php
}
}
/* {/block 'content'} */
}
