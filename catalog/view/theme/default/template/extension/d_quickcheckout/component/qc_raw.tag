<qc_raw>
    <span></span>
    <script>
        this.mixin({store:d_quickcheckout_store});
        this.root.innerHTML = this.store.raw(opts.content);

        this.on('updated', function(){
        this.root.innerHTML = this.store.raw(opts.content);
        })

    </script>
</qc_raw>