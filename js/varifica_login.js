document.addEventListener('DOMContentLoaded', async () => {
    let response = await fetch('../../acoes/valida_adm.php');
    if(!response.ok){
        document.location.replace('http://localhost/Configurando_tokenCSRF_e_jwt/app/views/login.php')
    }
    
})