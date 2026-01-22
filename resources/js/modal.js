function showNewLeverancierForm() {
    const modal = document.getElementById("NewLeverancierFormModal");

    if (modal.classList.contains("hidden")) {
        modal.classList.remove("hidden");
    } else {
        modal.classList.add("hidden");
    }
}

function showNewLeveringForm() {
    const modal = document.getElementById("NewLeveringFormModal");

    if (modal.classList.contains("hidden")) {
        modal.classList.remove("hidden");
    } else {
        modal.classList.add("hidden");
    }
}
