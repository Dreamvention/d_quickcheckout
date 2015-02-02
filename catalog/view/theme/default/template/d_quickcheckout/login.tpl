<!-- Quick Checkout v4.1.2 by Dreamvention.com quickcheckout/login.tpl -->
<?php if($login_style == 'popup') { ?>

  <div id="option_login_popup_trigger_wrap" class="form-inline clearfix <?php echo ($count) ? '' : 'qc-hide'; ?>">

      <!-- #option_register_popup-->
      <div class="btn-group">
      <label id="option_register_popup" class="btn <?php echo ($account == 'register') ? 'btn-primary' : 'btn-default'; ?> <?php if(!$data['option']['register']['display']){ echo 'qc-hide'; } ?>">

          <input type="radio" 
                 name="account" 
                 value="register" 
                 id="register" 
                 <?php echo ($account == 'register') ? 'checked="checked"' : ''; ?> 
                 class="hidden" 
                 data-refresh="1" 
                 autocomplete='off' />
          <?php echo $data['option']['register']['title']; ?>
      </label>
      <?php if ($guest_checkout) { ?>
      <!-- #option_guest_popup-->
      <label id="option_guest_popup" class="btn <?php echo ($account == 'guest') ? 'btn-primary' : 'btn-default'; ?>  <?php if(!$data['option']['guest']['display']){ echo 'qc-hide'; } ?>">

          <input type="radio" 
                 name="account" 
                 value="guest" 
                 id="guest" 
                 <?php echo ($account == 'guest') ? 'checked="checked"' : ''; ?> 
                 class="hidden" 
                 data-refresh="1" 
                 autocomplete='off'/>
          <?php echo $data['option']['guest']['title']; ?>
        </label>
      <?php } ?>
    </div>

      <!-- option_login_popup_trigger -->
      <a id="option_login_popup_trigger" data-toggle="modal" data-target="#option_login_popup" class="button btn btn-primary pull-right <?php echo (!$data['option']['login']['display']) ? 'qc-hide' : ''; ?>"><?php echo $button_login; ?></a>

    <?php if (isset($providers)) { ?>
      <style>
      #quickcheckout #d_social_login{
        display: inline-block;
        float: right;
      }
      #quickcheckout #option_login_popup_trigger{
        margin-left: 5px;
        margin-bottom: 5px;
      }
      <?php foreach($providers as $provider){ ?>
        #quickcheckout #dsl_<?php echo $provider['id']; ?>_button{
          background:  <?php echo $provider['background_color']; ?>
        }
        #quickcheckout #dsl_<?php echo $provider['id']; ?>_button:active{
          background: <?php echo $provider['background_color_active']; ?>;
        }
      <?php } ?>
      </style> 
      <div id="d_social_login">
        <span class="qc-dsl-label qc-dsl-label-<?php echo $dsl_size; ?>"><?php echo $button_sign_in; ?></span>
        <?php foreach($providers as $provider){ ?><?php if ($provider['enabled']) { ?><a id="dsl_<?php echo $provider['id']; ?>_button" class="qc-dsl-button qc-dsl-button-<?php echo $dsl_size; ?>" href="index.php?route=module/d_social_login/provider_login&provider=<?php echo $provider['id']; ?>"><span class="l-side"><span class="dsl-icon-<?php echo $provider['id']; ?> qc-dsl-icon"></span></span><span class="r-side"><?php echo $provider['heading']; ?></span></a><?php }  ?><?php } ?>
      </div>
    <?php }  ?>
  </div>

  <!-- Modal #option_login_popup -->
  <div class="modal fade" id="option_login_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title" id="myModalLabel">
            <?php echo $text_returning_customer; ?>
          </h4>
        </div>
        <div class="modal-body">
          
          <div class="form-group">
            <label for="login_email" class=" control-label"><?php echo $entry_email; ?></label>
            
              <input type="text" name="email" class="form-control" value="" id="login_email" placeholder="<?php echo $entry_email; ?>" />
           
          </div>

          <div class="form-group">
            <label for="login_password" class="control-label"><?php echo $entry_password; ?></label>
            
              <input type="password" name="password" class="form-control" value="" id="login_password" placeholder="<?php echo $entry_password; ?>"/>
            
          </div>
        </div>
     
        <div class="modal-footer">
          <input type="button" value="<?php echo $button_login; ?>" id="button_login_popup" class="btn btn-primary pull-left" />
          <a id="remeber_password" class="btn btn-link" href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a> 
        </div>
      </div>
    </div>
  </div>

  <script><!--
  $(function(){
    if($.isFunction($.fn.uniform)){
      $(" .styled, input:radio.styled").uniform().removeClass('styled');
    }

    $(document).on('click', '.qc-dsl-button', function(){
        $('.qc-dsl-button').find('.l-side').spin(false);
        $(this).find('.l-side').spin('<?php echo $dsl_size; ?>', '#fff');
        
        $('.qc-dsl-button').find('.qc-dsl-icon').removeClass('qc-dsl-hide-icon');
        $(this).find('.qc-dsl-icon').addClass('qc-dsl-hide-icon');
      })
  })
  //--></script>

<?php }else{ ?>
<?php $col = ($count) ? 'col-md-' . (12/3) : ''; ?>
<div id="login_wrap" class="row" >
  <div id="option_login" class=" <?php echo $col; ?> <?php echo (!$data['option']['login']['display'])? 'qc-hide' :''; ?>">
    <div class="panel panel-default ">
      <div class="panel-heading">
        <span class="wrap">
          <span class="fa fa-fw qc-icon-key"></span>
        </span>
        <span class="text"><?php echo $text_returning_customer; ?></span>
      </div>
      <div class="panel-body">
        <div class="form-horizontal">
          <div class="form-group email">
            <label class="col-xs-3 control-label" for="login_email"><?php echo $entry_email; ?></label>
            <div class="col-xs-9">
              <input type="text" name="email" value="" id="login_email" placeholder="<?php echo $entry_email; ?>" class="form-control"/>
            </div>
          </div>
          <div class="form-group password">
            <label class="col-xs-3 control-label" for="login_password"><?php echo $entry_password; ?></label>
            <div class="col-xs-9">
              <input type="password" name="password" value="" id="login_password" placeholder="<?php echo $entry_password; ?>" class="form-control"/>
            </div>
          </div>
          <div class="form-group">
            <div class="col-xs-offset-3 col-xs-9">
              <input type="button" value="<?php echo $button_login; ?>" id="button_login" class="button btn btn-primary" />
              <a id="remeber_password" href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
            </div>
          </div>
          
        </div>
        <?php if (isset($providers)) { ?>

          <style>
          #quickcheckout #d_social_login{
          padding-top: 20px;
          clear: both;
          }
          <?php foreach($providers as $provider){ ?>
            #quickcheckout #dsl_<?php echo $provider['id']; ?>_button{
              background:  <?php echo $provider['background_color']; ?>
            }
            #quickcheckout #dsl_<?php echo $provider['id']; ?>_button:active{
              background: <?php echo $provider['background_color_active']; ?>;
            }
          <?php } ?>
          </style>
          <div id="d_social_login">
            <span class="qc-dsl-label qc-dsl-label-<?php echo $dsl_size; ?>"><?php echo $button_sign_in; ?></span>
            <?php foreach($providers as $provider){ ?><?php if ($provider['enabled']) { ?><a id="dsl_<?php echo $provider['id']; ?>_button" class="qc-dsl-button qc-dsl-button-<?php echo $dsl_size; ?>" href="index.php?route=module/d_social_login/provider_login&provider=<?php echo $provider['id']; ?>"><span class="l-side"><span class="dsl-icon-<?php echo $provider['id']; ?> qc-dsl-icon"></span></span><span class="r-side"><?php echo $provider['heading']; ?></span></a><?php }  ?><?php } ?>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <div id="option_register" class="<?php echo $col; ?> <?php if ($account == 'register') { ?> selected <?php } ?> <?php if(!$data['option']['register']['display']){ echo 'qc-hide'; } ?>">
    <div class="panel panel-default ">
      <div class="panel-heading">
        <span class="wrap">
          <span class="fa fa-fw qc-icon-profile-add"></span>
        </span> 
        <span class="text"><?php echo $text_new_customer; ?></span>
      </div>
      <div class="panel-body">
        <div class="block-row register">
          <div class="radio">
            <label for="register">
              <input type="radio" name="account" value="register" id="register" <?php echo ($account == 'register') ? 'checked="checked"' : ''; ?> class="styled" data-refresh="1"  autocomplete='off' />
              <?php echo $data['option']['register']['title']; ?>
            </label>
          </div>
        </div>
        <div class="block-row text"><?php echo $data['option']['register']['description']; ?></div>
      </div>
    </div>
  </div>
  <?php if ($guest_checkout) { ?>
  <div id="option_guest" class="<?php echo $col; ?> <?php if ($account == 'guest') { ?> selected <?php } ?> <?php if(!$data['option']['guest']['display']){ echo 'qc-hide'; } ?>" >
    <div class="panel panel-default ">
      <div class="panel-heading">
        <span class="wrap">
          <span class="fa fa-fw qc-icon-profile-guest"></span>
        </span> 
        <span class="text"><?php echo $text_guest; ?></span></div>
      <div class="panel-body">
        <div class="block-row guest">
          <div class="radio">
            <label for="guest">
              <input type="radio" name="account" value="guest" id="guest" <?php echo ($account == 'guest') ? 'checked="checked"' : ''; ?> class="styled" data-refresh="1"  autocomplete='off'/>
              <?php echo $data['option']['guest']['title']; ?>
            </label>
          </div>
        </div>
        <div class="block-row text"><?php echo $data['option']['guest']['description']; ?></div>
      </div>
    </div>
  </div>
  <?php } ?>
</div>

<script><!--
$(function(){
  if($.isFunction($.fn.uniform)){
    $(" .styled, input:radio.styled").uniform().removeClass('styled');
  }
});
$(document).ready(function(){      
    setHeight('#step_1 .box-content'); 

    $('.qc-dsl-button').on('click', function(){
      alert()
      $('.qc-dsl-button').find('.l-side').spin(false);
      $(this).find('.l-side').spin('<?php echo $dsl_size; ?>', '#fff');
      
      $('.qc-dsl-button').find('.qc-dsl-icon').removeClass('qc-dsl-hide-icon');
      $(this).find('.qc-dsl-icon').addClass('qc-dsl-hide-icon');
    })
})
var maxHeight = 0;
function setHeight(column) {
  column = $(column);
  column.each(function() {       
    if($(this).height() > maxHeight) {
      maxHeight = $(this).outerHeight();
    }
  $(column).css('height', maxHeight+'px')
  });

}

//--></script>
<?php } //if login_style ?>