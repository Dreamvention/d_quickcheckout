<shipping_method>
    <div class="step">

        <shipping_method_setting if={riot.util.tags.selectTags().search('"shipping_method_setting"') && getState().edit} step="{opts.step}"></shipping_method_setting>

        <pro_label if={ riot.util.tags.selectTags().search('"shipping_method_setting"') < 0 && getState().edit}></pro_label>

        <!-- Step -->
        <div class="panel panel-default" show={ getConfig().shipping_method.display == 1 }>
            <div class="panel-heading">
                <h4 class="panel-title">
                    <span wclass="icon">
                        <i class="{ getConfig().shipping_method.icon }"></i>
                    </span>
                    <span class="text">{ getLanguage().shipping_method.heading_title }</span>
                </h4>
                <h5 if={getLanguage().shipping_method.text_description}>{  getLanguage().shipping_method.text_description } </h5>
            </div>
            <div class="panel-body">
                <form id="shipping_method_list" if={getConfig().shipping_method.display_options == 1}>
                    <div if={ getConfig().shipping_method.input_style == 'radio'} 
                    each={ shipping_method, name in getSession().shipping_methods } 
                    class="radio-input radio" >
                        <div if={shipping_method}>
                            <strong if={getConfig().shipping_method.display_group_title == 1} class="title">{ shipping_method.title }</strong>
                            <div each={ quote, index in shipping_method.quote }>
                                <label  for="{ quote.code }">
                                    <input
                                    type="radio"
                                    name="shipping_method"
                                    value="{ quote.code }"
                                    id="{ quote.code }"
                                    checked={ getSession().shipping_method.code == quote.code }
                                    onclick={change}/>
                                    <span class="text">{ quote.title }</span> <span class="price">{ quote.text }</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div if={getConfig().shipping_method.input_style == 'select'}>

                            <select if={getConfig().shipping_method.display_group_title == 1} class="form-control" onchange={change}>
                                <optgroup label="{ shipping_method.title }" 
                                each={ shipping_method, name in getSession().shipping_methods } >
                                    <option 
                                        each={ quote, index in shipping_method.quote } 
                                        selected={ getSession().shipping_method.code == quote.code }
                                        value="{ quote.code }">
                                        <span class="text">{ quote.title }</span> <span class="price">{ quote.text }</span>
                                    </option>
                                </optgroup>
                            </select>

                            <select if={getConfig().shipping_method.display_group_title == 0} class="form-control" onchange={change}>
                                <option 
                                    each={ quote, index in flattenShippingMethods() } 
                                    selected={ getSession().shipping_method.code == quote.code }
                                    value="{ quote.code }">
                                    <span class="text">{ quote.title }</span> <span class="price">{ quote.text }</span>
                                </option>
                                </div>
                            </select>

                        </div>
                </form>
            </div>
        </div>

        <div show={(getConfig().shipping_method.display != 1 && getState().edit)}>
            <div class="panel panel-default" style="opacity: 0.5">
                <div class="panel-heading">{ getLanguage().shipping_method.heading_title } <div class="pull-right"><span class="label label-warning">{getLanguage().general.text_hidden}<span></div></div>
            </div>
        </div>
    </div>
    <script>

        this.setting_id = 'shipping_method_setting';

        var tag = this;
        
        change(e){
            this.store.dispatch('shipping_method/update', $(e.currentTarget).parents('form').serializeJSON());
        }

        flattenShippingMethods(){
            var result = {};
            for (var name in  this.store.getSession().shipping_methods) {
                var shipping_method = this.store.getSession().shipping_methods[name];
                for (var index in shipping_method.quote){
                    var quote = shipping_method.quote[index];
                    result[name+index] = quote ;
                }
            }
            return result;
        }
    </script>
</shipping_method>
