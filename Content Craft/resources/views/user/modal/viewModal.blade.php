 {{-- show user modal --}}
 <div class="modal view-modal" id="showUserModal">
     <div class="modal-inner-div">
         <div class="modal-body">
             <div class="row">
                 <div class="col-md-4">
                     <div class="profile-img">
                         <img alt="Profile Image" id="uViewavatar" />
                     </div>
                 </div>
                 <div class="col-md-6">
                     <div class="profile-head">
                         <h4>
                             <span id="uViewfname"></span>
                             <span id="uViewlname"></span>
                         </h4>
                         <ul class="nav nav-tabs" id="myTab" role="tablist">
                             <li class="nav-item">
                                 <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                     role="tab" aria-controls="home" aria-selected="true">About</a>
                             </li>
                         </ul>
                     </div>
                 </div>
                 <div class="col-md-2">
                     <a @role('ADMIN')
               href="{{ route('admin.edit', auth()->user()->id) }}"
                @elserole('MANAGER')
               href="{{ route('manager.edit', auth()->user()->id) }}"
                @elserole('USER')
                href="{{ route('user.edit', auth()->user()->id) }}"
                @endrole
                         class="profile-edit-btn">Edit
                         Profile</a>
                 </div>
             </div>
             <div class="detail-div ">
                 <div class="tab-pane fade show active">
                     <div class="row">
                         <div class="col-md-6">
                             <label>User Id</label>
                         </div>
                         <div class="col-md-6">
                             <p id="uViewId"></p>
                         </div>
                     </div>

                     <div class="row">
                         <div class="col-md-6">
                             <label>Email</label>
                         </div>
                         <div class="col-md-6">
                             <p id="uViewemail"></p>
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-md-6">
                             <label>Phone Number</label>
                         </div>
                         <div class="col-md-6">
                             <p id="uViewphone"></p>
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-md-6">
                             <label>Gender</label>
                         </div>
                         <div class="col-md-6">
                             <p id="uViewgender"></p>
                         </div>
                     </div>
                     @role('Manager')
                         <div class="row">
                             <div class="col-md-6">
                                 <label>Manager</label>
                             </div>
                             <div class="col-md-6">
                                 <p id="uViewmanager"></p>
                             </div>
                         </div>
                         @elserole('User')
                         <div class="row">
                             <div class="col-md-6">
                                 <label>Manager</label>
                             </div>
                             <div class="col-md-6">
                                 <p id="uViewmanager"></p>
                             </div>
                         </div>
                     @endrole

                     <div class="row">
                         <div class="col-md-6">
                             <label>Country</label>
                         </div>
                         <div class="col-md-6">
                             <p id="uViewcountry"></p>
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-md-6">
                             <label>Postal Code</label>
                         </div>
                         <div class="col-md-6">
                             <p id="uViewpostalCode"></p>
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-md-6">
                             <label>Address</label>
                         </div>
                         <div class="col-md-6">
                             <p id="uViewaddress"></p>
                         </div>
                     </div>
                 </div>
             </div>

             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" id="closeModal">Close</button>
             </div>
         </div>
     </div>
 </div>
