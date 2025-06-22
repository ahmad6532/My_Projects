const loginpage = document.querySelector(".sub");
const changepage = document.querySelector(".sub1");



// check validation for login

const username_fun=()=>{
    const uuname=document.querySelector("#uname");
    if(uuname.value !="")
    {
    document.querySelector("#userspan").style.visibility = "hidden";
    uuname.style.boxShadow = "none"
    }
 }
 
const password_fun=()=>{
   const ppss= document.querySelector("#pass");
    if(ppss.value !="")
    {
    ppss.style.boxShadow="none";
    document.querySelector("#passwordspan").style.visibility="hidden";
    }
 }


// Submit button functionalities

const submit = document.querySelector("#submit");
submit.addEventListener("click", () => {
    const username = document.querySelector("#uname");
const password = document.querySelector("#pass");
const uservalid = document.querySelector("#userspan");
const passvalid = document.querySelector("#passwordspan");
const uvalue=username.value;
const passvalue=password.value;
   
    if ( uvalue== "") {
        uservalid.style.visibility = "visible";
        username.style.boxShadow = " inset 0px 0px 2px 1px red"
    }
    else if (passvalue == "") {
        passvalid.style.visibility = "visible";
        password.style.boxShadow = " inset 0px 0px 2px 1px red";
    }
    else{
        
        const formdata= {
            'username':uvalue,
            'password':passvalue,
        };
       const jsondata= JSON.stringify(formdata);
       const url="php/login_backend.php";
       fetch(url,{
        method:'post',
        body:jsondata,
        headers:{
            'Content-type':'application/json'
        }
       }).then((response)=>response.json())
       .then((result)=>{
        if(result.message=='success')
        {
          window.location.href="dashboard.php";
        }
        else{
            document.querySelector(".fail-message").style.visibility="visible";
        }
       })
    }

})



//move login page to change password page
const change = document.querySelector("#change");
change.addEventListener("click", () => {
    loginpage.style.display = "none";
    changepage.style.display = "block";
    document.querySelector(".fail-message").style.visibility="hidden";

})
//To move change password page to login page
const backbtn=document.querySelector("#login");
backbtn.addEventListener("click", ()=>{
    loginpage.style.display = "block";
    changepage.style.display = "none";
    document.querySelector(".fail-message").style.visibility="hidden";

})

// Change password button functionalities
const changebutton = document.querySelector("#changepassword");
changebutton.addEventListener("click", () => {
    const oldpass = document.querySelector("#oldpass");
    const newpass = document.querySelector("#newpass");
    const conpass = document.querySelector("#conpass");

    if (oldpass.value == "") {
        document.querySelector("#oldspan").style.visibility = "visible";
        oldpass.style.boxShadow = "inset 0px 0px 2px 1px red";
    } else if (newpass.value == "") {
        document.querySelector("#newspan").style.visibility = "visible";
        newpass.style.boxShadow = "inset 0px 0px 2px 1px red";
    } else if (conpass.value == "") {
        document.querySelector("#conspan").style.visibility = "visible";
        conpass.style.boxShadow = "inset 0px 0px 2px 1px red";
    }
})

