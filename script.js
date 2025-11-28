// Confirmação do usuario
 /* @param {Event} event - O objeto Event do click do mouse.
 */

function confirmarExclusao(event) {
    const confirmacao = confirm("Tem certeza de que deseja excluir este produto? Esta ação é irreversível.");
    
    if (!confirmacao) {
        event.preventDefault();
        return false;
    }
    return true;
}