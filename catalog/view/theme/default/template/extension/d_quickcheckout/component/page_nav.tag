<page_nav>
    <div if={riot.util.tags.selectTags().search('"page_nav_setting"') > 0 && getState().edit}>
        <page_nav_setting  setting_id="page_nav_setting_{opts.page_id}" page_id="{opts.page_id}" page="{opts.page}">
            <label class="btn btn-default { (opts.page.display == 1) ? 'active' : ''}" for="payment_address_display" onclick="{parent.editCheckbox}">
                <input name="layout[pages][{opts.page_id}][display]" type="hidden" value="0">
                <input name="layout[pages][{opts.page_id}][display]" id="layout_pages_display_{opts.page_id}" type="checkbox" value="1" checked={ opts.page.display == 1 }>
                <i class="fa fa-eye"></i>
            </label>
        </page_nav_setting>

        <setting if={getState().edit} setting_id="page_nav_setting_{opts.page_id}" page_id="{opts.page_id}" page="{opts.page}" ref="setting" title="Page nav" >
            <div class="form-group">
                <label class="control-label">{getLanguage().general.text_display}</label>
                <div>
                    <switcher onclick="{parent.edit}" name="layout[pages][{opts.page_id}][display]" data-label-text="Enabled" value={opts.page.display} />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label">{getLanguage().general.text_title}</label>
                <div>
                    <input onchange="{parent.edit}" type="text" class="form-control" name="layout[pages][{opts.page_id}][text]" value={ opts.page.text } />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label">{getLanguage().general.text_description}</label>
                <div>
                    <input onchange="{parent.edit}" type="text" class="form-control" name="layout[pages][{opts.page_id}][description]" value={ opts.page.description } />
                </div>
            </div>
        </setting>
    </div>

    <pro_label if={ riot.util.tags.selectTags().search('"page_nav_setting"') < 0 && getState().edit}></pro_label>


    <a class="process-page-edit {(opts.page.display == '1') ? '' : 'process-page-hidden'}" onclick={pageOpen} if={getState().edit} >
        <div class="text">{opts.page.text}</div>
        <div class="description">{opts.page.description}</div>
        <span class="label label-warning {(opts.page.display == '1') ? 'hidden' : ''}">{getLanguage().general.text_hidden}<span></div>
    </a>

    <div if={ !getState().edit }>
        <div class="process-page-label">{opts.page.text}</div>
        <div class="process-page-progress">
            <div class="process-page-progress-bar"></div>
        </div>
        <a onclick={(opts.status == '2') ? pageOpen : ''} class="process-page-dot"></a>
        <div class="process-page-description">{opts.page.description}</div>
    </div>

    <script>
        this.setting_id = 'page_nav_setting_'+ opts.page_id;

        var tag = this;

        pageOpen(e){
            this.store.dispatch('page/open', { page_id: this.opts.page_id, sort_order: this.opts.sort_order });
        }

        edit(e){
            this.store.dispatch('setting/edit', $('#'+ tag.setting_id).find('form').serializeJSON());
        }

        editCheckbox(e){
            var checkbox = $(e.currentTarget).find('input[type=checkbox]');
            checkbox.prop("checked", !checkbox.prop("checked"));
            tag.store.dispatch('setting/edit', $(tag.root).find('.page-nav-setting').serializeJSON());

        }

    </script>
</page_nav>