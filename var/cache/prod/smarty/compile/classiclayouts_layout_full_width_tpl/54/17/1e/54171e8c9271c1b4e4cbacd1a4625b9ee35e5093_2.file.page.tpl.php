<?php
/* Smarty version 3.1.47, created on 2024-01-17 09:27:29
  from '/Applications/MAMP/htdocs/prestatest/themes/classic/templates/page.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.47',
  'unifunc' => 'content_65a78f71e19974_99189358',
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
function content_65a78f71e19974_99189358 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_187822313465a78f71e13ed2_45924019', 'content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['layout']->value);
}
/* {block 'page_title'} */
class Block_17698793165a78f71e14e60_06818566 extends Smarty_Internal_Block
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
class Block_122644891165a78f71e145a2_61719542 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17698793165a78f71e14e60_06818566', 'page_title', $this->tplIndex);
?>

    <?php
}
}
/* {/block 'page_header_container'} */
/* {block 'page_content_top'} */
class Block_89620074865a78f71e16c35_59510259 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'page_content'} */
class Block_18880932665a78f71e17563_89080350 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Page content -->
        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_21081691265a78f71e165c2_04889639 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <div id="content" class="page-content card card-block">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_89620074865a78f71e16c35_59510259', 'page_content_top', $this->tplIndex);
?>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_18880932665a78f71e17563_89080350', 'page_content', $this->tplIndex);
?>

      </div>
    <?php
}
}
/* {/block 'page_content_container'} */
/* {block 'page_footer'} */
class Block_109542345365a78f71e189f9_37782966 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Footer content -->
        <?php
}
}
/* {/block 'page_footer'} */
/* {block 'page_footer_container'} */
class Block_91027540865a78f71e18367_73848873 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <footer class="page-footer">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_109542345365a78f71e189f9_37782966', 'page_footer', $this->tplIndex);
?>

      </footer>
    <?php
}
}
/* {/block 'page_footer_container'} */
/* {block 'content'} */
class Block_187822313465a78f71e13ed2_45924019 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_187822313465a78f71e13ed2_45924019',
  ),
  'page_header_container' => 
  array (
    0 => 'Block_122644891165a78f71e145a2_61719542',
  ),
  'page_title' => 
  array (
    0 => 'Block_17698793165a78f71e14e60_06818566',
  ),
  'page_content_container' => 
  array (
    0 => 'Block_21081691265a78f71e165c2_04889639',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_89620074865a78f71e16c35_59510259',
  ),
  'page_content' => 
  array (
    0 => 'Block_18880932665a78f71e17563_89080350',
  ),
  'page_footer_container' => 
  array (
    0 => 'Block_91027540865a78f71e18367_73848873',
  ),
  'page_footer' => 
  array (
    0 => 'Block_109542345365a78f71e189f9_37782966',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


  <section id="main">

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_122644891165a78f71e145a2_61719542', 'page_header_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_21081691265a78f71e165c2_04889639', 'page_content_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_91027540865a78f71e18367_73848873', 'page_footer_container', $this->tplIndex);
?>


  </section>

<?php
}
}
/* {/block 'content'} */
}
