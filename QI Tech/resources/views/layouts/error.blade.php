@if(Session::has('success'))
   <div class="col-12 mt-3">
           <div class="col-12">
               <div class="alert alert-success alert-dismissible fade show to_hide_10  w-30 position-fixed top-0" role="alert" style="margin: 0px auto; left:50%; transform: translateX(-50%);; z-index: 9999">
               {!! session('success') !!}
                   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="hideParent(this)">
                       <span aria-hidden="true"></span>
                   </button>
               </div>
           </div>
   </div>
@endif
@if(Session::has('success_message'))
   <div class="col-12 mt-3">
           <div class="col-12">
               <div class="alert alert-success alert-dismissible fade show to_hide_10  w-30 position-fixed top-0" role="alert" style="margin: 0px auto; left:50%; transform: translateX(-50%); z-index: 9999">
               {!! session('success_message') !!}
                   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="hideParent(this)">
                       <span aria-hidden="true"></span>
                   </button>
               </div>
           </div>
   </div>
@endif
@if(Session::has('error'))
   <div class="col-12 mt-3">
           <div class="col-12">
               <div class="alert alert-danger alert-dismissible fade show to_hide_10 w-30 position-fixed top-0" role="alert" style="margin: 0px auto; left:50%; transform: translateX(-50%); z-index: 9999">
                    {!! session('error') !!}
                   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="hideParent(this)">
                       <span aria-hidden="true"></span>
                   </button>
               </div>
           </div>
   </div>
@endif
@if(Session::has('congrats'))
    <div class="alert alert-success alert-dismissible fade show animated-alert success-alert w-30 position-fixed bottom-0 end-0 m-3" role="alert" style="z-index: 9999">
        {!! session('congrats') !!}
        {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="hideAlert(this)">
            <span aria-hidden="true"></span>
        </button> --}}
    </div>
@endif

@if(Session::has('cu-error'))
    <div class="alert alert-danger alert-dismissible fade show animated-alert cu-error-alert w-30 position-fixed bottom-0 end-0 m-3" role="alert" style="z-index: 9999">
        {!! session('cu-error') !!}
        {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="hideAlert(this)">
            <span aria-hidden="true"></span>
        </button> --}}
    </div>
@endif


@if ($errors->any())
    @foreach ($errors->all() as $error)
            <div class="col-12 mt-3">
                <div class="alert alert-danger alert-dismissible fade show w-30 position-fixed top-0 to_hide_10" role="alert" style="margin: 0px auto; left:50%; transform: translateX(-50%); z-index: 9999">
                    <strong>{{$error}} </strong>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="hideParent(this)">
                        <span aria-hidden="true"></span>
                    </button> --}}
                </div>
            </div>
    @endforeach
@endif

<style>
    .animated-alert {
        opacity: 0;
        transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
        transform: translateX(20px);
    }
    
    .animated-alert.show {
        opacity: 1;
        transform: translateX(0);
    }

    .success-alert {
        background-color: #d4edda;
        color: #155724; 
    }

    .cu-error-alert {
        background-color: #f8d7da;
        color: #721c24; 
    }

    .alert-dismissible .btn-close {
        position: absolute;
        top: 10px;
        right: 10px;
    }
</style>

<script>
    function hideParent(element){
        $(element).closest('.mt-3').fadeOut();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const successAlert = document.querySelector('.success-alert');
        const cuErrorAlert = document.querySelector('.cu-error-alert');

        function showAlert(alertElement) {
            if (alertElement) {
                alertElement.classList.add('show');
                setTimeout(() => {
                    alertElement.classList.remove('show');
                    setTimeout(() => {
                        alertElement.style.display = 'none';
                    }, 500);
                }, 5000); 
            }
        }

        showAlert(successAlert);
        showAlert(cuErrorAlert);
    });

    function hideAlert(button) {
        button.parentElement.style.display = 'none';
    }
</script>