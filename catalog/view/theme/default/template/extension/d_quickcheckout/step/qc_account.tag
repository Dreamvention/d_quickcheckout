<qc_account>
    <div class="step">

        <qc_account_setting if={riot.util.tags.selectTags().search('"qc_account_setting"') && getState().edit} step="{opts.step}"></qc_account_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_account_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <div if={ (getState().session.account != 'logged') && (getConfig().account.display == 1) } >
            <div class="panel panel-default" if={ getState().config.guest.account.option.guest.display == 1 || getState().config.guest.account.option.register.display == 1 || (getState().config.guest.account.login_popup == 1 && getState().config.guest.account.option.login.display == 1 )}>
                <div class="btn-group btn-group-justified" data-toggle="buttons">
                  <label if={getState().config.guest.account.option.guest.display == 1} class="btn btn-primary { getAccount() == 'guest' ?  'active' : '' }" onclick={changeAccount}>
                    <input type="radio" name="account" value="guest" id="guest" autocomplete="off" checked={ getAccount() == 'guest' }> {getLanguage().account.entry_guest}
                  </label>
                  <label if={getState().config.guest.account.option.register.display == 1} class="btn btn-primary { getAccount() == 'register' ?  'active' : '' }" onclick={changeAccount}>
                    <input type="radio" name="account" value="register" id="register" autocomplete="off" checked={ getAccount() == 'register' }> {getLanguage().account.entry_register}
                  </label>
                  <a class="btn btn-primary" onclick={openLoginPopup} if={getState().config.guest.account.login_popup == 1 && getState().config.guest.account.option.login.display == 1 }> {getLanguage().account.entry_login}
                  </a>
                </div>
            </div>

            <div class="panel panel-default" if={getState().config.guest.account.login_popup != 1 && getState().config.guest.account.option.login.display == 1 }>
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <span class="icon">
                            <i class="{ getConfig().account.icon }"></i>
                        </span>
                        { getLanguage().account.heading_title }
                    </h4>
                    <h5 if={getLanguage().account.text_description}>{  getLanguage().account.text_description } </h5>
                </div>
                <div class="panel-body">
                    <div if={getError() && getError().account && getError().account.login } class="alert alert-danger">
                        {getError().account.login}
                    </div>
                    <div if={getState().config.guest.account.social_login.display == 1 && getState().config.guest.account.social_login.value} class="row form-group clearfix qc-social-login">
                        <div class="col-md-12">
                            <qc_raw content="{getState().config.guest.account.social_login.value}"></qc_raw>
                        </div>
                    </div>
                    <form class="row">
                        <div class="form-group clearfix">
                            <label class="col-md-5">{getLanguage().account.entry_email}</label>
                            <div class="col-md-7">
                                <input ref="email" type="text" autocomplete="email" class="form-control" name="email" value="">
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="col-md-5">{getLanguage().account.entry_password}</label>
                            <div class="col-md-7">
                                <input ref="password" type="password" autocomplete="current-password" class="form-control" name="password" value="">
                            </div>
                        </div>
                        
                        <div class="form-group clearfix">
                            <div class="col-md-7 col-md-offset-5">
                                <button class="btn btn-primary" onclick={login}>{getLanguage().account.button_login}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="login_popup"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <form >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">
                                    <span class="icon">
                                        <i class="{ getConfig().account.icon }"></i>
                                    </span>
                                    {getLanguage().account.entry_login}
                                </h4>
                                <h5 if={getLanguage().account.text_description}>{  getLanguage().account.text_description } </h5>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div if={getError() && getError().account && getError().account.login } class="alert alert-danger">
                                        {getError().account.login}
                                    </div>

                                    <div if={getState().config.guest.account.social_login.display == 1 && getState().config.guest.account.social_login.value} class="form-group clearfix qc-social-login">
                                        <div class="col-md-12">
                                            <qc_raw content="{getState().config.guest.account.social_login.value}"></qc_raw>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <label class="col-md-5">{getLanguage().account.entry_email}</label>
                                        <div class="col-md-7">
                                            <input ref="email" type="text" autocomplete="email" class="form-control" name="email" value="">
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label class="col-md-5">{getLanguage().account.entry_password}</label>
                                        <div class="col-md-7">
                                            <input ref="password" type="password" autocomplete="current-password" class="form-control" name="password" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary btn-block" onclick={login}>{getLanguage().account.button_login}</button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

        </div>

        <!-- Hidden Step -->
        <div show={(getConfig().account.display != 1 || getState().session.account == 'logged') && getState().edit}>
            <div class="panel panel-default" style="opacity: 0.5">
                <div class="panel-heading">{ getLanguage().account.heading_title } <div class="pull-right"><span class="label label-warning">{getLanguage().general.text_hidden}<span></div></div>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});

        var tag = this;

        login(e){
            this.store.dispatch('account/update', $(e.currentTarget).parents('form').serializeJSON());
            e.preventDefault();
        }

        changeAccount(e){
            this.store.dispatch('account/update', { account: $(e.currentTarget).find('input').val()});
        }

        openLoginPopup(e){
            $('#login_popup').modal('toggle');
        }

        this.store.subscribe('account/updated', function(data) {
            if(data.session.account == 'logged'){
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open')
            }
        });

    </script>
</qc_account>
