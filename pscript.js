const loginbt=document.getElementById('loginButton');
const logindiv=document.getElementById('bbox');


loginbt.addEventListener('click',function()
{
  
    if(logindiv.style.display =='none' || logindiv.style.display == '')
    {
        logindiv.style.display='block';
    }
})
document.addEventListener('keydown' ,function(event)
{
  
    if(event.key=='Enter')
    {
        
            logindiv.style.display='none';
        
    }
})


