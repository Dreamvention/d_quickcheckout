<qc_switcher>
    <label class="ve-switch">
        <input name={opts.name} type="hidden"  value="0">
        <input name={opts.name} type="checkbox" class="ve-input" ref="switcher" data-label-text={opts.data-label-text} value="1" checked={(this.opts.riotValue == 1)}>
        <i></i>
    </label>
    <style>
    .ve-switch {
  position: relative;
  display: inline-block;
  padding-left: 55px;
  padding-right: 5px;
  cursor: pointer;
  height: 22px; }
  .ve-switch [type=checkbox] {
    position: absolute;
    opacity: 0; }
  .ve-switch i:before {
    position: absolute;
    content: "";
    height: 34px;
    width: 60px;
    top: 0px;
    left: 0px;
    background-color: rgba(0, 0, 0, 0.15);
    border-radius: 6px;
    transition: background-color 0.5s ease; }
  .ve-switch i:after {
    position: absolute;
    content: "";
    height: 30px;
    width: 30px;
    top: 2px;
    left: 2px;
    background-color: white;
    transition: .4s;
    border-radius: 4px; }
  .ve-switch [type=checkbox]:checked + i:before {
    background-color: #1f90bb; }
  .ve-switch [type=checkbox]:checked + i:after {
    -webkit-transform: translateX(26px);
            transform: translateX(26px); }
            </style>
</qc_switcher>