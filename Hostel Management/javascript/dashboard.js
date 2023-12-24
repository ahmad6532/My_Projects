const dash = document.querySelector("#dashboard-btn");
const rom = document.querySelector("#room-btn");
const seet = document.querySelector("#seat-btn");
const std = document.querySelector("#student-btn");
const add_std = document.querySelector("#add-student-btn");
const dash_div = document.querySelector("#dash-div");
const room_div = document.querySelector(".room-main");
const avail_seat = document.querySelector(".available-seat");
const view_student = document.querySelector(".display-data");
const add_std_div = document.querySelector(".add-student-main");
const std_detail_div = document.querySelector(".detail_main");
const search = document.querySelector(".search-div");



// Navigation Starts here

const dashboard_data = () => {
    const url = "php/dashboard_backstd.php";
    fetch(url).then((response) => response.json()).then((result) => {
        if (result) {

            document.querySelector("#dash_tstd").innerText = result.total_std;
            document.querySelector("#dash_pstd").innerText = result.present;
            document.querySelector("#dash_lstd").innerText = result.left;


        }
        else {
            alert("No Data Available");
        }
    })
    const url2 = "php/dashboard_backrom.php";
    fetch(url2).then((response) => response.json()).then((result) => {
        if (result) {
            document.querySelector("#dash_tseat").innerText = result.trooms;
            document.querySelector("#dash_remseat").innerText = result.rrooms;
            document.querySelector("#dash_rseat").innerText = result.resrooms;
        }
        else {
            alert("No Data Available");
        }
    })
}

dashboard_data();


// dashboard navigation
dash.addEventListener("click", () => {
  dashboard_fun(); 
})
const dashboard_fun=()=>{
    dash_div.style.display = "block";
    add_std_div.style.display = "none";
    room_div.style.display = "none";
    view_student.style.display = "none";
    std_detail_div.style.display = "none";
    avail_seat.style.display = "none";
    search.style.visibility = "hidden";
    document.querySelector(".mobile_menue").style.visibility="hidden";

    dashboard_data();
}

// rooms navigation
rom.addEventListener("click", () => {
   rooms_fun();
})
const rooms_fun=()=>{
    dash_div.style.display = "none";
add_std_div.style.display = "none";
room_div.style.display = "block";
view_student.style.display = "none";
std_detail_div.style.display = "none";
avail_seat.style.display = "none";
search.style.visibility = "hidden";
document.querySelector(".mobile_menue").style.visibility="hidden";

const url = "php/rooms_backend.php";
fetch(url).then((response) => response.json()).then((result) => {
    if (result.message == "fail") {
        const room =
            `
        <div class="room-outer-div">            
       <div class="room-div">
          <div class="room-no">No Room Found</div>
          
       </div>
  </div>
        
        `;
        room_div.innerHTML = room;
    }
    else {
        const room =
            `
        <div class="room-outer-div">
        ${result.map((val) => `
        
       <div class="room-div">
          <div class="room-no">${val.room_no}</div>
          <div class="content-heading-div">
             <span>Seates</span>
             <span>${val.total_seats}</span>
          </div>
         <div class="content-heading-div">
             <span>Attach Bath</span>
             <span>${val.attach_bath}</span>
         </div>
         <div class="content-heading-div">
             <span>AC</span>
             <span>${val.ac}</span>
         </div>
       </div>
        
        `).join("\n")}
  </div>
        
        `;
        room_div.innerHTML = room;
    }
})
}


//available seats navigation
seet.addEventListener("click", () => {
avail_fun();

})
const avail_fun=()=>{
    dash_div.style.display = "none";
    add_std_div.style.display = "none";
    room_div.style.display = "none";
    view_student.style.display = "none";
    std_detail_div.style.display = "none";
    avail_seat.style.display = "block";
    search.style.visibility = "hidden";
    document.querySelector(".mobile_menue").style.visibility="hidden";

    const url = "php/rooms_backend.php";
    fetch(url).then((response) => response.json()).then((result) => {
        if (result.message == "fail") {
            const seat =
                `
            <div class="room-outer-div">            
           <div class="room-div">
              <div class="room-no">No Room Found</div>
              
           </div>
      </div>
            
            `;
            avail_seat.innerHTML = seat;
        }
        else {
            const seat =
                `
            <table>
            <thead>
                <tr>
                    <th>Room No</th>
                    <th class="total_seat_cls">Total Seats</th>
                    <th>Reserved Seats</th>
                    <th>Remaining Seats</th>
                    <th class="action_btn" >Actions</th>
                    
                </tr>
            </thead>
            <tbody>
            ${result.map((val) => `
            <tr>
            <td>${val.room_no}</td>
            <td class="total_seat_cls">${val.total_seats}</td>
            <td>${val.reserved_seats}</td>
            <td>${val.remaining_seats}</td>
            
           <td class="action_btn" >
           ${val.remaining_seats == 0 ?
                        `<span style="font-weight:800;color:red">Room Reserved</span>` :
                        `<span id="add_new_student" class="buttons" onclick=(add_std_fun())>Add Student</span>`}
   
           </td>
            
        </tr> 
            
            `).join("\n")}
           
                
            </tbody>
         </table>
            
            `;
            avail_seat.innerHTML = seat;
        }
    })



}

// add student navigation
add_std.addEventListener("click", () => {
   adstd_fun();

})
const adstd_fun=()=>{
    dash_div.style.display = "none";
    add_std_div.style.display = "block";
    room_div.style.display = "none";
    view_student.style.display = "none";
    std_detail_div.style.display = "none";
    avail_seat.style.display = "none";
    search.style.visibility = "hidden";
    document.querySelector(".mobile_menue").style.visibility="hidden";

}

// view student navigation
const view_reload = () => {
    const url = "php/view_std_backend.php";
    fetch(url).then((response) => response.json()).then((result) => {
        if (result.message == 'fail') {
            const tab = `
                
                <table>
                <tbody>
                    <tr>
                        <td>No Result Found</td>                        
                    </tr>
                </tbody>
             </table>
                `;
            document.querySelector(".display-data").innerHTML = tab;

        }
        else {

            const tab = `
                
                <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Room No</th>
                        <th colspan=3>Actions</th>
                        
                    </tr>
                </thead>
                <tbody>
                ${result.map((val) => `
                <tr>
                        <td>${val.student_id}</td>
                        <td>${val.name}</td>
                        <td>${val.room}</td>
                        <td><span id="edit_student" class="buttons" onclick="edit_student(${val.id})">Edit</span></td>
                        <td><span id="lev_student" class="buttons" onclick="leave_student(${val.id})">Leave</span></td>
                        <td><span id="View_detail" class="buttons" onclick="view_student_btn(${val.id})">View Detail</span></td>
                        
                    </tr> 
                
                `).join("\n")}
                    
                </tbody>
             </table>
                `;
            document.querySelector(".display-data").innerHTML = tab;
        }

    })
}
std.addEventListener("click", () => {
    view_std_fun();
})
const view_std_fun =()=>{
    dash_div.style.display = "none";
    add_std_div.style.display = "none";
    room_div.style.display = "none";
    view_student.style.display = "block";
    std_detail_div.style.display = "none";
    avail_seat.style.display = "none";
    search.style.visibility = "visible";
    document.querySelector(".mobile_menue").style.visibility="hidden";

    view_reload();

}
// search students
const search_record = () => {
    const search_data = document.querySelector("#search").value;
    const url = "php/search.php?search=" + search_data;
    fetch(url).then((response) => response.json()).then((result) => {
        if (result.message == 'fail') {
            const tab = `
        
        <table>
        <tbody>
            <tr>
                <td>No Result Found</td>                        
            </tr>
        </tbody>
     </table>
        `;
            document.querySelector(".display-data").innerHTML = tab;

        }
        else {

            const tab = `
        
        <table>
        <thead>
            <tr>
                <th class="std_view_cls">ID</th>
                <th>Name</th>
                <th>Room No</th>
                <th colspan=3>Actions</th>
                
            </tr>
        </thead>
        <tbody>
        ${result.map((val) => `
        <tr>
                <td class="std_view_cls">${val.student_id}</td>
                <td>${val.name}</td>
                <td>${val.room}</td>
                <td><span id="edit_student" class="buttons" onclick="edit_student(${val.id})">Edit</span></td>
                <td class="std_view_cls">${val.status == 'Present' ?
                    `<span id="lev_student" class="buttons" onclick="leave_student(${val.id})">Leave</span>` :
                    `Left`}
                     </td>
                <td><span id="View_detail" class="buttons" onclick="view_student_btn(${val.id})">View Detail</span></td>
                
            </tr> 
        
        `).join("\n")}
            
        </tbody>
     </table>
        `;
            document.querySelector(".display-data").innerHTML = tab;
        }


    })
}

const burger=document.querySelector(".burger");
burger.addEventListener("click",()=>{
    document.querySelector(".mobile_menue").style.visibility="visible";
})

const view_student_btn = (id) => {
    dash_div.style.display = "none";
    add_std_div.style.display = "none";
    room_div.style.display = "none";
    view_student.style.display = "none";
    std_detail_div.style.display = "block";
    avail_seat.style.display = "none";
    search.style.visibility = "hidden";
    const url = "php/view_detail_backend.php?id=" + id;
    fetch(url).then((response) => response.json()).then((result) => {
        if (result.message == 'fail') {
            alert("No Detail Available");
        }
        else {
            result.map((val) => {

                document.querySelector("#d_id").innerText = val.student_id;
                document.querySelector("#d_name").innerText = val.name;
                document.querySelector("#d_fname").innerText = val.father_name;
                document.querySelector("#d_cnic").innerText = val.cnic;
                document.querySelector("#d_phone").innerText = val.phone;
                document.querySelector("#d_room").innerText = val.room;
                document.querySelector("#d_status").innerText = val.status;
                document.querySelector("#d_insti").innerText = val.institute;
                document.querySelector("#d_address").innerText = val.address;
                document.querySelector("#d_date").innerText = val.date;

            })
        }
    })
}
const leave_student = (id) => {
    const url = "php/leave.php?id=" + id;
    fetch(url).then((response) => response.json()).then((result) => {
        if (result.message == "success") {
            dashboard_data();
            view_reload();
            alert("Student has been Lefted Succssfully...");
        }
        else {
            alert("Student not Left");
        }
    })
}






// Add Student Record
const sname = document.querySelector("#name");
const status = document.querySelector("#stat").value;
const fname = document.querySelector("#fname");
const cnic = document.querySelector("#cnic");
const phone = document.querySelector("#phone");
const iname = document.querySelector("#iname");
const regno = document.querySelector("#room_no");
const address = document.querySelector("#address");
const namespan = document.querySelector("#namespan");
const fnamespan = document.querySelector("#fnamespan");
const cnicspan = document.querySelector("#cnicspan");
const phonespan = document.querySelector("#phonespan");
const instspan = document.querySelector("#instspan");
const regspan = document.querySelector("#regspan");
const addresspan = document.querySelector("#addresspan");

const input_name = () => {
    namespan.style.visibility = "hidden";
    sname.style.border = "1px solid black";
}
const input_fname = () => {
    fnamespan.style.visibility = "hidden";
    fname.style.border = "1px solid black";
}
const input_cnic = () => {
    cnicspan.style.visibility = "hidden";
    cnic.style.border = "1px solid black";
}
const input_phone = () => {
    phonespan.style.visibility = "hidden";
    phone.style.border = "1px solid black";
}
const input_iname = () => {
    instspan.style.visibility = "hidden";
    iname.style.border = "1px solid black";
}
const input_regno = () => {
    regspan.style.visibility = "hidden";
    regno.style.border = "1px solid black";
}
const input_address = () => {
    addresspan.style.visibility = "hidden";
    address.style.border = "1px solid black";
}




const submit = document.querySelector("#submit");
submit.addEventListener("click", () => {
    const sname = document.querySelector("#name");
    const stat = document.querySelector("#stat").value;
    const fname = document.querySelector("#fname");
    const cnic = document.querySelector("#cnic");
    const phone = document.querySelector("#phone");
    const iname = document.querySelector("#iname");
    const regno = document.querySelector("#room_no");
    const address = document.querySelector("#address");
    const name_val = sname.value;
    const fname_val = fname.value;
    const cnic_val = cnic.value;
    const phone_val = phone.value;
    const iname_val = iname.value;
    const regno_val = regno.value;
    const address_val = address.value;


    if (name_val == "") {
        namespan.style.visibility = "visible";
        sname.style.border = "1px solid red";
    }
    else if (fname_val == "") {
        fnamespan.style.visibility = "visible";
        fname.style.border = "1px solid red";
    }
    else if (cnic_val == "") {
        cnic.style.border = "1px solid red";
        cnicspan.style.visibility = "visible";
    }
    else if (phone_val == "") {
        phonespan.style.visibility = "visible";
        phone.style.border = "1px solid red";

    }
    else if (iname_val == "") {
        instspan.style.visibility = "visible";
        iname.style.border = "1px solid red";

    }
    else if (regno_val == "") {
        regspan.style.visibility = "visible";
        regno.style.border = "1px solid red";

    }

    else if (address_val == "") {
        addresspan.style.visibility = "visible";
        address.style.border = "1px solid red";

    }
    else {

        const formdata = {
            'name': name_val,
            'status': stat,
            'fname': fname_val,
            'cnic': cnic_val,
            'phone': phone_val,
            'iname': iname_val,
            'regno': regno_val,
            'address': address_val,
        }
        const jsondata = JSON.stringify(formdata);
        const url = "php/add_student_backend.php";
        fetch(url, {
            method: 'post',
            body: jsondata,
            headers: {
                'Content-type': 'application/json'
            }
        }).then((response) => response.json())
            .then((result) => {
                if (result.message == 'success') {
                    document.querySelector(".add-success").style.display = "block";

                    setTimeout(function () {
                        document.querySelector(".add-success").style.display = "none";

                    }, 4000);
                    view_reload();
                    document.querySelector(".add-fail").style.display = "none";
                    document.querySelector(".form-div").reset();
                }
                else {
                    document.querySelector(".add-fail").style.display = "block";
                    document.querySelector(".add-success").style.display = "none";
                }
            })
    }

})

// close menue 
const close=document.querySelector("#close");
close.addEventListener("click",()=>{
    document.querySelector(".mobile_menue").style.visibility="hidden";

})
