var DvDialogify = function() {
    this.modalActive = null;
    this.beforeHideEvent = new Event("before_hide");
    this.afterHideEvent = new Event("after_hide");
    this.beforeShowEvent = new Event("before_show");
    this.afterShowEvent = new Event("after_show");
    this.selector = null;
    this.initModal = function(selector, options = undefined) {
        that = this;
        this.selector = selector;

        if (document.querySelector(selector + ' .dvdy-close')) {
            document.querySelector(selector + ' .dvdy-close').removeEventListener("click", function() {
                if (document.querySelector(selector + " .dvdy-modal-backdrop")) {
                    document.querySelector(selector + " .dvdy-modal-backdrop").style.display = "none";
                }
                document.querySelector("body").style.removeProperty("overflow");
                document.querySelector(selector + " .dvdy-modal-dialog").classList.remove("dvdy-opened");
            });
            document.querySelector(selector + ' .dvdy-close').addEventListener("click", function() {
                if (document.querySelector(selector + " .dvdy-modal-backdrop")) {
                    document.querySelector(selector + " .dvdy-modal-backdrop").style.display = "none";
                }
                document.querySelector("body").style.removeProperty("overflow");
                document.querySelector(selector + " .dvdy-modal-dialog").classList.remove("dvdy-opened");
            });
        }
        
        document.querySelector(selector + " .dvdy-modal-dialog").removeEventListener("mouseup", function(e) {
            if (e.target === document.querySelector(selector + " .dvdy-modal-dialog")) {
                document.querySelector(selector + " .dvdy-modal-dialog").classList.add("--scale");
                setTimeout(function() {
                    document.querySelector(selector + " .dvdy-modal-dialog").classList.remove("--scale");
                }, 40);
            }
        });
        document.querySelector(selector + " .dvdy-modal-dialog").addEventListener("mouseup", function(e) {
            if (e.target === document.querySelector(selector + " .dvdy-modal-dialog")) {
                document.querySelector(selector + " .dvdy-modal-dialog").classList.add("--scale");
                setTimeout(function() {
                    document.querySelector(selector + " .dvdy-modal-dialog").classList.remove("--scale");
                }, 40);
            }
        });
        this.modalActive = document.querySelector(selector + " .dvdy-modal-dialog").classList.contains("dvdy-opened");
        if (document.querySelector(selector + " .dvdy-modal-backdrop")) {
            document.querySelector(selector + " .dvdy-modal-backdrop").style.display = (this.modalActive ? "block" : "none");
        }
        if (this.modalActive) {
            document.querySelector("body").style.overflow = "hidden";
        } else {
            document.querySelector("body").style.removeProperty("overflow");
        }

        if (options?.beforeHide) {
            for (ev of options.beforeHide) {
                document.querySelector(selector + " .dvdy-modal-dialog").addEventListener("before_hide", ev);
            }
        }
        if (options?.afterHide) {
            for (ev of options.afterHide) {
                document.querySelector(selector + " .dvdy-modal-dialog").addEventListener("after_hide", ev);
            }
        }
        if (options?.beforeShow) {
            for (ev of options.beforeShow) {
                document.querySelector(selector + " .dvdy-modal-dialog").addEventListener("before_show", ev);
            }
        }
        if (options?.afterShow) {
            for (ev of options.afterShow) {
                document.querySelector(selector + " .dvdy-modal-dialog").addEventListener("after_show", ev);
            }
        }
        return this;
    };
    this.show = function() {
        document.querySelector(this.selector + " .dvdy-modal-dialog").dispatchEvent(this.beforeShowEvent);
        this.modalActive = true;
        document.querySelector(this.selector + " .dvdy-modal-dialog").classList.add("dvdy-opened");
        document.querySelector("body").style.overflow = "hidden";
        if (document.querySelector(this.selector + " .dvdy-modal-backdrop")) {
            document.querySelector(this.selector + " .dvdy-modal-backdrop").style.display = "block";
        }
        document.querySelector(this.selector + " .dvdy-modal-dialog").dispatchEvent(this.afterShowEvent);
    };
    this.hide = function() {
        document.querySelector(this.selector + " .dvdy-modal-dialog").dispatchEvent(this.beforeHideEvent);
        this.modalActive = false;
        document.querySelector(this.selector + " .dvdy-modal-dialog").classList.remove("dvdy-opened");
        document.querySelector("body").style.removeProperty("overflow");
        if (document.querySelector(this.selector + " .dvdy-modal-backdrop")) {
            document.querySelector(this.selector + " .dvdy-modal-backdrop").style.display = "none";
        }
        document.querySelector(this.selector + " .dvdy-modal-dialog").dispatchEvent(this.afterHideEvent);
    };
    this.toggle = function() {
        this.modalActive = !this.modalActive;
        if (this.modalActive) {
            this.show();
        } else {
            this.hide();
        }
    };

    return this;
};