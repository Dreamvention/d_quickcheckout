<?php 
/* path: admin/view/template/module/d_quickcheckout.tpl
*  author: dreamvention
*/
?><?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
 <div class="page-header">
  <div class="container-fluid">
   <div class="pull-right form-inline">
    <?php if($stores){ ?>
    <select class="form-control" onChange="location='<?php echo $module_link; ?>&store_id='+$(this).val()">
     <?php foreach($stores as $store){ ?>
     <?php if($store['store_id'] == $store_id){ ?>
     <option value="<?php echo $store['store_id']; ?>" selected="selected" ><?php echo $store['name']; ?></option>
     <?php }else{ ?>
     <option value="<?php echo $store['store_id']; ?>" ><?php echo $store['name']; ?></option>
     <?php } ?>
     <?php } ?>
    </select><?php } ?>
    <button onClick="saveAndStay()" data-toggle="tooltip" title="<?php echo $button_save_and_stay; ?>" class="btn btn-success"><i class="fa fa-save"></i></button>
    <button type="submit" form="form-featured" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
   <h1><?php echo $heading_title; ?></h1>
   <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
   </ul>
  </div>
 </div>
 <div class="container-fluid">
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
   <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
   <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
   </div>
   <div class="panel-body">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-featured" class="form-horizontal">
      <div class="row">
       <div class="col-sm-2">
        <ul class="nav nav-pills nav-stacked">
         <li class="active">
          <a href="#home" data-toggle="tab">
           <span class="fa fa-home fa-fw"></span> <span><?php echo $text_home; ?></span>
          </a>
         </li>
         <li>
          <a href="#general" data-toggle="tab">
           <i class="fa fa-cog fa-fw"></i> <span><?php echo $text_general; ?></span>
          </a>
         </li>
         <li>
          <a href="#login" data-toggle="tab">
           <i class="fa fa-key fa-fw"></i> <span><?php echo $text_login; ?></span>
          </a>
         </li>
         <li>
          <a href="#payment_address" data-toggle="tab">
           <i class="fa fa-book fa-fw"></i> <span><?php echo $text_payment_address; ?></span>
          </a>
         </li>
         <li>
          <a href="#shipping_address" data-toggle="tab">
           <i class="fa fa-book fa-fw"></i> <span><?php echo $text_shipping_address; ?></span>
          </a>
         </li>
         <li>
          <a href="#shipping_method" data-toggle="tab">
           <i class="fa fa-truck fa-fw"></i> <span><?php echo $text_shipping_method; ?></span>
          </a>
         </li>
         <li>
          <a href="#payment_method" data-toggle="tab">
           <i class="fa fa-credit-card fa-fw"></i> <span><?php echo $text_payment_method; ?></span>
          </a>
         </li>
         <li>
          <a href="#confirm" data-toggle="tab">
           <i class="fa fa-shopping-cart fa-fw"></i> <span><?php echo $text_confirm; ?></span>
          </a>
         </li>
         <li>
          <a href="#design" data-toggle="tab">
           <i class="fa fa-paint-brush fa-fw"></i> <span><?php echo $text_design; ?></span>
          </a>
         </li>
         <li>
          <a href="#analytics" data-toggle="tab">
           <i class="fa fa-bar-chart fa-fw"></i> <span><?php echo $text_analytics; ?></span>
          </a>
         </li>
         </ul>
       </div>
       <div class="col-sm-10">
        <div class="tab-content">
         <div id="home" class="tab-pane active">
           <div class="page-header">
            <h3><span class="fa fa-home"></span> <span><?php echo $text_home; ?></span></h3>
           </div>
           <div class="row">
             <div class="col-sm-6 col-md-4">
              <a href="#general" data-toggle="tab">
               <span class="fa fa-cog fa-5x col-xs-4"></span>
               <div class="col-xs-8">
                <h3><?php echo $text_general; ?></h3>
                <p><?php echo $text_intro_general; ?></p>
               </div>
              </a>
             </div>

             <div class="col-sm-6 col-md-4">
              <a href="#login" data-toggle="tab">
               <span class="fa fa-key fa-5x fa-5x col-xs-4"></span>
               <div class="col-xs-8">
                <h3><?php echo $text_login; ?></h3>
                <p><?php echo $text_intro_login; ?></p>
               </div>
              </a>
             </div>

             <div class="col-sm-6 col-md-4">
              <a href="#payment_address" data-toggle="tab">
               <span class="fa fa-book fa-5x fa-5x col-xs-4"></span>
               <div class="col-xs-8">
                <h3><?php echo $text_payment_address; ?></h3>
                <p><?php echo $text_intro_payment_address; ?></p>
               </div>
              </a>
             </div>

             <div class="col-sm-6 col-md-4">
              <a href="#shipping_address" data-toggle="tab">
               <span class="fa fa-book fa-5x fa-5x col-xs-4"></span>
               <div class="col-xs-8">
                <h3><?php echo $text_shipping_address; ?></h3>
                <p><?php echo $text_intro_shipping_address; ?></p>
               </div>
              </a>
             </div> 

             <div class="col-sm-6 col-md-4">
              <a href="#shipping_method" data-toggle="tab">
               <span class="fa fa-truck fa-5x fa-5x col-xs-4"></span>
               <div class="col-xs-8">
                <h3><?php echo $text_shipping_method; ?></h3>
                <p><?php echo $text_intro_shipping_method; ?></p>
               </div>
              </a>
             </div>

             <div class="col-sm-6 col-md-4">
              <a href="#payment_method" data-toggle="tab">
               <span class="fa fa-credit-card fa-5x fa-5x col-xs-4"></span>
               <div class="col-xs-8">
                <h3><?php echo $text_payment_method; ?></h3>
                <p><?php echo $text_intro_payment_method; ?></p>
               </div>
              </a>
             </div>

             <div class="col-sm-6 col-md-4">
              <a href="#confirm" data-toggle="tab">
               <span class="fa fa-shopping-cart fa-5x fa-5x col-xs-4"></span>
               <div class="col-xs-8">
                <h3><?php echo $text_confirm; ?></h3>
                <p><?php echo $text_intro_confirm; ?></p>
               </div>
              </a>
             </div>

             <div class="col-sm-6 col-md-4">
              <a href="#design" data-toggle="tab">
               <span class="fa fa-paint-brush fa-5x fa-5x col-xs-4"></span>
               <div class="col-xs-8">
                <h3><?php echo $text_design; ?></h3>
                <p><?php echo $text_intro_design; ?></p>
               </div>
              </a>
             </div>

             <div class="col-sm-6 col-md-4">
              <a href="#analytics" data-toggle="tab">
               <span class="fa fa-bar-chart fa-5x fa-5x col-xs-4"></span>
               <div class="col-xs-8">
                <h3><?php echo $text_analytics; ?></h3>
                <p><?php echo $text_intro_analytics; ?></p>
               </div>
              </a>
             </div>
           </div>
         </div><!-- /#home-->

  <script>
  $('.tab-content a[data-toggle]').click(function(){

    $('.panel .nav-stacked li.active').removeClass('active')
    $('.panel .nav-stacked li a[href="'+$(this).attr('href')+'"]').parent().addClass('active')
    //return false;
  })
  </script>
         <!---------------------------------- general ---------------------------------->
         <div id="general" class="tab-pane">
          
          <h3 class="page-header">
           <span class="fa fa-cog fa-fw"></span> <span><?php echo $text_general; ?></span>
          </h3>
          <div class="row">
            <div class="col-md-6">

             <div class="form-group">
              <label class="col-sm-6 control-label" for="input-catalog-limit">
               <span data-toggle="tooltip" title="<?php echo $help_general_enable; ?>">
                <?php echo $entry_general_enable; ?>
               </span>
              </label>
              <div class="col-sm-6">
               <div class="checkbox">
                <label>
                 <input type="hidden" value="0" name="<?=$id?>[general][enable]" />
                 <?php if(isset($d_quickcheckout['general']['enable']) && $d_quickcheckout['general']['enable'] == 1){ ?>
                  <input type="checkbox" value="1" name="<?=$id?>[general][enable]" id="checkout_enable" checked="checked" /> 
                 <?php }else{ ?>
                  <input type="checkbox" value="1" name="<?=$id?>[general][enable]" id="checkout_enable"/> 
                 <?php } ?> 
                 <?php echo $text_enable; ?>
                </label>
               </div>
              </div>
             </div>

             <div class="form-group">
              <label class="col-sm-6 control-label" for="input-catalog-limit">
               <span data-toggle="tooltip" title="<?php echo $help_general_default_option; ?>">
                <?php echo $entry_general_default_option; ?>
               </span>
              </label>
              <div class="col-sm-6">
               <div class="radio">
                <?php if(isset($d_quickcheckout['general']['default_option']) && $d_quickcheckout['general']['default_option'] == 'guest'){ ?>
                 <label for="general_default_option_guest">
                  <input type="radio" value="guest" name="<?=$id?>[general][default_option]" checked="checked" id="general_default_option_guest" />
                  <?php echo $text_guest; ?>
                 </label>
                 <label for="general_default_option_register">
                  <input type="radio" value="register" name="<?=$id?>[general][default_option]" id="general_default_option_register" />
                  <?php echo $text_register; ?>
                 </label>
                <?php }else{ ?>
                 <label for="general_default_option_guest">
                  <input type="radio" value="guest" name="<?=$id?>[general][default_option]" id="general_default_option_guest" />
                  <?php echo $text_guest; ?>
                 </label>
                 <label for="general_default_option_register">
                  <input type="radio" value="register" name="<?=$id?>[general][default_option]" checked="checked" id="general_default_option_register" />
                  <?php echo $text_register; ?>
                 </label>
                <?php } ?>
               </div>
              </div>
             </div>

             <div class="form-group">
              <label class="col-sm-6 control-label" for="input-catalog-limit">
               <span data-toggle="tooltip" title="<?php echo $help_general_main_checkout; ?>">
                <?php echo $entry_general_main_checkout; ?>
               </span>
              </label>
              <div class="col-sm-6">
               <div class="checkbox">
                <label for="general_main_checkout">
                 <input type="hidden" value="0" name="<?=$id?>[general][main_checkout]" />
                 <?php if(isset($d_quickcheckout['general']['main_checkout']) && $d_quickcheckout['general']['main_checkout'] == 1){ ?>
                  <input type="checkbox" value="1" name="<?=$id?>[general][main_checkout]" checked="checked" id="general_main_checkout" />
                 <?php }else{ ?>
                  <input type="checkbox" value="1" name="<?=$id?>[general][main_checkout]" id="general_main_checkout" />
                 <?php } ?>
                 <?php echo $text_enable; ?>
                </label>
               </div>
              </div>
             </div>

             <div class="form-group">
              <label class="col-sm-6 control-label" for="input-catalog-limit">
               <span data-toggle="tooltip" title="<?php echo $help_general_clear_session; ?>">
                <?php echo $entry_general_clear_session; ?>
               </span>
              </label>
              <div class="col-sm-6">
               <div class="checkbox">
                <label for="general_clear_session">
                 <input type="hidden" value="0" name="<?=$id?>[general][clear_session]" />
                 <?php if(isset($d_quickcheckout['general']['clear_session']) && $d_quickcheckout['general']['clear_session'] == 1){ ?>
                  <input type="checkbox" value="1" name="<?=$id?>[general][clear_session]" checked="checked" id="general_clear_session" />
                 <?php }else{ ?>
                  <input type="checkbox" value="1" name="<?=$id?>[general][clear_session]" id="general_clear_session" />
                 <?php } ?>
                 <?php echo $text_enable; ?>
                </label>
               </div>
              </div>
             </div>

             <div class="form-group">
              <label class="col-sm-6 control-label" for="input-catalog-limit">
               <span data-toggle="tooltip" title="<?php echo $help_general_login_refresh; ?>">
                <?php echo $entry_general_login_refresh; ?>
               </span>
              </label>
              <div class="col-sm-6">
               <div class="checkbox">
                <label for="general_login_refresh">
                 <input type="hidden" value="0" name="<?=$id?>[general][clear_session]" />
                 <?php if(isset($d_quickcheckout['general']['login_refresh']) && $d_quickcheckout['general']['login_refresh'] == 1){ ?>
                  <input type="checkbox" value="1" name="<?=$id?>[general][login_refresh]" checked="checked" id="general_login_refresh" />
                 <?php }else{ ?>
                  <input type="checkbox" value="1" name="<?=$id?>[general][login_refresh]" id="general_login_refresh" />
                 <?php } ?>
                 <?php echo $text_enable; ?>
                </label>
               </div>
              </div>
             </div>

             <div class="form-group">
              <label class="col-sm-6 control-label" for="input-catalog-limit">
               <span data-toggle="tooltip" title="<?php echo $help_general_default_email; ?>">
                <?php echo $entry_general_default_email; ?>
               </span>
              </label>
              <div class="col-sm-6">
                <?php if(isset($d_quickcheckout['general']['default_email']) && $d_quickcheckout['general']['default_email'] != ""){ ?>
                 <input type="text" value="<?php echo $d_quickcheckout['general']['default_email']; ?>" name="<?=$id?>[general][default_email]" id="general_default_email" class="form-control">
                <?php }else{ ?>
                 <input type="text" value="0" name="<?=$id?>[general][default_email]" id="general_default_email" class="form-control"/>
                <?php } ?>
              </div>
             </div>

            </div>
            <div class="col-md-6">

             <div class="form-group">
              <label class="col-sm-6 control-label" for="input-catalog-limit">
               <span data-toggle="tooltip" title="<?php echo $help_general_version; ?>">
                <?php echo $entry_general_version; ?>
               </span>
              </label>
              <div class="col-sm-2">
                <a id="version_check" class="btn btn-primary"><?php echo $button_version_check; ?></a>
              </div>
              <div class="col-sm-4">
                <div id="version_result"></div>
              </div>
             </div>

             <div class="form-group">
              <label class="col-sm-6 control-label" for="input-catalog-limit">
               <span data-toggle="tooltip" title="<?php echo $help_general_debug; ?>">
                <?php echo $entry_general_debug; ?>
               </span>
              </label>
              <div class="col-sm-6">
               <div class="checkbox">
                <label for="general_debug">
                 <input type="hidden" value="0" name="<?=$id?>[general][debug]" />
                 <?php if(isset($d_quickcheckout['general']['debug']) && $d_quickcheckout['general']['debug'] == 1){ ?>
                  <input type="checkbox" value="1" name="<?=$id?>[general][debug]" checked="checked" id="general_debug" />
                 <?php }else{ ?>
                  <input type="checkbox" value="1" name="<?=$id?>[general][debug]" id="general_debug" />
                 <?php } ?>
                 <?php echo $text_enable; ?>
                </label>
               </div>
              </div>
             </div>

             <div class="form-group">
              <label class="col-sm-6 control-label" for="input-catalog-limit">
               <span data-toggle="tooltip" title="<?php echo $help_general_min_order; ?>">
                <?php echo $entry_general_min_order; ?>
               </span>
              </label>
              <div class="col-sm-6">
               <div class="input-group">
                <label for="general_min_order_value" id="label_general_min_order_value" class="input-group-addon">%s</label>
                <?php if(isset($d_quickcheckout['general']['min_order']['value']) && $d_quickcheckout['general']['min_order']['value'] != ""){ ?>
                 <input type="text" value="<?php echo $d_quickcheckout['general']['min_order']['value']; ?>" name="<?=$id?>[general][min_order][value]" id="general_min_order_value" class="form-control"/>
                <?php }else{ ?>
                 <input type="text" value="0" name="<?=$id?>[general][min_order][value]" class="form-control" id="general_min_order_value"/>
                <?php } ?>
               </div>

               <?php foreach ($languages as $language) { ?>
               <div id="tab_general_min_order_text_<?php echo $language['language_id']; ?>" class="input-group">
                <label class="input-group-addon" for="general_min_order_text_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
                <input type="text" name="<?=$id?>[general][min_order][text][<?php echo $language['language_id']; ?>]" id="general_min_order_text_<?php echo $language['language_id']; ?>" value="<?php echo isset($d_quickcheckout['general']['min_order']['text'][$language['language_id']]) ? $d_quickcheckout['general']['min_order']['text'][$language['language_id']] : $text_value_min_order; ?>" class="form-control" >
               </div>
               <?php } ?>
              </div>
             </div>

             <div class="form-group">
              <label class="col-sm-6 control-label" for="input-catalog-limit">
               <span data-toggle="tooltip" title="<?php echo $help_general_min_quantity; ?>">
                <?php echo $entry_general_min_quantity; ?>
               </span>
              </label>
              <div class="col-sm-6">
               <div class="input-group">
                <label for="general_min_quantity_value" id="label_general_min_quantity_value" class="input-group-addon">%s</label>
                <?php if(isset($d_quickcheckout['general']['min_quantity']['value']) && $d_quickcheckout['general']['min_quantity']['value'] != ""){ ?>
                 <input type="text" value="<?php echo $d_quickcheckout['general']['min_quantity']['value']; ?>" name="<?=$id?>[general][min_quantity][value]" id="general_min_quantity_value" class="form-control"/>
                <?php }else{ ?>
                 <input type="text" value="0" name="<?=$id?>[general][min_quantity][value]" class="form-control" id="general_min_quantity_value"/>
                <?php } ?>
               </div>

               <?php foreach ($languages as $language) { ?>
               <div id="tab_general_min_quantity_text_<?php echo $language['language_id']; ?>" class="input-group">
                <label class="input-group-addon" for="general_min_quantity_text_<?php echo $language['language_id']; ?>" title="<?php echo $language['name']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
                <input type="text" name="<?=$id?>[general][min_quantity][text][<?php echo $language['language_id']; ?>]" id="general_min_quantity_text_<?php echo $language['language_id']; ?>" value="<?php echo isset($d_quickcheckout['general']['min_quantity']['text'][$language['language_id']]) ? $d_quickcheckout['general']['min_quantity']['text'][$language['language_id']] : $text_value_min_quantity; ?>" class="form-control" >
               </div>
               <?php } ?>
              </div>
             </div>

             <div class="form-group">
              <label class="col-sm-6 control-label" for="input-catalog-limit">
               <span data-toggle="tooltip" title="<?php echo $help_general_trigger; ?>">
                <?php echo $entry_general_trigger; ?>
               </span>
              </label>
              <div class="col-sm-6">
                <?php if(isset($d_quickcheckout['general']['trigger']) && $d_quickcheckout['general']['trigger'] != ""){ ?>
                 <input type="text" value="<?php echo $d_quickcheckout['general']['trigger']; ?>" name="<?=$id?>[general][trigger]" id="general_trigger" class="form-control"/>
                <?php }else{ ?>
                 <input type="text" value="0" name="<?=$id?>[general][trigger]" id="general_trigger" class="form-control"/>
                <?php } ?>
              </div>
             </div>
            </div>
           </div>
            <h3 class="page-header hidden">
             <span class="fa fa-cog fa-fw"></span> <span><?php echo $text_position_module; ?></span>
            </h3>
            <p class="hidden"><?php echo $help_position_module; ?></p>
              <table id="module" class="hidden table table-striped table-bordered table-hover">
               <thead>
                <tr>
                 <td class="text-left"><?php echo $entry_layout; ?></td>
                 <td class="text-left"><?php echo $entry_position; ?></td>
                 <td class="text-left"><?php echo $entry_status; ?></td>
                 <td class="text-left"><?php echo $entry_sort_order; ?></td>
                 <td></td>
                </tr>
               </thead>
               <?php $module_row = 0; ?>
               <?php foreach ($d_quickcheckout_modules as $module) { ?>
               <tbody id="module-row<?php echo $module_row; ?>">
               <tr>
                <td class="text-left">
                 <select name="<?=$id?>_module[<?php echo $module_row; ?>][layout_id]" class="form-control">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                 </select>
                </td>
                <td class="text-left">
                 <select name="<?=$id?>_module[<?php echo $module_row; ?>][position]" class="form-control">
                  <?php if ($module['position'] == 'content_top') { ?>
                  <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                  <?php } else { ?>
                  <option value="content_top"><?php echo $text_content_top; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'content_bottom') { ?>
                  <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                  <?php } else { ?>
                  <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_left') { ?>
                  <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                  <?php } else { ?>
                  <option value="column_left"><?php echo $text_column_left; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_right') { ?>
                  <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                  <?php } else { ?>
                  <option value="column_right"><?php echo $text_column_right; ?></option>
                  <?php } ?>
                 </select>
                </td>
                <td class="text-left">
                 <select name="<?=$id?>_module[<?php echo $module_row; ?>][status]" class="form-control">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                 </select>
                </td>
                <td class="text-left">
                 <input type="text" name="<?=$id?>_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" class="form-control" />
                </td>
                <td class="text-left">
                 <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                </td>
               </tr>
              </tbody>
              <?php $module_row++; ?>
              <?php } ?>
              <tfoot>
               <tr>
                <td colspan="4"></td>
                <td class="text-left">
                 <button type="button" onclick="addModule();" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $button_add_module; ?>"><i class="fa fa-plus-circle"></i></button>
                </td>
               </tr>
              </tfoot>
             </table>

        
           </div><!-- /#general-->

         <!---------------------------------- login ---------------------------------->
         <div id="login" class="tab-pane">
          
           <h3 class="page-header">
            <span class="fa fa-key fa-fw"></span> <span><?php echo $text_login; ?></span>
           </h3>
   
           <?php if($social_login) { ?>

           <div id="sortable_social_login" class="list-group atab col-md-5">

           <?php foreach($d_quickcheckout['general']['social_login']['providers'] as $provider_name => $provider){ ?>
            <?php if(isset($provider['id'])) { ?> 
            <div class="clearfix sort-item atab-item list-group-item">
             <div class="col-sm-5"><span><span class="<?php echo $provider['icon']; ?>"> </span><?php echo ${'text_'.$provider['id']};?></span></div>
             <div class="col-sm-7"><span>
              <input type="hidden" class="sort-value" value="<?php echo $provider['sort_order']; ?>" name="<?=$id?>[general][social_login][providers][<?php echo $provider_name; ?>][sort_order]">
              <input type="hidden" value="0" name="<?=$id?>[general][social_login][providers][<?php echo $provider_name; ?>][enabled]">
              <input type="checkbox" value="1" id="general_social_login_providers_<?php echo $provider['id']; ?>" <?php echo ($provider['enabled']) ? 'checked="checked"': ''; ?> name="<?=$id?>[general][social_login][providers][<?php echo $provider_name; ?>][enabled]"> <label for="general_social_login_providers_<?php echo $provider['id']; ?>"><?php echo $text_enable; ?><label></span> 
              <span class="fa fa-drag"></span>
             </div>
            </div>
            <?php } ?>
           <?php } ?>

           </div>

           <div class="col-md-7">

            <div class="form-group">
             <label class="col-sm-6 control-label">
              <span data-toggle="tooltip" title="" data-original-title="<?php echo $help_socila_login_style; ?>"><?php echo $entry_socila_login_style; ?></span>
             </label>
             <div class="col-sm-6">
              <select name="<?=$id?>[general][socila_login_style]" class="form-control">
              <?php foreach ($socila_login_styles as $style) {?>
               <?php if($d_quickcheckout['general']['socila_login_style'] == $style){ ?>
                <option value="<?php echo $style; ?>" selected="selected"><?php echo $style; ?></option>
               <?php }else{ ?>
                <option value="<?php echo $style; ?>"><?php echo $style; ?></option>
               <?php } ?>
              <?php } ?>
              </select>
             </div>
            </div>

            <div class="form-group">
             <div class="col-sm-offset-6 col-sm-6">
              <a href="<?php echo $link_social_login_edit; ?>" class="btn btn-primary"><?php echo $button_social_login_edit?></a>
             </div>
            </div> 

           </div>

          <?php }else{ ?>
           <div class="bs-callout bs-callout-warning"><?php echo $text_social_login_required; ?></div>
          <?php } ?>

  <script>
   // $(function() {
   //   $( "#sortable_social_login" ).sortable({
   //    axis: "y",
   //    placeholder: "ui-state-highlight",
   //    distance: 5,
   //    stop: function( event, ui ) {
   //     ui.item.children( ".sort-item" ).triggerHandler( "focusout" );
   //   $(this).find(".sort-item").each(function(i, el){
   //    $(this).find(".sort-value").val($(el).index())
   //   });
   //    }
   //   });
   // });
  </script>
       
         </div><!-- /#login-->

         <!---------------------------------- payment_address ---------------------------------->
         <div id="payment_address" class="tab-pane">
          
           <h3 class="page-header">
            <span class="fa fa-book fa-fw"></span> <span><?php echo $text_payment_address; ?></span>
           </h3>
           <div class="callout callout-warning"><?php echo $text_need_full_version; ?></div>

         </div><!-- /#payment_address-->

         <!---------------------------------- shipping_address ---------------------------------->
         <div id="shipping_address" class="tab-pane">
         
            <h3 class="page-header">
              <span class="fa fa-book fa-fw"></span> <span><?php echo $text_shipping_address; ?></span>
            </h3>
            <div class="callout callout-warning"><?php echo $text_need_full_version; ?></div>

         </div><!-- /#shipping_address-->

         <!---------------------------------- shipping_method ---------------------------------->
         <div id="shipping_method" class="tab-pane">
          
            <h3 class="page-header">
              <span class="fa fa-truck fa-fw"></span> <span><?php echo $text_shipping_method; ?></span>
            </h3>

            <div class="callout callout-warning"><?php echo $text_need_full_version; ?></div>

         </div><!-- /#general-->
   
         <!---------------------------------- payment_method ---------------------------------->
         <div id="payment_method" class="tab-pane">
          
            <h3 class="page-header">
              <span class="fa fa-credit-card fa-fw"></span> <span><?php echo $text_payment_method; ?></span>
            </h3>
            <div class="callout callout-warning"><?php echo $text_need_full_version; ?></div>
         </div><!-- /#payment_method-->

         <!---------------------------------- confirm ---------------------------------->
         <div id="confirm" class="tab-pane">
          
            <h3 class="page-header">
              <span class="fa fa-shopping-cart fa-fw"></span> <span><?php echo $text_cart; ?></span>
            </h3>

            <div class="callout callout-warning"><?php echo $text_need_full_version; ?></div>
  
         </div><!-- /#confirm-->

         <!---------------------------------- design ---------------------------------->
         <div id="design" class="tab-pane">
          
           <h3 class="page-header">
            <span class="fa fa-paint-brush fa-fw"></span> <span><?php echo $text_design; ?></span>
           </h3>

           <div class="callout callout-warning"><?php echo $text_need_full_version; ?></div>

         </div><!-- /#design-->

         <!---------------------------------- analytics ---------------------------------->
         <div id="analytics" class="tab-pane">
          <div class="row">
           <h3 class="page-header">
            <span class="fa fa-bar-chart fa-fw"></span> <span><?php echo $text_analytics; ?></span>
           </h3>
           <div class="bs-callout bs-callout-warning"><h4>In Development</h4>
            <p>Please feel free to send us feedback on the functionality you would like to see in the next updates via <a href="http://dreamvention.com/support">support</a></p></div>
          </div>
         </div><!-- /#analytics-->
        </div>
       </div>
      </div>
    </form>
   </div>
  </div>
 </div>
</div>

<?php 
$column_1 = $d_quickcheckout['design']['column_width'][1]; 
$column_2 = $d_quickcheckout['design']['column_width'][2];
$column_3 = $d_quickcheckout['design']['column_width'][3];
$column_4 = intval($d_quickcheckout['design']['column_width'][2]) + intval($d_quickcheckout['design']['column_width'][3]); 
?>
<style>
#column_width_1,
#column_1{ 
  width: <?=$column_1?>%;
}
#column_width_2,
#column_2{
  width: <?=$column_2?>%;
}
#column_width_3,
#column_3{
  width: <?=$column_3?>%;
}

#column_4{
  width: <?=$column_4?>%
}
.table-sortable .placeholder{
  height: 52px;
  width: 100%;
}
.table-sortable .placeholder td{
  background: #eee;
}
.table-sortable .dragged td {
  width: 25%;
}
.table-sortable {
  position: relative;
}
.table-sortable .dragged {
    position: absolute;
  z-index: 2000;
  width: 100%;
  display: table;
  background: #fff
}
</style>
<script type="text/javascript"><!--
// sorting fields
$('.sortable > tr').tsort({attr:'sort-data'});
$(function () {
  

  $(".table-sortable").sortable({
    containerSelector: 'table',
    itemPath: '',
    itemSelector: 'tr',
    distance: '10',
    pullPlaceholder: false,
    placeholder: '<tr class="placeholder"><td colspan="4" /></tr>',
    onDragStart: function (item, container, _super) {
      var offset = item.offset(),
      pointer = container.rootGroup.pointer

      adjustment = {
        left: pointer.left - offset.left,
        top: pointer.top - offset.top
      }

      _super(item, container)
    },
    onDrag: function (item, position) {
      item.css({
        left: position.left - adjustment.left,
        top: position.top - adjustment.top
      })
    },
    onDrop: function  (item, container, _super) {
      item.closest('tbody').find('tr').each(function (i, row) {
        console.log(i)
        $(row).find('.sort').val(i)
        
      })
   
      _super(item)
    }
  })
})


$('#column_slider').slider({'tooltip': 'hide'})
  .on('slide', function(ev){
    var pos1 = parseInt(ev.value[0])
    var pos2 = parseInt(ev.value[1])
    $("#column_1, #column_width_1").css({'width' : pos1+'%'})
    $("#column_width_1").val(pos1)
    $("#column_2, #column_width_2").css({'width' : pos2-pos1 +'%'})
    $("#column_width_2").val(pos2-pos1)
    $("#column_3, #column_width_3").css({'width' : parseInt(100-pos2) +'%'})
    $("#column_width_3").val(parseInt(100-pos2))
    $("#column_4").css({'width' : parseInt(100-pos1) +'%'})
    $("#column_width_4").val(parseInt(100-pos1))
    console.log(ev.value[0])
  })
var adjustment


var group = $("#column_groups ul.column").sortable({
  group: 'column',
  pullPlaceholder: false,
  // animation on drop
  onDrop: function  (item, targetContainer, _super) {

    var clonedItem = $('<li/>').css({height: 0})
    item.before(clonedItem)
    clonedItem.animate({'height': item.height()})
    
    item.animate(clonedItem.position(), function  () {
      clonedItem.detach()
      _super(item)
    })

    var data = group.sortable("serialize").get();

    var jsonString = JSON.stringify(data, null, ' ');

    $.each(data, function( column, column_value ) {
      $.each(column_value, function( row, row_value ) {
        console.log(row_value['id'] + ' column: ' + column+1 + 'row: ' + row)
        $('#step_'+ row_value['id'] + ' .data-column').val(column+1)
        $('#step_'+ row_value['id'] + ' .data-row').val(row)
       });
    });


  },

  // set item relative to cursor position
  onDragStart: function ($item, container, _super) {
    var offset = $item.offset(),
    pointer = container.rootGroup.pointer

    adjustment = {
      left: pointer.left - offset.left,
      top: pointer.top - offset.top
    }

    _super($item, container)
  },
  onDrag: function ($item, position) {
    $item.css({
      left: position.left - adjustment.left,
      top: position.top - adjustment.top
    })
  }
})


var module_row = <?php echo $module_row; ?>;

function addModule() { 
 html = '<tbody id="module-row' + module_row + '">';
 html += ' <tr>';
 html += '  <td class="text-left"><select name="<?=$id?>_module[' + module_row + '][layout_id]" class="form-control">';
 <?php foreach ($layouts as $layout) { ?>
 html += '   <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
 <?php } ?>
 html += '  </select></td>';
 html += '  <td class="text-left"><select name="<?=$id?>_module[' + module_row + '][position]" class="form-control">';
 html += '   <option value="content_top"><?php echo $text_content_top; ?></option>';
 html += '   <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
 html += '   <option value="column_left"><?php echo $text_column_left; ?></option>';
 html += '   <option value="column_right"><?php echo $text_column_right; ?></option>';
 html += '  </select></td>';
 html += '  <td class="text-left"><select name="<?=$id?>_module[' + module_row + '][status]" class="form-control">';
  html += '   <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
  html += '   <option value="0"><?php echo $text_disabled; ?></option>';
  html += '  </select></td>';
 html += '  <td class="text-left"><input type="text" class="form-control" name="<?=$id?>_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
 html += '  <td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" onclick="$(\'#module-row' + module_row + '\').remove();" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
 html += ' </tr>';
 html += '</tbody>';
 
 $('#module tfoot').before(html);
 
 module_row++;
}

function saveAndStay(){

  $.ajax( {
   type: "POST",
   url: $('#form-featured').attr( 'action' ) + '&save',
   data: $('#form-featured').serialize(),
  beforeSend: function() {
  $('#form-featured').fadeTo('slow', 0.5);
  },
  complete: function() {
  $('#form-featured').fadeTo('slow', 1);  
  },
   success: function( response ) {
    console.log( response );
   }
  } ); 
}
$('#version_check').click(function(){
 $.ajax( {
   type: "POST",
   url: 'index.php?route=module/d_quickcheckout/version_check&token=<?php echo $token; ?>',
  dataType: 'json',
  beforeSend: function() {
  $('#form-featured').fadeTo('slow', 0.5);
  },
  complete: function() {
  $('#form-featured').fadeTo('slow', 1);  
  },
   success: function( json ) {
    console.log( json );
  if(json['error']){
   $('#version_result').html('<div class="alert alert-danger">' + json['error'] + '</div>')
  }
  if(json['attention']){
   $html = '';
   if(json['update']){
     $.each(json['update'] , function(k, v) {
      $html += '<div>Version: ' +k+ '</div><div>'+ v +'</div>';
     });
   }
    $('#version_result').html('<div class="alert alert-warning">' + json['attention'] + $html + '</div>')
  }
  if(json['success']){
   $('#version_result').html('<div class="alert alert-success">' + json['success'] + '</div>')
  } 
   }
 })
})


//--></script> 
<?php echo $footer; ?>