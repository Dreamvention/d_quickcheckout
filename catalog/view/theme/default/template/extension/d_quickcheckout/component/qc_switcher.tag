<qc_switcher>
    <div ref="switcher-wrap" >
        <input name={opts.name} type="hidden"  value="0">
        <input name={opts.name} type="checkbox" ref="switcher" data-label-text={opts.data-label-text} value="1" checked={(this.opts.riotValue == 1)}>
    </div>
    
    <script>
        this.mixin({store:d_quickcheckout_store});
        this.on('mount', function(){
            $(this.refs.switcher).bootstrapSwitch('state', this.opts.riotValue == 1);
            $(this.refs.switcher).on('switchChange.bootstrapSwitch', function(event, state) {
                
                //call only on manual change
                if($(this.refs.switcher).prop('checked') != (this.opts.riotValue == 1)){
                    if(this.opts.riotValue == 1){
                        $(this.refs.switcher).prop('checked', false);
                    }else{
                        $(this.refs.switcher).prop('checked', true);
                    }
                    this.opts.onclick();
                }
            }.bind(this));
        });

        this.on('updated', function(){
            //$(this.refs.switcher).bootstrapSwitch('state', this.opts.riotValue == 1);
        });
    </script>
</qc_switcher>