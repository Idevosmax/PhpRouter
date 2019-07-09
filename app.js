let req = new Request("http://localhost:8080/home/?name=stanley",{
    'method' : "GET",
  
});

fetch(req)
.then(req =>{
    return req.text()
})
.then(text => {
    console.log(text)
})

