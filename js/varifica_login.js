document.addEventListener('DOMContentLoaded', async () => {
    let response = await fetch('../../acoes/valida_adm.php');
    let res = await response.text()
    console.log(res)
})