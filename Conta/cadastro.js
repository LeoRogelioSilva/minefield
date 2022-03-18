
"user strict";

function validar()
{
    
    if(document.forms["fCadastro"]["nome"].value==="")
    {
        alert("nome vazio")
        return false;
    }
    if(document.forms["fCadastro"]["email"].value==="")
    {
        alert("email vazio")
        return false;
    }
    if(document.forms["fCadastro"]["username"].value==="")
    {
        alert("nome de usuário vazio")
        return false;
    }
    if(document.forms["fCadastro"]["password"].value=="")
    {
        alert("senha vazia")
        return false;
    } 
    var senha = document.forms["fCadastro"]["password"].value;
    if((''+senha).length<8)
    {
        alert("a senha deve ter no mínimo 8 caracteres")
        return false;
    }
    if(document.forms["fCadastro"]["data"].value==="")
    {
        alert("data vazia")
        return false;
    }
    if(document.forms["fCadastro"]["cpf"].value==="")
    {
        alert("cpf vazio")
        return false;
    }

    return true;
}