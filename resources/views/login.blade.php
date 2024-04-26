@extends('layouts.app')
@section('content')

<section class="d-flex align-items-center min-vh-100">
  <div class="container-fluid px-4">
    <div class="row no-gutter justify-content-center">
      <div class="col-md-10 col-lg-8 shadow rounded overflow-hidden">
        <div class="row">
          <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center" 
          style="background: linear-gradient(57deg, var(--bs-primary), hsl(199deg 86% 42% / 30% )), 
                  url('assets/img/nuts-and-bolts.jpg') 
                  right/cover;">
            <article class="text-center text-white">
              <h2 class="mb-4">Bansal Private Limited</h2>
            </article>
          </div>
          <div class="col-lg-6">
            <section class="px-3 py-4">
              <img class="d-block mx-auto my-5" src="{{ asset('assets/img/curtis.png') }}" width="160" alt="curtis">
              <h5 class="mb-3 text-primary font-weight-bold">Log In</h5>

              <article class="alert alert-danger shadow-sm d-none" id="alert">
                <small>No such user exists.</small>
              </article>


              @error('message')
              <article class="alert alert-danger alert-dismissible fade show shadow-sm">
                <small>{{ $message }}</small>
                <button class="btn-close small" data-bs-dismiss="alert"></button>
              </article>
              @enderror

              {{-- login --}}
              <form action="{{ route('login') }}" method="POST" id="login">
                @csrf
                <div class="mb-3">
                  <input type="text" class="form-control py-3" id="username" placeholder="Username" required>
                  @error('username')
                    <div class="text-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>

                <div class="input-group mb-3">
                  <input type="password" class="form-control py-3 border-end-0" id="password" placeholder="Password" required>
                  <span class="input-group-text bg-white px-3">
                    <i class="fa fa-eye text-primary" id="eye-toggle" style="cursor: pointer;"></i>
                  </span>
                  @error('password')
                      <div class="text-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>

                <section class="d-flex justify-content-between mb-3">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label text-muted" for="remember">Remember me</label>
                  </div>
                  <a href="#">Forget Password?</a>
                </section>
                <button type="submit" class="btn w-100 btn-primary py-2 mb-2">Login</button>
              </form>

              {{-- login with otp --}}
              <form action="{{ route('login') }}" method="POST" id="otp" class="d-none">
                @csrf
                <input type="hidden" name="username">
                <input type="hidden" name="password">
                <input type="hidden" name="remember">

                <div class="mb-3">
                  <input type="text" class="form-control py-3" name="otp" placeholder="OTP" autocomplete="off" required>
                </div>

                <button type="submit" class="btn w-100 btn-primary py-2 mb-2">Verfiy OTP</button>
              </form>

            </section>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection

@push('scripts')
  <script>
    $(document).ready(()=>{
        
      passwordEl =  $('input#password');

      $('#eye-toggle').click(function(){

          $(this).toggleClass('fa-eye-slash');
          
          passwordEl.attr('type') == 'password'
            ? passwordEl.attr('type', 'text')
            : passwordEl.attr('type', 'password');
      });
      
      $('form#login').submit(function(e){

        e.preventDefault();
        $('form#login button[type=submit]').prop('disabled', true);

        // check such user exists 

        $.ajax({
            url : `{{ route('user.check') }}`,
            method : 'POST',
            dataType : 'json',            
            data : { '_token' : `{{ csrf_token() }}` , username : $('input#username').val() }
        }).done((data)=>{

            if(!data.username){ 
              $('#alert').removeClass('d-none'); 
              setTimeout(() => { $('#alert').addClass('d-none'); }, 3000);
              $('form#login').find('input').val('');
              $('form#login button[type=submit]').prop('disabled', false);
              return;
            }

            $('form#login').addClass('d-none');
            $('[name=username]').val($('#username').val());
            $('[name=password]').val($('#password').val());
            if($('#remember').is(':checked')){ $('[name=remember]').val($('#remember').val()); }
            $('form#otp').removeClass('d-none');

        }).fail((error)=> console.log(error));
        
      });

    });
  </script>
@endpush