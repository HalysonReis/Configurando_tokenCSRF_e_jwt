document.getElementById('logar').addEventListener('submit', async (e) => {
    e.preventDefault()

    let form = document.getElementById('logar')

    const formData = new FormData(form)

    let dados = await fetch('../../acoes/fazer_login.php', {
        method: 'POST',
        body: formData
    })

    let response = await dados.json()
    if('location' in response){
        window.location.replace(response.location)
    }else{
        alert(response.msg)
    }
})