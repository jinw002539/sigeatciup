function fecharModal() {
    const modal = document.getElementById('modalErro');
    if (modal) modal.style.display = 'none';
    window.history.replaceState({}, document.title, window.location.pathname);
}

window.addEventListener('click', function (e) {
    const modal = document.getElementById('modalErro');
    if (e.target === modal) fecharModal();
});
