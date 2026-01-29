@vite(['resources/js/components/toast.js']);

<script>
function dropdownMenu() {
    return {
        open: false,
        left: 0,
        top: 0,

        toggle(event) {
            const btn = event.currentTarget;
            const rect = btn.getBoundingClientRect();

            this.left = rect.left - 175;
            this.top = rect.bottom + window.scrollY + 5;
            this.open = !this.open;
        },

        close() {
            this.open = false;
        }
    }
}
</script>
