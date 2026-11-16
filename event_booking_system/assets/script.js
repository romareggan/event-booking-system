document.addEventListener("DOMContentLoaded", () => {
    // Confirmation dialog before sensitive actions (e.g. canceling a booking)
    document.querySelectorAll("[data-confirm]").forEach(link => {
        link.addEventListener("click", event => {
            if (!confirm(link.dataset.confirm || "Are you sure?")) {
                event.preventDefault();
            }
        });
    });

    // Form validation check
    document.querySelectorAll(".validate-form").forEach(form => {
        form.addEventListener("submit", event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
            }
        });
    });

    // Dynamic ticket price calculator on booking page
    const count = document.getElementById("ticket-count");
    const total = document.getElementById("booking-total");
    if (count && total) {
        const price = parseFloat(count.dataset.price || "0");
        const updateTotal = () => {
            const amount = Math.max(0, parseInt(count.value || "0", 10)) * price;
            total.textContent = "KSh " + amount.toLocaleString("en-KE", { minimumFractionDigits: 2 });
        };
        count.addEventListener("input", updateTotal);
    }
});
