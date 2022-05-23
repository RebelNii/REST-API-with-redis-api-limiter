//DOM elements

regBtn = document.querySelector(".reg");
getBtn = document.querySelector(".get");
box = document.querySelector(".pen");
show = document.querySelector(".show")
email = document.querySelector("#email")
submit = document.querySelector(".submit")

//DOM manipulation
box.style.display = 'none';
show.style.display = 'none';


//events
regBtn.addEventListener("click", () => {
    box.style.display = 'block';
    show.style.display = 'none';
});


getBtn.addEventListener("click", () => {
    box.style.display = 'none'
    show.style.display = 'block'
})

submit.addEventListener("click", (e)=>{
    e.preventDefault()

    fetch('http://localhost:80/phpscripts/api1/backend.php', {
        method:'POST',
        mode:'cors',
        body: new FormData(document.querySelector(".input"))
    }).then(res=>res.json()
    ).then(data=>{
        //console.log(data)
        if(data.status == 'success'){
            box.innerHTML = `${data.status}  <br> ${data.api}`;
            sessionStorage.setItem('api',data.api)
        }
    }
    ).catch(err=>console.log(err))

})

getBtn.addEventListener("click", ()=>{
    fetch('http://localhost:80/phpscripts/api1/backend.php', {
        mode: 'cors',
        credentials: 'include',
        headers:{
            'x-api': sessionStorage.getItem('api')
        }
    }).then(res=>res.json()
    ).then(data=>{
        console.log(data)
        show.innerHTML = ''
        if(data.status){
            show.innerHTML = data.status
        }else{
            data.forEach(e=>{
                const p = document.createElement("p")
                p.innerHTML = `${e.email}`
                show.appendChild(p)
            })
        }
    }).catch(err=>console.log(err))
})