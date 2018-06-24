<raw>
  <span></span>
  <script>
  this.root.innerHTML = this.store.raw(opts.content);

  this.on('updated', function(){
    this.root.innerHTML = this.store.raw(opts.content);
  })

  </script>
</raw>