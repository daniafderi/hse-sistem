document.addEventListener('alpine:init', () => {
    Alpine.store('toast', {
        visible: false,
        show() {
            this.visible = true;
            setTimeout(() => this.visible = false, 3000);
        }
    });
});