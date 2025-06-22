<div class="add-student-main">
        <div class="heading-div">
        <h1 class="add-heading">Add New Student</h1>
        <div class="add-message add-success">
        <span>Student Added Successfully...</span>
        </div>
        <div class="add-message add-fail">
        <span>Student Not Added</span>
        </div>
        </div>
        
            <form class="form-div">
            <div class="outer-div">
                    <div class="in-div">
                        <div class="input-div">
                            <label>Student Name</label>
                            <input type="hidden" value="Present" id="stat">
                            <input type="text" id="name" class="add-input" onkeyup="input_name()" placeholder="Enter Student Name">
                            <span id="namespan" class="valid-span">Please Fill Student Name</span>
                        </div>
                    </div>
                    <div class="in-div">
                        <div class="input-div">
                            <label>Father Name</label>
                            <input type="text" id="fname" class="add-input" onkeyup="input_fname()"  placeholder="Enter Student Name">
                            <span id="fnamespan" class="valid-span">Please Fill Father Name</span>
                        </div>
                    </div>
            </div>
                
            <div class="outer-div">
                    <div class="in-div">
                        <div class="input-div">
                            <label>CNIC</label>
                            <input type="text" id="cnic" class="add-input"  onkeyup="input_cnic()" placeholder="Enter Student CNIC">
                            <span id="cnicspan" class="valid-span">Please Fill CNIC</span>
                        </div>
                    </div>
                    <div class="in-div">
                        <div class="input-div">
                            <label>Phone Number</label>
                            <input type="text" id="phone" class="add-input" onkeyup="input_phone()" pattern="[0-9]+" placeholder="Enter Phone Number">
                            <span id="phonespan" class="valid-span">Please Fill Phone Number</span>
                        </div>
                    </div>
            </div>
            <div class="outer-div">
                    <div class="in-div">
                        <div class="input-div">
                            <label>Institute Name</label>
                            <input type="text" id="iname" class="add-input"  onkeyup="input_iname()" placeholder="Enter Institute Name">
                            <span id="instspan" class="valid-span">Please Fill Institute Name</span>
                        </div>
                    </div>
                    <div class="in-div">
                        <div class="input-div">
                            <label>Room Number</label>
                            <select  id="room_no" class="add-input sel"  onchange="input_regno()" >
                                <option value="">Select Room</option>
                                <option value="R1">R1</option>
                                <option value="R2">R2</option>
                                <option value="R3">R3</option>
                                <option value="R4">R4</option>
                                <option value="R5">R5</option>
                                <option value="R6">R6</option>
                            </select>
                            <span id="regspan" class="valid-span">Please Select Room Number</span>
                        </div>
                    </div>
            </div>
            <div class="outer-div">
                    
                        <div class="input-div">
                    <div class="mobi-div-address">
                            <label>Address</label>
                            <textarea id="address" class="add-input h" onkeyup="input_address()"  placeholder="Enter Address"></textarea>
                            <span id="addresspan" class="valid-span">Please Fill Address</span>
                        
                    </div>       
                    </div>
                   
            </div>
            <div class="outer-div">
                    
                        <div class="add-btn-div">
                            <input type="button" class="add-student" value="Add Student" id="submit">
                        </div>
            </div>
                
            </form>

    </div>