<qc_account>
    <div class="step qc-account">

        <qc_account_setting if={riot.util.tags.selectTags().search('"qc_account_setting"') && getState().edit} step="{opts.step}"></qc_account_setting>

        <qc_pro_label if={ riot.util.tags.selectTags().search('"qc_account_setting"') < 0 && getState().edit}></qc_pro_label>

        <!-- Step -->
        <div if={ (getState().session.account != 'logged') && (getConfig().account.display == 1) } >
            <div class="ve-mb-4" if={ getState().config.guest.account.option.guest.display == 1 || getState().config.guest.account.option.register.display == 1 || (getState().config.guest.account.login_popup == 1 && getState().config.guest.account.option.login.display == 1 )}>
                <div class="ve-btn-group ve-btn-group--block" data-toggle="buttons">
                  <label if={getState().config.guest.account.option.guest.display == 1} class="ve-btn d-vis ve-btn--primary { getAccount() == 'guest' ?  'active' : '' }" onclick={changeAccount}>
                    <input type="radio" name="account" value="guest" id="guest" autocomplete="off" checked={ getAccount() == 'guest' }> {getLanguage().account.entry_guest}
                  </label>
                  <label if={getState().config.guest.account.option.register.display == 1} class="ve-btn d-vis ve-btn--primary { getAccount() == 'register' ?  'active' : '' }" onclick={changeAccount}>
                    <input type="radio" name="account" value="register" id="register" autocomplete="off" checked={ getAccount() == 'register' }> {getLanguage().account.entry_register}
                  </label>
                  <a class="ve-btn d-vis ve-btn--primary" onclick={openLoginPopup} if={getState().config.guest.account.login_popup == 1 && getState().config.guest.account.option.login.display == 1 }> {getLanguage().account.entry_login}
                  </a>
                </div>
            </div>
            
            <!-- style card -->
            <div class="ve-card" if={getState().config.guest.account.login_popup != 1 && getState().config.guest.account.option.login.display == 1 && getState().config.guest.account.style == 'card' }>
                <div class="ve-card__header">
                    <h4 class="ve-h4">
                        <span class="icon">
                            <i class="{ getConfig().account.icon }"></i>
                        </span>
                        { getLanguage().account.heading_title }
                    </h4>
                    <p class="ve-p" if={getLanguage().account.text_description}>{  getLanguage().account.text_description } </p>
                </div>
                <div class="ve-card__section">
                    <div if={getError() && getError().account && getError().account.login } class="alert alert-danger">
                        {getError().account.login}
                    </div>
                    <div if={getState().config.guest.account.social_login.display == 1 && getState().config.guest.account.social_login.value} class="qc-row form-group d-vis clearfix qc-social-login">
                        <div class="ve-col-md-12">
                            <qc_raw content="{getState().config.guest.account.social_login.value}"></qc_raw>
                        </div>
                    </div>
                    <form>
                        <div class="ve-field d-vis clearfix">
                            <div class="ve-row">
                                <label class="ve-col-md-5">{getLanguage().account.entry_email}</label>
                                <div class="ve-col-md-7">
                                    <input ref="email" type="text" autocomplete="email" class="ve-input" name="email" value="">
                                </div>
                            </div>
                        </div>
                        <div class="ve-field d-vis clearfix">
                            <div class="ve-row">
                                <label class="ve-col-md-5">{getLanguage().account.entry_password}</label>
                                <div class="ve-col-md-7">
                                    <input ref="password" type="password" autocomplete="current-password" class="ve-input" name="password" value="">
                                </div>
                            </div>
                        </div>

                        <div class="ve-field d-vis clearfix">
                            <div class="ve-row">
                                <div class="ve-col-md-7 ve-col-offset-5">
                                    <button class="ve-btn d-vis ve-btn--primary" onclick={login}>{getLanguage().account.button_login}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- style clear -->
            <div class="ve-mb-3 ve-clearfix" if={getState().config.guest.account.login_popup != 1 && getState().config.guest.account.option.login.display == 1 && getState().config.guest.account.style == 'clear' }>
                <p class="ve-p" if={getLanguage().account.text_description}>{  getLanguage().account.text_description } </p>
            
                <div if={getError() && getError().account && getError().account.login } class="alert alert-danger">
                    {getError().account.login}
                </div>
                <div if={getState().config.guest.account.social_login.display == 1 && getState().config.guest.account.social_login.value} class="qc-row form-group d-vis clearfix qc-social-login">
                    <div class="ve-col-md-12">
                        <qc_raw content="{getState().config.guest.account.social_login.value}"></qc_raw>
                    </div>
                </div>
                <form>
                    <div class="ve-field d-vis clearfix">
                        <div class="ve-row">
                            <label class="ve-col-md-5">{getLanguage().account.entry_email}</label>
                            <div class="ve-col-md-7">
                                <input ref="email" type="text" autocomplete="email" class="ve-input" name="email" value="">
                            </div>
                        </div>
                    </div>
                    <div class="ve-field d-vis clearfix">
                        <div class="ve-row">
                            <label class="ve-col-md-5">{getLanguage().account.entry_password}</label>
                            <div class="ve-col-md-7">
                                <input ref="password" type="password" autocomplete="current-password" class="ve-input" name="password" value="">
                            </div>
                        </div>
                    </div>

                    <div class="ve-field d-vis clearfix">
                        <div class="ve-row">
                            <div class="ve-col-md-7 ve-col-offset-5">
                                <button class="ve-btn d-vis ve-btn--primary" onclick={login}>{getLanguage().account.button_login}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal fade" id="login_popup"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="ve-card">
                        <form >
                            <div class="ve-card__header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="ve-h4">
                                    <span if={ getConfig().account.icon } class="icon">
                                        <i class="{ getConfig().account.icon }"></i>
                                    </span>
                                    {getLanguage().account.entry_login}
                                </h4>
                                <p class="ve-p" if={getLanguage().account.text_description}>{  getLanguage().account.text_description } </p>
                            </div>
                            <div class="ve-card__section">
                                <div if={getError() && getError().account && getError().account.login } class="ve-alert ve-alert--danger">
                                    {getError().account.login}
                                </div>
                            
                                <div if={getState().config.guest.account.social_login.display == 1 && getState().config.guest.account.social_login.value} class="form-group d-vis clearfix qc-social-login">
                                    <qc_raw content="{getState().config.guest.account.social_login.value}"></qc_raw>
                                </div>

                                <div class="ve-field d-vis clearfix">
                                    <label class="ve-label">{getLanguage().account.entry_email}</label>
                                    <input ref="email" type="text" autocomplete="email" class="ve-input" name="email" value="">
                                </div>
                                <div class="ve-field d-vis clearfix">
                                    <label class="ve-label">{getLanguage().account.entry_password}</label>
                                    <input ref="password" type="password" autocomplete="current-password" class="ve-input" name="password" value="">
                                </div>

                            </div>
                            <div class="ve-card__footer">
                                <button class="ve-btn d-vis ve-btn--primary ve-btn--block qc-btn" onclick={login}>{getLanguage().account.button_login}</button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

        </div>

        <!-- Hidden Step -->
        <div show={(getConfig().account.display != 1 || getState().session.account == 'logged') && getState().edit}>
            <div class="ve-card" style="opacity: 0.5">
                <div class="ve-card__header">{ getLanguage().account.heading_title } <div class="ve-pull-right"><span class="ve-badge ve-badge--warning">{getLanguage().general.text_hidden}<span></div></div>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:d_quickcheckout_store});

        var tag = this;

        login(e){
            this.store.dispatch('account/login', $(e.currentTarget).parents('form').serializeJSON());
            e.preventDefault();
        }

        changeAccount(e){
            this.store.dispatch('account/update', { account: $(e.currentTarget).find('input').val()});
        }

        openLoginPopup(e){
            $('#login_popup').modal('toggle');
        }

        this.on('mount', function(){
            $(this.root).find('#login_popup').appendTo('body');
        })

        this.store.subscribe('account/updated', function(data) {
            if(data.session.account == 'logged'){
                $('.modal-backdrop').remove();

                //bugfix: required to close the model window.
                $('#login_popup').modal('hide');
                $('body').removeClass('modal-open')
            }
        });

    </script>
</qc_account>
