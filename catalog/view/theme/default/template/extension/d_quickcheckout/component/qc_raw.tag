<qc_raw>
    <div></div>
    <script>
        this.mixin({store:d_quickcheckout_store})
        this.set = function(){
            $(this.root).html(opts.content)
        }
        this.on('update', this.set)
        this.on('mount', this.set)
    </script>
</qc_raw>